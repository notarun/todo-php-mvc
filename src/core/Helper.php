<?php

namespace Core;

/**
 * This class contains helper methods.
 */
class Helper
{
    /**
     * Dump and die.
     *
     * @param $var
     * @return void
     */
    public static function dd($var): void
    {
        echo '<pre>' . var_export($var, true) . '</pre>';
        die();
    }

    /**
     * Echo out HTML and escape.
     *
     * @param string $var
     * @param string $encoding
     * @return void
     */
    public static function e(string $var, string $encoding = 'UTF-8'): void
    {
        echo(htmlentities($var, ENT_QUOTES | ENT_HTML5, $encoding));
    }
}