<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Services\LeagueService;
use Illuminate\Http\Request;

class LeagueController extends Controller
{
    /**
     * define constant
     */
    const HTTP_OK = 200;
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    /**
     * @var LeagueService
     */
    protected $leagueService;

    /**
     * PostController Construct
     */
    public function __construct(LeagueService $leagueService)
    {
        $this->leagueService = $leagueService;
    }

    public function getListLeague() {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get match schedule
        $data = $this->leagueService->getListLeague();
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        $return['data'] = $data['data'];
        return response()->json($return, self::HTTP_OK);
    }

    public function getListLeagueForAdmin() {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get match schedule
        $data = $this->leagueService->getListLeagueForAdmin();
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        $return['totalPages'] = $data['totalPages'];
        $return['data'] = $data['data'];
        return response()->json($return, self::HTTP_OK);
    }

    public function getLeagueInfo(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get match schedule
        $data = $this->leagueService->getLeagueInfo($request->all());
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        $return['data'] = $data['data'];
        return response()->json($return, self::HTTP_OK);
    }

    public function updateLeague(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get match schedule
        $data = $this->leagueService->updateLeague($request->all());
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        return response()->json($return, self::HTTP_OK);
    }

    public function deleteLeague(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get match schedule
        $data = $this->leagueService->deleteLeague($request->all());
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        return response()->json($return, self::HTTP_OK);
    }

    public function createLeague(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get match schedule
        $data = $this->leagueService->createLeague($request->all());
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        return response()->json($return, self::HTTP_OK);
    }

    public function searchLeague(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // update post
        $result = $this->leagueService->searchLeague($request->all());
        if (!$result['status']) {
            $return['message'] = $result['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $result['message'];
        $return['data'] = $result['data'];
        return response()->json($return, self::HTTP_OK);
    }
}
