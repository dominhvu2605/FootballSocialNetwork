<?php

namespace App\Services;

use App\Repositories\ClubRepository;
use App\Repositories\FollowingsRepository;
use App\Repositories\FootballerRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FollowingsService {
    /**
     * @var FollowingsRepository
     * @var ClubRepository
     * @var FootballerRepository
     */
    protected $flRepo;
    protected $clubRepo;
    protected $fbRepo;

    /**
     * PostService Construct
     */
    public function __construct(FollowingsRepository $flRepo, ClubRepository $clubRepo, FootballerRepository $fbRepo)
    {
        $this->flRepo = $flRepo;
        $this->clubRepo = $clubRepo;
        $this->fbRepo = $fbRepo;
    }

    public function getFollowingsClub($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'userId' => 'required|numeric|exists:users,id'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // get list followings club by usreId
        $userId = $data['userId'];
        $listClubs = $this->flRepo->getFollowingsClub($userId);
        foreach ($listClubs as $key => $club) {
            $listClubs[$key] = [
                'club_id' => $club->club_id_fl,
                'club_name' => $club->full_name,
            ];
        }

        // return data
        $return['status'] = true;
        $return['message'] = 'Get list clubs successfully.';
        $return['data'] = $listClubs;
        return $return;
    }

    public function getFollowingsFootballer($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'userId' => 'required|numeric|exists:users,id'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // get list followings footballer by usreId
        $userId = $data['userId'];
        $listFootballer = $this->flRepo->getFollowingsFootballer($userId);
        foreach ($listFootballer as $key => $footballer) {
            $listFootballer[$key] = [
                'footballer_id' => $footballer->footballer_id_fl,
                'footballer_name' => $footballer->full_name,
            ];
        }

        // return data
        $return['status'] = true;
        $return['message'] = 'Get list footballers successfully.';
        $return['data'] = $listFootballer;
        return $return;
    }

    public function getSuggestedClub($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'userId' => 'required|numeric|exists:users,id'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // get list suggested club
        $allClubs = $this->clubRepo->getAllClub();
        $listFamousClubs = $this->flRepo->getFamousClubs();
        $followedClubs = $this->flRepo->getFollowedClubs($data['userId']);
        $listClub = [];
        foreach ($listFamousClubs as $famousClub) {
            if (in_array($famousClub->club_id_fl, $followedClubs)) {
                continue;
            }
            unset($famousClub->count);
            $listClub[] = $famousClub;
            if (count($listClub) >= config('constants.suggestCount')) {
                break;
            }
        }
        $addedClub = array_column($listClub, 'club_id_fl');
        if (count($listClub) < config('constants.suggestCount')) {
            foreach ($allClubs as $club) {
                if (in_array($club->id, $addedClub) || in_array($club->id, $followedClubs)) {
                    continue;
                }
                $listClub[] = $club;
                if (count($listClub) >= config('constants.suggestCount')) {
                    break;
                }
            }
        }
        foreach ($listClub as $key => $club) {
            $listClub[$key] = $club->full_name;
        }
        // return data
        $return['status'] = true;
        $return['message'] = 'Get list clubs successfully.';
        $return['data'] = $listClub;
        return $return;
    }

    public function getSuggestedFootballer($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'userId' => 'required|numeric|exists:users,id'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // get list suggested club
        $allFootballer = $this->fbRepo->getAllFootballer();
        $listFamousFootballer = $this->flRepo->getFamousFootballer();
        $followedFootballers = $this->flRepo->getFollowedFootballers($data['userId']);
        $listFootballer = [];
        foreach ($listFamousFootballer as $famousFootballer) {
            if (in_array($famousFootballer->footballer_id_fl, $followedFootballers)) {
                continue;
            }
            unset($famousFootballer->count);
            $listFootballer[] = $famousFootballer;
            if (count($listFootballer) >= config('constants.suggestCount')) {
                break;
            }
        }
        $addedFootballer = array_column($listFootballer, 'footballer_id_fl');
        if (count($listFootballer) < config('constants.suggestCount')) {
            foreach ($allFootballer as $footballer) {
                if (in_array($footballer->id, $addedFootballer) || in_array($footballer->id, $followedFootballers)) {
                    continue;
                }
                $listFootballer[] = $footballer;
                if (count($listFootballer) >= config('constants.suggestCount')) {
                    break;
                }
            }
        }
        foreach ($listFootballer as $key => $footballer) {
            $listFootballer[$key] = $footballer->full_name;
        }
        // return data
        $return['status'] = true;
        $return['message'] = 'Get list footballers successfully.';
        $return['data'] = $listFootballer;
        return $return;
    }

    public function followingClub($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'userId' => 'required|numeric|exists:users,id',
            'clubId' => 'required|numeric|exists:clubs,id'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        try {
            $userId = $data['userId'];
            $clubId = $data['clubId'];
            // Check to see if you have followed the club
            if ($this->flRepo->checkFollowedClub($userId, $clubId)) {
                // followed => unfollow
                $this->flRepo->unFollowClub($userId, $clubId);
                $return['status'] = true;
                $return['message'] = 'Unfollow club successfully.';
                return $return;
            } else {
                $dataInsert = [
                    'user_id' => $userId,
                    'club_id_fl' => $clubId,
                    'followings_at' => date('Y/m/d H:i:s')
                ];
                $this->flRepo->addFollow($dataInsert);
                $return['status'] = true;
                $return['message'] = 'Follow club successfully.';
                return $return;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $return['message'] = $e->getMessage();
            return $return;
        }
    }

    public function followingFootballer($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'userId' => 'required|numeric|exists:users,id',
            'footballerId' => 'required|numeric|exists:footballers,id'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        try {
            $userId = $data['userId'];
            $footballerId = $data['footballerId'];
            // Check to see if you have followed the club
            if ($this->flRepo->checkFollowedFootballer($userId, $footballerId)) {
                // followed => unfollow
                $this->flRepo->unFollowFootballer($userId, $footballerId);
                $return['status'] = true;
                $return['message'] = 'Unfollow footballer successfully.';
                return $return;
            } else {
                $dataInsert = [
                    'user_id' => $userId,
                    'footballer_id_fl' => $footballerId,
                    'followings_at' => date('Y/m/d H:i:s')
                ];
                $this->flRepo->addFollow($dataInsert);
                $return['status'] = true;
                $return['message'] = 'Follow footballer successfully.';
                return $return;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $return['message'] = $e->getMessage();
            return $return;
        }
    }
}