<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\ClubRepository;
use App\Repositories\FootballerRepository;
use Illuminate\Support\Facades\Validator;

use function PHPSTORM_META\type;

class PostService {
    /**
     * @var PostRepository
     * @var ClubRepository
     * @var FootballerRepository
     */
    protected $postRepo;
    protected $clubRepo;
    protected $footballerRepo;

    /**
     * PostService Construct
     */
    public function __construct(
        PostRepository $postRepo,
        ClubRepository $clubRepo,
        FootballerRepository $footballerRepo
    )
    {
        $this->postRepo = $postRepo;
        $this->clubRepo = $clubRepo;
        $this->footballerRepo = $footballerRepo;
    }

    public function getPostList() {
        $return = [];
        $postList = $this->postRepo->getPostList();
        $postList = json_decode(json_encode($postList));
        $return['totalPages'] = $postList->last_page;
        $return['data'] = $postList->data;
        return $return;
    }

    public function getPostByClub($clubId) {
        // get club name to create searchKey
        $clubName = (array) $this->clubRepo->getClubName($clubId);
        if (empty($clubName)) {
            return [];
        }

        // create searchKey
        $fullName = array_map('trim', explode(',', $clubName['full_name']));
        $shortName = array_map('trim', explode(',', $clubName['short_name']));
        $searchKey = array_merge($fullName, $shortName);
        $searchKey = array_filter($searchKey, fn($value) => !is_null($value) && $value !== '');
        if (empty($searchKey)) {
            return [];
        }
        
        // get list post by searchKey
        $postList = $this->postRepo->getPostBySearchKey($searchKey);
        return $postList;
    }

    public function getPostByFootballer($footballerId) {
        // get footballer name to create searchKey
        $footballerName = (array) $this->footballerRepo->getFootballerName($footballerId);
        if (empty($footballerName)) {
            return [];
        }

        // create searchKey
        $fullName = array_map('trim', explode(',', $footballerName['full_name']));
        $shortName = array_map('trim', explode(',', $footballerName['short_name']));
        $searchKey = array_merge($fullName, $shortName);
        $searchKey = array_filter($searchKey, fn($value) => !is_null($value) && $value !== '');
        if (empty($searchKey)) {
            return [];
        }

        // get list post by searchKey
        $postList = $this->postRepo->getPostBySearchKey($searchKey);
        return $postList;
    }

    public function getAllPost() {
        $return = [];
        $postList = $this->postRepo->getAllPost();
        $postList = json_decode(json_encode($postList));
        $return['totalPages'] = $postList->last_page;
        $return['data'] = $postList->data;
        return $return;
    }

    public function getPostDetail($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'postId' => 'required|numeric|exists:posts,id'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // get post detail
        $postDetail = $this->postRepo->getPostDetail($data['postId']);
        $return['status'] = true;
        $return['message'] = 'Get post detail successfully.';
        $return['data'] = $postDetail;
        return $return;
    }

    public function updatePost($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'postId' => 'required|numeric|exists:posts,id',
            'title' => 'required|max:65535',
            'content' => 'required|max:16777215'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // update post
        $dataUpdate = [
            'title' => $data['title'],
            'content' => $data['content']
        ];
        $updateResult = $this->postRepo->updatePost($data['postId'], $dataUpdate);
        if (!$updateResult) {
            $return['message'] = 'Update post information failed.';
            return $return;
        }
        $return['status'] = true;
        $return['message'] = 'Update post information successfully.';
        return $return;
    }

    public function deletePost($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'postId' => 'required|numeric|exists:posts,id'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // delete post
        $result = $this->postRepo->deletePost($data['postId']);
        if (!$result) {
            $return['message'] = 'Delete post failed.';
            return $return;
        }
        $return['status'] = true;
        $return['message'] = 'Delete post successfully.';
        return $return;
    }

    public function createPost($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'title' => 'required|max:65535',
            'content' => 'required|max:16777215'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // update post
        $newData = [
            'title' => $data['title'],
            'content' => $data['content']
        ];
        $result = $this->postRepo->createNewPost($newData);
        if (!$result) {
            $return['message'] = 'Create new post failed.';
            return $return;
        }
        $return['status'] = true;
        $return['message'] = 'Create new post successfully.';
        return $return;
    }

    public function searchPost($data) {
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
        $result = $this->postRepo->searchPost($data['searchKey']);
        if (!$result) {
            $return['message'] = 'Search post by key failed.';
            return $return;
        }
        $return['status'] = true;
        $return['message'] = 'Search post by key successfully.';
        $return['data'] = $result;
        return $return;
    }
}