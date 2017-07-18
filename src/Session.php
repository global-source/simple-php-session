/*!
 * Simple Php Session
 * Simple Instance for access PHP's Session.
 *
 * Author : Shankar Thiyagaraajan
 * Email  : shankarthiyagaraajan@gmail.com
 * Github : https://github.com/shankarThiyagaraajan
 *
 * Source
 * https://github.com/global-source/simple-php-session
 *
 * Site
 * https://global-source.github.io/simple-php-session/
 *
 * Copyright 2017
 *
 * Released under the MIT license
 * https://github.com/global-source/simple-php-session/blob/master/LICENSE
 *
 */


<?php

/**
 * For complete session management class.
 */
class Session
{
    
    /**
     * To init PHP session.
     *
     * @param array $args for init php session
     * @return bool|string session id.
     */
    public static function init($args = [])
    {
        // Check session id is inititated or not.
        if (!session_id()) {
            // If session is not initiated, then init.
            session_start($args);
        }
        return session_id();
    }
    
    /**
     * Adding new item or replace item to the session;
     *
     * @param $key string, key of the session.
     * @param $value string|array, value of the session item.
     * @return bool true|false
     */
    public static function set($key, $value)
    {
        // Sanity check.
        if (false === $key || is_null($key)) return false;
        if (false === $value || is_null($value)) return false;
        // make it as string value.
        $key = strval($key);
        // Sanity check.
        if (!$key) return false;
        // Sanitizing the string.
        self::sanityFilter($key);
        self::sanityFilter($value);

        // Store the value to the session.
        $_SESSION[$key] = $value;
        return true;
    }

    /**
     * To get the value from the session.
     *
     * @param $key string, key of the session.
     * @param bool $default , if session item is not available, then it takes this value.
     * @return string value from session or default value.
     */
    public static function get($key, $default = false)
    {
        // Sanity check.
        if (false === $key || is_null($key)) return $default;
        // String conversion.
        $key = strval($key);
        // If not valid, then return the default.
        if (!$key) return $default;
        // Filter the string.
        self::sanityFilter($key);
        // Set to session.
        $response = $_SESSION[$key];
        // Checks with the data and return default if not exist.
        if (!$response || is_null($response) || false === $response) $response = $default;
        // Response from session.
        return $response;
    }

    /**
     * To remove item from session.
     *
     * @param $key string, key of the session.
     * @return bool true|false
     */
    public static function remove($key)
    {
        // Sanity check
        if (false === $key || is_null($key)) return false;
        // String conversion.
        $key = strval($key);
        // Sanity check.
        if (!$key) return false;
        // Filter the string "$key"
        self::sanityFilter($key);
        // Check whether the item is exist or not.
        if (isset($_SESSION[$key])) {
            // Remove item from session.
            unset($_SESSION[$key]);
            // Remove successfully.
            return true;
        }
        // Failed on remove.
        return false;
    }

    /**
     * To filter the content with it's specific type.
     *
     * @param $value string, content to filter.
     * @param string $type , filtered content.
     */
    public static function sanityFilter(&$value, $type = 'string')
    {
        switch ($type) {
            case 'string':
                $value = filter_var($value, FILTER_SANITIZE_STRING);
                break;
            case 'email':
                $value = filter_var($value, FILTER_SANITIZE_EMAIL);
                break;
            default:
                $value = filter_var($value, FILTER_SANITIZE_STRING);
                break;
        }
    }

}
