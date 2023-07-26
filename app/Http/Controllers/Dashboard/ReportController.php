<?php

namespace App\Http\Controllers\Dashboard;


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

        $query = DB::table('clients')
            ->join('orders', 'clients.id', '=', 'orders.client_id')
            ->join('product_order', 'orders.id', '=', 'product_order.order_id')
            ->join('products', 'product_order.product_id', '=', 'products.id');
        if ($request->from_day && $request->to_day) {

            $query->whereBetween(DB::raw('DAY(orders.created_at)'), [$request->from_day, $request->to_day]);
        }
        if ($request->month) {
            $query->whereRaw("MONTH(orders.created_at) = $request->month");
        }
        if ($request->year) {
            $query->whereRaw("YEAR(orders.created_at) = $request->year");
        }
        $orders_count = $query->select('orders.id')->count();

        $orders = $query->selectRaw('DATE(orders.created_at) as created_at,COUNT(orders.id) as order_count , SUM(product_order.quantity) as quantity,SUM(orders.total_price) as total_price, orders.client_id as client_id,SUM(products.sale_price - products.purchase_price) * quantity as profit')
            ->groupBy(['client_id','created_at'])->latest()->get();

        $total_profit = collect($orders)->sum('profit');
        $total_sales = collect($orders)->sum('total_price');
        $total_product = collect($orders)->sum('quantity');



        dd($orders);


        return view('dashboard.monthlyReports', compact('years', 'months', 'days', 'orders_count', 'orders', 'total_profit', 'total_sales', 'total_product'));


    } //end of index

}
