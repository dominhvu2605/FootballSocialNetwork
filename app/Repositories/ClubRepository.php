<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class ClubRepository {

    public function getClubName($clubId) {
        return DB::table('clubs')
            ->select('full_name', 'short_name')
            ->where('id', '=', $clubId)
            ->first();
    }

    public function getAllClub() {
        return DB::table('clubs')
            ->select('id', 'full_name')
            ->get();
    }

    public function getClubInfo($clubId) {
        return DB::table('clubs')
            ->select('*')
            ->where('id', '=', $clubId)
            ->first();
    }
}