    <?php 
            require 'assets/classes.php';
            session_start();
            if(!isset($_SESSION['user']))header("location: hello/");
        
        
            echo '
                 <!DOCTYPE html>
                    <html>
                        <head>
                                <link rel="stylesheet" href="main.css">
                                <meta charset="utf-8">
                                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <title>Silvaro | Home</title>
                                <link rel="icon" href="assets/img/icn_logo.png">
            
                                 <!--Navigation Bar-->
                                 <div id="nav">
                                    <a href=""><img src="assets/img/icn_logo.png" style="width: 50px; height: 95%; margin: 0 15px;"></a>
                                    <input type="text" style="width:20%; position: relative; left:5px; bottom:15px;">
                                        <div id="navbuttons">
                                                <button><a href='.$_SESSION["user"]->get_id().'><img src="'.$_SESSION["user"]->get_id()."/".$_SESSION["user"]->get_profile_pic().'"></a></button>
                                                <button><img src="assets/img/icn_msg.png"></button>
                                                <button><img src="assets/img/icn_notification.png"></button>
                                                <button><a href="settings/settings.php"><img src="assets/img/icn_settings.png"></a></button>
                                                <button><a href="assets/operation/logout.php"><img src="assets/img/icn_logout.png"></a></button>
                                            </div> 
                                        </div>
                                <div style="height:45px; background-color: white;"></div>
                            </head>
                        <body>'
?>








    </body>  
</html>
            