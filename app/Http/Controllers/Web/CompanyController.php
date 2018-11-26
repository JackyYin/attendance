<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyContactPerson;
use App\Models\CompanyProfile;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $session = $request->session();

        view()->share('session', $session);
    }

    public function create()
    {
        return view('web.pages.company.create');
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'tax_id_number' => 'required|unique:companies,tax_id_number',
                'name' => 'required|max:50',
                'contact_person' => 'required|max:50',
                'contact_phone_number' => 'required|max:20',
                'contact_email' => 'required|email',
                'password' => 'required|confirmed|min:6'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dump($e->getResponse()->original);
            $request->session()->flash('danger', $e->getResponse()->original);
            return redirect()->route('web.company.create');
        }

        DB::beginTransaction();

        try {
            $company = Company::create([
                'tax_id_number' => $request->tax_id_number,
                'password' => Hash::make($request->password)
            ]);

            $company->profile()->create([
                'name' => $request->name
            ]);

            $company->contactPersons()->create([
                'name' => $request->contact_person,
                'email' => $request->contact_email,
                'phone_number' => $request->contact_phone_number
            ]);

            $company->departments()->create([
                'name' => $request->name
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            $request->session()->flash('danger', [
                'error' => [
                    $e->getMessage()
                ]
            ]);
            return redirect()->route('web.company.create');
        }

        $request->session()->put('login_web_company_'.md5('Illuminate\Auth\Guard'), $company->id);
        return redirect('/');
    }
}
