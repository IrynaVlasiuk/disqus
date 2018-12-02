<?php

class PasswordHandler
{
    private static function getHashPassword($email)
    {
        return Repository::query("SELECT password FROM users WHERE email = '$email' LIMIT 1");
    }

    /**
     * Check if user exists in database
     *
     * @param $email
     * @param $password
     * @return array|mixed|null|string
     */
    public static function getUser($email, $password)
    {
        if(empty(self::getHashPassword($email)[0]["password"])) {
            return null;
        } elseif(password_verify($password, self::getHashPassword($email)[0]["password"])) {
                return Repository::query("SELECT * FROM users WHERE email = '$email' LIMIT 1");
        } else {
            return null;
        }

    }

    /**
     * Set hash password
     *
     * @param $password
     * @return bool|string
     */
    public static function setHashPassword($password)
    {
       return password_hash(trim($password), PASSWORD_BCRYPT);
    }
}