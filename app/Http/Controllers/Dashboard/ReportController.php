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
            ->join('orders','clients.id','=','orders.client_id')
            ->join('product_order','orders.id','=','product_order.order_id')
            ->join('products','product_order.product_id','=','products.id');
            if($request->from_day && $request->to_day){
                
                $query->whereBetween('DAY(orders.created_at)',[$request->from_day,$request->to_day]);
            }
            if($request->month){
                $query->whereRaw("MONTH(orders.created_at) = $request->month");
            }
            if($request->year){
                $query->whereRaw("YEAR(orders.created_at) = $request->year");
            }
        $order_count = $query->select('orders.*')->count();

        $orders = $query->selectRaw('clients.name as client_name, orders.id as order_id , SUM(product_order.quantity) as quantity, SUM(orders.total_price) as total_price , orders.status as status,SUM(orders.total_price) as total_sales ,SUM(products.sale_price - products.purchase_price) as profit' )
        ->groupBy('order_id')->latest()->get();

            $total_profit = collect($orders)->sum('profit');

            $total_sales = collect($orders)->sum('total_sales');

            $total_product = collect($orders)->sum('quantity');
        





        return view('dashboard.monthlyReports',compact('years','months','days','order_count','orders','total_profit','total_sales','total_product'));

        
    } //end of index


    public function yearlyReport(Request $request)
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



        return view('dashboard.monthlyReports', compact('order','product','sales','profit', 'years','months','days'));
    } //end of yearlyReport
}
