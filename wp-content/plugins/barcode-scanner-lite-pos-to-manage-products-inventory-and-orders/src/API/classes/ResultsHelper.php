<?php

namespace UkrSolution\BarcodeScanner\API\classes;

use UkrSolution\BarcodeScanner\features\locations\Locations;

class ResultsHelper
{
    private static $locationsList = array();

    public static function getLocationsList()
    {
        if (!self::$locationsList) {
            $Locations = new Locations();
            self::$locationsList = $Locations->get();
        }

        return self::$locationsList;
    }
}
