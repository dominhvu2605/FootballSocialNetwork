<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class FootballerRepository {

    public function getFootballerName($footballerId) {
        return DB::table('footballers')
            ->select('full_name', 'short_name')
            ->where('id', '=', $footballerId)
            ->first();
    }

    public function getAllFootballer() {
        return DB::table('footballers')
            ->select('id', 'full_name')
            ->get();
    }

    public function GetFootballerByClub($clubId) {
        return DB::table('footballers')
            ->select('id', 'full_name', 'nationality', 'date_of_birth', 'height', 'clothers_number', 'market_price')
            ->where('club_id', '=', $clubId)
            ->get();
    }

    public function GetFootballerById($footballerId) {
        return DB::table('footballers as fb')
            ->select('fb.full_name', 'fb.nationality', 'fb.date_of_birth', 'fb.height', 'clubs.full_name as club_name', 'fb.clothers_number', 'fb.market_price')
            ->join('clubs', 'clubs.id', '=', 'fb.club_id')
            ->where('fb.id', '=', $footballerId)
            ->first();
    }
}