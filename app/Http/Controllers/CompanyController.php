<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;
use App\Models\Company;
use App\Models\Country;
use App\Models\City;
use App\Models\User;
use App\Models\SubscriptionInvoice;
use Illuminate\Support\Facades\Auth;


class CompanyController extends Controller
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
        $company=Company::find(Auth::user()->company_id);
        $countries=Country::all();
        $cities=City::where('country_id',$company->country_id)->get();
        $data=compact('company','countries','cities');
        return view('company_profile')->with($data);
    }
    public function add_company(Request $request)
    {
        $filename = '';
        if($request->hasFile('logo'))
        {
            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension();
            $filename = time().'.'.$extension;
            $file->move(public_path().'/company_logos/',$filename);
        }
        else
        {
            $filename = 'noimage.jpg';
        }
        $company = new Company;
        $company->company_name = $request->company_name;
        $company->address = $request->company_address;
        $company->city_id = $request->company_city;
        $company->country_id = $request->company_country;
        $company->linkedin = $request->company_linkedin;
        $company->website = $request->company_website;
        $company->logo = $filename;
        $company->save();
        $company_id = $company->id;
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $user->company_id = $company_id;
        $user->save();
        //remove is_new from session
        $request->session()->forget('is_new');
        //create subscription invoice
        $subscription_invoice = new SubscriptionInvoice;
        $subscription_invoice->stripe_id = "Free";
        $subscription_invoice->company_id = $company_id;
        $subscription_invoice->subscription_id = 1;
        $subscription_invoice->amount = 0;
        $subscription_invoice->is_active = 1;
        $subscription_invoice->start_date = date('Y-m-d');
        $subscription_invoice->end_date = "Lifetime";
        $subscription_invoice->save();
        return redirect('/company_profile')->with('success','Company Profile Setup Successfully');
    }
    public function update_company(Request $request,$id)
    {
        $filename = '';
        $company=Company::find($id);
        if($request->hasFile('logo'))
        {
            $file = $request->file('logo');
            $extension = $file->getClientOriginalExtension();
            $filename = time().'.'.$extension;
            $file->move(public_path().'/company_logos/',$filename);
        }
        else
        {
            $filename = $company->logo;
        }
        $company->company_name = $request->company_name;
        $company->address = $request->company_address;
        $company->city_id = $request->company_city;
        $company->country_id = $request->company_country;
        $company->linkedin = $request->company_linkedin;
        $company->website = $request->company_website;
        $company->logo = $filename;
        $company->save();
        return redirect('/company_profile')->with('success','Company Profile Updated Successfully');
    }
}
