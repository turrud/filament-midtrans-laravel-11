<?php

namespace App\Http\Controllers\ViewOrder;

use App\Models\Order;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class OrderController extends Controller
{

    // public function index()
    // {
    //     $orders = Order::all();
    //     return view('order.index', compact('orders'));
    // }

    public function store(Request $request, $id){
        // Temukan Package berdasarkan ID atau UUID
        $package = Package::findOrFail($id);

        // Validasi data request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'duration' => 'integer|min:1',
            'checkin' => 'required|date',
            'checkout' => 'required|date|after_or_equal:checkin',
        ]);

        // Tambahkan harga total ke data
        $validatedData['total_price'] = ($request->quantity * $package->price) * $request->duration;
        $validatedData['duration'] =  $request->duration;
        $validatedData['status'] = 'unpaid';
        $validatedData['package_id'] = $package->id;

        // Buat pesanan
        Order::create(array_merge($validatedData, ['package_id' => $package->id]));

        dd([
            'validated_data' => $validatedData,
            'package_price' => $package->price
        ]);

        // Redirect dengan pesan sukses
        // return redirect()->route('order.success')->with('message', 'Order berhasil dibuat!');
    }






}