<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DatabaseAcces
 *
 * @author Adrien Huygens
 */
class DatabaseAcces {
    /*     * Paramètrage d'accès à la base de donnée * */

    private static $develop = false;
    private static $bdd = "shop"; //Nom de la base de donnée
    private static $pass = ""; //Mot de passe pour la base de donnée{
    private static $user = ""; //Utilisateur
    private static $serveur = ""; //Serveur hebergeant la base de donnée
    private static $driver = "mysql"; //Driver de connexion
    private static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            try {
                if (DatabaseAcces::$develop) {
                    DatabaseAcces::$pass = "6wf2buzt";
                    DatabaseAcces::$user = "root";
                    DatabaseAcces::$serveur = "localhost";
                } else {
                    DatabaseAcces::$pass = "q6PgmCQ2h1Ar";
                    DatabaseAcces::$user = "adminYLNZmbx";
                    DatabaseAcces::$serveur = "127.4.198.130:3306";
                }

                $strConnection = self::$driver . ':host=' . self::$serveur . ';dbname=' . self::$bdd;
                $arrExtraParam = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
                $pdo = new PDO($strConnection, self::$user, self::$pass, $arrExtraParam);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance = $pdo;
            } catch (PDOException $e) {
                $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
                die($msg);
            }
        }
        return self::$instance;
    }

}
