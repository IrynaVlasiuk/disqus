<?php

class CommentController extends Controller
{
    /**
     * Adds new comment
     *
     * @param $data
     * @return false|string
     */
    public static function addComment($data)
    {
        $message = trim($data["message"]);
        $date = date("Y-m-d");
        $user_id  = $data["id"];
        $parent_id = 0;

        if(isset($data['parent_id'])){
            $parent_id = $data['parent_id'];
        }
        self::validation($data);
        $response = new Response();

        if(empty(self::getErrors())){
            self::query("INSERT INTO comments (description, date, user_id, parent_id) VALUES ('$message', '$date', '$user_id','$parent_id')");
            $response->status = 'OK';
        } else {
            $response->status = 'ERROR';
            $response->errors = self::getErrors();
        }
        return print_r(json_encode($response));
    }

    /**
     * Edit comment
     *
     * @param $data
     * @return mixed
     */
    public static function editComment($data)
    {
        $id = $data['comment_id'];
        $message = $data['message'];
        $response = new Response();
        if(empty($message)) {
            $response->status = 'ERROR';
            array_push(self::$formErrors, 'Please write something');
        } else{
            self::query("UPDATE comments SET description = '$message' WHERE id = '$id'");
            $response->status = 'OK';
            $response->data = self::getEditedComment($id);
        }
        $response->errors = self::getErrors();
        return print_r(json_encode($response));
    }

    /**
     * Get all comments
     *
     * @return array|mixed|string
     */
    public static function getAllComments()
    {
        return print_r(json_encode(self::query("SELECT c.id, c.description, c.date, c.user_id, COUNT(rating.id) AS ratingCount FROM comments AS c LEFT JOIN rating ON c.id = rating.comment_id WHERE c.parent_id = 0 GROUP BY c.id")));
    }

    /**
     * Get edited comment
     *
     * @param $id
     * @return array|mixed|string
     */
    public static function getEditedComment($id)
    {
        return self::query("SELECT * FROM comments WHERE id='$id'");
    }

    /**
     * Get replies to current comment
     *
     * @param $parent_id
     * @return mixed
     */
    public static function getReplies($parent_id)
    {
        return print_r(json_encode(self::query("SELECT c.id, c.description, c.date, c.user_id, c.parent_id, COUNT(rating.id) AS ratingCount FROM comments AS c LEFT JOIN rating ON c.id = rating.comment_id WHERE c.parent_id = '$parent_id' GROUP BY c.id")));
    }

    /**
     * Remove comment
     *
     * @param $id
     */
    public static function deleteComment($id)
    {
        self::query("DELETE FROM comments WHERE id = '$id'");
    }

    private static function validation($data)
    {
        if(empty($data['message'])) {
            array_push(self::$formErrors, 'Please write something');
        } else {
            self::$formErrors = array();
        }
        return self::$formErrors;
    }
}