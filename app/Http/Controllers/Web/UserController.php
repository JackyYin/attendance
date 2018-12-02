<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
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
        $departments = $request->user->departments()->with(['roles' => function ($query) {
            $query->orderBy('priority');
        }])->whereNotNull('parent_id')->get();

        return response()->json([
            'departments' => $departments
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'birth_date' => 'required|date_format:Y-m-d',
            'on_board_date' => 'required|date_format:Y-m-d',
            'department' => 'exists:departments,id',
            'role' => 'exists:roles,id'
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'company_id'    => $request->user->id,
                'department_id' => $request->filled('department') ?
                $request->department->id : $request->user->correspondingDepartment()->id,
                'email' => $request->email,
                'password' => Hash::make(Carbon::parse($request->birth_date)->format('Ymd'))
            ]);

            $user->profile()->create([
                'name' => $request->name,
                'staff_code' => $request->input('staff_code'),
                'title' => $request->input('title'),
                'on_board_date' => Carbon::parse($request->on_board_date)->timestamp,
                'birth_date' => Carbon::parse($request->birth_date)->timestamp
            ]);

            if ($request->filled('role')) {
                $user->roles()->attach($request->role);
            } else {
                $role = $user->department->roles()->max('priority');

                $user->roles()->attach($role);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'user' => $user->load('profile')
        ], 200);
    }
}
