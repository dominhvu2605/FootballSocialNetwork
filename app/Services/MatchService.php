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
}