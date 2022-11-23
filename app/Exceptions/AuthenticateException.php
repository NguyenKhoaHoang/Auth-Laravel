<?php

namespace App\Exceptions;

use App\Exceptions\BaseException;

class AuthenticateException extends BaseException
{
    public static function invalidCredentials()
    {
        return self::code('errors.login_info');
    }

    public static function lockedAccount()
    {
        return self::code('errors.locked_account');
    }

    public static function tooManyLoginAttempt()
    {
        return self::code('errors.too_many_login');
    }

    public static function updatePasswordFail()
    {
        return self::code('errors.invalid_password');
    }

    public static function invalidIp()
    {
        return self::code('errors.invalid_ip');
    }

    public static function invalidAuthen()
    {
        return self::code('errors.user_not_login');
    }

    public static function invalidStatus()
    {
        return self::code('errors.invalid_status');
    }
}
