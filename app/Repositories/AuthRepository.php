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

    public function getListUser() {
        return DB::table('users')
                ->select('id', 'user_name', 'phone')
                ->orderBy('modified_at', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
    }

    public function getUserInfoById($userId) {
        return DB::table('users')
                ->select('id', 'user_name', 'phone')
                ->where('id', '=', $userId)
                ->first();
    }

    public function updateUser($userId, $dataUpdate) {
        return DB::table('users')
            ->where('id', '=', $userId)
            ->update($dataUpdate);
    }

    public function deleteUser($userId) {
        return DB::table('users')
            ->where('id', '=', $userId)
            ->delete();
    }

    public function searchUser($searchKey) {
        return DB::table('users')
            ->select('id', 'user_name', 'phone')
            ->where(DB::raw('lower(user_name)'), 'REGEXP', strtolower($searchKey))
            ->orWhere(DB::raw('lower(phone)'), 'REGEXP', strtolower($searchKey))
            ->orderBy('modified_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}