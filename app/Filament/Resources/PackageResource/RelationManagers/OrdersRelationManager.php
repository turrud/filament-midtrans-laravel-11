<?php

namespace App\Filament\Resources\PackageResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use App\Models\Package;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Pages\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';
    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->required()
                    ->maxLength(20),
                Forms\Components\Textarea::make('address')
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->label('Person')
                    ->required()
                    ->numeric(),
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
                Forms\Components\TextInput::make('total_price')
                    ->prefix('IDR ')
                    ->readOnly()
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),
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
                Forms\Components\Select::make('status')
                    ->default('unpaid')
                    ->label('Status')
                    ->options([
                        'paid' => 'Paid',
                        'unpaid' => 'Unpaid',
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('phone'),
            Tables\Columns\TextColumn::make('address'),
            Tables\Columns\TextColumn::make('quantity')
                ->label('Person'),
            Tables\Columns\TextColumn::make('total_price')->money('IDR'),
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
            Tables\Columns\TextColumn::make('status')
                ->formatStateUsing(fn ($state) => $state === 'paid' ? 'Paid' : 'Unpaid'),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),
        ])
        ->filters([
            //
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }
}