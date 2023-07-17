<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController;


class HomeController extends BaseController
{
    public function index()
    {

        if (permission('dashboard-access')){

            $this->setPageData('Dashboard','Dashboard','fas fa-technometer');
            return view('home');
        }else{
            return redirect('unauthorized')->with(['status'=>'error','message'=>'Unauthorized Access Blocked']);
        }
    }

    // public function dashboard_data($start_date,$end_date)
    // {
    //     if($start_date && $end_date)
    //     {
    //         $sale = DB::table('sales')
    //                 ->whereDate('sale_date','>=',$start_date)
    //                 ->whereDate('sale_date','<=',$end_date)
    //                 ->sum('grand_total');
    //         $purchase = DB::table('purchases')->whereDate('purchase_date','>=',$start_date)
    //                     ->whereDate('purchase_date','<=',$end_date)
    //                     ->sum('grand_total');
    //         $income = DB::table('sales')
    //                     ->whereDate('sale_date','>=',$start_date)
    //                     ->whereDate('sale_date','<=',$end_date)
    //                     ->sum('paid_amount');
    //         $expense = DB::table('expenses')
    //         ->whereDate('date','>=',$start_date)
    //         ->whereDate('date','<=',$end_date)
    //         ->sum('amount');

    //         $sr_commission_due= DB::table('transactions as t')
    //         ->join('chart_of_accounts as coa','t.chart_of_account_id','=','coa.id')
    //         ->select(DB::raw("(SUM(t.credit) - SUM(t.debit)) as due_commission"))
    //         ->whereNotNull('coa.salesmen_id')
    //         ->whereDate('t.voucher_date','<=',$end_date)
    //         ->first();
    //         $supplier_due = DB::table('transactions as t')
    //             ->join('chart_of_accounts as coa','t.chart_of_account_id','=','coa.id')
    //             ->select(DB::raw("(SUM(t.credit) - SUM(t.debit)) as due"))
    //             ->whereNotNull('coa.supplier_id')
    //             ->whereDate('t.voucher_date','<=',$end_date)
    //             ->first();
    //         $total_customer_dues = 0;
    //         $customer_dues = DB::table('sales as s')
    //             ->leftJoin('customers as c','s.customer_id','=','c.id')
    //             ->selectRaw('s.customer_id,s.due_amount,max(s.id) as last_due_id')
    //             ->groupBy('s.customer_id')
    //             ->where('s.due_amount','>',0)
    //             ->when($start_date,function($q) use($start_date){
    //                 $q->whereDate('s.sale_date','>=',$start_date);
    //             })
    //             ->when($end_date,function($q) use($end_date){
    //                 $q->whereDate('s.sale_date','<=',$end_date);
    //             })
    //             ->get();
    //         if(!$customer_dues->isEmpty())
    //         {
    //             foreach ($customer_dues->chunk(10) as $chunk) {
    //                 foreach ($chunk as $value)
    //                 {
    //                     $total_customer_dues += $value->due_amount;
    //                 }
    //             }
    //         }
    //         $customers = DB::table('customers')->count();
    //         $data = [
    //             'sale'              => $sale,
    //             'purchase'          => $purchase,
    //             'income'            => $income,
    //             'expense'           => $expense,
    //             'supplier_due'      => $supplier_due ? $supplier_due->due : 0,
    //             'customer_due'      => $total_customer_dues,
    //             'sr_commission_due' => $sr_commission_due ? $sr_commission_due->due_commission : 0,
    //             'total_customer'    => $customers,
    //         ];
    //         return response()->json($data);
    //     }

    // }
    
    public function unauthorized()
    {
        $this->setPageData('Unauthorized','Unauthorized','fas fa-ban',[['name' => 'Unauthorized']]);
        return view('unauthorized');
    }

}
