<?php

/*
 *      Тестовая страница для проверки работоспособности классов User
 */

require_once ('2.php');
require_once ('1.php');

use Gateway as GW;

//  Действие 1. Создание временной таблицы Users
echo "Тест 1. Создание временной таблицы.<br>";
$pdo = GW\User::getInstance();
$pdo->exec("CREATE TEMPORARY TABLE Users (id INT AUTO_INCREMENT PRIMARY KEY, name TEXT NOT NULL, lastName TEXT, from_ TEXT, age INT, settings TEXT)");
echo "Выполнено.<br><br>";


// Действие 2. Добавление пользователя
echo "Тест 2. Добавление пользователя.<br>";
$user = new \Manager\User();
$ids = $user->addUsers([['name'=>'Митрофан', 'lastName'=>'Варенков', 'age'=>19]]);
echo "Добавлено: " . count($ids) . ' пользователей<br>';
$users = $user->getUsersAll();
echoAllUsers($users);
echo "Выполнено.<br><br>";

// Действие 3. Добавим пять пользователей
echo "Тест 3. Добавление пяти пользователей.<br>";
$u4 = [
    ['name'=>'Махмуд', 'lastName'=>'Шварценкопф', 'age'=>69],
    ['name'=>'Антонина', 'lastName'=>'Иальская', 'age'=>55],
    ['name'=>'Ваган', 'lastName'=>'Мояслеёцкин', 'age'=>100],
    ['name'=>'Братислав', 'lastName'=>'Хвружный', 'age'=>27],
    ['name'=>'Ваган', 'lastName'=>'Устыпаев', 'age'=>40],
];
$ids = $user->addUsers($u4);
echo "Добавлено: " . count($ids) . ' пользователей<br>';
$users = $user->getUsersAll();
echoAllUsers($users);
echo "Выполнено.<br><br>";

// Действие 4. Вывести пользователей старше 40 лет
echo "Тест 4. Вывести пользователей старше 40.<br>";
$users = $user->getUsers(40);
echoAllUsers($users);
echo "Выполнено.<br><br>";

// Действие 5. Вывести пользователей по имени
echo "Тест 5. Вывести пользователей с именами [{$_GET['names']}].<br>";
echo "
<br><a href='test.php?names=Братислав, Антонина'>Вывести пользователей с именами Братислав и Антонина</a><br>
<a href='test.php?names=Махмуд, Митрофан, Ваган'>Вывести пользователей с именами Махмуд, Митрофан и Ваган</a><br><br>
";
$users = $user->getByNames($_GET['names']);
echoAllUsers($users);
echo "Выполнено.<br><br>";



// функция для вывода на экран содержимого запроса из таблицы
function echoAllUsers($users)
{
    echo "Найдено пользователей: " .count($users) . "<br>";
    foreach($users as $u) echo "{$u['id']}, {$u['name']}, {$u['lastName']}, {$u['age']}<br>";
}