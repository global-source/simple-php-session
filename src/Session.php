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
     * To init PHP session's time to live.
     *
     * @param int $time duration to expiry session.
     */
    public static function initExpiry($time = 600)
    {
        // Convert with integer.
        $time = intval($time);
        // If not a valid data, then use default value.
        if (!$time) $time = 600;
        // Update custom PHP INI param.
        ini_set('session.gc-maxlifetime', $time);
    }

    /**
     * To init PHP session.
     *
     * @param array $args for init php session
     * ex. $args = [
     *          'cookie_lifetime' => 43200, // 12 hours
     *          'read_and_close'  => true
     *             ]
     * @return bool|string session id.
     */
    public static function init($args = [])
    {
        // Check session id is init or not.
        if (!session_id()) {
            // If session is not initiated, then init.
            session_start([$args]);
        }

        return session_id();
    }

    /**
     * Adding new item or replace item to the session;
     *
     * @param $key string, key of the session.
     * @param $value string|array, value of the session item.
     * @param $withExpiry bool, To enable auto expiry or not.
     * @return bool true|false
     */
    public static function set($key, $value, $withExpiry = false)
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
        // set auto expiry time to item.
        if (true === $withExpiry) self::setTimeToExpiry($key);
        // Status.
        return true;
    }

    /**
     * To set item with remove auto expiry.
     *
     * @param $key string, key of the session.
     * @param int|bool $time time to live in session.
     * @return bool
     */
    public static function setTimeToExpiry($key, $time = false)
    {
        // Formatting the time with integer.    
        $time = intval($time);
        // Sanitize check.
        if (false === $key || false === $time || is_null($key)) return false;
        $ttlItems = self::get('__ttlItems', []);
        // Current time.
        $value = time();
        // Update to the session.
        $ttlItems[$key] = $value;
        // Default index to maintain the created time.
        self::set('__ttlItems', $ttlItems);
    }

    /**
     * To set item with remove auto expiry.
     *
     * @param int|bool $time time to live in session.
     * @return bool
     */
    public static function setDefaultTimeToExpiry($time = false)
    {
        // Formatting the time with integer.
        $time = intval($time);
        // Sanitize check.
        if (false === $time) return false;
        // Default session expiry index.
        $key = 'session_expiry_duration';
        // Set value to expiry.
        $value = $time;
        // Set time expiry to session.
        self::set($key, $value);
    }

    /**
     * To check and remove item, if time expired.
     */
    public static function checkExpiry()
    {
        // Check the time expiry index is set or not.
        if (false !== ($exp_time = self::get('session_expiry_duration', false))) {
            // Get ttl items from session.
            $items = self::get('__ttlItems', false);
            // Sanity check.
            if (false !== $items) {
                // Looping the items to get update the session.
                foreach ($items as $index => $item_time) {
                    // If time is expired, then it will remove automatically.
                    if ((time() - $item_time) > $exp_time) {
                        // trigger remove index.
                        self::remove($index);
                    }
                }
            }
        }
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
    protected static function sanityFilter(&$value, $type = 'string')
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
