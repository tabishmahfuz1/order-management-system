<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SalesOrder;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $dashboard_data = []; 
        $dashboard_data['monthSales']   = SalesOrder::getMonthSales();
        $dashboard_data['yearSales']    = SalesOrder::getYearSales();
        $dashboard_data['pendingOrders']    = SalesOrder::getPendingOrdersCount();
        return view('home', $dashboard_data);
    }
}
