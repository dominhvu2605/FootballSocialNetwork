<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * define constant
     */
    const HTTP_OK = 200;
    const HTTP_UNPROCESSABLE_ENTITY = 422;

    /**
     * @var SearchService
     */
    protected $searchService;

    /**
     * PostController Construct
     */
    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function getHistory(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => ''
        ];

        // get history search key
        $data = $this->searchService->getHistory($request->all());
        if (!$data['status']) {
            $return['message'] = $data['message'];
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $data['message'];
        $return['data'] = $data['data'];
        return response()->json($return, self::HTTP_OK);
    }

    public function addSearchKey(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'An error occurred while adding the search key.'
        ];

        $result = $this->searchService->addSearchKey($request->all());
        if (!$result['status']) {
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $result['message'];
        return $return;
    }

    public function deleteSearchKey(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'An error occurred while deleting the search key.'
        ];

        $result = $this->searchService->deleteSearchKey($request->all());
        if (!$result['status']) {
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $result['message'];
        return $return;
    }

    public function deleteAll(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'An error occurred while deleting all search key.'
        ];

        $result = $this->searchService->deleteAll($request->all());
        if (!$result['status']) {
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $result['message'];
        return $return;
    }

    public function search(Request $request) {
        $return = [
            'code' => self::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'An error occurred while searching.'
        ];

        $result = $this->searchService->search($request->all());
        return $result;
        if (!$result['status']) {
            return response()->json($return, self::HTTP_UNPROCESSABLE_ENTITY);
        }
        $return['code'] = self::HTTP_OK;
        $return['message'] = $result['message'];
        $return['data'] = $result['data'];
        return $return;
    }
}
