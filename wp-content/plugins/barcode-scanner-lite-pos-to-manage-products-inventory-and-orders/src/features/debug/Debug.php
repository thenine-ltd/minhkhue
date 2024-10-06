<?php

namespace UkrSolution\BarcodeScanner\features\Debug;

use UkrSolution\BarcodeScanner\features\settings\Settings;

class Debug
{
    public static $status = null;

    private static $startTime = null;
    private static $startMemory = null;
    private static $lastPointTime = null;
    private static $sumLustPointTime = null;
    private static $points = array();

    public static function init()
    {
        try {
            $settings = new Settings();
            $field = $settings->getSettings("debugInfo");
            $value = $field === null ? $settings->getField("general", "debugInfo", "") : $field->value;

            self::$status = $value === "on";

            if (self::$status) {
                self::$startTime = microtime(true);
                self::$startMemory = memory_get_peak_usage(true);
                self::$lastPointTime = 0;
                self::$sumLustPointTime = 0;
                self::ADDPoint('Start Benchmark');
            }
        } catch (\Throwable $th) {
        }
    }

    public static function addPoint($name = "")
    {
        self::$sumLustPointTime += self::$lastPointTime;

        self::$lastPointTime = microtime(true) - self::$startTime - self::$sumLustPointTime;

        self::$points[] = array(
            'name' => $name,
            'totalTime' => microtime(true) - self::$startTime,
            'pointTime' => self::$lastPointTime,
            'pointMemory' => ((memory_get_peak_usage(true) - self::$startMemory) / 1024 / 1024)
        );
    }

    public static function getResult()
    {
        self::addPoint('Stop Benchmark');

        $result = array("points" => array(), "total" => array());

        if (self::$status) {
            $prevMem = 0;
            $usedTime = 0;

            foreach (self::$points as $key => $value) {
                $result["points"][] = array(
                    "." => $value['name'],
                    "execution total time" => sprintf('%.3F sec', $value['totalTime']),
                    "execution time" => sprintf('%.3F sec', $value['pointTime']),
                    "memory used" => sprintf('%.3FM', ($value['pointMemory'] - $prevMem)),
                    "memory total used" => sprintf('%.3FM', $value['pointMemory']),
                );

                $prevMem += $value['pointMemory'];
                $usedTime += $value['pointTime'];
            }

            $result["total"] = array(
                "memory used" => $prevMem . 'M / ' . ini_get('memory_limit'),
                "execution time used" => number_format($usedTime, 3, ".", "") . 'sec / ' . ini_get('max_execution_time') . 'sec'
            );
        }

        return $result;
    }
}
