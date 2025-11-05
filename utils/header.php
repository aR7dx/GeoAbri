<?php 

    include_once 'router.php';
    include_once 'pdo_agile.php';
    include_once 'functions.php';
    include_once 'notification.php';

    $db_username = "root";
    $db_password = "";
    $db_host = "localhost";
    $db_name = "geoabri";

    $db = "mysql:host=$db_host;dbname=$db_name;charset=utf8";  

    $conn = OuvrirConnexionPDO($db,$db_username,$db_password);
    
    
    session_start();

?>