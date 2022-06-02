<?php

namespace Manager;
require_once ('2.php');

use Gateway as GW;

class User
{
    const limit = 10;

    /**
     * Возвращает пользователей старше заданного возраста.
     * @param int $ageFrom
     * @return array
     */
    function getUsers(int $ageFrom): array
    {
        $ageFrom = (int)trim($ageFrom);

        return GW\User::getUsers($ageFrom);
    }

    /**
     * Возвращает всех пользователей из таблицы
     * @return array
     */
    function getUsersAll() : array
    {
        return GW\User::getUsersAll(self::limit);
    }

    /**
     * Возвращает пользователей по списку имен.
     * param string $names
     * @return array
     */
    public static function getByNames($names): array
    {
        $users = [];
        $names_array = preg_split('/[,;.]/', $names);
        if(isset($names_array) && count($names_array) > 0)
        foreach ($names_array as $name)
        {
            $um = GW\User::user(trim($name));
            foreach($um as $u) array_push($users, $u);
        }
        return $users;
    }

    /**
     * Добавляет пользователей в базу данных.
     * @param array $users
     * @return array
     */
    public function addUsers($users): array
    {
        $ids = [];
        GW\User::getInstance()->beginTransaction();
        try {
            foreach ($users as $user) {
                    GW\User::add($user['name'], $user['lastName'], $user['age']);
                    $ids[] = GW\User::getInstance()->lastInsertId();
                }
            GW\User::getInstance()->commit();
            } catch (\Exception $e) {
                GW\User::getInstance()->rollBack();
                echo "Ошибка добавления пользователей";
            }
        return $ids;
    }
}
