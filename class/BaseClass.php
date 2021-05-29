<?php


class BaseClass {
    function __construct(){

    }
    public static function sterilize($userString) {
        $filterRegex = preg_match("[^a-zA-Z0-9 -]", $userString);
        if (null !==$filterRegex && is_str($filterRegex) ) {
            return $filterRegex;
        }
        else {
            echo "<div style='width:400px; height:400px; background:red; color:white;font-size:24px; position:fixed; top:100px; left:100px; border:2px solid #333;'>
                    There was a problem, <<span>BaseClass->sterilize(userString:$userString) function has been invoked and has errored on trying to filter the passed string.</span>
                    </div>";
        }
    }
}