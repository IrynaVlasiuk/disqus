<?php

class Controller extends Repository
{
    protected $field;
    protected static $formErrors = array();

    /**
     * @param $viewName
     */
    public static function renderView($viewName)
    {
        require_once 'views/'.$viewName.'.php';
    }

    /**
     * @return array
     */
    public static function getErrors()
    {
        return self::$formErrors;
    }
}