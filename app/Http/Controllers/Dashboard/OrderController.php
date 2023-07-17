<?php

namespace App\Http\Controllers\Dashboard;

use App\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::whereHas('client', function ($q) use ($request) {

            return $q->where('name', 'like', '%' . $request->search . '%');
        })->orderBy('created_at', 'desc')->paginate(5);

        return view('dashboard.orders.index', compact('orders'));
    } //end of index

    public function products(Order $order)
    {
        $products = $order->products;
        return view('dashboard.orders._products', compact('order', 'products'));
    } //end of products

    public function updateStatus($id)
    {
        $order = Order::find($id);
        $order->update(['status' => 'deliverd']);
        $order->save();
        return redirect()->route('dashboard.orders.index');
        //return response()->json(['success' => true]);
    } //end of products

    public function destroy(Order $order)
    {
        foreach ($order->products as $product) {

            $product->update([
                'stock' => $product->stock + $product->pivot->quantity
            ]);
        } //end of for each

        $order->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.orders.index');
    } //end of order

}//end of controller
