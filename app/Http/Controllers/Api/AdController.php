<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdRequest;
use App\Http\Resources\AdResource;
use App\Models\Ad;
use Illuminate\Http\Request;
use Spatie\FlareClient\Api;

class AdController extends Controller
{
    public function index(){
        $ads = Ad::latest()->paginate(1);
        if (count($ads) > 0 ){

            if ($ads->total() > $ads->perPage()){
                $data = [
                    'records' => AdResource::collection($ads),
                    'pagination links' => [
                        'current page' => $ads->currentPage(),
                        'per page' => $ads->perPage(),
                        'total' => $ads->total(),
                        'links' => [
                            'first' =>$ads->url(1),
                            'last' => $ads ->url($ads->lastPage()),
                        ]
                    ]
                ];
            }else {
                $data = AdResource::collection($ads);
            }

            return ApiResponse::sendResponse(200 , 'Ads Retrieved Successfully' , $data);
        }else{
            return ApiResponse::sendResponse(200 , 'Ads not Available' , null);
        }
    }// end of index

    public function latest(){
        $ads = Ad::latest()->get();
        if (count($ads) > 0 ){
            return ApiResponse::sendResponse(200 , 'Ads Retrieved Successfully' , AdResource::collection($ads));
        }else{
            return ApiResponse::sendResponse(200 , 'Ads not Available' , null);
        }
    }// end of latest

    public function search(Request $request){
        $word= $request->has('search') ? $request ->input('search') : null ;
        $ads = Ad::when( $word != null , function ($q) use ($word){
            $q->where('title' , 'like' , '%' . $word . '%');
        })->latest()->get();

        if (count($ads) > 0){
            return ApiResponse::sendResponse(200 , 'Search success' , AdResource::collection($ads));
        }else{
            return ApiResponse::sendResponse(200 , 'No Matching Data' , null);
        }

    }// end of search

    public function create(AdRequest $request){
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $record = Ad::create($data);
        if ($record) return ApiResponse::sendResponse(201, 'Your Ad created successfully', new AdResource($record));
    }// end of create


    public function showAds(Request $request){
        $ads = Ad::where('user_id' , $request->user()->id)->latest()->get();
        if (count($ads) > 0) {
            return ApiResponse::sendResponse(200 , 'Ads Retrieved Successfully' , AdResource::collection($ads));
        }else{
            return ApiResponse::sendResponse(200 , 'You do not have Any Ads' , null);
        }
    }// end of showAds

    public function update (AdRequest $request , $adId){
        $ad = Ad::findOrFail($adId);

        if ($ad->user_id != $request->user()->id){
            return ApiResponse::sendResponse(403 , 'You Are Not Allowed to Take This Action' , null);
        }
        $data = $request->validated();
        $updating = $ad->update($data);
        if ($updating) return ApiResponse::sendResponse(201, 'Your Ad Updated successfully', new AdResource($ad));

    }// end of update

    public function delete(Request$request , $adId){
        $ad= Ad::findOrFail($adId);
        if ($ad->user_id != $request->user()->id){
            return ApiResponse::sendResponse(403 , 'You Are Not Allowed to Take This Action' , null);
        }
        $deleted = $ad->delete();
        if ($deleted) return ApiResponse::sendResponse('200' , 'Your Ad Deleted Successfully' , null);

    }// end of delete

}// end of controller
