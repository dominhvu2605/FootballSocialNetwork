<?php

namespace App\Services;

use App\Repositories\ClubRepository;
use App\Repositories\FootballerRepository;
use Illuminate\Support\Facades\Validator;

class ClubService {
    /**
     * @var ClubRepository
     * @var FootballerRepository
     */
    protected $clubRepo;
    protected $fbRepo;

    /**
     * SearchService Construct
     */
    public function __construct(ClubRepository $clubRepo, FootballerRepository $fbRepo) {
        $this->clubRepo = $clubRepo;
        $this->fbRepo = $fbRepo;
    }

    public function getClubInfo($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'clubId' => 'required|numeric|exists:clubs,id',
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // get club information
        $clubInfo = (array) $this->clubRepo->getClubInfo($data['clubId']);
        unset($clubInfo['created_at']);
        unset($clubInfo['modified_at']);
        unset($clubInfo['deleted_at']);
        $return['status'] = true;
        $return['message'] = 'Get club information successfully.';
        $return['data'] = $clubInfo;
        return $return;
    }

    public function getFootballer($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'clubId' => 'required|numeric|exists:clubs,id',
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // get list footballer of club
        $listFootballer = $this->fbRepo->GetFootballerByClub($data['clubId']);
        $return['status'] = true;
        $return['message'] = 'Get list footballer of club successfully.';
        $return['data'] = $listFootballer;
        return $return;
    }
}