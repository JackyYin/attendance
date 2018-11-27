<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\CompanyContactPerson;
use App\Models\CompanyProfile;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function create(Request $request)
    {
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email',
                'birth_date' => 'required',
                'on_board_date' => 'required',
                'gender' => 'required'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
        }

    }
}
