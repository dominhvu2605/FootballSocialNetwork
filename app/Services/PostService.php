<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Repositories\ClubRepository;
use App\Repositories\FootballerRepository;

class PostService {
    /**
     * @var PostRepository
     * @var ClubRepository
     * @var FootballerRepository
     */
    protected $postRepo;
    protected $clubRepo;
    protected $footballerRepo;

    /**
     * PostService Construct
     */
    public function __construct(
        PostRepository $postRepo,
        ClubRepository $clubRepo,
        FootballerRepository $footballerRepo
    )
    {
        $this->postRepo = $postRepo;
        $this->clubRepo = $clubRepo;
        $this->footballerRepo = $footballerRepo;
    }

    public function getPostList() {
        $postList = $this->postRepo->getPostList();
        return $postList;
    }

    public function getPostByClub($clubId) {
        // get club name to create searchKey
        $clubName = (array) $this->clubRepo->getClubName($clubId);
        if (empty($clubName)) {
            return [];
        }

        // create searchKey
        $fullName = array_map('trim', explode(',', $clubName['full_name']));
        $shortName = array_map('trim', explode(',', $clubName['short_name']));
        $searchKey = array_merge($fullName, $shortName);
        $searchKey = array_filter($searchKey, fn($value) => !is_null($value) && $value !== '');
        if (empty($searchKey)) {
            return [];
        }
        
        // get list post by searchKey
        $postList = $this->postRepo->getPostBySearchKey($searchKey);
        return $postList;
    }

    public function getPostByFootballer($footballerId) {
        // get footballer name to create searchKey
        $footballerName = (array) $this->footballerRepo->getFootballerName($footballerId);
        if (empty($footballerName)) {
            return [];
        }

        // create searchKey
        $fullName = array_map('trim', explode(',', $footballerName['full_name']));
        $shortName = array_map('trim', explode(',', $footballerName['short_name']));
        $searchKey = array_merge($fullName, $shortName);
        $searchKey = array_filter($searchKey, fn($value) => !is_null($value) && $value !== '');
        if (empty($searchKey)) {
            return [];
        }

        // get list post by searchKey
        $postList = $this->postRepo->getPostBySearchKey($searchKey);
        return $postList;
    }
}