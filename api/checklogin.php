<?php 
        session_start();
        if($_SESSION['isLogin']!=true){
            header('Location: http://localhost/arcsfrontend/login.php');
            die();
        }
        
?>