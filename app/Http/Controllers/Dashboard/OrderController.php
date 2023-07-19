<?php

namespace App\Http\Controllers\Dashboard;

use App\Order;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $month = Carbon::createFromDate(null, $i, null)->format('F');
            $months[$i] = $month;
        }
     
        $query = DB::table('orders')->join('clients', 'orders.client_id', '=', 'clients.id');

        if ($request->month) {
            $query->whereMonth('orders.created_at', '=', $request->month);
        }

        if ($request->search) {
            $query->where('clients.name', 'like', '%' . $request->search . '%');
        }

        $orders = $query->select('orders.*', 'clients.name as client_name',
            DB::raw('DATE_FORMAT(orders.created_at,"%Y-%m-%d") as created_at')
        )->paginate(10);

        return view('dashboard.orders.index', compact('orders', 'months'));
    } //end of index


    public function products(Order $order)
    {
        $products = $order->products;
        return view('dashboard.orders._products', compact('order', 'products'));
    } //end of products

    public function updateStatus(Request $request)
    {

        $order = Order::find($request->id);
        $order->update(['status' => 'deliverd']);
        $order->save();


        // return redirect()->route('dashboard.orders.index');
        return response()->json(['success' => true, "trans" => __("site.$order->status")]);
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
