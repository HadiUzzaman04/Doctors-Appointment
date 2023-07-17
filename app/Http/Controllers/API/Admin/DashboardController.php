<?php

namespace App\Http\Controllers\API\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\APIController;

class DashboardController extends APIController
{
    public function summaryData(Request $request)
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;
        try{
            $total_customer_dues = 0;
            $start_date = $request->get('start_date') ? $request->get('start_date') : date('Y-m-d');
            $end_date = $request->get('end_date') ? $request->get('end_date') : date('Y-m-d');
            $sale = DB::table('sales')
                    ->select(DB::raw("SUM(grand_total) as sales_amount"),
                    DB::raw("SUM(paid_amount) as collection_amount")
                    )
                    ->whereDate('sale_date','>=',$start_date)
                    ->whereDate('sale_date','<=',$end_date)
                    ->first();
            $purchase = DB::table('purchases')
                        ->whereDate('purchase_date','>=',$start_date)
                        ->whereDate('purchase_date','<=',$end_date)
                        ->sum('grand_total');
            $expense = DB::table('expenses')
            ->whereDate('date','>=',$start_date)
            ->whereDate('date','<=',$end_date)
            ->sum('amount');
            $sr_commission_due= DB::table('transactions as t')
            ->join('chart_of_accounts as coa','t.chart_of_account_id','=','coa.id')
            ->select(DB::raw("(SUM(t.credit) - SUM(t.debit)) as due_commission"))
            ->whereNotNull('coa.salesmen_id')
            ->whereDate('t.voucher_date','<=',$end_date)
            ->first();
            $supplier_due = DB::table('transactions as t')
                ->join('chart_of_accounts as coa','t.chart_of_account_id','=','coa.id')
                ->select(DB::raw("(SUM(t.credit) - SUM(t.debit)) as due"))
                ->whereNotNull('coa.supplier_id')
                ->whereDate('t.voucher_date','<=',$end_date)
                ->first();
            $customer_dues = DB::table('sales as s')
                ->leftJoin('customers as c','s.customer_id','=','c.id')
                ->selectRaw('s.customer_id,s.due_amount,max(s.id) as last_due_id')
                ->groupBy('s.customer_id')
                ->where('s.due_amount','>',0)
                ->when($start_date,function($q) use($start_date){
                    $q->whereDate('s.sale_date','>=',$start_date);
                })
                ->when($end_date,function($q) use($end_date){
                    $q->whereDate('s.sale_date','<=',$end_date);
                })
                ->get();
            if(!$customer_dues->isEmpty())
            {
                foreach ($customer_dues->chunk(10) as $chunk) {
                    foreach ($chunk as $value)
                    {
                        $total_customer_dues += $value->due_amount;
                    }
                }
            }
            $customers = DB::table('customers')->count();
            $data = [
                'total_sale_amount' => $sale ? number_format($sale->sales_amount,2,'.','') : number_format(0,2,'.',''),
                'total_collection_amount' => $sale ? number_format($sale->collection_amount,2,'.','') : number_format(0,2,'.',''),
                'total_purchase_amount' => number_format($purchase,2,'.',''),
                'total_expense_amount' => number_format($expense,2,'.',''),
                'total_supplier_due_amount' => $supplier_due ? number_format($supplier_due->due,2,'.','') : number_format(0,2,'.',''),
                'total_sr_commission_due_amount' =>  $sr_commission_due ? number_format( $sr_commission_due->due_commission,2,'.','') : number_format(0,2,'.',''),
                'total_customer_dues' =>  number_format( $total_customer_dues,2,'.',''),
                'total_customers' => $customers
            ];
        } catch (Exception $e) {
            $status  = false;
            $message = $e->getMessage();
        }
        return $this->sendResult($message,$data,$errors,$status);
    }
}
