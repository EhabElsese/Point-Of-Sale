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


        $currentMonth = Carbon::now()->month;


        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $month = Carbon::createFromDate(null, $i, null)->format('F');
            $months[$i] = $month;
        }
        $orders = Order::whereHas('client', function ($q) use ($request) {

            return $q->where('name', 'like', '%' . $request->search . '%');


        })->orderBy('created_at', 'desc')->paginate(10);


        if ($request->month === null) {
            $orders = Order::whereHas('client', function ($q) use ($request) {

                return $q->where('name', 'like', '%' . $request->search . '%');


            })->orderBy('created_at', 'desc')->paginate(10);

        } elseif ($request->month !== null && $request->search !== null) {

            $orders = Order::whereMonth('created_at', $request->month)
                ->whereHas('client', function ($q) use ($request) {
                    return $q->where('name', 'like', '%' . $request->search . '%');
                })->paginate(10);

//            $orders = DB::table('orders')
//                ->join('clients', 'orders.client_id', '=', 'clients.id')
//                ->whereMonth('orders.created_at', '=', $request->month)
//                ->where('clients.name', 'like', '%'.$request->search.'%')
//                ->select('orders.*','clients.name as client_name')
//                ->paginate(10);

        } else {
            $orders = Order::whereMonth('created_at', $request->month)->paginate(10);

        }



        return view('dashboard.orders.index', compact('orders', 'months', 'currentMonth'));
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
        return response()->json(['success' => true, "trans" => __("site.{{$order->status}}")]);
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
