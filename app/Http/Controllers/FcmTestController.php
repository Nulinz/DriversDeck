<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Fcm;

class FcmTestController extends Controller
{
    public function testFcm(Request $request, Fcm $fcm)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        return response()->json(
            $fcm->send_notify(
                $request->token,
                'Test Notification',
                'Hello from Laravel'
            )
        );
    }
}
