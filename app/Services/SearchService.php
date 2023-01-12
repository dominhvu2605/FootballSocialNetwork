<?php

namespace App\Services;

use App\Repositories\SearchRepository;
use Illuminate\Support\Facades\Validator;

class SearchService {
    /**
     * @var SearchRepository
     */
    protected $searchRepo;

    /**
     * SearchService Construct
     */
    public function __construct(SearchRepository $searchRepo) {
        $this->searchRepo = $searchRepo;
    }

    public function getHistory($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'userId' => 'required|numeric|exists:users,id',
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // get list search history
        $listSearch = $this->searchRepo->getHistory($data['userId']);
        $return['status'] = true;
        $return['message'] = 'Get list search key successfully.';
        $return['data'] = $listSearch;
        return $return;
    }

    public function addSearchKey($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'userId' => 'required|numeric|exists:users,id',
            'searchKey' => 'required'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // add search key to table
        try {
            $dataInsert = [
                'user_id' => $data['userId'],
                'search_key' => $data['searchKey']
            ];
            if ($this->searchRepo->checkSearchKeyExists($data['userId'], $data['searchKey'])) {
                $return['status'] = true;
                $return['message'] = 'Search key existed.';
                return $return;
            }
            $this->searchRepo->addSearchKey($dataInsert);
            $return['status'] = true;
            $return['message'] = 'Add search key successfully.';
            return $return;
        } catch (\Exception $e) {
            $return['message'] = $e->getMessage();
            return $return;
        }
    }

    public function deleteSearchKey($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'userId' => 'required|numeric|exists:search_history,user_id',
            'searchKey' => 'required|exists:search_history,search_key'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // delete search key
        try {
            $this->searchRepo->deleteSearchKey($data['userId'], $data['searchKey']);
            $return['status'] = true;
            $return['message'] = 'Delete search key successfully.';
            return $return;
        } catch (\Exception $e) {
            $return['message'] = $e->getMessage();
            return $return;
        }
    }

    public function deleteAll($data) {
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

        //delete all search key of user
        try {
            $this->searchRepo->deleteAllSearchKeyByUser($data['userId']);
            $return['status'] = true;
            $return['message'] = 'Delete all search key successfully.';
            return $return;
        } catch (\Exception $e) {
            $return['message'] = $e->getMessage();
            return $return;
        }
    }

    public function search($data) {
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

        // search
        try {
            $clubResult = $this->searchRepo->clubSearch($data['searchKey']);
            $footballerResult = $this->searchRepo->footballerSearch($data['searchKey']);
            $postResult = $this->searchRepo->postSearch($data['searchKey']);
            $return['status'] = true;
            $return['message'] = 'Search by key successfully.';
            $return['data'] = [
                'club' => $clubResult,
                'footballer' => $footballerResult,
                'post' => $postResult
            ];
            return $return;
        } catch (\Exception $e) {
            $return['message'] = $e->getMessage();
            return $return;
        }
    }
}