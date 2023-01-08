<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Services\LikeService;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * define constant
     */
    const HTTP_OK = 200;
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    /**
     * @var LikeService
     */
    protected $likeService;

    /**
     * PostController Construct
     */
    public function __construct(LikeService $likeService)
    {
        $this->likeService = $likeService;
    }

    public function getListUserLiked(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get list post
        $data = $this->likeService->getListUserLiked($request->all());
        if (!$data['status']) {
            $return['message'] = 'Get list user liked post failded.';
            $return['data'] = [];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = 'Get list user liked post successfully.';
        $return['data'] = $data['data'];
        return response()->json($return, self::HTTP_OK);
    }

    public function like(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get list post
        $data = $this->likeService->actionLike($request->all());
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        return response()->json($return, self::HTTP_OK);
    }
}
