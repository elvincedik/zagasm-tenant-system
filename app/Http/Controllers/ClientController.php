<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\PaymentMethod;
use App\Models\EcommerceClient;
use App\Models\Setting;
use App\Models\Account;
use App\utils\helpers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\SaleReturn;
use App\Models\PaymentSaleReturns;
use App\Models\Sale;
use App\Models\PaymentSale;
use DB;
use Illuminate\Http\Request;

class ClientController extends BaseController
{

    //------------- Get ALL Customers -------------\\

    public function index(request $request)
    {
        $this->authorizeForUser($request->user('api'), 'view', Client::class);
        // How many items do you want to display.
        $perPage = $request->limit;
        $pageStart = \Request::get('page', 1);
        // Start displaying items from this number;
        $offSet = ($pageStart * $perPage) - $perPage;
        $order = $request->SortField;
        $dir = $request->SortType;
        $helpers = new helpers();
        // Filter fields With Params to retrieve
        $columns = array(0 => 'name', 1 => 'code', 2 => 'phone', 3 => 'email');
        $param = array(0 => 'like', 1 => 'like', 2 => 'like', 3 => 'like');
        $data = array();
        $clients = Client::where('deleted_at', '=', null);

        //Multiple Filter
        $Filtred = $helpers->filter($clients, $columns, $param, $request)
        // Search With Multiple Param
            ->where(function ($query) use ($request) {
                return $query->when($request->filled('search'), function ($query) use ($request) {
                    return $query->where('name', 'LIKE', "%{$request->search}%")
                        ->orWhere('code', 'LIKE', "%{$request->search}%")
                        ->orWhere('phone', 'LIKE', "%{$request->search}%")
                        ->orWhere('email', 'LIKE', "%{$request->search}%");
                });
            });
        $totalRows = $Filtred->count();
        if($perPage == "-1"){
            $perPage = $totalRows;
        }
        $clients = $Filtred->offset($offSet)
            ->limit($perPage)
            ->orderBy($order, $dir)
            ->get();

        foreach ($clients as $client) {

            $client_exist = EcommerceClient::where('client_id', $client->id)->exists();

            if($client_exist){
                $item['client_ecommerce'] = 'yes';
            }else{
                $item['client_ecommerce'] = 'no';
            }

            $item['total_amount'] = DB::table('sales')
                ->where('deleted_at', '=', null)
                ->where('statut', 'completed')
                ->where('client_id', $client->id)
                ->sum('GrandTotal');

            $item['total_paid'] = DB::table('sales')
                ->where('deleted_at', '=', null)
                ->where('statut', 'completed')
                ->where('client_id', $client->id)
                ->sum('paid_amount');

            $item['due'] = $item['total_amount'] - $item['total_paid'];

            $item['total_amount_return'] = DB::table('sale_returns')
                ->where('deleted_at', '=', null)
                ->where('client_id', $client->id)
                ->sum('GrandTotal');

            $item['total_paid_return'] = DB::table('sale_returns')
                ->where('sale_returns.deleted_at', '=', null)
                ->where('sale_returns.client_id', $client->id)
                ->sum('paid_amount');

            $item['return_Due'] = $item['total_amount_return'] - $item['total_paid_return'];

            $item['id'] = $client->id;
            $item['name'] = $client->name;
            $item['phone'] = $client->phone;
            $item['tax_number'] = $client->tax_number;
            $item['code'] = $client->code;
            $item['email'] = $client->email;
            $item['country'] = $client->country;
            $item['city'] = $client->city;
            $item['adresse'] = $client->adresse;
            $data[] = $item;
        }

        $company_info = Setting::where('deleted_at', '=', null)->first();
        $accounts = Account::where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id','account_name']);

        $clientsWithoutEcommerce = \App\Models\Client::whereNotIn('id', function($query){
            $query->select('client_id')->from('ecommerce_clients');
        })->count();

        $module_name = config('store.name');
        
        return response()->json([
            'clients' => $data,
            'company_info' => $company_info,
            'totalRows' => $totalRows,
            'clients_without_ecommerce' => $clientsWithoutEcommerce,
            'module_name' => $module_name,
            'accounts' => $accounts,
        ]);
    }

    //------------- Store new Customer -------------\\

    public function store(Request $request)
    {
        $this->authorizeForUser($request->user('api'), 'create', Client::class);

        $this->validate($request, [
            'name' => 'required',
            ]
        );

        Client::create([
            'name' => $request['name'],
            'code' => $this->getNumberOrder(),
            'adresse' => $request['adresse'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'country' => $request['country'],
            'city' => $request['city'],
            'tax_number' => $request['tax_number'],
            'organization_id' => auth()->user()->organization_id,
        ]);
        return response()->json(['success' => true]);
    }

    //------------ function show -----------\\

    public function show($id){
        //
        
    }

    //------------- Update Customer -------------\\

    public function update(Request $request, $id)
    {
        $this->authorizeForUser($request->user('api'), 'update', Client::class);
        
        $this->validate($request, [
            'name' => 'required',
            ]
        );

        Client::whereId($id)->update([
            'name' => $request['name'],
            'adresse' => $request['adresse'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'country' => $request['country'],
            'city' => $request['city'],
            'tax_number' => $request['tax_number'],
        ]);
        return response()->json(['success' => true]);

    }

    //------------- delete client -------------\\

    public function destroy(Request $request, $id)
    {
        $this->authorizeForUser($request->user('api'), 'delete', Client::class);

        Client::whereId($id)->update([
            'deleted_at' => Carbon::now(),
        ]);
        return response()->json(['success' => true]);
    }

    //-------------- Delete by selection  ---------------\\

    public function delete_by_selection(Request $request)
    {
        $this->authorizeForUser($request->user('api'), 'delete', Client::class);
        $selectedIds = $request->selectedIds;

        foreach ($selectedIds as $Client_id) {
            Client::whereId($Client_id)->update([
                'deleted_at' => Carbon::now(),
            ]);
        }
        return response()->json(['success' => true]);
    }


    //------------- get Number Order Customer -------------\\

    public function getNumberOrder()
    {
        $last = DB::table('clients')->latest('id')->first();

        if ($last) {
            $code = $last->code + 1;
        } else {
            $code = 1;
        }
        return $code;
    }

    //------------- Get Clients Without Paginate -------------\\

    public function Get_Clients_Without_Paginate()
    {
        $clients = Client::where('deleted_at', '=', null)->get(['id', 'name']);
        return response()->json($clients);
    }

    //------------- Get Clients get_client_store_data Paginate -------------\\

    public function get_client_store_data($id)
    {
        $client = EcommerceClient::where('client_id', $id)->first();

        $data['id']        = $client->id;
        $data['client_id'] = $client->client_id;
        $item['name']      = $client->username;
        $data['email']     = $client->email;
        $data['NewPassword']     = NULL;

        return response()->json($data);
    }



     // import clients
     public function import_clients(Request $request)
     {
 
         ini_set('max_execution_time', 600); //600 seconds = 10 minutes 
        
         $file = $request->file('clients');
         $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
         if ($ext != 'csv') {
             return response()->json([
                 'msg' => 'must be in csv format',
                 'status' => false,
             ]);
         } else {
             $data = [];
             $rowcount = 0;
             if (($handle = fopen($file->getPathname(), "r")) !== false) {
                 $max_line_length = defined('MAX_LINE_LENGTH') ? MAX_LINE_LENGTH : 10000;
                 $header = fgetcsv($handle, $max_line_length, ';'); // Use semicolon as the delimiter
             
                 // Process the header row
                 $escapedHeader = [];
                 foreach ($header as $key => $value) {
                     $lheader = strtolower($value);
                     $escapedItem = preg_replace('/[^a-z]/', '', $lheader);
                     $escapedHeader[] = $escapedItem;
                 }
             
                 $header_colcount = count($header);
                 while (($row = fgetcsv($handle, $max_line_length, ';')) !== false) { // Use semicolon as the delimiter
                     $row_colcount = count($row);
                     if ($row_colcount == $header_colcount) {
                         // Replace empty values with null
                         $row = array_map(function($value) {
                             return $value === '' ? null : $value;
                         }, $row);
                         
                         $entry = array_combine($escapedHeader, $row);
                         $data[] = $entry;
                     } else {
                         return null;
                     }
                     $rowcount++;
                 }
                 fclose($handle);
             } else {
                 return null;
             }
             
 
             $cleanedData = [];
 
             foreach ($data as $row) {
                 $cleanedRow = [];
                 foreach ($row as $key => $value) {
                     $cleanedKey = trim($key);
                     $cleanedRow[$cleanedKey] = $value;
                 }
                 $cleanedData[] = $cleanedRow;
             }
         
            
             $rules = array('name' => 'required');
 
             //-- Create New Client
             foreach ($cleanedData as $key => $value) {
                 $input['name'] = $value['name'];
 
                 $validator = Validator::make($input, $rules);
                 if (!$validator->fails()) {
                     
                     Client::create([
                         'name' => $value['name'],
                         'code' => $this->getNumberOrder(),
                         'adresse' => $value['adresse'] == '' ? null : $value['adresse'],
                         'phone' => $value['phone'] == '' ? null : $value['phone'],
                         'email' => $value['email'] == '' ? null : $value['email'],
                         'country' => $value['country'] == '' ? null : $value['country'],
                         'city' => $value['city'] == '' ? null : $value['city'],
                         'tax_number' => $value['taxnumber'] == '' ? null : $value['taxnumber'],
                     ]);
 
                 }
                
 
             }
 
             return response()->json([
                 'status' => true,
             ], 200);
         }
 
     }
 


     //------------- clients_pay_due -------------\\

     public function clients_pay_due(Request $request)
     {
         $this->authorizeForUser($request->user('api'), 'pay_due', Client::class);
        
         if($request['amount'] > 0){
            $client_sales_due = Sale::where('deleted_at', '=', null) 
            ->where('statut', 'completed')
            ->where([
                ['payment_statut', '!=', 'paid'],
                ['client_id', $request->client_id]
            ])->get();

            $paid_amount_total = $request->amount;

            foreach($client_sales_due as $key => $client_sale){
                if($paid_amount_total == 0)
                break;
                $due = $client_sale->GrandTotal  - $client_sale->paid_amount;

                if($paid_amount_total >= $due){
                    $amount = $due;
                    $payment_status = 'paid';
                }else{
                    $amount = $paid_amount_total;
                    $payment_status = 'partial';
                }

                $payment_sale = new PaymentSale();
                $payment_sale->sale_id = $client_sale->id;
                $payment_sale->account_id =  $request['account_id']?$request['account_id']:NULL;
                $payment_sale->Ref = app('App\Http\Controllers\PaymentSalesController')->getNumberOrder();
                $payment_sale->date = Carbon::now();
                $payment_sale->payment_method_id = $request['payment_method_id'];
                $payment_sale->montant = $amount;
                $payment_sale->change = 0;
                $payment_sale->notes = $request['notes'];
                $payment_sale->user_id = Auth::user()->id;
                $payment_sale->save();

                $account = Account::where('id', $request['account_id'])->exists();

                if ($account) {
                    // Account exists, perform the update
                    $account = Account::find($request['account_id']);
                    $account->update([
                        'balance' => $account->balance + $amount,
                    ]);
                }

                $client_sale->paid_amount += $amount;
                $client_sale->payment_statut = $payment_status;
                $client_sale->save();

                $paid_amount_total -= $amount;
            }
        }
        
         return response()->json(['success' => true]);
 
     }

    //------------- clients_pay_sale_return_due -------------\\

    public function pay_sale_return_due(Request $request)
    {
        $this->authorizeForUser($request->user('api'), 'pay_sale_return_due', Client::class);
        
        if($request['amount'] > 0){
            $client_sell_return_due = SaleReturn::where('deleted_at', '=', null)
            ->where([
                ['payment_statut', '!=', 'paid'],
                ['client_id', $request->client_id]
            ])->get();

            $paid_amount_total = $request->amount;

            foreach($client_sell_return_due as $key => $client_sale_return){
                if($paid_amount_total == 0)
                break;
                $due = $client_sale_return->GrandTotal  - $client_sale_return->paid_amount;

                if($paid_amount_total >= $due){
                    $amount = $due;
                    $payment_status = 'paid';
                }else{
                    $amount = $paid_amount_total;
                    $payment_status = 'partial';
                }

                $payment_sale_return = new PaymentSaleReturns();
                $payment_sale_return->sale_return_id = $client_sale_return->id;
                $payment_sale_return->account_id =  $request['account_id']?$request['account_id']:NULL;
                $payment_sale_return->Ref = app('App\Http\Controllers\PaymentSaleReturnsController')->getNumberOrder();
                $payment_sale_return->date = Carbon::now();
                $payment_sale_return->payment_method_id = $request['payment_method_id'];
                $payment_sale_return->montant = $amount;
                $payment_sale_return->change = 0;
                $payment_sale_return->notes = $request['notes'];
                $payment_sale_return->user_id = Auth::user()->id;
                $payment_sale_return->save();

                $account = Account::where('id', $request['account_id'])->exists();

                if ($account) {
                    // Account exists, perform the update
                    $account = Account::find($request['account_id']);
                    $account->update([
                        'balance' => $account->balance - $amount,
                    ]);
                }

                $client_sale_return->paid_amount += $amount;
                $client_sale_return->payment_statut = $payment_status;
                $client_sale_return->save();

                $paid_amount_total -= $amount;
            }
        }
        
        return response()->json(['success' => true]);

    }

}