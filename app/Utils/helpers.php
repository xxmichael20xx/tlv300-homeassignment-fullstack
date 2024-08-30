<?php

use Illuminate\Support\Facades\Log;

if (! function_exists('debug_log')) {
    /**
     * Add a new log entry for debugging.
     *
     * @param string $title
     * @param mixed $data
     * @return void
     */
    function debug_log(string $title, mixed $data): void
    {
        Log::info($title);
        Log::info(json_encode($data));
    }
}
