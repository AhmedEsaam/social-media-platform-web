<?php 
                require '../assets/classes.php';
                session_start();
                if(!isset($_SESSION['user']))header("location: ../");
                $_SESSION['offset']=0;

                //aquire the usernames
                $user_id = $_SESSION['user']->get_id();
                $target_id = substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],"/",1)+1);
                $target_id = strtolower(substr($target_id,0,strlen($target_id)-1));
                //refetch the data
                $_SESSION['user'] = new user($user_id);
                $_SESSION['target'] = new user($target_id);
                //check if the target is the user
                $isVisitor = TRUE;
                if($user_id == $target_id) $isVisitor = FALSE;


                
             echo '
                    <!DOCTYPE html>
                        <html id="html">
        
                            <head>
                                    <link rel="stylesheet" href="../profile/main.css">
                                    <meta charset="utf-8">
                                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                    <title>Chatverse | '.$_SESSION["target"]->get_name().'</title>
                                    <link rel="icon" href="../assets/img/icn_logo.png">
        
                                    <!--Navigation Bar-->
                                    <div id="nav">
                                       <a href="../"><img src="../assets/img/icn_logo.png" style="width: 30px;  margin: 5px 20px;"></a>
                                       
                                       
                                       <form>
                                       <input type="text" id="searchbar">
                                       </form><button type="submit"><img style="width:12px; padding:0; margin:0;" src="../assets/img/icn_search.png"></button>
                                       <div id="searchbox">
                                       <div class="searchUnit">
                                       <samp>Search Results will be shown here!</samp>
                                       </div>
                                       </div>
                                       
                                       
                                       <div id="navbuttons">
                                                   <button><a href="../'.$_SESSION["user"]->get_id().'"><img src="../'.$_SESSION["user"]->get_id()."/".$_SESSION["user"]->get_profile_pic().'"></a></button>
                                                   <button><img src="../assets/img/icn_msg.png"></button>
                                                   <button id="notiBtn"><img id="noti_img" src="../assets/img/icn_notification'.$_SESSION["user"]->get_noti_statues().'.png"></button>
                                                   <button id="arrow"><img src="../assets/img/icn_settings.png"></button>
                                                   <div id="noti">';
                                                   $noti = new notification($_SESSION['user']->get_id());
                                                   $noti->get_noti(); 
                                                   echo '</div>
                                                   
                                                   <ul id="menu">
                                                           <li><a href="../settings">Settings</li>
                                                           <li><a href="../saved">Saved Posts</li>
                                                           <li><a href="../assets/operation/logout.php">Logout</a></li>
                                                   </ul>
                                               </div> 
                                           </div>
                                   <div style="height:40px; background-color: white;"></div>
   
             
                                   <script>
                                   var arrow = document.getElementById("arrow");
                                   var notiBtn = document.getElementById("notiBtn");
   
                                   var menu = document.getElementById("menu");  
                                   var noti = document.getElementById("noti");
                                   arrow.onclick = function() {
                                       if(menu.style.display == "block")menu.style.display = "none"
                                       else menu.style.display = "block";}
                                   notiBtn.onclick = function() {
                                       if(noti.style.display == "block")noti.style.display = "none"
                                       else {
   
                                           var xhttp = new XMLHttpRequest();
                                           xhttp.onreadystatechange = function() {
                                             if (this.readyState == 4 && this.status == 200) {
                                              document.getElementById("noti_img").src = "../assets/img/icn_notification.png";
                                             }
                                           };
                                           xhttp.open("GET","../assets/operation/db_update.php");
                                           xhttp.send();
                                           noti.style.display = "block";
                                       }
                                   }


                                   /////////

                                   var searchBar = document.getElementById("searchbar");
                                   var searchBox = document.getElementById("searchbox");
                                   searchBar.onfocus= function(){
                                       searchBox.style.display = "block";
                                   }
                                   searchBar.onblur= function(){
                                       myVar = setInterval(function () {
                                           searchBox.style.display = "none";
                                           clearInterval(myVar);
                                       }, 100);
                                   }
   
   
                                   searchBar.oninput=function(){
                                   var xhttp = new XMLHttpRequest();
                                   xhttp.onreadystatechange = function() {
                                     if (this.readyState == 4 && this.status == 200) {
                                       searchBox.innerHTML = this.responseText;
                                     }
                                   };
                                   xhttp.open("GET","../assets/operation/search.php?index=" + searchBar.value);
                                   xhttp.send();
                                   }



                                   </script>
   
   
                               </head>
                           <body id="body">'
   ?>



        <div id="NCP">  
                <div id="cover">
                    <?php if(!$isVisitor) echo '<button id="coverBtn"><img src="../assets/img/icn_upload.png"></button>'?>
                    <img src=<?php echo "../".$target_id."/".$_SESSION['target']->get_cover_pic(); ?>>
                </div>
                <div id="PNB">
                    <div style="display: inline-block; margin: 0 5%;">
                        <img id="pp" src=<?php echo "../".$target_id."/".$_SESSION['target']->get_profile_pic(); ?>>
                        <?php if(!$isVisitor) echo '<button id="ppBtn"><img src="../assets/img/icn_upload.png"></button>' ;?>
                        <p><?php echo $_SESSION['target']->get_name(); ?></p>
                    </div>
                    <form id="buttons" method="GET" action="../assets/operation/friend_button.php">
                        <input  type="hidden" name = "target" value = "<?php echo $target_id ?>">
                        <?php
                        if($isVisitor){
                        if(friendship::isFriend($user_id,$target_id)) echo '<input type="submit" name="op" value="Unfriend">';
                        else if(friendship::isFrRequest($target_id,$user_id)) echo '<input type="submit" name="op" value="Accept"><input type="submit" name="op" value="Refuse">';
                        else if(!(friendship::isFrRequest($user_id,$target_id))) echo '<input type="submit" name="op" value="Add Friend">';
                        else echo '<input type="submit" name="op" value="Cancel Request">';}
                        ?>                                   
                    </form>
                </div>
        </div>



        <!-- User Details-->
        <div style="width:23%; margin: 20px 2%; height: 800px; display:inline-block; vertical-align:top;">

            <!-- user info section -->
            <div id="user_info" class="datablock">
            <img src="../assets/img/user_info.png"><p>User Info</p>
                    <hr>
                    <div style="padding:0 10px;"> 
                        <p><samp>Bio: </samp>
                        <?php
                         echo $_SESSION['target']->get_bio();
                         if(!$isVisitor) echo '<button id="bioBtn" style="float:right; background-color:transparent; border:0px;"><img style="width:15px; height:15px;; margin:0;" src="../assets/img/edit_txt_icon.png"></button>';
                         ?></p>
                        <p><samp>Email: </samp><?php echo $_SESSION['target']->get_email()?> </p>
                        <p><samp>Phone: </samp><?php echo $_SESSION['target']->get_phone()?> </p>
                        <p><samp>Gender: </samp><?php echo $_SESSION['target']->get_gender()?> </p>
                        <p><samp>Birthdate: </samp>
                        <?php
                        $date=date_create($_SESSION['target']->get_birth_date());
                        echo date_format($date,"Y/m/d");
                        ?></p>
                     </div>
                </div>

            <!-- friends section -->
            <div id="friendsblock" class="datablock">
            <img src="../assets/img/friends.png"><p>Friends  (<?php echo $_SESSION['target']->get_friends_no();?>)</p>
                <button id="friendsBtn">See More</button>
                <hr>
                <!-- friends units -->
                <div style="margin: 0 0 0 2%"> 
                <?php
                $friends = $_SESSION['target']->get_friends();
                $friends_no = $_SESSION['target']->get_friends_no();
                if($friends_no!=0){
                    $start = 0;
                    for($i=0; $i<6; $i++){
                    if($i == $friends_no) break;
                    $end = strpos($friends,",",$start + 1);
                    $friend = new user(substr($friends,$start,$end - $start));
                    $start = $end + 1;
                    echo'
                        <div class="dataunit">
                            <img src="../'. $friend->get_id()."/". $friend->get_profile_pic().'"><br>
                            <a href="../'.$friend->get_id().'">' . $friend->get_name() . '</a>
                        </div>';
                    }
                }
                else echo '<p style="text-align:center; color:gray; font-weight:bolder; font-size:150%; margin: 30px;">No Friends To Show</p>'
                ?>
                </div>
            </div>

            <!-- market section -->
            <?php if($_SESSION['target']->get_market_statues()==1){
            
            echo '
            
            <div id="marketblock" class="datablock">
            <img src="../assets/img/market.png"><p>Marketplace  ('.$_SESSION['target']->get_products_no().')</p>
                <button id="marketBtn">Open</button>
                <hr>

                <!-- products units -->
                <div style="margin: 0 0 0 2%">';
                

                if($_SESSION['target']->get_products_no()!=0){
                    $targetMarket = new marketplace($_SESSION['target']->get_id());
                    $targetMarket->show_some_products();
                }

                else echo '<p style="text-align:center; color:gray; font-weight:bolder; font-size:150%; margin: 30px;">No Products To Show</p>';
                echo'
                </div>
            </div>';}

            ?>

             <!-- any other section -->

         </div>
  

        <!-- Posts Section-->
        <div id="newsfeed">


        </div>




        <div id="comments">
                <samp style="font-size:150%; color:royalblue; font-weight:bolder; text-decoration:underline;">Comments</samp>
                <samp style="font-size:150%; color:red; font-weight:bolder; float:right; cursor:pointer;" onclick="closecomment()">X</samp>
                <hr style="margin-top:5px 0; padding:0;">
                <!-- Load Comments -->
                <div id="load">
                </div>
                <!-- Write Comment -->
                <div id="writecomment">
                    <img src="http://localhost/social-media-platform-web/<?php echo $_SESSION['user']->get_id()."/".$_SESSION['user']->get_profile_pic()  ?>">
                    <form id="writecommentform" method="POST" action="http://localhost/social-media-platform-web/assets/operation/post.php">
                        <textarea  placeholder="Write a comment" type="textbox" name="body"></textarea>
                        <input style="width:20%; height:40px; border-radius:5px;  float:right; font-size:90%; margin: 20px 1px" name="submit" type="submit" value="Write">
                    </form>
                </div>

        </div>



            <script>
                                var html = document.getElementById("html");
                                var body = document.getElementById("body");
                                var newsfeed = document.getElementById("newsfeed");
                                var execute = true;
                                body.onscroll=function(){
                                    if(execute && html.scrollHeight - html.scrollTop <= html.clientHeight + 2){
                                        execute = false;
                                        var xhttp = new XMLHttpRequest();
                                        xhttp.onreadystatechange = function() {
                                        if (this.readyState == 4 && this.status == 200) {
                                            newsfeed.innerHTML += this.responseText;
                                            execute = true;
                                        }
                                        };
                                        xhttp.open("GET","http://localhost/social-media-platform-web/assets/operation/post.php?op=load&page=profile");
                                        xhttp.send();
                                    }
                                }
                                body.onload=function(){
                                    
                                        var xhttp = new XMLHttpRequest();
                                        xhttp.onreadystatechange = function() {
                                        if (this.readyState == 4 && this.status == 200) {
                                            newsfeed.innerHTML += this.responseText;
                                            {
                                                var xhttp2 = new XMLHttpRequest();
                                                xhttp2.onreadystatechange = function() {
                                                if (this.readyState == 4 && this.status == 200) {
                                                    newsfeed.innerHTML += this.responseText;
                                                }
                                                };
                                                xhttp2.open("GET","http://localhost/social-media-platform-web/assets/operation/post.php?op=load&page=profile");
                                                xhttp2.send(); 
                                            }
                                        }
                                        };
                                        xhttp.open("GET","http://localhost/social-media-platform-web/assets/operation/post.php?op=load&page=profile");
                                        xhttp.send();
                                }
                                ///// buttons of post
                                function love(post_id){
                                        var xhttp = new XMLHttpRequest();
                                        xhttp.onreadystatechange = function() {
                                            if (this.readyState == 4 && this.status == 200) {
                                            var img = document.getElementById("love" + post_id);
                                            var num = document.getElementById("l" + post_id);
                                            if(img.src == "http://localhost/social-media-platform-web/assets/img/post_love1.png") 
                                            {
                                            img.src = "http://localhost/social-media-platform-web/assets/img/post_love2.png";
                                            num.innerHTML = Number(num.innerHTML) + 1;
                                            }
                                            else 
                                            {
                                            img.src = "http://localhost/social-media-platform-web/assets/img/post_love1.png";
                                            num.innerHTML = Number(num.innerHTML) - 1;
                                            }
                                        }
                                        };
                                        xhttp.open("GET","http://localhost/social-media-platform-web/assets/operation/post.php?op=love&id=" + post_id );
                                        xhttp.send();
                                }
                                function comment(post_id){
                                    var commentbox = document.getElementById("comments");
                                    var comments = document.getElementById("load");
                                    var xhttp = new XMLHttpRequest();
                                        xhttp.onreadystatechange = function() {
                                        if (this.readyState == 4 && this.status == 200) {
                                            comments.innerHTML = this.responseText;
                                        }
                                        };
                                        xhttp.open("GET","http://localhost/social-media-platform-web/assets/operation/post.php?op=loadcomments&id="+post_id);
                                        xhttp.send();
                                    commentbox.style.display = "block";
                                }
                                function closecomment(){
                                    var commentbox = document.getElementById("comments");
                                    commentbox.style.display = "none";
                                }
                                function share(post_id){
                                        var xhttp = new XMLHttpRequest();
                                        xhttp.onreadystatechange = function() {
                                        if (this.readyState == 4 && this.status == 200) {
                                            var num = document.getElementById("s" + post_id);
                                            num.innerHTML = Number(num.innerHTML) + Number(this.responseText);
                                        }
                                        };
                                        xhttp.open("GET","http://localhost/social-media-platform-web/assets/operation/post.php?op=share&id=" + post_id );
                                        xhttp.send();
                                }
                                function save(post_id){
                                        var xhttp = new XMLHttpRequest();
                                        xhttp.onreadystatechange = function() {
                                        if (this.readyState == 4 && this.status == 200) {
                                            var img = document.getElementById("save" + post_id);
                                            if(img.src == "http://localhost/social-media-platform-web/assets/img/post_save1.png") 
                                            {
                                            img.src = "http://localhost/social-media-platform-web/assets/img/post_save2.png";
                                            }
                                            else 
                                            {
                                            img.src = "http://localhost/social-media-platform-web/assets/img/post_save1.png";
                                            }
                                        }
                                        };
                                        xhttp.open("GET","http://localhost/social-media-platform-web/assets/operation/post.php?op=save&id=" + post_id );
                                        xhttp.send();
                                }


            </script>










                <!-- We Work Here -->
        <div id="uploadPPBox" class="modal">
            <div class="modal-content">
            <span class="close">&times;</span>
                <form action="../assets/operation/upload_pic.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="type" value="pp">
                    <input style="background-color: gray; width:70%;" type="file" name="fileToUpload" id="fileToUpload"></br></br>
                    <input style="background-color: silver; width:25%;" type="submit" name="submit" value="Upload" >
                </form>
            </div>
        </div>

        <div id="uploadCoverBox" class="modal">
            <div class="modal-content">
            <span class="close">&times;</span>
                <form action="../assets/operation/upload_pic.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="type" value="cover">
                    <input style="background-color: gray; width:70%;" type="file" name="fileToUpload" id="fileToUpload"></br></br>
                    <input style="background-color: silver; width:25%;" type="submit" name="submit" value="Upload" >
                </form>
            </div>
        </div>
        <div id="uploadBioBox" class="modal">
            <div class="modal-content">
            <span class="close">&times;</span>
                <form action="../assets/operation/update_bio.php" method="post">
                    <input style="background-color: white; width:70%;" type="text" name="bio"></br></br>
                    <input style="background-color: silver; width:25%;" type="submit" name="submit" value="Upload Bio" >
                </form>
            </div>
        </div>

        <div id="marketBox" class="modal">
            <div class="modal-mp-content">
            <span class="close">&times;</span>

            <?php 
            $targetMarket = new marketplace($_SESSION['target']->get_id());
            $targetMarket->show_all_products($_SESSION['user']->get_id());
            ?>

            </div>
            <?php if(!$isVisitor){
            echo '<div class="modal-ma-content">
                    <form action="../assets/operation/market.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="type" value="add_product">
                            <p>Product name:</p><input type="text" name="product_name">
                            <p>Product description:</p><textarea rows="8" type="textbox" name="product_desc"></textarea>
                            <p>Product image:</p><input style="background-color: gray; width:60%;" type="file" name="fileToUpload"><br></br>
                            <input type="submit" name="submit" value="Add Product" >
                    </form>
            </div>';} ?>
        </div>


        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>  //Cover and PP Buttons and market
                var ppBox = document.getElementById("uploadPPBox");
                var ppBtn = document.getElementById("ppBtn");
                var closePP = document.getElementsByClassName("close")[0];
                ppBtn.onclick = function() {
                ppBox.style.display = "block";
                }
                closePP.onclick = function() {
                ppBox.style.display = "none";
                }
                //
                var coverBox = document.getElementById("uploadCoverBox");
                var coverBtn = document.getElementById("coverBtn");
                var closeCover = document.getElementsByClassName("close")[1];
                coverBtn.onclick = function() {
                coverBox.style.display = "block";
                }
                closeCover.onclick = function() {
                coverBox.style.display = "none";
                }
                //
                var bioBox = document.getElementById("uploadBioBox");
                var bioBtn = document.getElementById("bioBtn");
                var closeBio = document.getElementsByClassName("close")[2];
                bioBtn.onclick = function() {
                bioBox.style.display = "block";
                }
                closeBio.onclick = function() {
                bioBox.style.display = "none";
                }
                </script>
                
                
                <script>
                var marketBox = document.getElementById("marketBox");
                var marketBtn = document.getElementById("marketBtn");
                var closeMarket = document.getElementsByClassName("close")[3];
                marketBtn.onclick = function() {
                marketBox.style.display = "block";
                }
                closeMarket.onclick = function() {
                marketBox.style.display = "none";
                }
                //
                function x(id){
                    $.ajax({  
                        type:"POST",  
                        url:"../assets/operation/market.php",  
                        data:"type=remove_product"+'&id='+id,
                        success: location.reload()
                    }); 
                }
                function y(name){
                $.ajax({  
                    type:"POST",  
                    url:"../assets/operation/market.php",  
                    data:"type=notify"+'&name='+name,
                    success: location.reload(),
                }); 
                }
        </script>
        
    </body>  
</html>
