<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class FootballerRepository {

    public function getFootballerName($footballerId) {
        return DB::table('footballers')
            ->select('full_name', 'short_name')
            ->where('id', '=', $footballerId)
            ->first();
    }

    public function getAllFootballer() {
        return DB::table('footballers')
            ->select('id', 'full_name')
            ->get();
    }

    public function getFootballerByClub($clubId) {
        return DB::table('footballers')
            ->select('id', 'full_name', 'nationality', 'date_of_birth', 'height', 'clothers_number', 'market_price')
            ->where('club_id', '=', $clubId)
            ->get();
    }

    public function getFootballerById($footballerId) {
        return DB::table('footballers as fb')
            ->select('fb.id', 'fb.full_name', 'fb.short_name', 'fb.nationality', 'fb.date_of_birth', 'fb.date_of_birth',
                    'fb.height', 'fb.club_id', 'clubs.full_name as club_name', 'fb.clothers_number', 'fb.market_price')
            ->join('clubs', 'clubs.id', '=', 'fb.club_id')
            ->where('fb.id', '=', $footballerId)
            ->first();
    }

    public function getAllFbForAdmin() {
        return DB::table('footballers as fb')
            ->select('fb.id', 'fb.full_name', 'fb.short_name', 'fb.nationality', 'fb.place_of_birth', 'fb.date_of_birth',
                    'fb.height', 'fb.club_id', 'clubs.full_name as club_name', 'fb.clothers_number', 'fb.market_price')
            ->join('clubs', 'clubs.id', '=', 'fb.club_id')
            ->orderBy('fb.modified_at', 'desc')
            ->orderBy('fb.created_at', 'desc')
            ->paginate(config('constants.perPage'));
    }

    public function updateFootballer($footballerId, $dataUpdate) {
        return DB::table('footballers')
            ->where('id', '=', $footballerId)
            ->update($dataUpdate);
    }

    public function deleteFootballer($footballerId) {
        return DB::table('footballers')
            ->where('id', '=', $footballerId)
            ->delete();
    }

    public function createFootballer($newFootballer) {
        return DB::table('footballers')
            ->insert($newFootballer);
    }

    public function searchFootballer($searchKey) {
        return DB::table('footballers')
            ->select('*')
            ->where(DB::raw('lower(full_name)'), 'REGEXP', strtolower($searchKey))
            ->orWhere(DB::raw('lower(short_name)'), 'REGEXP', strtolower($searchKey))
            ->orWhere(DB::raw('lower(nationality)'), 'REGEXP', strtolower($searchKey))
            ->orWhere(DB::raw('lower(place_of_birth)'), 'REGEXP', strtolower($searchKey))
            ->orWhere(DB::raw('lower(date_of_birth)'), 'REGEXP', strtolower($searchKey))
            ->orWhere(DB::raw('lower(height)'), 'REGEXP', strtolower($searchKey))
            ->orWhere(DB::raw('lower(club_id)'), 'REGEXP', strtolower($searchKey))
            ->orWhere(DB::raw('lower(clothers_number)'), 'REGEXP', strtolower($searchKey))
            ->orWhere(DB::raw('lower(market_price)'), 'REGEXP', strtolower($searchKey))
            ->get();
    }
}