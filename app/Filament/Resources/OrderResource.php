<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Package;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('address')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('quantity')
                    ->label('Person')
                    ->required()
                    ->numeric()
                    ->live()  // Buat reaktif
                    ->afterStateUpdated(function ($state, $set, $get) {
                        if (!$state) return;

                        // Ambil price dari relasi package
                        $package = Package::find($get('package_id'));
                        if (!$package) return;

                        $price = $package->price;
                        $duration = $get('duration') ?? 1;
                        $quantity = $state;

                        // Hitung total
                        $total = $price * $duration * $quantity;
                        $set('total_price', $total);
                    }),

                Forms\Components\TextInput::make('duration')
                    ->label('Duration (days)')
                    ->readOnly()
                    ->default(1)
                    ->required()
                    ->numeric()
                    ->dehydrated(true)
                    ->live()  // Buat reaktif
                    ->afterStateUpdated(function ($state, $set, $get) {
                        if (!$state) return;

                        // Ambil price dari relasi package
                        $package = Package::find($get('package_id'));
                        if (!$package) return;

                        $price = $package->price;
                        $duration = $state;
                        $quantity = $get('quantity') ?? 1;

                        // Hitung total
                        $total = $price * $duration * $quantity;
                        $set('total_price', $total);
                    }),

                Forms\Components\DatePicker::make('checkin') // Ganti ke DatePicker
                    ->required()
                    ->live()
                    ->format('Y-m-d')
                    ->default(now())
                    ->afterStateUpdated(function (callable $set, $state, callable $get) {
                        if (!$state || !$get('checkout')) {
                            return;
                        }

                        $checkin = Carbon::parse($state);
                        $checkout = Carbon::parse($get('checkout'));

                        // Pastikan checkout tidak lebih awal dari checkin
                        if ($checkout->lt($checkin)) {
                            $set('checkout', $state);
                            $set('duration', 1);
                            return;
                        }

                        // Hitung durasi dari checkin ke checkout (bukan sebaliknya)
                        $duration = $checkin->diffInDays($checkout);
                        $set('duration', $duration);
                    }),

                Forms\Components\DatePicker::make('checkout') // Ganti ke DatePicker
                    ->required()
                    ->live()
                    ->format('Y-m-d')
                    ->default(now())
                    ->afterStateUpdated(function (callable $set, $state, callable $get) {
                        if (!$state || !$get('checkin')) {
                            return;
                        }

                        $checkin = Carbon::parse($get('checkin'));
                        $checkout = Carbon::parse($state);

                        // Pastikan checkout tidak lebih awal dari checkin
                        if ($checkout->lt($checkin)) {
                            $set('checkout', $get('checkin'));
                            $set('duration', 1);
                            return;
                        }

                        // Hitung durasi dari checkin ke checkout (bukan sebaliknya)
                        $duration = $checkin->diffInDays($checkout);
                        $set('duration', $duration);
                    }),
                Forms\Components\TextInput::make('total_price')
                    ->prefix('IDR ')
                    ->readOnly()
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),

                Forms\Components\Select::make('status')
                    ->default('unpaid')
                    ->label('Status')
                    ->options([
                        'paid' => 'Paid',
                        'unpaid' => 'Unpaid',
                    ]),
                Forms\Components\Select::make('package_id')
                    ->relationship('package', 'name')  // Ganti 'name' dengan field yang ingin ditampilkan
                    ->required()
                    ->live()  // Buat reaktif
                    ->afterStateUpdated(function ($state, $set, $get) {
                        if (!$state) return;

                        $package = Package::find($state);
                        if (!$package) return;

                        // Set price
                        $set('price', $package->price);

                        // Hitung ulang total
                        $price = $package->price;
                        $duration = $get('duration') ?? 1;
                        $quantity = $get('quantity') ?? 1;

                        $total = $price * $duration * $quantity;
                        $set('total_price', $total);
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Person')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->prefix( 'IDR ')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('package.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration (days)')
                    ->prefix('Days ')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('checkin')
                    ->date('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('checkout')
                    ->date('Y-m-d')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}