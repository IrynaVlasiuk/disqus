<?php

class LoginController extends Controller
{
    private static $user;

    /**
     * Login user
     *
     * @param $userData
     */
    public static function loginUser($userData)
    {
        $email = trim($userData["email"]);
        $password = trim($userData["password"]);
        self::$user = PasswordHandler::getUser($email ,$password);
        self::validate($email ,$password);

        if(self::$formErrors == null){
            $_SESSION['logged-in'] = true;
            $_SESSION['user-name'] = self::$user[0]['name'];
            $_SESSION['user_id'] = (int)self::$user[0]['id'];
            header('Location: index');
        } else {
            print_r(json_encode(self::getErrors()));
        }
    }


    private static function validate($email ,$password)
    {
        if(empty($email) || empty($password)) {
            array_push(self::$formErrors, 'All fields are required');
        } else{
            if(empty(self::$user)){
                array_push(self::$formErrors, 'Please enter valid email or password');
            } else {
                self::$formErrors = null;
            }
        }
        return self::$formErrors;
    }

}