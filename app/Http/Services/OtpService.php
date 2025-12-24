<?php

namespace App\Services;

use App\Models\Driver;
use App\Models\Corporate;

class OtpHelper
{
   
    public function sendOtp($phone, $userType = 'driver')
    {
        $otp = ($phone == '1234567890') ? 1234 : rand(1000, 9999);
        
        // Send SMS
        $this->sendSms($phone, $otp);
        
        // Store OTP in database
        $this->storeOtp($phone, $otp, $userType);
        
        return [
            'success' => true,
            'message' => 'OTP sent successfully',
            'otp' => $otp, // Remove in production
            'phone' => $phone
        ];
    }

    // Verify OTP
     
    public function verifyOtp($phone, $otp, $userType = 'driver')
    {
        $user = $this->getUser($phone, $userType);
        
        if (!$user || $user->otp != $otp) {
            return [
                'success' => false,
                'message' => 'Invalid OTP'
            ];
        }
        
        // Clear OTP after verification
        $user->update(['otp' => null, 'is_verified' => true]);
        
        return [
            'success' => true,
            'message' => 'OTP verified successfully',
            'user' => $user
        ];
    }

    
    // Send SMS using API
   
    private function sendSms($phone, $otp)
    {
        $authKey = "your_auth_key_here";
        $senderId = "DRDECK";
        $route = "2";
        $country = "91";
        $dltTeId = "1707175066512828187";
        
        $message = urlencode("Dear user, your DriversDeck registration OTP is $otp. Please do not share this with anyone. - DRDECK");
        
        $url = "http://promo.smso2.com/api/sendhttp.php?authkey=$authKey&mobiles=$phone&message=$message&sender=$senderId&route=$route&country=$country&DLT_TE_ID=$dltTeId";
        
        file_get_contents($url);
    }

    // Store OTP in database
     
    private function storeOtp($phone, $otp, $userType)
    {
        if ($userType == 'driver') {
            $user = Driver::where('phone', $phone)->first();
            
            if (!$user) {
                Driver::create([
                    'phone' => $phone,
                    'otp' => $otp,
                    'is_verified' => false,
                ]);
            } else {
                $user->update([
                    'otp' => $otp,
                    'is_verified' => false,
                ]);
            }
        } else {
            $user = Corporate::where('phone', $phone)->first();
            
            if (!$user) {
                Corporate::create([
                    'phone' => $phone,
                    'otp' => $otp,
                    'is_verified' => false,
                ]);
            } else {
                $user->update([
                    'otp' => $otp,
                    'is_verified' => false,
                ]);
            }
        }
    }

    
    // Get user by phone and type

    private function getUser($phone, $userType)
    {
        if ($userType == 'driver') {
            return Driver::where('phone', $phone)->first();
        } else {
            return Corporate::where('phone', $phone)->first();
        }
    }
}