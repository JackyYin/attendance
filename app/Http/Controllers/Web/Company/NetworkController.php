<?php

namespace App\Http\Controllers\Web\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Network;
use DB;
use Illuminate\Http\Request;

class NetworkController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'ssid' => 'required',
            'bssid' => 'required|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/|unique:networks,wifi_bssid'
        ]);

        $departmentId = $request->user->correspondingDepartment()->id;

        DB::beginTransaction();

        try {
            $network = $request->user->networks()->create([
                'department_id' => $departmentId,
                'wifi_ssid'     => $request->filled('ssid') ? $request->ssid : '',
                'wifi_bssid'    => $request->filled('bssid') ? $request->bssid : '',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            return response()->json([
                'error' => [
                    $e->getMessage()
                ]
            ], 400);

            DB::rollback();
        }

        return response()->json([
            'network' => $network
        ], 200);
    }
}
