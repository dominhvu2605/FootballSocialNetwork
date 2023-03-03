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

    /**
     * For admin api
     */
    public function getAllPost() {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get list post
        $data = $this->postService->getAllPost();
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

    public function getPostDetail(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get post detail
        $data = $this->postService->getPostDetail($request->all());
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        $return['data'] = $data['data'];
        return response()->json($return, self::HTTP_OK);
    }

    public function updatePost(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // update post
        $result = $this->postService->updatePost($request->all());
        if (!$result['status']) {
            $return['message'] = $result['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $result['message'];
        return response()->json($return, self::HTTP_OK);
    }

    public function deletePost(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // update post
        $result = $this->postService->deletePost($request->all());
        if (!$result['status']) {
            $return['message'] = $result['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $result['message'];
        return response()->json($return, self::HTTP_OK);
    }

    public function createPost(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // update post
        $result = $this->postService->createPost($request->all());
        if (!$result['status']) {
            $return['message'] = $result['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $result['message'];
        return response()->json($return, self::HTTP_OK);
    }

    public function searchPost(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // update post
        $result = $this->postService->searchPost($request->all());
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