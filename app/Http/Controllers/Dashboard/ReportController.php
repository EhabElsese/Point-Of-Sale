<?php

namespace App\Http\Controllers\Dashboard;


use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function monthlyReport(Request $request)
    {
        $years = [];
        for ($i = 1; $i <= 100; $i++) {
            $year = 2000;
            $years[$i] = $year + $i;
        }

        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $month = Carbon::createFromDate(null, $i, null)->format('F');
            $months[$i] = $month;
        }
        $days = [];
        for ($i = 1; $i <= 31; $i++) {
            $day = Carbon::createFromDate(null, null, $i)->format('d');
            $days[$i] = $day;
        }

        $query = Order::selectRaw('DATE(orders.created_at) as date')
    ->selectRaw('COUNT(orders.id) as number_of_orders')
    ->selectRaw('SUM(products.sale_price * product_order.quantity) as total_of_sale')
    ->selectRaw('SUM((products.sale_price - products.purchase_price) * product_order.quantity) as total_profit')
    ->selectRaw('SUM(product_order.quantity) as total_quantity')
    ->leftJoin('product_order', 'orders.id', '=', 'product_order.order_id')
    ->leftJoin('products', 'product_order.product_id', '=', 'products.id');


        if ($request->from_day && $request->to_day) {
            $query->whereBetween(DB::raw('DAY(orders.created_at)'), [$request->from_day, $request->to_day]);
        }

        if ($request->month) {
            $query->whereMonth('orders.created_at', $request->month);
        }

        if ($request->year) {
            $query->whereYear('orders.created_at', $request->year);
        }



        // Get the grouped results
        $orders = $query->groupBy('date')->orderBy('date', 'desc')->get();

        $total_profits = collect($orders)->sum('total_profit');
        $total_sales = collect($orders)->sum('total_of_sale');
        $total_product = collect($orders)->sum('total_quantity');

       // dd($orders);


        return view('dashboard.monthlyReports', compact('years', 'months', 'days', 'orders', 'total_profits', 'total_sales', 'total_product'));
    } //end of index

}
