<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class PostRepository {

    public function getPostList() {
        return DB::table('posts')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->orderBy('modified_at', 'desc')
            ->paginate(config('constants.perPage'))
            ->getCollection()
            ->map(function($item) {
                return $item;
            })->toArray();
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
}