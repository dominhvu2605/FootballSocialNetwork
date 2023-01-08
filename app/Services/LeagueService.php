<?php

namespace App\Services;

use App\Repositories\LeagueRepository;

class LeagueService {
    /**
     * @var LeagueRepository
     */
    protected $leagueRepo;

    /**
     * SearchService Construct
     */
    public function __construct(LeagueRepository $leagueRepo) {
        $this->leagueRepo = $leagueRepo;
    }

    public function getListLeague() {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // get list league
        $leagues = $this->leagueRepo->getListLeague();
        $return['status'] = true;
        $return['message'] = 'Get list leagues successfully.';
        $return['data'] = $leagues;
        return $return;
    }
}