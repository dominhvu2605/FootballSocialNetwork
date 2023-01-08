<?php

namespace App\Services;

use App\Repositories\CommentRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CommentService {
    /**
     * @var CommentRepository
     */
    protected $commentRepo;

    /**
     * PostService Construct
     */
    public function __construct(CommentRepository $commentRepo)
    {
        $this->commentRepo = $commentRepo;
    }

    public function addComment($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'userId' => 'required|numeric|exists:users,id',
            'postId' => 'required|numeric|exists:posts,id',
            'comment' => 'required'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // add comment
        try {
            $dataInsert = [
                'post_id' => $data['postId'],
                'user_id' => $data['userId'],
                'content' => $data['comment'],
                'created_at' => date('Y/m/d H:i:s')
            ];
            $this->commentRepo->addComment($dataInsert);
            $return['status'] = true;
            $return['message'] = 'Comment the post successfully.';
            return $return;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $return['message'] = $e->getMessage();
            return $return;
        }
    }

    public function getListComment($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'postId' => 'required|numeric|exists:posts,id'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // get list comment by postId
        $listComment = $this->commentRepo->getListComment($data['postId']);
        foreach ($listComment as $key => $comment) {
            if ($comment->modified_at) {
                $listComment[$key]->diffTime = 'Last edited: ' . $this->diffDate($comment->created_at, date('Y/m/d H:i:s'));
                unset($listComment[$key]->created_at);
                unset($listComment[$key]->modified_at);
            } else {
                $listComment[$key]->diffTime = $this->diffDate($comment->created_at, date('Y/m/d H:i:s'));
                unset($listComment[$key]->created_at);
                unset($listComment[$key]->modified_at);
            }
        }
        $return['status'] = true;
        $return['message'] = 'Get list comment successfully.';
        $return['data'] = $listComment;
        return $return;
    }

    public function editComment($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'commentId' => 'required|numeric|exists:posts_comments,id',
            'userId' => 'required|numeric|exists:users,id',
            'postId' => 'required|numeric|exists:posts,id',
            'newComment' => 'required'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // check input is correct or not
        $comment = $this->commentRepo->getCommentById($data['commentId']);
        if ($comment->post_id != $data['postId'] || $comment->user_id != $data['userId']) {
            $return['message'] = 'You cannot edit other people\'s comments.';
            return $return;
        }
        $dataUpdate = [
            'content' => $data['newComment'],
            'modified_at' => date('Y/m/d H:i:s')
        ];
        if ($this->commentRepo->updateComment($data['commentId'], $dataUpdate)) {
            $return['status'] = true;
            $return['message'] = 'Edit commit successefully.';
            return $return;
        }
        $return['message'] = 'Edit commit failed.';
        return $return;
    }

    public function deleteComment($data) {
        $return = [
            'status' => false,
            'message' => ''
        ];

        // validate input
        $validate = Validator::make($data, [
            'commentId' => 'required|numeric|exists:posts_comments,id',
            'userId' => 'required|numeric|exists:users,id',
            'postId' => 'required|numeric|exists:posts,id'
        ]);
        if ($validate->fails()) {
            $return['message'] = $validate->errors()->first();
            return $return;
        }

        // check input is correct or not
        $comment = $this->commentRepo->getCommentById($data['commentId']);
        if ($comment->post_id != $data['postId'] || $comment->user_id != $data['userId']) {
            $return['message'] = 'You cannot delete other people\'s comments.';
            return $return;
        }
        if ($this->commentRepo->deleteComment($data['commentId'])) {
            $return['status'] = true;
            $return['message'] = 'Delete commit successefully.';
            return $return;
        }
        $return['message'] = 'Delete commit failed.';
        return $return;
    }

    public function diffDate($date1, $date2) {
        $fromDate = date_create($date1);
        $toDate = date_create($date2);
        $diff = date_diff($fromDate, $toDate);
        $diffYear = $diff->format('%y');
        $diffMonth = $diff->format('%m');
        $diffDate = $diff->format('%d');
        $diffHour = $diff->format('%h');
        $diffMinute = $diff->format('%i');
        $diffSecond = $diff->format('%s');
        $return = '';
        // year
        $diffYear == 0 ? '' : ($diffYear == 1 ? $return .= '1 year ' : $return .= $diffYear . ' years ');
        if ($return != '') return $return .= 'ago';
        // month
        $diffMonth == 0 ? '' : ($diffMonth == 1 ? $return .= '1 month ' : $return .= $diffMonth . ' months ');
        if ($return != '') return $return .= 'ago';
        // day
        $diffDate == 0 ? '' : ($diffDate == 1 ? $return .= '1 day' : $return .= $diffDate . ' days ');
        if ($return != '') return $return .= 'ago';
        // hour
        $diffHour == 0 ? '' : ($diffHour == 1 ? $return .= '1 hour ' : $return .= $diffHour . ' hours ');
        if ($return != '') return $return .= 'ago';
        // minute
        $diffMinute == 0 ? '' : ($diffMinute == 1 ? $return .= '1 minute ' : $return .= ($diffMinute . ' minutes '));
        if ($return != '') return $return .= 'ago';
        // second
        $diffSecond == 0 ? '' : ($diffSecond == 1 ? $return .= '1 second ' : $return .= ($diffSecond . ' seconds '));
        if ($return != '') return $return .= 'ago';
        return $return;
    }
}