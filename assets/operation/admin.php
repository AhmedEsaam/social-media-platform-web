<?php
            require '../classes.php';
            session_start();

            if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET['op']=="fetch") {
                $_SESSION['admin']->fetch_statistics();
                die();
            }
            else if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET['op']=="fetch2") {
                admin::get_trends($_GET['time']);
                die();
            }
            else if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET['op']=="fetch3") {
                admin::get_top_pages(0);
                die();
            }
            else if ($_SERVER["REQUEST_METHOD"] == "GET" && $_GET['op']=="delete") {
                echo $_SESSION['admin']->ban_unit($_GET['id']);
                die();
            }
?>