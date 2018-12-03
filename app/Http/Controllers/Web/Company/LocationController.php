<?php

namespace App\Http\Controllers\Web\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Location;
use DB;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function update(Request $request)
    {
        $data = [];
        $companyId = $request->user->id;
        $departmentId = $request->user->correspondingDepartment()->id;

        DB::beginTransaction();

        try {
            foreach ($request->locations as $requestArr) {

                $requestObj = (object)$requestArr;

                if (isset($requestObj->id)) {
                    $location = Location::where('id', $requestObj->id)
                        ->where('company_id', $request->user->id)->first();

                    if (!$location) {
                        DB::rollback();

                        return response()->json([
                            'location' => [
                                'Location ID : '.$location->id.' not found'
                            ]
                        ], 400);
                    }

                    $location->update([
                        'company_id' => $companyId,
                        'department_id' => $departmentId,
                        'address'   => isset($requestObj->address) ? $requestObj->address : '',
                        'latitude'  => isset($requestObj->latitude) ? $requestObj->latitude : '',
                        'longitude' => isset($requestObj->longitude) ? $requestObj->longitude : '',
                        'legal_distance' => isset($requestObj->legal_disrance) ? $requestObj->legal_disrance : 100,
                    ]);

                } else {
                    $location = $request->user->locations()->create([
                        'department_id' => $departmentId,
                        'address'   => isset($requestObj->address) ? $requestObj->address : '',
                        'latitude'  => isset($requestObj->latitude) ? $requestObj->latitude : '',
                        'longitude' => isset($requestObj->longitude) ? $requestObj->longitude : '',
                        'legal_distance' => isset($requestObj->legal_disrance) ? $requestObj->legal_disrance : 100,
                    ]);
                }

                $data[] = $location;
            }

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
            'locations' => $data
        ], 200);
    }
}
