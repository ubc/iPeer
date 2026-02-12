<?php

/**
 * Request-scoped context. Generally PHP runtimes reset state
 * for every request, but to be safe, the `reset()` function
 * should be called early in each request's lifecycle to
 * prevent data leakage across requests.
 */
class RequestContext
{

    static $_correlationId = null;
    static $_userId = null;
    static $_username = null;

    static function reset()
    {
        self::$_correlationId = null;
        self::$_userId = null;
        self::$_username = null;
    }

    static function correlationId()
    {
        if (self::$_correlationId === null) {
            self::$_correlationId = self::_generateUuidV4();
        }
        return self::$_correlationId;
    }

    static function setUserId($userId)
    {
        self::$_userId = $userId;
    }

    static function userId()
    {
        return self::$_userId;
    }

    static function setUsername($username)
    {
        self::$_username = $username;
    }

    static function username()
    {
        return self::$_username;
    }

    private static function _generateUuidV4()
    {
        # Adapted from https://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
        try {
            $bytes = random_bytes(16);
        } catch (\Random\RandomException $e) {
            return null;
        }
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }
}
