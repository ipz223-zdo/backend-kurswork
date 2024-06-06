<?php

namespace models;

use core\Core;
use core\Model;

/**
 * @property int id ID
 * @property string login Логін
 * @property string password Пароль
 * @property string $firstName Ім'я
 * @property string $lastName Прізвище
 */
class Users extends Model
{
    public static string $tableName = "users";
    public static function FindByLoginAndPassword($login, $password)
    {
        $rows = self::findByCondition(['login' => $login, 'password' => $password]);
        if (!empty($rows))
            return $rows[0];
        else
            return null;
    }
    public static function FindByLogin($login)
    {
        $rows = self::findByCondition(['login' => $login]);
        if (!empty($rows))
            return $rows[0];
        else
            return null;
    }
    public static function IsUserLogged(): bool
    {
        return !empty(Core::get()->session->get('user'));
    }
    public static function LoginUser($user): void
    {
        Core::get()->session->set('user', $user);
    }
    public static function LogoutUser(): void
    {
        Core::get()->session->remove('user');
    }
    public static function RegisterUser($login, $password, $firstname, $lastname): void
    {
        $user = new Users();
        $user->login = $login;
        $user->password = $password;
        $user->firstName = $firstname;
        $user->lastName = $lastname;
        $user->save();
    }
}
