<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Services\FootballerService;
use Illuminate\Http\Request;

class FootballerController extends Controller
{
    /**
     * define constant
     */
    const HTTP_OK = 200;
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    /**
     * @var FootballerService
     */
    protected $fbService;

    /**
     * PostController Construct
     */
    public function __construct(FootballerService $fbService)
    {
        $this->fbService = $fbService;
    }

    public function getFootballerInfo(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get club information
        $data = $this->fbService->getFootballerInfo($request->all());
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        $return['data'] = $data['data'];
        return response()->json($return, self::HTTP_OK);
    }

    public function getAllFootballer() {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get club information
        $data = $this->fbService->getAllFootballer();
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        $return['data'] = $data['data'];
        return response()->json($return, self::HTTP_OK);
    }

    public function updateFootballer(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get club information
        $data = $this->fbService->updateFootballer($request->all());
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        return response()->json($return, self::HTTP_OK);
    }

    public function deleteFootballer(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get club information
        $data = $this->fbService->deleteFootballer($request->all());
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        return response()->json($return, self::HTTP_OK);
    }

    public function createFootballer(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // update post
        $result = $this->fbService->createFootballer($request->all());
        if (!$result['status']) {
            $return['message'] = $result['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $result['message'];
        return response()->json($return, self::HTTP_OK);
    }

    public function searchFootballer(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // update post
        $result = $this->fbService->searchFootballer($request->all());
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
