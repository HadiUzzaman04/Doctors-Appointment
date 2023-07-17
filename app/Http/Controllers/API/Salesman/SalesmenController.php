<?php
namespace App\Http\Controllers\API\Salesman;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\APIController;

class SalesmenController extends APIController
{

    public function customer_list(Request $request)
    {
        $errors    = [];
        $data    = [];
        $message = "";
        $status  = true;
        try {            
            $search_text = $request->customer;
            $salesman_routes = DB::table('sales_men_daily_routes')->where('salesmen_id',auth()->user()->id)->select('route_id')->get();
            $routes = [];
            if(!$salesman_routes->isEmpty())
            {
                foreach ($salesman_routes as $value) {
                    array_push($routes,$value->route_id);
                }
                $customers = DB::table('customers as c')
                ->leftJoin('customer_groups as cg','c.customer_group_id','=','cg.id')
                ->select('c.id','c.name','c.shop_name','cg.percentage')
                ->where('c.status',1)
                ->whereIn('c.route_id',$routes)
                ->when($search_text,function($q) use ($search_text){
                    $q->where('c.name','like','%'.$search_text.'%')
                    ->orWhere('c.shop_name','like','%'.$search_text.'%');
                })
                ->get();
                if(!$customers->isEmpty()){
                    foreach ($customers as $value) {
                        $customer_previous_balance = DB::table('transactions as t')
                        ->leftjoin('chart_of_accounts as coa','t.chart_of_account_id','=','coa.id')
                        ->select(DB::raw("SUM(t.debit) - SUM(t.credit) as balance"),'coa.id','coa.code')
                        ->groupBy('t.chart_of_account_id')
                        ->where('coa.customer_id',$value->id)
                        ->where('t.approve',1)
                        ->first();
                        $data[] = [
                            'id'           => $value->id,
                            'name'         => $value->shop_name.' ('.$value->name.')',
                            'percentage'   => $value->percentage ? number_format($value->percentage,2,'.','') : number_format(0,2,'.',''),
                            'previous_due' => $customer_previous_balance ? number_format($customer_previous_balance->balance,2,'.','') : number_format(0,2,'.','')
                        ];
                    }
                    $message = "Data found Successfully";
                }else{
                    $status  = false;
                    $message = "No Records Found";
                }
            }else{
                $status  = false;
                $message = "No Records Found";
            }
            
        } catch (Exception $e) {
            $status  = false;
            $message = $e->getMessage();
        }
        return $this->sendResult($message,$data,$errors,$status);
    }

    public function salesmen_data_summary(Request $request){
        $errors    = [];
        $data    = [];
        $message = "";
        $status  = true;

        $sales_amount = 0;
        $collection_amount = 0;
        $customer_total_dues = 0;
        $sr_commission = 0;
        $sr_total_commission_paid = 0;
        $sr_previous_commission = 0;
        $sr_total_due_commission = 0;

        try {            
            $salesmen_id = auth()->user()->id;
            $coa_id = auth()->user()->coa->id;
            $start_date = $request->start_date ? $request->start_date : date('Y-m-01');
            $end_date   = $request->end_date ? $request->end_date : date('Y-m-31');
       
            $product_sale_data= DB::table('sales')
                                ->select(DB::raw("SUM(grand_total) as sales_amount"),
                                DB::raw("SUM(paid_amount) as collection_amount")
                                )
                                ->where('salesmen_id',$salesmen_id)
                                ->whereDate('sale_date','>=',$start_date)
                                ->whereDate('sale_date','<=',$end_date)
                                ->groupBy('salesmen_id')
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
                    ->when($salesmen_id,function($q) use($salesmen_id){
                        $q->where('s.salesmen_id',$salesmen_id);
                    })
                    ->get();
                if(!$customer_dues->isEmpty())
                {
                    foreach ($customer_dues->chunk(10) as $chunk) {
                        foreach ($chunk as $value)
                        {
                            $customer_total_dues += $value->due_amount;
                        }
                    }
                }

                $sr_commission_data= DB::table('transactions')
                                    ->select(DB::raw("SUM(debit) as sr_total_commission_paid"),
                                    DB::raw("SUM(credit) as sr_commission"),
                                    )
                                    ->where('chart_of_account_id',$coa_id)
                                    ->whereDate('voucher_date','>=',$start_date)
                                    ->whereDate('voucher_date','<=',$end_date)
                                    ->groupBy('chart_of_account_id')
                                    ->first();

                $sr_commission_previous_due= DB::table('transactions')
                                    ->select(DB::raw("SUM(credit) as sr_previous_commission"))
                                    ->where('chart_of_account_id',$coa_id)
                                    ->whereDate('voucher_date','<',$start_date)
                                    ->groupBy('chart_of_account_id')
                                    ->first();

                $sr_commission_due= DB::table('transactions')
                                    ->select(DB::raw("(SUM(credit) - SUM(debit)) as due_commission"))
                                    ->where('chart_of_account_id',$coa_id)
                                    ->whereDate('voucher_date','<',$end_date)
                                    ->groupBy('chart_of_account_id')
                                    ->first();

                $sales_amount = ($product_sale_data) ? $product_sale_data->sales_amount : 0;
                $collection_amount = ($product_sale_data) ? $product_sale_data->collection_amount : 0;
                $sr_commission = ($sr_commission_data) ? $sr_commission_data->sr_commission : 0;
                $sr_total_commission_paid = ($sr_commission_data) ? $sr_commission_data->sr_total_commission_paid : 0;
                $sr_previous_commission = ($sr_commission_previous_due) ? $sr_commission_previous_due->sr_previous_commission : 0;
                $sr_total_due_commission = ($sr_commission_due) ? $sr_commission_due->due_commission : 0;

                $data['sales_amount']  = ($sales_amount) ? $sales_amount : 0;
                $data['collection_amount']  = ($collection_amount) ? $collection_amount : 0;
                $data['customer_due_amount']  = ($customer_total_dues) ? $customer_total_dues : 0;
                $data['sr_commission']  = ($sr_commission) ? $sr_commission : 0;
                $data['sr_previous_commission']  = ($sr_commission_previous_due) ? $sr_previous_commission : 0;
                $data['sr_total_due_commission']  = ($sr_total_due_commission) ? $sr_total_due_commission : 0;
                $data['sr_total_commission_paid']  = ($sr_total_commission_paid) ? $sr_total_commission_paid : 0;
            
        } catch (Exception $e) {
            $status  = false;
            $message = $e->getMessage();
        }
        return $this->sendResult($message,$data,$errors,$status);

    }

}
