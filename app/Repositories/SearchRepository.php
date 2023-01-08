<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class SearchRepository {

    public function getHistory($userId) {
        return DB::table('search_history')
            ->where('user_id', '=', $userId)
            ->pluck('search_key');
    }

    public function checkSearchKeyExists($userId, $searchKey) {
        return DB::table('search_history')
            ->where('user_id', '=', $userId)
            ->where('search_key', '=', $searchKey)
            ->exists();
    }

    public function addSearchKey($dataInsert) {
        return DB::table('search_history')
            ->insert($dataInsert);
    }

    public function deleteSearchKey($userId, $searchKey) {
        return DB::table('search_history')
            ->where('user_id', '=', $userId)
            ->where('search_key', '=', $searchKey)
            ->delete();
    }

    public function deleteAllSearchKeyByUser($userId) {
        return DB::table('search_history')
            ->where('user_id', '=', $userId)
            ->delete();
    }

    public function clubSearch($searchKey) {
        return DB::table('clubs')
            ->select('id', 'full_name')
            ->where(DB::raw('lower(full_name)'), 'like', '%' . strtolower($searchKey) . '%')
            ->orWhere(DB::raw('lower(short_name)'), 'like', '%' . strtolower($searchKey) . '%')
            ->get();
    }

    public function footballerSearch($searchKey) {
        return DB::table('footballers')
            ->select('id', 'full_name')
            ->where(DB::raw('lower(full_name)'), 'like', '%' . strtolower($searchKey) . '%')
            ->orWhere(DB::raw('lower(short_name)'), 'like', '%' . strtolower($searchKey) . '%')
            ->get();
    }

    public function postSearch($searchKey) {
        return DB::table('posts')
            ->select('id', 'title', 'content')
            ->where(DB::raw('lower(title)'), 'like', '%' . strtolower($searchKey) . '%')
            ->orWhere(DB::raw('lower(content)'), 'like', '%' . strtolower($searchKey) . '%')
            ->get();
    }
}