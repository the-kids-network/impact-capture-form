<?php

namespace Laravel\Spark\Configuration;

trait ManagesAppOptions
{
    /**
     * Where to redirect users after authentication.
     *
     * @var string
     */
    public static $afterLoginRedirectTo = '/home';

    /**
     * Minimum length a user given password can be.
     *
     * @var string
     */
    public static $minimumPasswordLength = 6;

    /**
     * Where to redirect users after authentication.
     *
     * @return string
     */
    public static function afterLoginRedirect()
    {
        return value(static::$afterLoginRedirectTo);
    }

    /**
     * Set the path to redirect to after authentication.
     *
     * @return void
     */
    public static function afterLoginRedirectTo($path)
    {
        static::$afterLoginRedirectTo = $path;
    }

    /**
     * Get or set the minimum length a user given password can be.
     *
     * @param  string|null  $length
     * @return string
     */
    public static function minimumPasswordLength($length = null)
    {
        if (is_null($length)) {
            return static::$minimumPasswordLength;
        } else {
            static::$minimumPasswordLength = $length;

            return new static;
        }
    }
}
