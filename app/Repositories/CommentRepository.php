<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class CommentRepository {

    public function addComment($commentData) {
        return DB::table('posts_comments')
            ->insert($commentData);
    }

    public function getListComment($postId) {
        return DB::table('posts_comments')
            ->select('users.id as userId', 'users.user_name as userName', 'posts_comments.id as commentId',
                    'posts_comments.content as comment', 'posts_comments.created_at', 'posts_comments.modified_at')
            ->join('users', 'users.id', '=', 'posts_comments.user_id')
            ->where('posts_comments.post_id', '=', $postId)
            ->whereNull('posts_comments.deleted_at')
            ->orderBy('posts_comments.created_at', 'desc')
            ->get();
    }

    public function getCommentById($commentId) {
        return DB::table('posts_comments')
            ->select('*')
            ->where('id', '=', $commentId)
            ->first();
    }

    public function updateComment($commentId, $dataUpdate) {
        return DB::table('posts_comments')
            ->where('id', '=', $commentId)
            ->update($dataUpdate);
    }

    public function deleteComment($commentId) {
        return DB::table('posts_comments')
            ->where('id', '=', $commentId)
            ->delete();
    }
}