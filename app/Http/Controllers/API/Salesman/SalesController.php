<?php

namespace App\Http\Controllers\API\Salesman;

use Exception;
use App\Models\Unit;
use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Modules\Sale\Entities\Sale;
use Illuminate\Support\Facades\DB;
use Modules\Sale\Entities\SaleProduct;
use Modules\Customer\Entities\Customer;
use Modules\Account\Entities\Transaction;
use App\Http\Requests\API\SaleFormRequest;
use App\Http\Controllers\API\APIController;
use Modules\Product\Entities\WarehouseProduct;

class SalesController extends APIController
{
    use UploadAble;
    public function customer_data(int $id)
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;

        $customer_group_data = DB::table('customers as c')
        ->leftJoin('customer_groups as cg','c.customer_group_id','=','cg.id')
        ->selectRaw('cg.percentage')
        ->where('c.id',$id)
        ->first();

        $data['percentage'] = $customer_group_data ? number_format($customer_group_data->percentage,2,'.','') : number_format(0,2,'.','');

        $customer_previous_balance = DB::table('transactions as t')
                ->leftjoin('chart_of_accounts as coa','t.chart_of_account_id','=','coa.id')
                ->select(DB::raw("SUM(t.debit) - SUM(t.credit) as balance"),'coa.id','coa.code')
                ->groupBy('t.chart_of_account_id')
                ->where('coa.customer_id',$id)
                ->where('t.approve',1)
                ->first();

        $data['previous_due'] = $customer_previous_balance ? number_format($customer_previous_balance->balance,2,'.','') : number_format(0,2,'.','');
        return $this->sendResult($message,$data,$errors,$status);
    }

    public function tax_list()
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;

        $taxes = DB::table('taxes')
        ->select('name','rate')
        ->where('status',1)
        ->get();

        if(!$taxes->isEmpty())
        {
            $data = $taxes;
        }else{
            $message = 'No data found!';
            $status = false;
        }
        return $this->sendResult($message,$data,$errors,$status);
    }

    public function account_list(int $payment_method)
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;
        $payment_array = [1,2,3];
        if($payment_method)
        {
            if(in_array($payment_method,$payment_array))
            {
                $warehouse_id = auth()->user()->warehouse_id;
                $accounts = DB::table('chart_of_accounts as coa');
                if($payment_method == 1)
                {
                    $accounts = $accounts->select('coa.id','coa.name')->where(['coa.code' =>  '1020101','coa.status'=>1]);
                }elseif ($payment_method == 2) {
                    $accounts = $accounts->leftJoin('banks as b','coa.bank_id','=','b.id')
                    ->select('coa.id','coa.name')
                    ->whereNotNull('coa.bank_id')
                    ->where('coa.status',1)
                    ->where('b.warehouse_id',$warehouse_id);
                }elseif ($payment_method == 3) {
                    $accounts = $accounts->leftJoin('mobile_banks as b','coa.mobile_bank_id','=','b.id')
                    ->select('coa.id','coa.name')
                    ->whereNotNull('coa.mobile_bank_id')
                    ->where('coa.status',1)
                    ->where('b.warehouse_id',$warehouse_id);
                }

                $accounts = $accounts->get();
                if(!$accounts->isEmpty())
                {
                    $data = $accounts;
                }else{
                    $message = 'No data found!';
                    $status = false;
                }
            }else{
                $message = 'Invalid Payment Method!';
                $status = false;
            }
        }else{
            $message = 'Please select payment method!';
            $status = false;
        }
        return $this->sendResult($message,$data,$errors,$status);
    }

    public function store_sale_data(SaleFormRequest $request)
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;
        DB::beginTransaction();
        try {
            // dd($request->all());
            $customer = Customer::with('coa')->find($request->customer_id);
            $warehouse_id = auth()->user()->warehouse_id;
            $sale_data = [
                'memo_no'            => $request->memo_no,
                'warehouse_id'       => $warehouse_id,
                'district_id'        => $customer->district_id,
                'upazila_id'         => $customer->upazila_id,
                'route_id'           => $customer->route_id,
                'area_id'            => $customer->area_id,
                'salesmen_id'        => auth()->user()->id,
                'customer_id'        => $customer->id,
                'item'               => $request->item,
                'total_qty'          => $request->total_qty,
                'total_discount'     => 0,
                'total_tax'          => $request->total_tax ? $request->total_tax : 0,
                'total_price'        => $request->total_price,
                'order_tax_rate'     => $request->order_tax_rate,
                'order_tax'          => $request->order_tax,
                'order_discount'     => $request->order_discount ? $request->order_discount : 0,
                'shipping_cost'      => $request->shipping_cost ? $request->shipping_cost : 0,
                'labor_cost'         => $request->labor_cost ? $request->labor_cost : 0,
                'grand_total'        => $request->grand_total,
                'previous_due'       => $request->previous_due ? $request->previous_due : 0,
                'net_total'          => $request->net_total,
                'paid_amount'        => $request->paid_amount ? $request->paid_amount : 0,
                'due_amount'         => $request->due_amount ?  $request->due_amount : 0,
                'sr_commission_rate' => auth()->user()->cpr,
                'total_commission'   => $request->total_commission ? $request->total_commission :0,
                'payment_status'     => $request->payment_status,
                'payment_method'     => $request->payment_method ? $request->payment_method : null,
                'account_id'         => $request->account_id ? $request->account_id : null,
                'reference_no'       => $request->reference_no ? $request->reference_no : null,
                'note'               => $request->note,
                'sale_date'          => $request->sale_date,
                'created_by'         => auth()->user()->name
            ];

            //payment data for account transaction
            $payment_data = [
                'payment_method' => $request->payment_method ? $request->payment_method : null,
                'account_id'     => $request->account_id ? $request->account_id : null,
                'paid_amount'    => $request->paid_amount ? $request->paid_amount : 0,
            ];

            if(!empty($request->document)){
                $sale_data['document'] = $this->upload_base64_image($request->document,SALE_DOCUMENT_PATH);
            }

            $sale  = Sale::create($sale_data);

            $saleData = Sale::with('sale_products')->find($sale->id);
            //purchase products
            $products = [];
            $direct_cost = [];
            if($request->has('products'))
            {
                foreach ($request->products as $key => $value) {
                    $unit = Unit::find($value['base_unit_id']);
                    if($unit->operator == '*'){
                        $qty = $value['qty'] * $unit->operation_value;
                    }else{
                        $qty = $value['qty'] / $unit->operation_value;
                    }

                    $products[] = [
                        'sale_id'          => $sale->id,
                        'product_id'       => $value['id'],
                        'qty'              => $value['qty'],
                        'sale_unit_id'     => $unit ? $unit->id : null,
                        'net_unit_price'   => $value['net_unit_price'],
                        'discount'         => 0,
                        'tax_rate'         => $value['tax_rate'],
                        'tax'              => $value['tax'],
                        'total'            => $value['subtotal']
                    ];
                    
                    $product = DB::table('production_products as pp')
                    ->selectRaw('pp.*')
                    ->join('productions as p','pp.production_id','=','p.id')
                    ->where([
                        ['p.warehouse_id', $warehouse_id],
                        ['pp.product_id',$value['id']],
                    ])
                    ->first();
                    if($product){
                        $direct_cost[] = $qty * ($product ? $product->per_unit_cost : 0);
                    }

                    $warehouse_product = WarehouseProduct::where([
                        ['warehouse_id', $warehouse_id],
                        ['product_id',$value['id']],['qty','>',0],
                    ])->first();
                    if($warehouse_product)
                    {
                        $warehouse_product->qty -= $qty;
                        $warehouse_product->update();
                    }
                }
               
                if(!empty($products) && count($products) > 0)
                {
                    SaleProduct::insert($products);
                }
            }

            $sum_direct_cost = array_sum($direct_cost);
            $total_tax = ($request->total_tax ? $request->total_tax : 0) + ($request->order_tax ? $request->order_tax : 0);
            
            if(empty($sale))
            {
                if(!empty($sale_data['document'])){
                    $this->delete_file($sale_data['document'], SALE_DOCUMENT_PATH);
                }
            }
            

            $data = $this->sale_balance_add($request->memo_no,$request->grand_total,$total_tax,
            $sum_direct_cost,$customer->coa->id,$customer->name,$request->sale_date,
            $payment_data, $warehouse_id);

            if($sale)
            {
                $status = true;
                $message = 'Data has been stored successfully!';
                $data    = [
                    'sale_id'=>$sale->id
                ];
            }else{
                $status = false;
                $message = 'Failed to store data!';
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            $status = false;
            $message = $e->getMessage();
        }
        return $this->sendResult($message,$data,$errors,$status);
    }

    private function sale_balance_add($invoice_no, $grand_total, $total_tax,$sum_direct_cost, int $customer_coa_id, string $customer_name, $sale_date, array $payment_data,int $warehouse_id) {

        //Inventory Credit
        $coscr = array(
            'chart_of_account_id' => DB::table('chart_of_accounts')->where('code', $this->coa_head_code('inventory'))->value('id'),
            'warehouse_id'        => $warehouse_id,
            'voucher_no'          => $invoice_no,
            'voucher_type'        => 'INVOICE',
            'voucher_date'        => $sale_date,
            'description'         => 'Inventory Credit For Invoice No '.$invoice_no,
            'debit'               => 0,
            'credit'              => $sum_direct_cost,
            'posted'              => 1,
            'approve'             => 1,
            'created_by'          => auth()->user()->name,
            'created_at'          => date('Y-m-d H:i:s')
        ); 

            // customer Debit
            $sale_coa_transaction = array(
            'chart_of_account_id' => $customer_coa_id,
            'warehouse_id'        => $warehouse_id,
            'voucher_no'          => $invoice_no,
            'voucher_type'        => 'INVOICE',
            'voucher_date'        => $sale_date,
            'description'         => 'Customer debit For Invoice No -  ' . $invoice_no . ' Customer ' .$customer_name,
            'debit'               => $grand_total,
            'credit'              => 0,
            'posted'              => 1,
            'approve'             => 1,
            'created_by'          => auth()->user()->name,
            'created_at'          => date('Y-m-d H:i:s')
        );

        $product_sale_income = array(
            'chart_of_account_id' => DB::table('chart_of_accounts')->where('code', $this->coa_head_code('product_sale'))->value('id'),
            'warehouse_id'        => $warehouse_id,
            'voucher_no'          => $invoice_no,
            'voucher_type'        => 'INVOICE',
            'voucher_date'        => $sale_date,
            'description'         => 'Sale Income For Invoice NO - ' . $invoice_no . ' Customer ' .$customer_name,
            'debit'               => 0,
            'credit'              => $grand_total,
            'posted'              => 1,
            'approve'             => 1,
            'created_by'          => auth()->user()->name,
            'created_at'          => date('Y-m-d H:i:s')
        ); 

        Transaction::insert([
            $coscr, $sale_coa_transaction, $product_sale_income
        ]);

        if($total_tax){
            $tax_info = array(
                'chart_of_account_id' => DB::table('chart_of_accounts')->where('code', $this->coa_head_code('tax'))->value('id'),
                'warehouse_id'        => $warehouse_id,
                'voucher_no'          => $invoice_no,
                'voucher_type'        => 'INVOICE',
                'voucher_date'        => $sale_date,
                'description'         => 'Sale Total Tax For Invoice NO - ' . $invoice_no . ' Customer ' .$customer_name,
                'debit'               => $total_tax,
                'credit'              => 0,
                'posted'              => 1,
                'approve'             => 1,
                'created_by'          => auth()->user()->name,
                'created_at'          => date('Y-m-d H:i:s')
            ); 
            Transaction::create($tax_info);
        }
        

        if(!empty($payment_data['paid_amount']))
        {
        
            /****************/
            $customer_credit = array(
                'chart_of_account_id' => $customer_coa_id,
                'warehouse_id'        => $warehouse_id,
                'voucher_no'          => $invoice_no,
                'voucher_type'        => 'INVOICE',
                'voucher_date'        => $sale_date,
                'description'         => 'Customer credit for Paid Amount For Customer Invoice NO- ' . $invoice_no . ' Customer- ' . $customer_name,
                'debit'               => 0,
                'credit'              => $payment_data['paid_amount'],
                'posted'              => 1,
                'approve'             => 1,
                'created_by'          => auth()->user()->name,
                'created_at'          => date('Y-m-d H:i:s')
            );
            if($payment_data['payment_method'] == 1){
                //Cah In Hand debit
                $payment = array(
                    'chart_of_account_id' => $payment_data['account_id'],
                    'warehouse_id'        => $warehouse_id,
                    'voucher_no'          => $invoice_no,
                    'voucher_type'        => 'INVOICE',
                    'voucher_date'        => $sale_date,
                    'description'         => 'Cash in Hand in Sale for Invoice No - ' . $invoice_no . ' customer- ' .$customer_name,
                    'debit'               => $payment_data['paid_amount'],
                    'credit'              => 0,
                    'posted'              => 1,
                    'approve'             => 1,
                    'created_by'          => auth()->user()->name,
                    'created_at'          => date('Y-m-d H:i:s')
                );
            }else{
                // Bank Ledger
                $payment = array(
                    'chart_of_account_id' => $payment_data['account_id'],
                    'warehouse_id'        => $warehouse_id,
                    'voucher_no'          => $invoice_no,
                    'voucher_type'        => 'INVOICE',
                    'voucher_date'        => $sale_date,
                    'description'         => 'Paid amount for customer  Invoice No - ' . $invoice_no . ' customer -' . $customer_name,
                    'debit'               => $payment_data['paid_amount'],
                    'credit'              => 0,
                    'posted'              => 1,
                    'approve'             => 1,
                    'created_by'          => auth()->user()->name,
                    'created_at'          => date('Y-m-d H:i:s')
                );
            }
            Transaction::insert([$customer_credit,$payment]);
            
        }
    }

    public function sales_list(Request $request)
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;

        $sales = DB::table('sales as s')
        ->selectRaw('s.*,c.name as customer_name,c.shop_name,w.name as warehouse_name,
        d.name as district_name,u.name as upazila_name,r.name as route_name,a.name as area_name')
        ->leftjoin('customers as c','s.customer_id','=','c.id')
        ->leftjoin('warehouses as w','s.warehouse_id','=','w.id')
        ->leftjoin('locations as d', 'c.district_id', '=', 'd.id')
        ->leftjoin('locations as u', 'c.upazila_id', '=', 'u.id')
        ->leftjoin('locations as r', 'c.route_id', '=', 'r.id')
        ->leftjoin('locations as a', 'c.area_id', '=', 'a.id')
        ->where('s.salesmen_id',auth()->user()->id)
        ->orderBy('s.id','desc')
        ->paginate(10);
        if(!$sales->isEmpty())
        {
            $data = $sales;
        }else{
            $status = false;
            $message = 'No data found!';
        }
        return $this->sendResult($message,$data,$errors,$status);
    }

    public function sale_view(int $id)
    {
        $errors  = [];
        $data    = [];
        $message = "";
        $status  = true;

        $sale = DB::table('sales as s')
        ->leftJoin('customers as c','s.customer_id','=','c.id')
        ->select('s.id','s.memo_no', 's.item', 's.total_qty', 's.total_discount', 's.total_tax', 's.total_price', 's.order_tax_rate', 's.order_tax', 
        's.order_discount', 's.shipping_cost', 's.labor_cost', 's.grand_total', 's.previous_due', 's.net_total', 's.paid_amount', 
        's.due_amount','s.sr_commission_rate','s.total_commission', 's.payment_status',  's.note', 's.sale_date', 
        's.delivery_status', 's.delivery_date','c.name as customer_name','c.shop_name as customer_shop_name','c.address as customer_address')
        ->where([['s.id',$id],['s.salesmen_id',auth()->user()->id]])
        ->first();
        if($sale)
        {
            $products = DB::table('sale_products as sp')
                ->leftJoin('products as p','sp.product_id','=','p.id')
                ->leftJoin('units as u','sp.sale_unit_id','=','u.id')
                ->selectRaw('p.name,p.code,u.unit_name,sp.qty,sp.net_unit_price,sp.tax,sp.total')
                ->where('sp.sale_id',$sale->id)
                ->get();
            if($products)
            {
                $data = [
                    "id"                 => $sale->id,
                    "memo_no"            => $sale->memo_no,
                    "item"               => $sale->item,
                    "total_qty"          => $sale->total_qty,
                    "total_discount"     => $sale->total_discount,
                    "total_tax"          => $sale->total_tax,
                    "total_price"        => $sale->total_price,
                    "order_tax_rate"     => $sale->order_tax_rate,
                    "order_tax"          => $sale->order_tax,
                    "order_discount"     => $sale->order_discount,
                    "shipping_cost"      => $sale->shipping_cost,
                    "labor_cost"         => $sale->labor_cost,
                    "grand_total"        => $sale->grand_total,
                    "previous_due"       => $sale->previous_due,
                    "net_total"          => $sale->net_total,
                    "paid_amount"        => $sale->paid_amount,
                    "due_amount"         => $sale->due_amount,
                    "sr_commission_rate" => $sale->sr_commission_rate,
                    "total_commission"   => $sale->total_commission,
                    "payment_status"     => $sale->payment_status,
                    "note"               => $sale->note,
                    "sale_date"          => $sale->sale_date,
                    "delivery_status"    => $sale->delivery_status,
                    "delivery_date"      => $sale->delivery_date,
                    "customer_name"      => $sale->customer_name,
                    "customer_shop_name" => $sale->customer_shop_name,
                    "customer_address"   => $sale->customer_address,
                    'sale_products'      => $products
                ];
            }else{
                $status = false;
                $message = 'No data found!';
            }
        }else{
            $status = false;
            $message = 'No data found!';
        }
        return $this->sendResult($message,$data,$errors,$status);
    }



}
