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

    public function getListMatch() {
        $schedule = DB::table('matches as m')
            ->select(
                'm.id as match_id',
                'hteam.id as home_team_id',
                'hteam.full_name as home_team_name',
                'ateam.id as away_team_id',
                'ateam.full_name as away_team_name',
                'l.id as league_id',
                'l.name as league_name',
                'm.time_start',
                'm.stadium',
                'ms.name as match_status',
                'm.predicted_result',
                'm.result',
                'm.penalty_result'
            )
            ->join('clubs as hteam', 'hteam.id', '=', 'm.home_team_id')
            ->join('clubs as ateam', 'ateam.id', '=', 'm.away_team_id')
            ->join('leagues as l', 'l.id', '=', 'm.league_id')
            ->join('matches_status as ms', 'm.status_id', '=', 'ms.id')
            ->orderBy('m.time_start', 'desc')
            ->get();
        return $schedule;
    }

    public function getMatchInfo($matchId) {
        $matchInfo = DB::table('matches as m')
            ->select(
                'm.id as match_id',
                'hteam.id as home_team_id',
                'hteam.full_name as home_team_name',
                'ateam.id as away_team_id',
                'ateam.full_name as away_team_name',
                'l.id as league_id',
                'l.name as league_name',
                DB::raw('DATE_FORMAT(m.time_start, "%d-%m-%Y %H:%i:%s") as time_start'),
                'm.stadium',
                'ms.name as match_status',
                'm.predicted_result',
                'm.result',
                'm.penalty_result'
            )
            ->join('clubs as hteam', 'hteam.id', '=', 'm.home_team_id')
            ->join('clubs as ateam', 'ateam.id', '=', 'm.away_team_id')
            ->join('leagues as l', 'l.id', '=', 'm.league_id')
            ->join('matches_status as ms', 'm.status_id', '=', 'ms.id')
            ->orderBy('m.time_start', 'desc')
            ->where('m.id', '=', $matchId)
            ->get();
        return $matchInfo;
    }

    public function updateMatch($matchId, $dataUpdate) {
        return DB::table('matches')
            ->where('id', '=', $matchId)
            ->update($dataUpdate);
    }

    public function deleteMatch($matchId) {
        return DB::table('matches')
            ->where('id', '=', $matchId)
            ->delete();
    }

    public function createNewMatch($newData) {
        return DB::table('matches')
            ->insert($newData);
    }

    public function searchMatch($searchKey) {
        $listMatch = DB::table('matches as m')
            ->select(
                'm.id as match_id',
                'hteam.id as home_team_id',
                'hteam.full_name as home_team_name',
                'ateam.id as away_team_id',
                'ateam.full_name as away_team_name',
                'l.id as league_id',
                'l.name as league_name',
                'm.time_start',
                'm.stadium',
                'ms.name as match_status',
                'm.predicted_result',
                'm.result',
                'm.penalty_result'
            )
            ->join('clubs as hteam', 'hteam.id', '=', 'm.home_team_id')
            ->join('clubs as ateam', 'ateam.id', '=', 'm.away_team_id')
            ->join('leagues as l', 'l.id', '=', 'm.league_id')
            ->join('matches_status as ms', 'm.status_id', '=', 'ms.id')
            ->where(DB::raw('lower(hteam.full_name)'), 'REGEXP', strtolower($searchKey))
            ->orWhere(DB::raw('lower(ateam.full_name)'), 'REGEXP', strtolower($searchKey))
            ->orWhere(DB::raw('lower(l.name)'), 'REGEXP', strtolower($searchKey))
            ->orWhere(DB::raw('lower(m.stadium)'), 'REGEXP', strtolower($searchKey))
            ->orWhere(DB::raw('lower(m.time_start)'), 'REGEXP', strtolower($searchKey))
            ->orderBy('m.time_start', 'desc')
            ->get();
        return $listMatch;
    }
}