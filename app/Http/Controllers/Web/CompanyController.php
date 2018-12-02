<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyContactPerson;
use App\Models\CompanyProfile;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTAuth;

class CompanyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'tax_id_number' => 'required|unique:companies,tax_id_number',
            'name' => 'required|max:50',
            'contact_person' => 'required|max:50',
            'contact_phone_number' => 'required|max:20',
            'contact_email' => 'required|email',
            'password' => 'required|confirmed|min:6'
        ]);

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

            $department = $company->departments()->create([
                'name' => $request->name
            ]);

            $department->roles()->createMany([
                [
                    'company_id' => $company->id,
                    'name' => '主管',
                    'priority' => 1
                ],
                [
                    'company_id' => $company->id,
                    'name' => '管理員',
                    'priority' => 2
                ],
                [
                    'company_id' => $company->id,
                    'name' => '員工',
                    'priority' => 3
                ],
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }

        try {
            if (! $token = $this->jwt->fromUser($company)) {
                return response()->json([
                    'auth' => [
                        'invalid_credentials'
                    ]
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'auth' => [
                    'could_not_create_token'
                ]
            ], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'expires_in'   => $this->jwt->factory()->getTTL() * 60
        ]);
    }
}
