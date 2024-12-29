<?php

namespace App\Http\Controllers\ViewPackage;

use App\Models\Order;
use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PackageController extends Controller
{

    public function index()
    {
        $packages = Package::all();
        return view('frontend.viewpackage.index', compact('packages'));
    }

    public function show(Package $package)
    {
        return view('frontend.viewpackage.show', compact('package'));
    }

    public function store(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'quantity' => 'required',
            'total_price' => 'required',
            'checkin' => 'required',
            'checkout' => 'required',
        ]);

        $request->request->add(['total_price' => $request->quantity * $package->price, 'status' => 'unpaid']);
        // $order = Order::create($request->all());
        return dd($request->all());


    }
}