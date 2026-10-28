<?php

use App\Models\Month;

if (!function_exists('getCurrentMonth')) {
    function getCurrentMonth(): ?Month
    {
        return Month::where('is_current', true)->first();
    }
}
