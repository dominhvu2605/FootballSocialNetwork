<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * define constant
     */
    const HTTP_OK = 200;
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * AuthController Construct
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService; 
    }

    public function register(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // register
        $userInfo = $request->all();
        $registrationResults = $this->authService->register($userInfo);

        // error response
        if (!$registrationResults['status']) {
            $return['message'] = $registrationResults['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }

        // success response
        $return['code'] = self::HTTP_OK;
        $return['message'] = 'Register success.';
        return response()->json($return, self::HTTP_OK);
    }

    public function login(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        $userInfo = $request->all();
        $loginResult = $this->authService->login($userInfo);
        if (!$loginResult['status']) {
            $return['message'] = $loginResult['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $loginResult['message'];
        if (isset($loginResult['api_token'])) {
            $return['api_token'] = $loginResult['api_token'];
        }
        return response()->json($return, self::HTTP_OK);
    }

    public function logout(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        $token = $request->all();
        $logoutResult = $this->authService->logout($token);
        if (!$logoutResult['status']) {
            $return['message'] = $logoutResult['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $logoutResult['message'];
        return response()->json($return, self::HTTP_OK);
    }

    public function getInfo(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        $phone = $request->all();
        $result = $this->authService->getInfo($phone);
        if (!$result['status']) {
            $return['message'] = $result['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $result['message'];
        $return['data'] = $result['data'];
        return response()->json($return, self::HTTP_OK);
    }

    public function sendOtpCode(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        $data = $request->all();
        $getOtpResult = $this->authService->sendOtpCode($data);
        
        if (!$getOtpResult['status']) {
            $return['message'] = $getOtpResult['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $getOtpResult['message'];
        return response()->json($return, self::HTTP_OK);
    }

    public function changePassword(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        $data = $request->all();
        $changePassResult = $this->authService->changePassword($data);

        if (!$changePassResult['status']) {
            $return['message'] = $changePassResult['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $changePassResult['message'];
        return response()->json($return, self::HTTP_OK);
    }

    public function forgotPassword(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        $data = $request->all();
        $forgotPassResult = $this->authService->forgotPassword($data);

        if (!$forgotPassResult['status']) {
            $return['message'] = $forgotPassResult['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $forgotPassResult['message'];
        return response()->json($return, self::HTTP_OK);
    }

    /**
     * For admin api
     */
    public function getListUser() {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get list post
        $result = $this->authService->getListUser();
        $return['code'] = self::HTTP_OK;
        $return['message'] = $result['message'];
        $return['totalPages'] = $result['totalPages'];
        $return['data'] = $result['data'];
        return response()->json($return, self::HTTP_OK);
    }

    public function getUserInfo(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // gt user info
        $data = $this->authService->getUserInfo($request->all());
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        $return['data'] = $data['data'];
        return response()->json($return, self::HTTP_OK);
    }

    public function updateUser(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // update user info
        $data = $this->authService->updateUser($request->all());
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        return response()->json($return, self::HTTP_OK);
    }

    public function deleteUser(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // update post
        $result = $this->authService->deleteUser($request->all());
        if (!$result['status']) {
            $return['message'] = $result['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $result['message'];
        return response()->json($return, self::HTTP_OK);
    }

    public function createUser(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // update post
        $result = $this->authService->register($request->all());
        if (!$result['status']) {
            $return['message'] = $result['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $result['message'];
        return response()->json($return, self::HTTP_OK);
    }

    public function searchUser(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // update post
        $result = $this->authService->searchUser($request->all());
        if (!$result['status']) {
            $return['message'] = $result['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $result['message'];
        $return['data'] = $result['data'];
        return response()->json($return, self::HTTP_OK);
    }
}
