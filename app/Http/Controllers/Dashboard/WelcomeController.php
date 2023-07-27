<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use App\Client;
use App\Order;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        $categories_count = Category::count();
        $products_count = Product::count();
        $clients_count = Client::count();
        $users_count = User::whereRoleIs('admin')->count();

        // $sales_data = Order::select(
        //     DB::raw('YEAR(created_at) as year'),
        //     DB::raw('MONTH(created_at) as month'),
        //     DB::raw('SUM(total_price) as sum')
        // )->groupBy('month')->get();

        $sales_data = DB::table('orders')
        ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_price) as sum')
        ->groupBy('month','year')
        ->get();
        $sales_data2 = DB::table('orders')
        ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_price) as sum')
        ->groupBy('month','year')
        ->get();
        return view('dashboard.welcome', compact('categories_count', 'products_count', 'clients_count', 'users_count', 'sales_data','sales_data2'));
    
    }//end of index
    
}//end of controller
