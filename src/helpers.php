<?php

declare(strict_types=1);

if (! function_exists('')) {

}

if (! function_exists('exec_time')) {
    /**
     * Measure the execution time of a script or a specific code block.
     * Gist: https://gist.github.com/alex-kassel/7cfd2708ad1908d3e172807582c3196e
     *
     * This function calculates the elapsed time in seconds between the current moment and a starting time.
     * It is useful for performance profiling and benchmarking code execution.
     *
     * @param float $start_time_float (Optional) The start time as a floating-point number obtained using microtime().
     *                                If not provided, the start time defaults to $_SERVER['REQUEST_TIME_FLOAT'].
     * @param int $precision (Optional) The number of decimal places in the result. Default is 3.
     *
     * @return float The execution time in seconds, rounded to the specified precision.
     */
    function exec_time(float $start_time_float = 0, int $precision = 3): float {
        // Calculate the execution time by subtracting the start time from the current time
        $elapsed_time = microtime(true) - ($start_time_float ?: $_SERVER['REQUEST_TIME_FLOAT']);

        // Round the result to the specified precision
        return round($elapsed_time, $precision);
    }
}

if (! function_exists('array_filter_recursive')) {
    /**
     * Recursively filters elements of an array based on a given callback function.
     * Gist: https://gist.github.com/alex-kassel/089ebb111fa0ad3d037e5849fac0afaf
     * Inspired by: https://gist.github.com/benjamw/1690140
     *
     * This function applies the provided callback function to each element in the array, including nested arrays.
     *
     * @param array $array The input array to filter.
     * @param callable|null $callback (Optional) The callback function to apply. If null, it removes empty values.
     * @param bool $remove_empty_arrays (Optional) Whether to remove empty arrays from the result. Default is false.
     *
     * @return array The filtered array.
     */
    function array_filter_recursive(array $array, callable $callback = null, bool $remove_empty_arrays = false): array {
        foreach ($array as $key => & $value) {
            if (is_array($value)) {
                $value = call_user_func_array(__FUNCTION__, array($value, $callback, $remove_empty_arrays));
                if ($remove_empty_arrays && ! $value) {
                    unset($array[$key]);
                }
            } elseif (is_null($callback)) {
                if (in_array($value, ['', null], true)) {
                    unset($array[$key]);
                }
            } elseif (! $callback($value, $key)) {
                unset($array[$key]);
            }
        }
        unset($value);

        return $array;
    }
}


if (! function_exists('str_squish')) {
    /**
     * Squish a string by replacing multiple spaces with a single replacement and trim it.
     * Gist: https://gist.github.com/alex-kassel/f6d9d68e7102a7b7305cf39d706ea3a4
     *
     * @param string $string The input string to squish.
     * @param bool $replaceNonBreakingSpaces (Optional) Whether to replace non-breaking spaces with spaces. Default is true.
     * @param string $characters (Optional) A list of additional characters to trim. Default is null.
     * @param string $replacement (Optional) The character to replace multiple spaces with. Default is a single space " ".
     *
     * @return string The squished string.
     */
    function str_squish(
        string $string,
        bool $replaceNonBreakingSpaces = true,
        string $characters = null,
        string $replacement = ' '
    ): string {
        if ($replaceNonBreakingSpaces) {
            // Replace &nbsp; and its UTF-8 incarnations with spaces if they exist
            $string = preg_replace('/(&nbsp;|\xC2\xA0|\xE1\x9A\x80|\xE2\x80\xAF|\xE3\x80\x80)/u', ' ', $string);
        }

        // Trim the string, optionally using the provided character list
        $string = trim($string, strval($characters) . " \n\r\t\v\x00");

        // Replace multiple spaces with the specified replacement string and return
        return preg_replace('/(?:' . preg_quote($replacement, '/') . '|\s)+/', $replacement, $string);
    }
}

if (! function_exists('str_contains_all')) {
    /**
     * Check if a string contains all parts of a needle with optional placeholders (*).
     * Gist: https://gist.github.com/alex-kassel/e7ca1f0a0b5a8210e17c50191c66db91
     *
     * @param string $haystack The string to search in.
     * @param string $needle The string to search for, with optional placeholders (*).
     *
     * @return bool True if all parts of the needle are found in the haystack; otherwise, false.
     */
    function str_contains_all(string $haystack, string $needle): bool {
        foreach (explode('*', $needle) as $part) {
            if (false === $pos = strpos($haystack, $part)) {
                return false;
            }

            $haystack = substr($haystack, $pos + strlen($part));
        }

        return true;
    }
}

