<?php

namespace App\Services;

use App\Repositories\LeagueRepository;
use Illuminate\Support\Facades\Validator;

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

    public function getListLeagueForAdmin() {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // get list league
        $leagues = $this->leagueRepo->getListLeagueForAdmin();
        $leagues = json_decode(json_encode($leagues));
        $return['status'] = true;
        $return['message'] = 'Get list leagues successfully.';
        $return['totalPages'] = $leagues->last_page;
        $return['data'] = $leagues->data;
        return $return;
    }

    public function getLeagueInfo($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'leagueId' => 'required|numeric|exists:leagues,id',
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // get footballer information
        $leagueInfo = $this->leagueRepo->getLeagueInfo($data['leagueId']);
        $return['status'] = true;
        $return['message'] = 'Get league information successfully.';
        $return['data'] = $leagueInfo;
        return $return;
    }

    public function updateLeague($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'leagueId' => 'required|numeric|exists:leagues,id',
            'name' => 'required|max:255',
            'shortName' => 'max:50'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // update post
        $dataUpdate = [
            'name' => $data['name'],
            'short_name' => $data['shortName']
        ];
        $updateResult = $this->leagueRepo->updateLeague($data['leagueId'], $dataUpdate);
        if (!$updateResult) {
            $return['message'] = 'Update league information failed.';
            return $return;
        }
        $return['status'] = true;
        $return['message'] = 'Update league information successfully.';
        return $return;
    }

    public function deleteLeague($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'leagueId' => 'required|numeric|exists:leagues,id',
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // delete post
        $result = $this->leagueRepo->deleteLeague($data['leagueId']);
        if (!$result) {
            $return['message'] = 'Delete league failed.';
            return $return;
        }
        $return['status'] = true;
        $return['message'] = 'Delete league successfully.';
        return $return;
    }

    public function createLeague($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'name' => 'required|max:255',
            'shortName' => 'max:50'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // update post
        $newData = [
            'name' => $data['name'],
            'short_name' => $data['shortName']
        ];
        $result = $this->leagueRepo->createLeague($newData);
        if (!$result) {
            $return['message'] = 'Create new league information failed.';
            return $return;
        }
        $return['status'] = true;
        $return['message'] = 'Create new league information successfully.';
        return $return;
    }

    public function searchLeague($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'searchKey' => 'required'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // search post
        $result = $this->leagueRepo->searchLeague($data['searchKey']);
        if (!$result) {
            $return['message'] = 'Search league by key failed.';
            return $return;
        }
        $return['status'] = true;
        $return['message'] = 'Search league by key successfully.';
        $return['data'] = $result;
        return $return;
    }
}