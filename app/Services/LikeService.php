<?php

namespace App\Services;

use App\Repositories\LikeRepository;
use Illuminate\Support\Facades\Validator;

class LikeService {
    /**
     * @var LikeRepository
     */
    protected $likeRepo;

    /**
     * LikeService Construct
     */
    public function __construct(LikeRepository $likeRepo) {
        $this->likeRepo = $likeRepo;
    }

    public function getListUserLiked($data) {
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

        // get the list of users who liked the post
        $listUser = $this->likeRepo->getListUserLiked($data['postId']);
        $return['status'] = true;
        $return['message'] = 'Get list user liked post successfully.';
        $return['data'] = [
            'count' => count($listUser),
            'users' => $listUser
        ];
        return $return;
    }

    public function actionLike($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'userId' => 'required|numeric|exists:users,id',
            'postId' => 'required|numeric|exists:posts,id'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        $userId = $data['userId'];
        $postId = $data['postId'];

        // check like or unlike
        $liked = $this->likeRepo->checkUserLiked($userId, $postId);
        if ($liked) {
            if ($this->likeRepo->deleteLike($userId, $postId)) {
                $return['status'] = true;
                $return['message'] = 'Unlike successfully.';
                return $return;
            } else {
                $return['message'] = 'Unlike failed.';
                return $return;
            }
        } else {
            if ($this->likeRepo->addLike(['post_id' => $postId, 'user_id' => $userId])) {
                $return['status'] = true;
                $return['message'] = 'Add like successfully.';
                return $return;
            } else {
                $return['message'] = 'Add like failed.';
                return $return;
            }
        }
    }
}