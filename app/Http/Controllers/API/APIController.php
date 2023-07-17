<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class APIController extends Controller
{
    
    public function sendResult($message,$data = [],$errors = [],$status = true)
    {
        $result = [
            "status" => $status,
            "data" => $data,
            "message" => $message,
            "errors" => $errors
        ];
        return response()->json($result);
    }

    protected function coa_head_code(string $head_name)
    {
        switch (strtolower(str_replace('-','_',$head_name))) {
            case 'assets':
                return 1;
                break;
            case 'non_current_asset':
                return 101;
                break;
            case 'inventory':
                return 10101;
                break;
            case 'current_asset':
                return 102;
                break;
            case 'cash_&_cash_equivalent':
                return 10201;
                break;
            case 'cash_in_hand':
                return 1020101;
                break;
            case 'cash_at_bank':
                return 1020102;
                break;
            case 'cash_at_mobile_bank':
                return 1020103;
                break;
            case 'account_receivable':
                return 10202;
                break;
            case 'customer_receivable':
                return 10202010001;
                break;
            case '1_walking_customer':
                return 10202010001;
                break;
            case 'loan_receivable':
                return 1020202;
                break;
            case 'equity':
                return 2;
                break;
            case 'income':
                return 3;
                break;
            case 'product_sale':
                return 301;
                break;
            case 'service_income':
                return 302;
                break;
            case 'expense':
                return 4;
                break;
            case 'default_expense':
                return 401;
                break;
            case 'material_purchase':
                return 402;
                break;
            case 'employee_salary':
                return 403;
                break;
            case 'machine_purchase':
                return 404;
                break;
            case 'maintenance_service':
                return 405;
                break;
            case 'liabilities':
                return 5;
                break;
            case 'non_current_liabilities':
                return 501;
                break;
            case 'current_liabilities':
                return 502;
                break;
            case 'account_payable':
                return 50201;
                break;
            case 'default_supplier':
                return 5020101;
                break;
            case 'employee_ledger':
                return 50202;
                break;
            case 'tax':
                return 50203;
                break;
        }
    }

}
