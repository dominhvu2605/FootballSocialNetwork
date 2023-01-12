<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * define constant
     */
    const HTTP_OK = 200;
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    /**
     * @var PostService
     */
    protected $postService;

    /**
     * PostController Construct
     */
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function getPostList() {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get list post
        $data = $this->postService->getPostList();
        if (empty($data)) {
            $return['message'] = 'Get post failded.';
            $return['data'] = [];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = 'Get post successfully.';
        $return['data'] = $data;
        return response()->json($return, self::HTTP_OK);
    }

    public function getPostByClub($clubId) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get list post by club
        $data = $this->postService->getPostByClub($clubId);
        $return['code'] = self::HTTP_OK;
        $return['message'] = 'Get post successfully.';
        $return['data'] = $data;
        return response()->json($return, self::HTTP_OK);
    }

    public function getPostByFootballer($footballerId) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get list post by club
        $data = $this->postService->getPostByFootballer($footballerId);
        $return['code'] = self::HTTP_OK;
        $return['message'] = 'Get post successfully.';
        $return['data'] = $data;
        return response()->json($return, self::HTTP_OK);
    }
}
