<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class LikeRepository {

    public function getListUserLiked($postId) {
        return DB::table('posts_likes')
            ->join('users', 'users.id', '=', 'posts_likes.user_id')
            ->where('posts_likes.post_id', '=', $postId)
            ->orderBy('posts_likes.created_at', 'desc')
            ->pluck('users.user_name');
    }

    public function addLike($dataInsert) {
        return DB::table('posts_likes')
            ->insert($dataInsert);
    }

    public function deleteLike($userId, $postId) {
        return DB::table('posts_likes')
            ->where('user_id', '=', $userId)
            ->where('post_id', '=', $postId)
            ->delete();
    }

    public function checkUserLiked($userId, $postId) {
        return DB::table('posts_likes')
            ->where('user_id', '=', $userId)
            ->where('post_id', '=', $postId)
            ->exists();
    }
}