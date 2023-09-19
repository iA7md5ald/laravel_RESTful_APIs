<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\DistrictResource;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request , $city_id)
    {
        $districts = District::where('city_id' , $city_id)->get();
        if ( count($districts) > 0){
            return ApiResponse::sendResponse(200 , 'Districts Retrieved Successfuly', DistrictResource::collection($districts));
        }else {
            return ApiResponse::sendResponse(200 , 'Districts are Empty' , null);
        }
    }
}
