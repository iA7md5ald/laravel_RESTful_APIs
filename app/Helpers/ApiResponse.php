<?php

namespace App\Helpers;

class ApiResponse {

    static function sendResponse ( $status = 200, $msg = null, $data = null ) {
        $response = [
            'status' => $status,
            'message' => $msg,
            'data' => $data
        ];
        return response()->json( $response , $status);
    }
}

