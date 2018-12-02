<?php

class Route
{
  public static $validationRoutes = array();

    /**
     * Set route
     *
     * @param $route
     * @param $function
     */
  public static function set($route, $function){
      self::$validationRoutes[] = $route;
      if(strtolower($_GET["url"]) == strtolower($route)) {
          $function->__invoke();
      }
  }
}