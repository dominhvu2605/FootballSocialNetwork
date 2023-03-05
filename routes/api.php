<?php

use App\Http\Controllers\ApiController\AuthController;
use App\Http\Controllers\ApiController\ClubController;
use App\Http\Controllers\ApiController\CommentController;
use App\Http\Controllers\ApiController\FollowingsController;
use App\Http\Controllers\ApiController\FootballerController;
use App\Http\Controllers\ApiController\LeagueController;
use App\Http\Controllers\ApiController\LikeController;
use App\Http\Controllers\ApiController\MatchController;
use App\Http\Controllers\ApiController\PostController;
use App\Http\Controllers\ApiController\SearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('/auth')->group(function() {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/get_user_infor', [AuthController::class, 'getInfo']);
    Route::post('/send_otp', [AuthController::class, 'sendOtpCode']);
    Route::post('/change_password', [AuthController::class, 'changePassword']);
    Route::post('/forgot_password', [AuthController::class, 'forgotPassword']);
});

Route::prefix('/post')->group(function() {
    Route::get('/get_post_list', [PostController::class, 'getPostList']);
    Route::get('/get_club_post/{clubId}', [PostController::class, 'getPostByClub']);
    Route::get('/get_footballer_post/{footballerId}', [PostController::class, 'getPostByFootballer']);
});

Route::prefix('/followings')->group(function() {
    // followed
    Route::post('/get_followings_club', [FollowingsController::class, 'getFollowingsClub']);
    Route::post('/get_followings_footballer', [FollowingsController::class, 'getFollowingsFootballer']);
    // suggestions
    Route::post('/get_suggested_club', [FollowingsController::class, 'getSuggestedClub']);
    Route::post('/get_suggested_footballer', [FollowingsController::class, 'getSuggestedFootballer']);
    // follow
    Route::post('/club/follow', [FollowingsController::class, 'followingClub']);
    Route::post('/footballer/follow', [FollowingsController::class, 'followingFootballer']);
});

Route::prefix('/comment')->group(function() {
    Route::post('/list_comment', [CommentController::class, 'getListComment']);
    Route::post('/add', [CommentController::class, 'addComment']);
    Route::post('/edit', [CommentController::class, 'editComment']);
    Route::post('/delete', [CommentController::class, 'deleteComment']);
});

Route::prefix('/like')->group(function() {
    Route::post('/list_user', [LikeController::class, 'getListUserLiked']);
    Route::post('/like_post', [LikeController::class, 'like']);
});

Route::prefix('/search')->group(function() {
    Route::post('/history', [SearchController::class, 'getHistory']);
    Route::post('/add', [SearchController::class, 'addSearchKey']);
    Route::post('/delete', [SearchController::class, 'deleteSearchKey']);
    Route::post('/delete_all', [SearchController::class, 'deleteAll']);
    Route::post('/search_by_key', [SearchController::class, 'search']);
});

Route::prefix('/club')->group(function() {
    Route::post('/info', [ClubController::class, 'getInfo']);
    Route::post('/listFootballer', [ClubController::class, 'getListFootballer']);
});

Route::prefix('/footballer')->group(function() {
    Route::post('/info', [FootballerController::class, 'getFootballerInfo']);
});

Route::prefix('match')->group(function() {
    Route::post('/list_matches_by_day', [MatchController::class, 'getListMatchesByDay']);
    Route::post('/list_matches_by_league', [MatchController::class, 'getListMatchesByLeague']);
});

Route::prefix('/league')->group(function() {
    Route::post('/list', [LeagueController::class, 'getListLeague']);
});

/**
 * Admin api
 */
Route::prefix('/admin')->group(function() {
    Route::prefix('/post')->group(function() {
        Route::get('/all', [PostController::class, 'getAllPost']);
        Route::post('/detail', [PostController::class, 'getPostDetail']);
        Route::post('/edit', [PostController::class, 'updatePost']);
        Route::post('/delete', [PostController::class, 'deletePost']);
        Route::post('/create', [PostController::class, 'createPost']);
        Route::post('/search', [PostController::class, 'searchPost']);
    });
    Route::prefix('/club')->group(function() {
        Route::post('/all', [ClubController::class, 'getAllClub']);
        Route::post('/detail', [ClubController::class, 'getInfo']);
        Route::post('/edit', [ClubController::class, 'updateClub']);
        Route::post('/delete', [ClubController::class, 'deleteClub']);
        Route::post('/create', [ClubController::class, 'createClub']);
        Route::post('/search', [ClubController::class, 'searchClub']);
    });
    Route::prefix('/footballer')->group(function() {
        Route::post('/all', [FootballerController::class, 'getAllFootballer']);
        Route::post('/detail', [FootballerController::class, 'getFootballerInfo']);
        Route::post('/edit', [FootballerController::class, 'updateFootballer']);
        Route::post('/delete', [FootballerController::class, 'deleteFootballer']);
        Route::post('/create', [FootballerController::class, 'createFootballer']);
        Route::post('/search', [FootballerController::class, 'searchFootballer']);
    });
    Route::prefix('/league')->group(function() {
        Route::post('/all', [LeagueController::class, 'getListLeague']);
        Route::post('/detail', [LeagueController::class, 'getLeagueInfo']);
        Route::post('/edit', [LeagueController::class, 'updateLeague']);
        Route::post('/delete', [LeagueController::class, 'deleteLeague']);
        Route::post('/create', [LeagueController::class, 'createLeague']);
        Route::post('/search', [LeagueController::class, 'searchLeague']);
    });
    Route::prefix('/match')->group(function() {
        Route::post('/all', [MatchController::class, 'getListMatch']);
        Route::post('/detail', [MatchController::class, 'getMatchInfo']);
        Route::post('/edit', [MatchController::class, 'updateMatch']);
        Route::post('/delete', [MatchController::class, 'deleteMatch']);
        Route::post('/create', [MatchController::class, 'createMatch']);
        Route::post('/search', [MatchController::class, 'searchMatch']);
    });
    Route::prefix('/user')->group(function() {
        Route::post('/all', [AuthController::class, 'getListUser']);
        Route::post('/detail', [AuthController::class, 'getUserInfo']);
        Route::post('/edit', [AuthController::class, 'updateUser']);
        Route::post('/delete', [AuthController::class, 'deleteUser']);
        Route::post('/create', [AuthController::class, 'createUser']);
        Route::post('/search', [AuthController::class, 'searchUser']);
    });
});

Route::get('/user', function(Request $request) {
    return response()->json(['code' => '200', 'message' => 'done']);
})->middleware('CheckAuth:admin');