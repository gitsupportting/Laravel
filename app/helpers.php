<?php

if (!function_exists('time_schedule')) {
    function time_schedule() {
        $hours = [];
        foreach (range(1, 23) as $hour) {
            $hours[] = "$hour:00";
        }
        $hours[] = '00:00';
        return $hours;
    }
}
