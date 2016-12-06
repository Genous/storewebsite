#!/usr/local/bin/php

<?php
        session_start();

        if(!isset($_SESSION["user_name"]) || !isset($_SESSION["user_psw"]))
        {
                header('Location: login.php') ;
                exit();
        }
?>


