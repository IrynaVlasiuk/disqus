<?php
require_once 'Controller.php';

class RegistrationController extends Controller
{
    /**
     * Register new user
     *
     * @param $userData
     * @return mixed
     */
    public static function registerUser($userData)
    {
        $username = trim($userData["name"]);
        $email = trim($userData["email"]);
        $password =  trim($userData["password"]);
        $confirmPassword =  trim($userData["confirm-password"]);
        $hashPassword = PasswordHandler::setHashPassword($password);
        self::validate($username, $email, $password, $confirmPassword);
        if(self::getErrors() == null){
           self::query("INSERT INTO users (name, email, password) VALUES ('$username', '$email', '$hashPassword')");
           $_SESSION['user_id'] = (int)self::getAuthUser($email)[0]["id"];
           $_SESSION['user-name'] = self::getAuthUser($email)[0]["name"];
           $_SESSION['logged-in'] = true;
           header('Location:index');
       } else {
           return print_r(json_encode(self::getErrors()));
       }
    }

    private static function validate($username, $email, $password, $confirmPassword)
    {
        if(empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
            array_push(self::$formErrors, 'All fields are required');
        } else{
            if(self::unique($email) == 0) {
                if(self::checkPassword($password)) {
                    if(self::isEqualPasswords($password, $confirmPassword)){
                        self::$formErrors = null;
                    } else {
                        array_push(self::$formErrors, 'Passwords do not match');
                    }
                } else {
                    array_push(self::$formErrors, 'Minimum length of the password is 6 characters');
                }
            } else {
                array_push(self::$formErrors, 'A user with same email already exist');
            }
        }
    }

    private static function getAuthUser($email)
    {
        return self::query("SELECT id, name FROM users WHERE email = '$email'");
    }

    private static function unique($email)
    {
        return self::query("SELECT COUNT(1) AS res FROM users WHERE EXISTS (SELECT * FROM users WHERE users.email = '$email') LIMIT 1");
    }

    private static function checkPassword($field, $min = 6)
    {
        return (strlen($field) >= $min) ;
    }

    private static function isEqualPasswords($field1, $field2)
    {
        return $field1 === $field2 ;
    }
}