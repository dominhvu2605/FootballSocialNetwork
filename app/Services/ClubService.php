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
        $listFootballer = $this->fbRepo->getFootballerByClub($data['clubId']);
        $return['status'] = true;
        $return['message'] = 'Get list footballer of club successfully.';
        $return['data'] = $listFootballer;
        return $return;
    }

    public function getAllClub() {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // get list footballer of club
        $allClub = $this->clubRepo->getAllClub();
        $return['status'] = true;
        $return['message'] = 'Get all club successfully.';
        $return['data'] = $allClub;
        return $return;
    }

    public function updateClub($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'clubId' => 'required|numeric|exists:clubs,id',
            'fullName' => 'required|max:255',
            'shortName' => 'max:50',
            'foundedIn' => 'max:255',
            'owner' => 'max:255',
            'website' => 'max:255'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // update club
        $dataUpdate = [
            'full_name' => $data['fullName'],
            'short_name' => isset($data['shortName']) ? $data['shortName'] : null,
            'founded_in' => isset($data['foundedIn']) ? $data['foundedIn'] : null,
            'owner' => isset($data['owner']) ? $data['owner'] : null,
            'website' => isset($data['website']) ? $data['website'] : null,
        ];
        $updateResult = $this->clubRepo->updateClub($data['clubId'], $dataUpdate);
        if (!$updateResult) {
            $return['message'] = 'Update club information failed.';
            return $return;
        }
        $return['status'] = true;
        $return['message'] = 'Update club information successfully.';
        return $return;
    }

    public function deleteClub($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'clubId' => 'required|numeric|exists:clubs,id'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // delete post
        $result = $this->clubRepo->deleteClub($data['clubId']);
        if (!$result) {
            $return['message'] = 'Delete club failed.';
            return $return;
        }
        $return['status'] = true;
        $return['message'] = 'Delete club successfully.';
        return $return;
    }

    public function createClub($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'fullName' => 'required|max:255',
            'shortName' => 'max:50',
            'foundedIn' => 'max:255',
            'owner' => 'max:255',
            'website' => 'max:255'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // update club
        $newClub = [
            'full_name' => $data['fullName'],
            'short_name' => isset($data['shortName']) ? $data['shortName'] : null,
            'founded_in' => isset($data['foundedIn']) ? $data['foundedIn'] : null,
            'owner' => isset($data['owner']) ? $data['owner'] : null,
            'website' => isset($data['website']) ? $data['website'] : null,
        ];
        $result = $this->clubRepo->createClub($newClub);
        if (!$result) {
            $return['message'] = 'Create new club failed.';
            return $return;
        }
        $return['status'] = true;
        $return['message'] = 'Create new club successfully.';
        return $return;
    }

    public function searchClub($data) {
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

        // search post by search key
        $result = $this->clubRepo->searchClub($data['searchKey']);
        if (!$result) {
            $return['message'] = 'Search club by key successfully.';
            return $return;
        }
        $return['status'] = true;
        $return['message'] = 'Search club by key successfully.';
        $return['data'] = $result;
        return $return;
    }
}