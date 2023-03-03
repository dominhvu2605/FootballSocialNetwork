<?php

namespace App\Services;

use App\Repositories\MatchRepository;
use Illuminate\Support\Facades\Validator;

class MatchService {
    /**
     * @var MatchRepository
     */
    protected $matchRepo;

    /**
     * SearchService Construct
     */
    public function __construct(MatchRepository $matchRepo) {
        $this->matchRepo = $matchRepo;
    }

    public function getListMatchesByDay($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'type' => 'required|in:schedule,result,predict',
            'days' => 'required|integer|between:0,' . config('constants.scheduleDays')
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // get matches schedule by day
        $schedule = $this->matchRepo->getListMatchesByDay($data['type'], $data['days']);
        $scheduleFinal = [];
        if (count($schedule) > 0) {
            foreach ($schedule as $match) {
                $leagueName = $match->league_name;
                $leagueId = $match->league_id;
                unset($match->league_id);
                unset($match->league_name);
                $match->date = date('d-m-Y', strtotime($match->time_start));
                $match->time_start = date('H:i:s', strtotime($match->time_start));
                $scheduleFinal[$leagueName]['league_id'] = $leagueId;
                $scheduleFinal[$leagueName]['schedule'][] = $match;
            }
        }
        $return['status'] = true;
        $return['message'] = 'Get list matches successfully.';
        $return['data'] = $scheduleFinal;
        return $return;
    }

    public function getListMatchesByLeague($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'type' => 'required|in:schedule,result,predict',
            'leagueId' => 'required|numeric|exists:leagues,id',
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // get matches schedule by leagueId
        $schedule = $this->matchRepo->getListMatchesByLeague($data['type'], $data['leagueId']);
        $scheduleFinal = [];
        $scheduleFinal['leagueId'] = $data['leagueId'];
        if (count($schedule) > 0) {
            $scheduleFinal['leagueName'] =  $schedule[0]->league_name;
            $scheduleFinal['schedule'] = [];
            foreach ($schedule as $match) {
                unset($match->league_id);
                unset($match->league_name);
                $dateStart = date('d-m-Y', strtotime($match->time_start));
                $match->time_start = date('H:i:s', strtotime($match->time_start));
                $scheduleFinal['schedule'][$dateStart][] = $match;
            }
        } else {
            $scheduleFinal['schedule'] = [];
        }
        $return['status'] = true;
        $return['message'] = 'Get list matches successfully.';
        $return['data'] = $scheduleFinal;
        return $return;
    }

    public function getListMatch() {
        $return = [
            'status' => false,
            'message' => ''
        ];

        $schedule = $this->matchRepo->getListMatch();
        $return['status'] = true;
        $return['message'] = 'Get list matches successfully.';
        $return['data'] = $schedule;
        return $return;
    }

    public function getMatchInfo($data) {
        $return = [
            'status' => true,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'matchId' => 'required|numeric|exists:matches,id'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // get matches schedule by day
        $matchInfo = $this->matchRepo->getMatchInfo($data['matchId']);
        $return['message'] = 'Get matche info successfully.';
        $return['data'] = $matchInfo;
        return $return;
    }

    public function updateMatch($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'matchId' => 'required|numeric|exists:matches,id',
            'homeTeamId' => 'required|numeric|exists:clubs,id',
            'awayTeamId' => 'required|numeric|exists:clubs,id',
            'leagueId' => 'required|numeric|exists:leagues,id',
            'timeStart' => 'date_format:d-m-Y H:i:s',
            'stadium' => 'max:255',
            'statusId' => 'required|numeric|exists:matches_status,id',
            'predictedResult' => 'max:50',
            'result' => 'max:50',
            'penaltyResult' => 'max:50'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // create data to update
        $updateData = [
            'home_team_id' => $data['homeTeamId'],
            'away_team_id' => $data['awayTeamId'],
            'league_id' => $data['leagueId'],
            'time_start' => isset($data['timeStart']) ? date('Y-m-d H:i:s', strtotime($data['timeStart'])) : null,
            'stadium' => isset($data['stadium']) ? $data['stadium'] : null,
            'status_id' => $data['statusId'],
            'predicted_result' => isset($data['predictedResult']) ? $data['predictedResult'] : null,
            'result' => isset($data['result']) ? $data['predictedResult'] : null,
            'penalty_result' => isset($data['penaltyResult']) ? $data['penaltyResult'] : null,
        ];
        if ($this->matchRepo->updateMatch($data['matchId'], $updateData)) {
            $return['status'] = true;
            $return['message'] = 'Update matches info successfully.';
            return $return;
        }

        $return['message'] = 'Update matches info failed.';
        return $return;
    }
}