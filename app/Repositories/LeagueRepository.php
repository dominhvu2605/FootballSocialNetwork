<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class LeagueRepository {

    public function getListLeague() {
        return DB::table('leagues')
            ->select('id', 'name', 'short_name')
            ->whereNull('deleted_at')
            ->get();
    }

    public function getLeagueInfo($leagueId) {
        return DB::table('leagues')
            ->select('id', 'name', 'short_name')
            ->where('id', '=', $leagueId)
            ->first();
    }

    public function updateLeague($leagueId, $dataUpdate) {
        return DB::table('leagues')
            ->where('id', '=', $leagueId)
            ->update($dataUpdate);
    }

    public function deleteLeague($leagueId) {
        return DB::table('leagues')
            ->where('id', '=', $leagueId)
            ->delete();
    }

    public function createLeague($newLeague) {
        return DB::table('leagues')
            ->insert($newLeague);
    }

    public function searchLeague($searchKey) {
        return DB::table('leagues')
            ->select('id', 'name', 'short_name')
            ->where(DB::raw('lower(name)'), 'REGEXP', strtolower($searchKey))
            ->orWhere(DB::raw('lower(short_name)'), 'REGEXP', strtolower($searchKey))
            ->get();
    }
}