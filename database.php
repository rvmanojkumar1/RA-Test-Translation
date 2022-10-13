<?php
    //Test
    $host = 'localhost';
    $db_name = 'beneficiary_db';
    $username = 'postgres';
    $password = 'pass@123';
    $port = '5432';
    $conn;

    //UAT
    // $host = '10.11.13.132';
    // $db_name = 'beneficiary_db';
    // $username = 'mguatadmin';
    // $password = 'bVqTHv9FEXUxhR3k';
    // $port = '5432';
    // $conn;

    //Prod
    // $host = '3.7.81.85';
    // $db_name = 'beneficiary_db';
    // $username = 'mgprodadmin';
    // $password = 'tmkamhpH945ZWFHx';
    // $port = '5432';
    // $conn;

    try { 
        $conn = new PDO('pgsql:host='.$host.';port='.$port.';dbname='.$db_name.';user='.$username.';password='.$password);
        //$conn = new PDO('pgsql:host=' . $host . ';dbname=' . $db_name, $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) {
        echo 'Connection Error: ' . $e->getMessage();
    }
?>