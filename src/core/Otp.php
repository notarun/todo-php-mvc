<?php

namespace Core;

class Otp
{
    /**
     * Returns a unique verification code generated from
     * email, APP_KEY and unixtime
     *
     * @param string $email
     * @param string $time
     * @return string
     */
    public static function generate(string $email, string $time = null): string
    {
        $time = $time ?? time();

        return md5(APP_KEY . $email . $time) . $time;
    }

    /**
     * Verify if given verification code is correct or not.
     *
     * @param string $email
     * @param string $code
     * @return boolean
     */
    public static function verify(string $email, string $code): bool
    {
        // unix time when code was generated
        $generatedAt = substr($code, -10);

        if (self::hasExpired($code, $generatedAt)) {
            return false;
        }

        return self::generate($email, $generatedAt) === $code;
    }

    /**
     * Checks if verification code has expired or not.
     *
     * @param string $code
     * @param string $generatedAt
     * @return boolean
     */
    private function hasExpired(string $code, string $generatedAt): bool
    {
        // time difference in minutes
        $timeDiff = (time() - $generatedAt) / 60;

        return ($timeDiff > EMAIL_EXPIRY_TIME) ? true : false;
    }
}
