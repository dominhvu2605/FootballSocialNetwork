<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Services\MatchService;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    /**
     * define constant
     */
    const HTTP_OK = 200;
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    /**
     * @var MatchService
     */
    protected $matchService;

    /**
     * PostController Construct
     */
    public function __construct(MatchService $matchService)
    {
        $this->matchService = $matchService;
    }

    public function getListMatchesByDay(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get match schedule
        $data = $this->matchService->getListMatchesByDay($request->all());
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        $return['data'] = $data['data'];
        return response()->json($return, self::HTTP_OK);
    }

    public function getListMatchesByLeague(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get club information
        $data = $this->matchService->getListMatchesByLeague($request->all());
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        $return['data'] = $data['data'];
        return response()->json($return, self::HTTP_OK);
    }

    public function getListMatch() {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get club information
        $data = $this->matchService->getListMatch();
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        $return['data'] = $data['data'];
        return response()->json($return, self::HTTP_OK);
    }

    public function getMatchInfo(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get club information
        $data = $this->matchService->getMatchInfo($request->all());
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        $return['data'] = $data['data'];
        return response()->json($return, self::HTTP_OK);
    }

    public function updateMatch(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get club information
        $data = $this->matchService->updateMatch($request->all());
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        return response()->json($return, self::HTTP_OK);
    }

    public function deleteMatch(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // update post
        $result = $this->matchService->deleteMatch($request->all());
        if (!$result['status']) {
            $return['message'] = $result['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $result['message'];
        return response()->json($return, self::HTTP_OK);
    }

    public function createMatch(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // update post
        $result = $this->matchService->createMatch($request->all());
        if (!$result['status']) {
            $return['message'] = $result['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $result['message'];
        return response()->json($return, self::HTTP_OK);
    }

    public function searchMatch(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // update post
        $result = $this->matchService->searchMatch($request->all());
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
