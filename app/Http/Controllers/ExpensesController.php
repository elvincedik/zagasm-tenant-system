<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\UserWarehouse;
use App\Models\Account;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\PaymentMethod;
use App\Models\Role;
use App\Models\Warehouse;
use App\utils\helpers;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpensesController extends BaseController
{

    //-------------- Show All  Expenses -----------\\

    public function index(request $request)
    {
        $this->authorizeForUser($request->user('api'), 'view', Expense::class);

        // How many items do you want to display.
        $perPage = $request->limit;
        $pageStart = \Request::get('page', 1);
        // Start displaying items from this number;
        $offSet = ($pageStart * $perPage) - $perPage;
        $order = $request->SortField;
        $dir = $request->SortType;
        $helpers = new helpers();
        $role = Auth::user()->roles()->first();
        $view_records = Role::findOrFail($role->id)->inRole('record_view');
        // Filter fields With Params to retrieve
        $columns = array(0 => 'Ref', 1 => 'warehouse_id', 2 => 'date', 3 => 'expense_category_id', 4 => 'account_id', 5 => 'payment_method_id');
        $param = array(0 => 'like', 1 => '=', 2 => '=', 3 => '=', 4 => '=', 5 => '=');
        $data = array();

        // Check If User Has Permission View  All Records
        $Expenses = Expense::with('expense_category', 'warehouse', 'account')
            ->where('deleted_at', '=', null)
            ->where(function ($query) use ($view_records) {
                if (!$view_records) {
                    return $query->where('user_id', '=', Auth::user()->id);
                }
            });

        //Multiple Filter
        $Filtred = $helpers->filter($Expenses, $columns, $param, $request)
        //Search With Multiple Param
            ->where(function ($query) use ($request) {
                return $query->when($request->filled('search'), function ($query) use ($request) {
                    return $query->where('Ref', 'LIKE', "%{$request->search}%")
                        ->orWhere('date', 'LIKE', "%{$request->search}%")
                        ->orWhere('details', 'LIKE', "%{$request->search}%")
                        ->orWhere(function ($query) use ($request) {
                            return $query->whereHas('expense_category', function ($q) use ($request) {
                                $q->where('name', 'LIKE', "%{$request->search}%");
                            });
                        })
                        ->orWhere(function ($query) use ($request) {
                            return $query->whereHas('warehouse', function ($q) use ($request) {
                                $q->where('name', 'LIKE', "%{$request->search}%");
                            });
                        })
                         ->orWhere(function ($query) use ($request) {
                            return $query->whereHas('payment_method', function ($q) use ($request) {
                                $q->where('name', 'LIKE', "%{$request->search}%");
                            });
                        })
                        ->orWhere(function ($query) use ($request) {
                            return $query->whereHas('account', function ($q) use ($request) {
                                $q->where('account_name', 'LIKE', "%{$request->search}%");
                            });
                        });
                });
            });
        $totalRows = $Filtred->count();
        if($perPage == "-1"){
            $perPage = $totalRows;
        }
        $Expenses = $Filtred->offset($offSet)
            ->limit($perPage)
            ->orderBy($order, $dir)
            ->get();

        foreach ($Expenses as $Expense) {

            $item['id'] = $Expense->id;
            $item['date'] = $Expense->date;
            $item['Ref'] = $Expense->Ref;
            $item['details'] = $Expense->details;
            $item['amount'] = $Expense->amount;
            $item['payment_method']  = $Expense['payment_method']->name;
            $item['warehouse_name'] = $Expense['warehouse']->name;
            $item['category_name'] = $Expense['expense_category']->name;
            $item['account_name'] = $Expense['account']?$Expense['account']->account_name:'N/D';

            $data[] = $item;
        }

        $Expenses_category = ExpenseCategory::where('deleted_at', '=', null)->get(['id', 'name']);
        $accounts = Account::where('deleted_at', '=', null)->get(['id', 'account_name']);
        $payment_methods = PaymentMethod::where('deleted_at', '=', null)->get(['id', 'name']);

          //get warehouses assigned to user
          $user_auth = auth()->user();
          if($user_auth->is_all_warehouses){
             $warehouses = Warehouse::where('deleted_at', '=', null)->get(['id', 'name']);
          }else{
             $warehouses_id = UserWarehouse::where('user_id', $user_auth->id)->pluck('warehouse_id')->toArray();
             $warehouses = Warehouse::where('deleted_at', '=', null)->whereIn('id', $warehouses_id)->get(['id', 'name']);
          }

        return response()->json([
            'expenses' => $data,
            'Expenses_category' => $Expenses_category,
            'warehouses' => $warehouses,
            'accounts' => $accounts,
            'totalRows' => $totalRows,
            'payment_methods' => $payment_methods,
        ]);

    }

    //-------------- Store New Expense -----------\\

    public function store(Request $request)
    {
        $this->authorizeForUser($request->user('api'), 'create', Expense::class);

        \DB::transaction(function () use ($request) {
            request()->validate([
                'expense.date' => 'required',
                'expense.warehouse_id' => 'required',
                'expense.category_id' => 'required',
                'expense.details' => 'required',
                'expense.amount' => 'required',
                'expense.payment_method_id' => 'required',
            ]);

            Expense::create([
                'organization_id' => auth()->user()->organization_id,
                'user_id' => Auth::user()->id,
                'date' => $request['expense']['date'],
                'Ref' => $this->getNumberOrder(),
                'payment_method_id' => $request['expense']['payment_method_id'],
                'warehouse_id' => $request['expense']['warehouse_id'],
                'expense_category_id' => $request['expense']['category_id'],
                'account_id' => $request['expense']['account_id'],
                'details' => $request['expense']['details'],
                'amount' => $request['expense']['amount'],
            ]);

            $account = Account::find($request['expense']['account_id']);

            if($account){
                $account->update([
                    'balance' => $account->balance - $request['expense']['amount'],
                ]);
            }

        }, 10);

        return response()->json(['success' => true]);
    }

    //------------ function show -----------\\

    public function show($id){
        //
        
        }

    //-------------- Update  Expense -----------\\

    public function update(Request $request, $id)
    {

        $this->authorizeForUser($request->user('api'), 'update', Expense::class);
        \DB::transaction(function () use ($request, $id) {
            $role = Auth::user()->roles()->first();
            $view_records = Role::findOrFail($role->id)->inRole('record_view');
            $expense = Expense::findOrFail($id);

            // Check If User Has Permission view All Records
            if (!$view_records) {
                // Check If User->id === expense->id
                $this->authorizeForUser($request->user('api'), 'check_record', $expense);
            }

            request()->validate([
                'expense.date' => 'required',
                'expense.warehouse_id' => 'required',
                'expense.category_id' => 'required',
                'expense.details' => 'required',
                'expense.amount' => 'required',
                'expense.payment_method_id' => 'required',
            ]);

            $account = Account::find($expense->account_id);

            if ($account) {
                $account->update([
                    'balance' => $account->balance + $expense->amount,
                ]);
            }

            Expense::whereId($id)->update([
                'date' => $request['expense']['date'],
                'payment_method_id' => $request['expense']['payment_method_id'],
                'warehouse_id' => $request['expense']['warehouse_id'],
                'expense_category_id' => $request['expense']['category_id'],
                'account_id' => $request['expense']['account_id']?$request['expense']['account_id']:NULL,
                'details' => $request['expense']['details'],
                'amount' => $request['expense']['amount'],
            ]);

            $account = Account::find($request['expense']['account_id']);
            if($account){
                $account->update([
                    'balance' => $account->balance - $request['expense']['amount'],
                ]);
            }

        }, 10);

        return response()->json(['success' => true]);
    }

    //-------------- Delete Expense -----------\\

    public function destroy(Request $request, $id)
    {
        $this->authorizeForUser($request->user('api'), 'delete', Expense::class);
        $role = Auth::user()->roles()->first();
        $view_records = Role::findOrFail($role->id)->inRole('record_view');
        $expense = Expense::findOrFail($id);

        // Check If User Has Permission view All Records
        if (!$view_records) {
            // Check If User->id === expense->id
            $this->authorizeForUser($request->user('api'), 'check_record', $expense);
        }

        Expense::whereId($id)->update([
            'deleted_at' => Carbon::now(),
        ]);

        $account = Account::where('id', $expense->account_id)->exists();

        if ($account) {
            // Account exists, perform the update
            $account = Account::find($expense->account_id);
            $account->update([
                'balance' => $account->balance + $expense->amount,
            ]);
        }

        return response()->json(['success' => true]);
    }

    //-------------- Delete by selection  ---------------\\

    public function delete_by_selection(Request $request)
    {
        $this->authorizeForUser($request->user('api'), 'delete', Expense::class);
        $selectedIds = $request->selectedIds;
        $role = Auth::user()->roles()->first();
        $view_records = Role::findOrFail($role->id)->inRole('record_view');

        foreach ($selectedIds as $expense_id) {
            $expense = Expense::findOrFail($expense_id);

            // Check If User Has Permission view All Records
            if (!$view_records) {
                // Check If User->id === expense->id
                $this->authorizeForUser($request->user('api'), 'check_record', $expense);
            }
            Expense::whereId($expense_id)->update([
                'deleted_at' => Carbon::now(),
            ]);
    
            $account = Account::where('id', $expense->account_id)->exists();

            if ($account) {
                // Account exists, perform the update
                $account = Account::find($expense->account_id);
                $account->update([
                    'balance' => $account->balance + $expense->amount,
                ]);
            }
        }
        return response()->json(['success' => true]);
    }

    //--------------- Reference Number of Expense ----------------\\

    public function getNumberOrder()
    {

        $last = DB::table('expenses')->latest('id')->first();

        if ($last) {
            $item = $last->Ref;
            $nwMsg = explode("_", $item);
            $inMsg = $nwMsg[1] + 1;
            $code = $nwMsg[0] . '_' . $inMsg;
        } else {
            $code = 'EXP_1111';
        }
        return $code;

    }


    //---------------- Show Form Create Expense ---------------\\

    public function create(Request $request)
    {

        $this->authorizeForUser($request->user('api'), 'create', Expense::class);

        //get warehouses assigned to user
        $user_auth = auth()->user();
        if($user_auth->is_all_warehouses){
            $warehouses = Warehouse::where('deleted_at', '=', null)->get(['id', 'name']);
        }else{
            $warehouses_id = UserWarehouse::where('user_id', $user_auth->id)->pluck('warehouse_id')->toArray();
            $warehouses = Warehouse::where('deleted_at', '=', null)->whereIn('id', $warehouses_id)->get(['id', 'name']);
        }

        $Expenses_category = ExpenseCategory::where('deleted_at', '=', null)->get(['id', 'name']);
        $accounts = Account::where('deleted_at', '=', null)->get(['id', 'account_name']);
        $payment_methods = PaymentMethod::where('deleted_at', '=', null)->get(['id', 'name']);

        return response()->json([
            'Expenses_category' => $Expenses_category,
            'warehouses' => $warehouses,
            'accounts' => $accounts,
            'payment_methods' => $payment_methods,
        ]);
    }

    //------------- Show Form Edit Expense -----------\\

    public function edit(Request $request, $id)
    {

        $this->authorizeForUser($request->user('api'), 'update', Expense::class);
        $role = Auth::user()->roles()->first();
        $view_records = Role::findOrFail($role->id)->inRole('record_view');
        $Expense = Expense::where('deleted_at', '=', null)->findOrFail($id);

        // Check If User Has Permission view All Records
        if (!$view_records) {
            // Check If User->id === Expense->id
            $this->authorizeForUser($request->user('api'), 'check_record', $Expense);
        }

        if ($Expense->warehouse_id) {
            if (Warehouse::where('id', $Expense->warehouse_id)
                ->where('deleted_at', '=', null)
                ->first()) {
                $data['warehouse_id'] = $Expense->warehouse_id;
            } else {
                $data['warehouse_id'] = '';
            }
        } else {
            $data['warehouse_id'] = '';
        }


        if ($Expense->account_id) {
            if (Account::where('id', $Expense->account_id)
                ->where('deleted_at', '=', null)
                ->first()) {
                $data['account_id'] = $Expense->account_id;
            } else {
                $data['account_id'] = '';
            }
        } else {
            $data['account_id'] = '';
        }


        if ($Expense->expense_category_id) {
            if (ExpenseCategory::where('id', $Expense->expense_category_id)
                ->where('deleted_at', '=', null)
                ->first()) {
                $data['category_id'] = $Expense->expense_category_id;
            } else {
                $data['category_id'] = '';
            }
        } else {
            $data['category_id'] = '';
        }

        $data['date'] = $Expense->date;
        $data['amount'] = $Expense->amount;
        $data['details'] = $Expense->details;
        $data['payment_method_id'] = $Expense->payment_method_id;

        //get warehouses assigned to user
        $user_auth = auth()->user();
        if($user_auth->is_all_warehouses){
            $warehouses = Warehouse::where('deleted_at', '=', null)->get(['id', 'name']);
        }else{
            $warehouses_id = UserWarehouse::where('user_id', $user_auth->id)->pluck('warehouse_id')->toArray();
            $warehouses = Warehouse::where('deleted_at', '=', null)->whereIn('id', $warehouses_id)->get(['id', 'name']);
        }

        $Expenses_category = ExpenseCategory::where('deleted_at', '=', null)->get(['id', 'name']);
        $accounts = Account::where('deleted_at', '=', null)->get(['id', 'account_name']);
        $payment_methods = PaymentMethod::where('deleted_at', '=', null)->get(['id', 'name']);

        return response()->json([
            'expense' => $data,
            'expense_Category' => $Expenses_category,
            'warehouses' => $warehouses,
            'accounts' => $accounts,
            'payment_methods' => $payment_methods,
        ]);
    }

}