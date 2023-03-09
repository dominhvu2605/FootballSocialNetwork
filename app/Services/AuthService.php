<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthService {
    /**
     * @var AuthRepository
     */
    protected $authRepo;

    /**
     * AuthService Construct
     */
    public function __construct(AuthRepository $authRepo)
    {
        $this->authRepo = $authRepo;
    }

    /**
     * register new user
     * @param $userInfo
     * @return array
     */
    public function register($userInfo) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate user info
        $validate = Validator::make($userInfo, [
            'user_name' => 'required|max:50',
            'phone' => 'required|min:10|max:11',
            'password' => 'required'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // check the phone number is used or not?
        if ($this->authRepo->checkPhoneExists($userInfo['phone'])) {
            $return['message'] = 'Phone number already in use, please register with another phone number.';
            return $return;
        }

        // register user info
        try {
            $userInfo['password'] = Hash::make($userInfo['password']);
            $this->authRepo->register($userInfo);
            $return['status'] = true;
            $return['message'] = 'Create new user successfully.';
            return $return;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $return['message'] = $e->getMessage();
            return $return;
        }
    }

    /**
     * login by phone number
     * @param $userInfo
     * @return array
     */
    public function login($userInfo) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate user info to login
        $validate = Validator::make($userInfo, [
            'phone' => 'required|min:10|max:11',
            'password' => 'required'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // login
        if (Auth::attempt($userInfo)) {
            try {
                $token = hash('sha256', uniqid($userInfo['phone'], true));
                $this->authRepo->updateUserInfoByPhone($userInfo['phone'], ['api_token' => $token]);
                $return['status'] = true;
                $return['message'] = 'Logged in successfully.';
                $return['api_token'] = $token;
                return $return;
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                $return['message'] = $e->getMessage();
                return $return;
            }
        } else {
            $return['message'] = 'Login information is incorrect, please check again!';
            return $return;
        }
    }

    /**
     * logout
     * @param $apiToken
     * @return array
     */
    public function logout($apiToken) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate api_token
        $validate = Validator::make($apiToken, [
            'api_token' => 'required',
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // check api_token exist in DB
        if (!$this->authRepo->checkApiKeyExists($apiToken['api_token'])) {
            $return['message'] = 'This api_key value does not exist in the DB!';
            return $return;
        }

        // remove api_key
        try {
            $this->authRepo->deleteApiToken($apiToken);
            $return['status'] = true;
            $return['message'] = 'Logout successfully.';
            return $return;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $return['message'] = $e->getMessage();
            return $return;
        }
    }

    /**
     * logout
     * @param $apiToken
     * @return array
     */
    public function getInfo($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate user phone
        $validate = Validator::make($data, [
            'phone' => ['required', 'regex:/^(0|\+84)+[0-9]{9}$/']
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // get user info
        $userInfo = $this->authRepo->getUserInfo(str_replace("+84", "0", $data['phone']));
        if (empty($userInfo)) {
            $return['message'] = 'User is not exists.';
            return $return;
        }
        $return['status'] = true;
        $return['message'] = 'Get user information successfully.';
        $return['data'] = $userInfo;
        return $return;
    }

    /**
     * send otp code
     * @param $data
     * @return array
     */
    public function sendOtpCode($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate user phone
        $validate = Validator::make($data, [
            'phone' => ['required', 'regex:/^(0|\+84)+[0-9]{9}$/']
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // get otp
        try {
            $otp_code = sprintf('%05d', mt_rand(0, 99999));
            $this->authRepo->updateUserInfoByPhone(str_replace("+84", "0", $data['phone']), ['verify_code' => $otp_code]);

            $this->sendNotification($data['phone'], 'Your opt code is: ' . $otp_code);
            $return['status'] = true;
            $return['message'] = 'Send otp code successfully.';
            return $return;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $return['message'] = $e->getMessage();
            return $return;
        }
    }

    /**
     * change password
     * @param $data
     * @return array
     */
    public function changePassword($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'phone' => ['required', 'regex:/^(0|\+84)+[0-9]{9}$/'],
            'otp_code' => 'required|numeric|digits:5',
            'old_password' => 'required',
            'password' => 'required|confirmed'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }
        
        // change password
        try {
            // check old_pasword
            if (!Auth::attempt(['phone' => str_replace("+84", "0", $data['phone']), 'password' => $data['old_password']])) {
                $return['message'] = 'Incorrect phone number or password.';
                return $return;
            }

            if (!$this->authRepo->checkOtpCode(str_replace("+84", "0", $data['phone']), $data['otp_code'])) {
                $return['message'] = 'Incorrect OTP.';
                return $return;
            }

            // update password
            if ($this->authRepo->updateUserInfoByPhone(str_replace("+84", "0", $data['phone']), ['password' => Hash::make($data['password'])])) {
                $return['status'] = true;
                $return['message'] = 'Change password successfully.';
                return $return;
            } else {
                $return['message'] = 'Change password failed.';
                return $return;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $return['message'] = $e->getMessage();
            return $return;
        }
    }

    /**
     * forgot password
     * @param $data
     * @return array
     */
    public function forgotPassword($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'phone' => ['required', 'regex:/^(0|\+84)+[0-9]{9}$/'],
            'otp_code' => 'required|numeric|digits:5'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // forgot password
        try {
            if (!$this->authRepo->checkOtpCode(str_replace("+84", "0", $data['phone']), $data['otp_code'])) {
                $return['message'] = 'Incorrect OTP.';
                return $return;
            }
            // update password
            $newPass = $this->ramdomString(8);
            if ($this->authRepo->updateUserInfoByPhone(str_replace("+84", "0", $data['phone']), ['password' => Hash::make($newPass)])) {
                $this->sendNotification($data['phone'], 'Your new password is: ' . $newPass);
                $return['status'] = true;
                $return['message'] = 'Reset password successfully. New password has been sent to your phone number.';
                return $return;
            } else {
                $return['message'] = 'Reset password failed.';
                return $return;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $return['message'] = $e->getMessage();
            return $return;
        }
    }

    /**
     * Send notification
     */
    public function sendNotification($phone, $message) {
        $account_sid = env('TWILIO_SID');
        $auth_token = env('TWILIO_TOKEN');
        $twilio_number = env('TWILIO_FROM');

        $client = new Client($account_sid, $auth_token);
        $client->messages->create($phone, [
            'from' => $twilio_number,
            'body' => $message
        ]);
    }

    /**
     * random password
     * @param $lengthPass
     * @return string
     */
    public function ramdomString($lengthString) {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $lengthString; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    public function getListUser() {
        $return = [
            'status' => false,
            'message' => ''
        ];

        $listUsers = $this->authRepo->getListUser();
        $listUsers = json_decode(json_encode($listUsers));
        $return['status'] = true;
        $return['message'] = 'Get list users successfully.';
        $return['totalPages'] = $listUsers->last_page;
        $return['data'] = $listUsers->data;
        return $return;
    }

    public function getUserInfo($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'userId' => 'required|numeric|exists:users,id'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // get user info
        $userInfo = $this->authRepo->getUserInfoById($data['userId']);
        $return['status'] = true;
        $return['message'] = 'Get user info successfully.';
        $return['data'] = $userInfo;
        return $return;
    }

    public function updateUser($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'userId' => 'required|numeric|exists:users,id',
            'userName' => 'required|string|max:50',
            'phone' => 'required|string|max:13|unique:users,phone'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // create data to update
        $updateData = [
            'user_name' => $data['userName'],
            'phone' => $data['phone']
        ];
        if ($this->authRepo->updateUser($data['userId'], $updateData)) {
            $return['status'] = true;
            $return['message'] = 'Update user info successfully.';
            return $return;
        }

        $return['message'] = 'Update user info failed.';
        return $return;
    }

    public function deleteUser($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'userId' => 'required|numeric|exists:users,id'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // delete user
        $result = $this->authRepo->deleteUser($data['userId']);
        if (!$result) {
            $return['message'] = 'Delete user failed.';
            return $return;
        }
        $return['status'] = true;
        $return['message'] = 'Delete user successfully.';
        return $return;
    }

    public function searchUser($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'searchKey' => 'required'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // search post
        $result = $this->authRepo->searchUser($data['searchKey']);
        if (!$result) {
            $return['message'] = 'Search user by key failed.';
            return $return;
        }
        $return['status'] = true;
        $return['message'] = 'Search user by key successfully.';
        $return['data'] = $result;
        return $return;
    }
}