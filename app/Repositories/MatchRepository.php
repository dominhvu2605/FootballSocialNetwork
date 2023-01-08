<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MatchRepository {

    public function getListMatchesByDay($type, $days) {
        $sql = DB::table('matches as m')
            ->select(
                'm.id as match_id',
                'hteam.full_name as home_team_name',
                'ateam.full_name as away_team_name',
                'l.id as league_id',
                'l.name as league_name',
                'm.time_start'
            )
            ->join('clubs as hteam', 'hteam.id', '=', 'm.home_team_id')
            ->join('clubs as ateam', 'ateam.id', '=', 'm.away_team_id')
            ->join('leagues as l', 'l.id', '=', 'm.league_id');
        if ($type == 'schedule') {
            $sql = $sql->where('m.status_id', '=', '1')
                        ->whereBetween('m.time_start', [Carbon::now()->addDays($days)->startOfDay(), Carbon::now()->addDays($days)->endOfDay()]);
        }
        if ($type == 'result') {
            $sql = $sql->addSelect('m.result', 'm.penalty_result');
            $sql = $sql->whereNotNull('m.result')
                        ->where('m.status_id', '=', '2')
                        ->whereBetween('m.time_start', [Carbon::now()->subDays($days)->startOfDay(), Carbon::now()->subDays($days)->endOfDay()]);
        }
        if ($type == 'predict') {
            $sql = $sql->addSelect('m.predicted_result');
            $sql = $sql->whereNotNull('m.predicted_result')
                        ->where('m.status_id', '=', '1')
                        ->whereBetween('m.time_start', [Carbon::now()->addDays($days)->startOfDay(), Carbon::now()->addDays($days)->endOfDay()]);
        }
        return $sql->orderBy('l.id')
                    ->orderBy('m.time_start')
                    ->get();
    }

    public function getListMatchesByLeague($type, $leagueId) {
        $sql = DB::table('matches as m')
            ->select(
                'm.id as match_id',
                'hteam.full_name as home_team_name',
                'ateam.full_name as away_team_name',
                'l.id as league_id',
                'l.name as league_name',
                'm.time_start'
            )
            ->join('clubs as hteam', 'hteam.id', '=', 'm.home_team_id')
            ->join('clubs as ateam', 'ateam.id', '=', 'm.away_team_id')
            ->join('leagues as l', 'l.id', '=', 'm.league_id')
            ->where('m.league_id', '=', $leagueId);
        if ($type == 'schedule') {
            $sql = $sql->where('m.status_id', '=', '1')
                        ->whereBetween('m.time_start', [Carbon::now()->startOfDay(), Carbon::now()->addDays(config('constants.scheduleDays'))->endOfDay()]);
        }
        if ($type == 'result') {
            $sql = $sql->addSelect('m.result', 'm.penalty_result');
            $sql = $sql->where('m.status_id', '=', '2')
                        ->whereBetween('m.time_start', [Carbon::now()->subDays(config('constants.scheduleDays'))->endOfDay(), Carbon::now()->endOfDay()]);
        }
        if ($type == 'predict') {
            $sql = $sql->addSelect('m.predicted_result');
            $sql = $sql->whereNotNull('m.predicted_result')
                        ->where('m.status_id', '=', '1')
                        ->whereBetween('m.time_start', [Carbon::now()->startOfDay(), Carbon::now()->addDays(config('constants.scheduleDays'))->endOfDay()]);
        }
        return $sql->orderBy('l.id')
                ->orderBy('m.time_start')
                ->get();
    }
}