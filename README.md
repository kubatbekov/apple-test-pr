<h1 align="center">Тестовое задание</h1>

<h3 align="center">Установка проекта</h5>

1. Клонировать репозиторий

```sh
$ https://github.com/kubatbekov/apple-test-pr.git
```
2. Перейти в папку проекта

```sh
$ cd apple-test-pr.git
```
3. Установить зависимости через composer

```sh
$ composer install
```
4. Настроить подключение к БД MYSQl в файле common/config/main-local.php 

```sh
<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=mysql;dbname=yii2',
            'username' => 'root',
            'password' => '123',
            'charset' => 'utf8',
        ],
    ],
];
```
5. Применить миграцию:

```sh
$ yii migrate
```

<h3>Как посмотреть тестовое задание?</h3>
Перейти по адресу: <b>http://localhost/backend/web/index.php</b>
<p>Тестовый юзер:</p>

```sh
 Login: admin
 Password: admin123
```
