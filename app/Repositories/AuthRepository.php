<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class AuthRepository {

    // register new user
    public function register($userInfo) {
        return DB::table('users')->insert($userInfo);
    }

    // check phone is exists
    public function checkPhoneExists($phone) {
        return DB::table('users')
                ->where('phone', '=', $phone)
                ->exists();
    }

    // update user information by phone
    public function updateUserInfoByPhone($phone, $arrData) {
        return DB::table('users')
                ->where('phone', '=', $phone)
                ->update($arrData);
    }

    // check api_token is exists
    public function checkApiKeyExists($apiToken) {
        return DB::table('users')
                ->where('api_token', '=', $apiToken)
                ->exists();
    }

    // delete api_token
    public function deleteApiToken($apiToken) {
        return DB::table('users')
                ->where('api_token', '=', $apiToken)
                ->update(['api_token' => '']);
    }

    // check otp code by phone
    public function checkOtpCode($phone, $otpCode) {
        return DB::table('users')
                ->where('phone', '=', $phone)
                ->where('verify_code', '=', $otpCode)
                ->exists();
    }

    // get user info
    public function getUserInfo($phone) {
        return DB::table('users')
                ->select('id', 'user_name', 'phone')
                ->where('phone', '=', $phone)
                ->first();
    }
}