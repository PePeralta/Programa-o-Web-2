<?php

session_start();
include 'db.php';

if(!isset($_SESSION['user'])){

    header('Location: login.php');
    exit;
}else{

    $id = $_SESSION['user'];

    $sql = 'DELETE FROM users WHERE id=' . $id . '';
    if(mysqli_query($conexao, $sql)){
        
        session_destroy();
    }
}

?>