<?php

namespace Gateway;

require_once ('config/config_db.php');

use DB_config;
use PDO;

class User
{
    /**
     * @var PDO
     */
    public static $instance;

    /**
     * Реализация singleton
     * @return PDO
     */
    public static function getInstance(): PDO
    {
        if (is_null(self::$instance)) {
            $connectData = DB_config::getConnectData();
            self::$instance = new PDO($connectData['dns'], $connectData['user'], $connectData['password']);
        }
        return self::$instance;
    }

    /**
     * Возвращает список пользователей старше заданного возраста.
     * @param int $ageFrom
     * @return array
     */
    public static function getUsers(int $ageFrom, $limit=10): array
    {
        $stmt = self::getInstance()->prepare("SELECT id, name, lastName, from_, age, settings FROM Users WHERE age > {$ageFrom} LIMIT {$limit}");
        $stmt->execute();
        return self::getUserAssoc($stmt);
    }

    /**
     * Возвращает список пользователей старше заданного возраста.
     * @return array
     */
    public static function getUsersAll($limit=10): array
    {
        $stmt = self::getInstance()->query("SELECT id, name, lastName, from_, age, settings FROM Users LIMIT " . $limit);
        return self::getUserAssoc($stmt);
    }

    /**
     * Возвращает пользователя по имени.
     * @param string $name
     * @return array
     */
    public static function user(string $name): array
    {
        $stmt = self::getInstance()->query("SELECT id, name, lastName, from_, age, settings FROM Users WHERE name = '{$name}'");
        return self::getUserAssoc($stmt);
    }

    /**
     * Возвращает массив из строки, полученной от БД
     * @param $stmt
     * @return array
     */
    private static function getUserAssoc($stmt)
    {
        $users = [];
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $settings = json_decode($row['settings']);
            $users[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'lastName' => $row['lastName'],
                'from' => $row['from_'],
                'age' => $row['age'],
                'key' => $settings['key'],
            ];
        }

        return $users;
    }


    /**
     * Добавляет пользователя в базу данных.
     * @param string $name
     * @param string $lastName
     * @param int $age
     * @return int
     */
    public static function add(string $name, string $lastName, int $age): int
    {
        $sth = self::getInstance()->prepare("INSERT INTO users(name, lastName, age) VALUES(:name, :lastName, :age)");
        $sth->execute(['name' => $name, 'age'=>$age, 'lastName'=>$lastName]);
        return self::getInstance()->lastInsertId();
    }
}

