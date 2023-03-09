<?php

namespace App\Services;

use App\Repositories\FootballerRepository;
use Illuminate\Support\Facades\Validator;

class FootballerService {
    /**
     * @var FootballerRepository
     */
    protected $fbRepo;

    /**
     * SearchService Construct
     */
    public function __construct(FootballerRepository $fbRepo) {
        $this->fbRepo = $fbRepo;
    }

    public function getFootballerInfo($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'footballerId' => 'required|numeric|exists:footballers,id',
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // get footballer information
        $footballerInfo = (array) $this->fbRepo->getFootballerById($data['footballerId']);
        $return['status'] = true;
        $return['message'] = 'Get footballer information successfully.';
        $return['data'] = $footballerInfo;
        return $return;
    }

    public function getAllFootballer() {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // get list footballer of club
        $allFootballer = $this->fbRepo->getAllFbForAdmin();
        $allFootballer = json_decode(json_encode($allFootballer));
        $return['status'] = true;
        $return['message'] = 'Get all footballer successfully.';
        $return['totalPages'] = $allFootballer->last_page;
        $return['data'] = $allFootballer->data;
        return $return;
    }

    public function updateFootballer($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'footballerId' => 'required|numeric|exists:footballers,id',
            'fullName' => 'required|max:255',
            'shortName' => 'max:50',
            'nationality' => 'max:255',
            'placeOfBirth' => 'max:255',
            'dateOfBirth' => 'date',
            'height' => 'numeric',
            'clubId' => 'numeric|exists:clubs,id',
            'clothersNumber' => 'numeric',
            'market_price' => 'string'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // update club
        $dataUpdate = [
            'full_name' => $data['fullName'],
            'short_name' => isset($data['shortName']) ? $data['shortName'] : null,
            'nationality' => isset($data['nationality']) ? $data['nationality'] : null,
            'place_of_birth' => isset($data['placeOfBirth']) ? $data['placeOfBirth'] : null,
            'date_of_birth' => isset($data['dateOfBirth']) ? $data['dateOfBirth'] : null,
            'height' => isset($data['height']) ? $data['height'] : null,
            'club_id' => isset($data['clubId']) ? $data['clubId'] : null,
            'clothers_number' => isset($data['clothersNumber']) ? $data['clothersNumber'] : null,
            'market_price' => isset($data['market_price']) ? $data['market_price'] : null,
        ];
        $updateResult = $this->fbRepo->updateFootballer($data['footballerId'], $dataUpdate);
        if (!$updateResult) {
            $return['message'] = 'Update footballer information failed.';
            return $return;
        }
        $return['status'] = true;
        $return['message'] = 'Update footballer information successfully.';
        return $return;
    }

    public function deleteFootballer($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'footballerId' => 'required|numeric|exists:footballers,id'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // delete post
        $result = $this->fbRepo->deleteFootballer($data['footballerId']);
        if (!$result) {
            $return['message'] = 'Delete footballer failed.';
            return $return;
        }
        $return['status'] = true;
        $return['message'] = 'Delete footballer successfully.';
        return $return;
    }

    public function createFootballer($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'fullName' => 'required|max:255',
            'shortName' => 'max:50',
            'nationality' => 'max:255',
            'placeOfBirth' => 'max:255',
            'dateOfBirth' => 'date',
            'height' => 'numeric',
            'clubId' => 'numeric|exists:clubs,id',
            'clothersNumber' => 'numeric',
            'market_price' => 'string'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // update club
        $newData = [
            'full_name' => $data['fullName'],
            'short_name' => isset($data['shortName']) ? $data['shortName'] : null,
            'nationality' => isset($data['nationality']) ? $data['nationality'] : null,
            'place_of_birth' => isset($data['placeOfBirth']) ? $data['placeOfBirth'] : null,
            'date_of_birth' => isset($data['dateOfBirth']) ? $data['dateOfBirth'] : null,
            'height' => isset($data['height']) ? $data['height'] : null,
            'club_id' => isset($data['clubId']) ? $data['clubId'] : null,
            'clothers_number' => isset($data['clothersNumber']) ? $data['clothersNumber'] : null,
            'market_price' => isset($data['market_price']) ? $data['market_price'] : null,
        ];
        $updateResult = $this->fbRepo->createFootballer($newData);
        if (!$updateResult) {
            $return['message'] = 'Create new footballer information failed.';
            return $return;
        }
        $return['status'] = true;
        $return['message'] = 'Create new footballer information successfully.';
        return $return;
    }

    public function searchFootballer($data) {
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
        $result = $this->fbRepo->searchFootballer($data['searchKey']);
        if (!$result) {
            $return['message'] = 'Search footballer by key successfully.';
            return $return;
        }
        $return['status'] = true;
        $return['message'] = 'Search footballer by key successfully.';
        $return['data'] = $result;
        return $return;
    }
}