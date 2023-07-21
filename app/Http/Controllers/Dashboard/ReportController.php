<?php

namespace App\Http\Controllers\Dashboard;



use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
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

        $order_query = DB::table('orders');
        $product_query = DB::table('products')
            ->join('product_order','products.id','=','product_order.product_id')
            ->join('orders','product_order.order_id','=','orders.id');
        $price_query = DB::table('orders');


        if($request->day){
            $order_query->whereRaw("DAY(orders.created_at) = $request->day");
            $product_query->whereRaw("DAY(orders.created_at) = $request->day");
            $price_query->whereRaw("DAY(orders.created_at) = $request->day");
        }
        if($request->month){
            $order_query->whereRaw("MONTH(orders.created_at) = $request->month");
            $product_query->whereRaw("MONTH(orders.created_at) = $request->month");
            $price_query->whereRaw("MONTH(orders.created_at) = $request->month");
        }
        if($request->year){
            $order_query->whereRaw("YEAR(orders.created_at) = $request->year");
            $product_query->whereRaw("YEAR(orders.created_at) = $request->year");
            $price_query->whereRaw("YEAR(orders.created_at) = $request->year");
        }
        $order = $order_query->count();
        $profit = $product_query->selectRaw('SUM(products.sale_price - products.purchase_price) as profit')->get();
        $product = $product_query->count();
        $sales = $price_query->selectRaw("SUM(orders.total_price) as total_sales")->get();

       // dd($profit);



        return view('dashboard.reports', compact('order','product','sales','profit', 'years','months','days'));
    } //end of index
}
