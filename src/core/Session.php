<?php

namespace Core;

/**
 * Wrapper class for $_SESSION super global
 * with chained key support.
 */
class Session
{
    /**
     * Checks whether key exists in $_SESSION or not.
     *
     * @param string $key
     * @return boolean
     */
    public static function has(string $key): bool
    {
        if (!self::isChainedKey($key)) {
            return !empty($_SESSION[$key]);
        }

        $keys = explode('.', $key);
        $session = $_SESSION;

        foreach ($keys as $key) {
            if (!isset($session[$key])) {
                return false;
            }

            $session = $session[$key];
        }

        return true;
    }

    /**
     * Returns the requested value from $_SESSION.
     *
     * @param string $key
     * @return void
     */
    public static function get(string $key)
    {
        if (!self::has($key)) {
            return null;
        }

        if (!self::isChainedKey($key)) {
            return $_SESSION[$key];
        }

        $keys = explode('.', $key);
        $session = $_SESSION;

        foreach ($keys as $key) {
            $session = $session[$key];
        }

        return $session;
    }

    /**
     * Set the given value in $_SESSION.
     *
     * @param string $key
     * @param $value
     * @return void
     */
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Unset the value from $_SESSION
     *
     * @param string $key
     * @return void
     */
    public static function unset(string $key): void
    {
        if (!self::isChainedKey($key)) {
            unset($_SESSION[$key]);
        }

        $keys = explode('.', $key);
        unset($_SESSION[$keys[0]]);
    }

    /**
     * Checks if given key is a chained key or not.
     *
     * @param string $key
     * @return boolean
     */
    private static function isChainedKey(string $key): bool
    {
        return strpos($key, '.') ? true : false;
    }
}