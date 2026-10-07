<?php

    class Connection
    {
        private static $pdo = null;

        public static function getConexao(){

            if (self::$pdo === null) {
                self::$pdo = new PDO("mysql:host=localhost;dbname=biblioteca_php;charset=utf8", "root", "");
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }

            return self::$pdo;
        }
    }

?>