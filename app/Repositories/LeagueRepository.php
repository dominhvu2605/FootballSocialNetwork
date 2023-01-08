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
}