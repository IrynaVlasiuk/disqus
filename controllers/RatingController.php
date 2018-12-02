<?php

class RatingController extends Controller
{
    /**
     * Add rating to comment
     *
     * @param $data
     * @return mixed
     */
   public static function addRating($data)
   {
       $comment_id = $data['comment_id'];
       $user_id = $data['user_id'];
       $count = self::getUserRate($comment_id, $user_id);
       $response = new Response();

       if($count == 0) {
           if(self::ifUserOwnersComment($comment_id, $user_id) == 1){
               array_push(self::$formErrors, 'You can`t rate own comment');
               $response->status = 'ERROR';
           } else{
               self::query("INSERT INTO rating (comment_id, user_id ) VALUES ('$comment_id', '$user_id') ");
               $response->status = 'OK';
           }
       } elseif($count >= 1){
           array_push(self::$formErrors, 'You can not rate one comment more than once');
           $response->status = 'ERROR';
      } else{
          array_push(self::$formErrors, 'There are some problems during saving into database');
      }

       $response->errors = self::getErrors();
       $response->data = self::getCurrentRating($comment_id);
       return print_r(json_encode($response));
   }

    private static function getUserRate($comment_id, $user_id)
    {
        $count = self::query("SELECT COUNT(1) AS res FROM rating WHERE comment_id = '$comment_id' AND user_id = '$user_id' LIMIT 1" );
        return (int)$count;
    }

    private static function ifUserOwnersComment($comment_id,$user_id)
    {
        $res = self::query("SELECT COUNT(1) AS res FROM comments WHERE id = '$comment_id' AND user_id = '$user_id' LIMIT 1");
        return (int)$res;
    }

    private static function getCurrentRating($comment_id)
    {
        return self::query("SELECT COUNT(1) AS res FROM rating WHERE comment_id = '$comment_id'");
    }
}