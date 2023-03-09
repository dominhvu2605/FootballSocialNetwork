<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class PostRepository {

    public function getPostList() {
        return DB::table('posts')
            ->whereNull('deleted_at')
            ->orderBy('modified_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(config('constants.perPage'));
    }

    public function getPostBySearchKey($searchKey) {
        if (empty($searchKey)) {
            return [];
        };
        $result = DB::table('posts')
            ->select('*')
            ->whereNull('deleted_at')
            ->where(DB::raw('lower(title)'), 'REGEXP', strtolower($searchKey[0]))
            ->orWhere(DB::raw('lower(content)'), 'REGEXP', strtolower($searchKey[0]));
        if (count($searchKey) >= 2) {
            for ($index = 1; $index < count($searchKey); $index++) {
                $result->orWhere(DB::raw('lower(title)'), 'REGEXP', strtolower($searchKey[$index]));
                $result->orWhere(DB::raw('lower(content)'), 'REGEXP', strtolower($searchKey[$index]));
            }
        }           
        $result = $result->orderBy('created_at', 'desc')
                        ->orderBy('modified_at', 'desc')
                        ->paginate(config('constants.perPage'))
                        ->getCollection()
                        ->map(function($item) {
                            return $item;
                        })->toArray();
        return $result;
    }

    public function getAllPost() {
        return DB::table('posts')
            ->select('id', 'title', 'content')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->orderBy('modified_at', 'desc')
            ->paginate(config('constants.perPage'));
    }

    public function getPostDetail($postId) {
        return DB::table('posts')
            ->select('id', 'title', 'content')
            ->where('id', '=', $postId)
            ->first();
    }

    public function updatePost($postId, $dataUpdate) {
        return DB::table('posts')
            ->where('id', '=', $postId)
            ->update($dataUpdate);
    }

    public function deletePost($postId) {
        return DB::table('posts')
            ->where('id', '=', $postId)
            ->delete();
    }

    public function createNewPost($newData) {
        return DB::table('posts')
            ->insert($newData);
    }

    public function searchPost($searchKey) {
        return DB::table('posts')
            ->select('id', 'title', 'content')
            ->where(DB::raw('lower(title)'), 'REGEXP', strtolower($searchKey))
            ->orWhere(DB::raw('lower(content)'), 'REGEXP', strtolower($searchKey))
            ->get();
    }
}