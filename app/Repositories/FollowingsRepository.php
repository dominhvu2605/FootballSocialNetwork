<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class FollowingsRepository {

    // followed
    public function getFollowingsClub($userId) {
        return DB::table('followings as fl')
            ->select('fl.club_id_fl', 'clubs.full_name')
            ->join('clubs', 'clubs.id', '=', 'fl.club_id_fl')
            ->where('fl.user_id', '=', $userId)
            ->whereNotNull('fl.followings_at')
            ->get();
    }

    public function getFollowingsFootballer($userId) {
        return DB::table('followings as fl')
            ->select('fl.footballer_id_fl', 'fb.full_name')
            ->join('footballers as fb', 'fb.id', '=', 'fl.footballer_id_fl')
            ->where('fl.user_id', '=', $userId)
            ->whereNotNull('fl.followings_at')
            ->get();
    }

    // suggest
    public function getFamousClubs() {
        return DB::table('followings as fl')
            ->select('fl.club_id_fl', 'clubs.full_name', DB::raw("count(fl.user_id) as count"))
            ->join('clubs', 'clubs.id', '=', 'fl.club_id_fl')
            ->whereNotNull('fl.followings_at')
            ->groupBy('fl.club_id_fl', 'clubs.full_name')
            ->orderBy('count', 'desc')
            ->get();
    }

    public function getFollowedClubs($userId) {
        return DB::table('followings as fl')
            ->where('user_id', '=', $userId)
            ->whereNotNull('club_id_fl')
            ->pluck('club_id_fl')
            ->toArray();
    }

    public function getFamousFootballer() {
        return DB::table('followings as fl')
            ->select('fl.footballer_id_fl', 'fb.full_name', DB::raw("count(fl.user_id) as count"))
            ->join('footballers as fb', 'fb.id', '=', 'fl.footballer_id_fl')
            ->whereNotNull('fl.followings_at')
            ->groupBy('fl.footballer_id_fl', 'fb.full_name')
            ->orderBy('count', 'desc')
            ->get();
    }

    public function getFollowedFootballers($userId) {
        return DB::table('followings as fl')
            ->where('user_id', '=', $userId)
            ->whereNotNull('footballer_id_fl')
            ->pluck('footballer_id_fl')
            ->toArray();
    }

    // follow club
    public function checkFollowedClub($userId, $clubId) {
        return DB::table('followings')
            ->where('club_id_fl', '=', $clubId)
            ->where('user_id', '=', $userId)
            ->whereNotNull('followings_at')
            ->exists();
    }

    public function unFollowClub($userId, $clubId) {
        return DB::table('followings')
            ->where('club_id_fl', '=', $clubId)
            ->where('user_id', '=', $userId)
            ->whereNotNull('followings_at')
            ->delete();
    }

    public function addFollow($dataInsert) {
        return DB::table('followings')
            ->insert($dataInsert);
    }

    // follow footballer
    public function checkFollowedFootballer($userId, $footballerId) {
        return DB::table('followings')
            ->where('footballer_id_fl', '=', $footballerId)
            ->where('user_id', '=', $userId)
            ->whereNotNull('followings_at')
            ->exists();
    }

    public function unFollowFootballer($userId, $footballerId) {
        return DB::table('followings')
            ->where('footballer_id_fl', '=', $footballerId)
            ->where('user_id', '=', $userId)
            ->whereNotNull('followings_at')
            ->delete();
    }
}