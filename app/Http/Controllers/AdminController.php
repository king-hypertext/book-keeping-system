<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Savings;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use Illuminate\Contracts\View\View;
use App\Http\Requests\SignUpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CustomerInfoRequest;

class AdminController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth']);
    // }
    /**
     * verify and authenticate user
     */
    public function verify_login(LoginRequest $request)
    {
        $request->validated();
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('admin/dashboard');
        }

        return back()->with('failed', 'Invalid login credentials');
    }
    /**
     * add a new user to the application
     */
    public function save(SignUpRequest $request)
    {
        $request->validated();
        $user = User::create([
            'name' => $request->name,
            'position' => $request->position,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        if ($user) {
            auth()->attempt($request->only('username', 'password'));
            return redirect('/admin/dashboard');
        }
    }
    /**
     * logout the user from the application
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();
        return redirect('/');
    }
    /**
     * add a new customer
     */
    public function addCustomer(CustomerInfoRequest $request)
    {
        $request->validated();
        Customer::create([
            'name' => $request->name,
            'date_of_birth' => $request->date_of_birth,
            'phone' => $request->phone,
            'card_number' => $request->card_number,
            'next_of_king' => $request->next_of_king,
            'daily_payable_amount' => $request->daily_payable_amount,
        ]);
        return redirect('/admin/dashboard')->with(['success' => 'New Customer Added Successfully']);
    }
    /**
     * search customer in the database
     */
    public function searchCustomer(Request $request)
    {
        $search = $request->customer;
        if (empty($search)) {
            return redirect('/admin/dashboard');
        }
        $customers = Customer::where('name', 'LIKE', "%$search%")->orWhere('card_number', 'LIKE', "%$search%")->latest()->get();

        return view('search', ['customers' => $customers, 'search' => $search]);
    }
    /**
     * shows the home page of the application
     */
    public function index(): View
    {
        $perPage = 10;
        $revenue = Savings::all()->sum('deposit_amount'); //selects the total(sum) revenue
        $withdrawals = Savings::all()->sum('withdrawal_amount'); //selects total(sum) withdrawals
        $balance = $revenue - $withdrawals; //calculate total balance
        $customers = Customer::latest()->paginate($perPage); //selects all records from current customer/15 records per table
        return view('dashboard', ['revenue' => $revenue, 'withdrawals' => $withdrawals, 'balance' => $balance, 'customers' => $customers]);
    }
    /**
     * shows the settings page of the application
     */
    public function settings(): View
    {
        $admin = Auth::user();
        return view('settings', ['admin' => $admin]);
    }
    /**
     * shows the form for adding new customer
     */
    public function create(): View
    {
        return view('add-customer');
    }
    /**
     * display specific customer in the application
     */
    public function showCustomer(Customer $customer): View
    {
        $perPage = 10;

        $customer_total = Savings::where('customer_id', $customer->id)->sum('deposit_amount');
        $customer_total_withdrawal = Savings::where(['trans_type' => 'withdraw', 'customer_id' => $customer->id])->sum('withdrawal_amount');
        $customer_total_balance = $customer_total - $customer_total_withdrawal;
        $customer_savings_info = Savings::where('card_number', $customer->card_number)->orderBy('updated_at', 'desc')->paginate($perPage);
        return view('customer', ['customer' => $customer, 'customer_data' => $customer_savings_info, 'total_savings' => $customer_total, 'withdrawals' => $customer_total_withdrawal, 'balance' => $customer_total_balance]);
    }
    /**
     * update a user in the application
     */
    public function editCustomer(Request $request, Customer $customer)
    {
        $request->validate([
            "name" => "required|string",
            "date_of_birth" => "required|date|before_or_equal:today",
            "phone" => "string|required|max_digits:14",
            "card_number" => "required|numeric|unique:customers,card_number,$customer->id",
            "next_of_king" => "required|string",
            "daily_payable_amount" => "required|integer"
        ]);
        $customer->update([
            'name' => $request->name,
            'date_of_birth' => $request->date_of_birth,
            'phone' => $request->phone,
            'card_number' => $request->card_number,
            'next_of_king' => $request->next_of_king,
            'daily_payable_amount' => $request->daily_payable_amount,
        ]);
        // dd($customer);
        return back()->with(['success' => 'Customer Info Updated']);
    }
    /**
     * delete a customer from the application / database
     */
    function deleteCustomer(Request $request, Customer $customer, Savings $savings)
    {
        if (!$request->isMethod('POST')) {
            abort(404, 'Not authorized!');
        }
        try {
            if ($savings->where('card_number', $customer->card_number)->delete()) {
                $customer->delete();
            }
            return redirect('/admin/dashboard')->with(['delete' => 'The Customer has been successfully deleted']);
        } catch (\Throwable $error) {
            throw $error;
        }
    }
    /**
     * make transaction - deposit
     */
    public function deposit(Request $request, Customer $customer): RedirectResponse
    {
        $deposit = Savings::create([
            'customer_id' => $customer->id,
            'card_number' => $customer->card_number,
            'deposit_amount' => $customer->daily_payable_amount,
            'withdrawal_amount' => 0,
            'trans_type' => $request->trans_type,
            'date' => date('Y-m-d')
        ]);
        if ($deposit) {
            return back()->with(['success' => 'Deposit Success']);
        }
        return back()->with(['error' => 'Transaction Failed']);
    }
    /**
     * make transaction - withdraw
     */
    public function withdraw(Request $request, Customer $customer): RedirectResponse
    {
        $total_dep_amount = Savings::where(['customer_id' => $customer->id, 'trans_type' => 'deposit'])->sum('deposit_amount');
        $total_with_amount = Savings::where(['customer_id' => $customer->id, 'trans_type' => 'withdraw'])->sum('withdrawal_amount');
        $customer_available_balance = $total_dep_amount - $total_with_amount;
        if (($request->amount) >= $customer_available_balance) {
            return back()->with(['error' => 'Transaction Failed']);
            exit;
        }
        $withdraw = Savings::create([
            'customer_id' => $customer->id,
            'card_number' => $customer->card_number,
            'withdrawal_amount' => $request->amount,
            'deposit_amount' => 0,
            'trans_type' => $request->trans_type,
            'date' => date('Y-m-d')
        ]);
        if ($withdraw) {
            return back()->with(['success' => 'Withdrawal success']);
        }
        return back()->with(['error' => 'Transaction Failed']);
    }
    /**
     * update user 
     */
    public function update(Request $request, User $admin)
    {
        $request->validate([
            "name" => "required|string",
            "position" => "nullable|string",
            "username" => "required|string|unique:admin,username,$admin->id",
            "email" => "required|unique:admin,email,$admin->id",
            "password" => "required|min:8"
        ]);
        $admin = User::find($admin->id);
        // dd($admin);
        $admin->update([
            'name' => $request->name,
            'position' => $request->position,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return redirect()->back()->with(['success' => 'Info Updated Successfully']);
    }
    /**
     * delete user from the application
     */
    public function deleteUser(Request $request, User $admin)
    {
        if (!$request->isMethod('POST')) {
            abort(404, 'Not authorized!');
        }
        try {
            DB::select("DELETE FROM admin WHERE id=$admin->id");
            return redirect()->route('login');
        } catch (\Throwable $th) {
            return back()->with(['error' => 'Could not perform operation']);
            throw $th;
        }
    }
    /**
     * upload user image
     */
    public function upload(Request $request): RedirectResponse
    {
        $id = auth()->id();

        $request->validate([
            "image" => "required|file|mimes:png,jpg,jpeg"
        ]);
        $fileName = time() . '_' . $request->image->getClientOriginalName();
        $request->image->move(public_path('assets/images/admin'), $fileName);
        $image = $fileName;
        $admin = User::find($id);
        $uploadedImage = public_path() . "/assets/images/admin/" . $admin->image;
        if (file_exists($uploadedImage)) {
            // $admin->update([
            //     "image" => ""
            // ]);
            @unlink($uploadedImage);
        }
        // dd($admin->image);
        $upload = $admin->update([
            "image" => $image
        ]);
        if ($upload) {
            return back()->with('success', 'Image Uploaded Succesfully');
        }
        return back()->with(['error' => 'Could not perform operation']);
    }
}
