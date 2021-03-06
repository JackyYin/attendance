<?php

namespace App\Http\Controllers\Api;

use App\Models\Attendance;
use App\Models\AttendanceDetail;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function gps(Request $request)
    {
        try {
            $this->validate($request, [
                'latitude' => 'required',
                'longitude' => 'required'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->getResponse()->original, 400);
        }

        $user = $request->user;

        DB::beginTransaction();

        try {
            $attendance = $user->attendances()->create([
                'company_id'    => $user->company->id,
                'department_id' => $user->company->correspondingDepartment()->id,
                'punched_at'    => Carbon::now()->timestamp
            ]);

            $attendance->detail()->create([
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
                'via'       => AttendanceDetail::VIA_GPS,
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }

        return response()->json([
            'attendance' => $attendance->load('detail')
        ]);
    }

    public function wifi(Request $request)
    {
        try {
            $this->validate($request, [
                'bssid' => 'required|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->getResponse()->original, 400);
        }

        $user = $request->user;

        DB::beginTransaction();

        try {
            $attendance = $user->attendances()->create([
                'company_id'    => $user->company->id,
                'department_id' => $user->company->correspondingDepartment()->id,
                'punched_at'    => Carbon::now()->timestamp
            ]);

            $attendance->detail()->create([
                'wifi_bssid' => $request->bssid,
                'via'        => AttendanceDetail::VIA_WIFI,
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }

        return response()->json([
            'attendance' => $attendance->load('detail')
        ]);
    } 
}
