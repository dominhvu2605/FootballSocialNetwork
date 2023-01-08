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
        $footballerInfo = (array) $this->fbRepo->GetFootballerById($data['footballerId']);
        $return['status'] = true;
        $return['message'] = 'Get footballer information successfully.';
        $return['data'] = $footballerInfo;
        return $return;
    }
}