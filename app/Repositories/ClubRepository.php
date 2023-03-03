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
            ->select('id', 'full_name', 'short_name', 'founded_in', 'owner', 'website')
            ->get();
    }

    public function getClubInfo($clubId) {
        return DB::table('clubs')
            ->select('*')
            ->where('id', '=', $clubId)
            ->first();
    }

    public function updateClub($clubId, $dataUpdate) {
        return DB::table('clubs')
            ->where('id', '=', $clubId)
            ->update($dataUpdate);
    }

    public function deleteClub($clubId) {
        return DB::table('clubs')
            ->where('id', '=', $clubId)
            ->delete();
    }

    public function createClub($newClub) {
        return DB::table('clubs')
            ->insert($newClub);
    }

    public function searchClub($searchKey) {
        return DB::table('clubs')
            ->select('*')
            ->where(DB::raw('lower(full_name)'), 'REGEXP', strtolower($searchKey))
            ->orWhere(DB::raw('lower(short_name)'), 'REGEXP', strtolower($searchKey))
            ->orWhere(DB::raw('lower(founded_in)'), 'REGEXP', strtolower($searchKey))
            ->orWhere(DB::raw('lower(owner)'), 'REGEXP', strtolower($searchKey))
            ->orWhere(DB::raw('lower(website)'), 'REGEXP', strtolower($searchKey))
            ->get();
    }
}