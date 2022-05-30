<?php

namespace Core;

use Core\Session;

class Redirect
{
    /**
     * Redirect to the specified url with input.
     *
     * @param string $uri
     * @param array $input
     * @return void
     */
    public static function to(string $uri, array $input = [])
    {
        if (!empty($input)) {
            $keys = array_keys($input);

            foreach ($keys as $key) {
                if (!empty($input[$key])) {
                    Session::set($key, $input[$key]);
                }
            }
        }

        $host = $_SERVER['HTTP_ORIGIN'];
        header("Location: ${host}${uri}");
        exit();
    }

    /**
     * Redirect to the last url with $input
     *
     * @param string $uri
     * @param array $input
     * @return void
     */
    public static function back(array $input = [])
    {
        $uri = substr($_SERVER['HTTP_REFERER'], strlen($_SERVER['HTTP_ORIGIN']));
        self::to($uri, $input);
    }
}
