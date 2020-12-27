<?php

            
                    //CLASS >> CONNECTION TO DATABASE
                    class connection{
                        private  $_server = "localhost";
                        private  $_user = "root";
                        private  $_pass = "";
                        private  $_dbname = "chatverse";
                        public   $conn;
                    
                        function __construct(){
                        $this->conn = new mysqli($this->_server, $this->_user, $this->_pass ,$this->_dbname) or die("Connection failed: " . $this->conn->connect_error);
                        }
                        function __destruct(){
                        $this->conn->close();
                         }
                    }

                    //CLASS >> CURRENT USER DATA
                    class user{

                        private $data;

                        function __construct($logID){
                            //get user data from parameter (username or email)
                            $connect = new connection;
                            $result = $connect->conn->query("SELECT * FROM users WHERE id='$logID' or email='$logID' or phone_num='$logID'");
                            $this->data = mysqli_fetch_assoc($result);
                        }
                        function get_name() {return $this->data['f_name']." ".$this->data['l_name'];}
                        function get_id()  {return $this->data['id'];}
                        function get_gender(){return $this->data['gender'];}
                        function get_email(){return $this->data['email'];}
                        function get_phone(){return $this->data['phone_num'];}
                        function get_bio(){return $this->data['bio'];}
                        function get_birth_date(){return $this->data['birth_date'];}
                        function get_profile_pic() {return $this->data['profile_pic'];}
                        function get_cover_pic()  {return $this->data['cover_pic'];}
                        function get_friends(){return $this->data['friends'];}
                        function get_friends_no(){return $this->data['friends_no'];}
                        function get_fr_requests(){return $this->data['fr_requests'];}
                        function get_noti_statues(){return $this->data['new_noti']; }
                        function get_market_statues(){return  $this->data['enable_market'];}
                        function get_products_no(){return $this->data['products_no'];}
                        function update_profile_pic($link)
                        {
                            $connect = new connection;
                            $id = $this->get_id();
                            $connect->conn->query("UPDATE users SET profile_pic='$link' WHERE id='$id'");
                        }
                        function update_cover_pic($link)
                        {
                            $connect = new connection;
                            $id = $this->get_id();
                            $connect->conn->query("UPDATE users SET cover_pic='$link' WHERE id='$id'");
                        }
                        function update_bio($text)
                        {
                            $connect = new connection;
                            $id = $this->get_id();
                            $connect->conn->query("UPDATE users SET bio='$text' WHERE id='$id'");
                        }
                        function enable_market($val){
                            $connect = new connection;
                            $id = $this->get_id();
                            $connect->conn->query("UPDATE users SET enable_market='$val' WHERE id='$id'");
                        }
                        function open_noti(){
                            $connect = new connection;
                            $id = $this->get_id();
                            $connect->conn->query("UPDATE users SET new_noti='' WHERE id='$id'");
                        }
                        function link_google($google){
                            $connect = new connection;
                            $id = $this->get_id();
                            $result = $connect->conn->query("SELECT * FROM users WHERE google_id='$google'");
                            if (mysqli_num_rows($result) == 0) {
                            $connect->conn->query("UPDATE users SET google_id='$google' WHERE id='$id'");
                            echo 'This Google Account associated Successfully!.';}
                            else echo 'This Google Account already associated!.';
                        }
                    }
                    //CLASS >> FRIENDSHIP
                    class friendship{
                        static function isFriend($user_id,$target_id){
                            $user = new user($user_id);
                            return strstr($user->get_friends(),$target_id);
                        }
                        static function isFrRequest($user_id,$target_id){
                            $target= new user($target_id);
                            return strstr($target->get_fr_requests(),$user_id);
                        }
                        static function friendRequest($user_id,$target_id){
                            $connect = new connection;
                            $target = new user($target_id);
                            $fr_requests = $target->get_fr_requests().$user_id.",";
                            $connect->conn->query("UPDATE users SET fr_requests='$fr_requests' WHERE id='$target_id'");
                        }
                        static function cancelRequest($user_id,$target_id){
                            $connect = new connection;
                            $target = new user($target_id);
                            $fr_requests = str_replace($user_id.",","",$target->get_fr_requests());
                            $connect->conn->query("UPDATE users SET fr_requests='$fr_requests' WHERE id='$target_id'");
                        }
                        static function addFriend($user_id,$target_id){
                            $connect = new connection;
                            $user = new user($user_id);
                            $user_friends = $user->get_friends()."$target_id".",";
                            $user_friends_no = $user->get_friends_no() + 1;
                            $connect->conn->query("UPDATE users SET friends='$user_friends' WHERE id='$user_id'");
                            $connect->conn->query("UPDATE users SET friends_no='$user_friends_no' WHERE id='$user_id'");
                            $target = new user($target_id);
                            friendship::cancelRequest($target_id,$user_id);
                            $trg_friends = $target->get_friends()."$user_id".",";
                            $trg_friends_no = $target->get_friends_no() + 1;
                            $connect->conn->query("UPDATE users SET friends='$trg_friends' WHERE id='$target_id'");
                            $connect->conn->query("UPDATE users SET friends_no='$trg_friends_no' WHERE id='$target_id'");
                        }
                        static function removeFriend($user_id,$target_id){
                            $connect = new connection;
                            $user = new user($user_id);
                            $user_friends =  str_replace($target_id.",","",$user->get_friends());
                            $user_friends_no = $user->get_friends_no() - 1;
                            $connect->conn->query("UPDATE users SET friends='$user_friends' WHERE id='$user_id'");
                            $connect->conn->query("UPDATE users SET friends_no='$user_friends_no' WHERE id='$user_id'");
                            $target = new user($target_id);
                            $trg_friends = str_replace($user_id.",","",$target->get_friends());
                            $trg_friends_no = $target->get_friends_no() - 1;
                            $connect->conn->query("UPDATE users SET friends='$trg_friends' WHERE id='$target_id'");
                            $connect->conn->query("UPDATE users SET friends_no='$trg_friends_no' WHERE id='$target_id'");
                        } 
                    }
                    class notification{

                        private $connect;
                        private $con;
                        private $user_id;

                        function __construct($user_id){
                            $this->user_id = $user_id;
                            $this->connect = new connection;
                            $this->con = $this->connect->conn;
                        }

                        function get_noti(){
                            $all_notifications = $this->con->query("SELECT * from notifications WHERE receiver='$this->user_id' order by at_time desc LIMIT 8");
                            for($i = 0; $i < mysqli_num_rows($all_notifications);$i++){
                            $notifications = mysqli_fetch_assoc($all_notifications);
                            $sender = new user($notifications['sender']);
                            $body = $notifications['body'];
                            $time = $notifications['at_time'];
                            echo '<div><a href="http://localhost/social-media-platform-web/'.$sender->get_id().'"><img src="http://localhost/social-media-platform-web/' .$sender->get_id().'/'.$sender->get_profile_pic().'"><a/><samp>'.$time.'</samp><p>'.$sender->get_name()." ".$body.'</p></div>';
                            }
                        }
                        function add_noti($target_id,$body){
                            $this->con->query("INSERT INTO notifications(sender,receiver,body) VALUES('$this->user_id','$target_id','$body')");
                            $this->con->query("UPDATE users SET new_noti='+' WHERE id='$target_id'");
                        }



                    }
                    class marketplace{
                        
                        private $seller_id;
                        private $seller;
                        private $products;
                        private $products_no;

                        function __construct($user_id){
                            $this->seller_id =$user_id;
                            $this->seller = new user($user_id);
                            $connect = new connection;
                            $this->products = $connect->conn->query("SELECT * FROM marketplace WHERE seller='$this->seller_id'");
                            $this->products_no=mysqli_num_rows($this->products);
                        }

                        function add_product($name,$desc,$pic){
                            $connect = new connection;
                            $connect->conn->query("INSERT INTO marketplace(seller,product_name,product_desc,product_pic) VALUES('$this->seller_id','$name','$desc','$pic')");
                            $this->products_no +=1;
                            $number = $this->products_no;
                            $connect->conn->query("UPDATE users SET products_no='$number' WHERE id='$this->seller_id'");
                        }

                        function remove_product($id){
                            $connect = new connection;
                            $connect->conn->query("DELETE FROM marketplace WHERE id='$id'");
                            $this->products_no -=1;
                            $number = $this->products_no;
                            $connect->conn->query("UPDATE users SET products_no='$number' WHERE id='$this->seller_id'");
                        }

                        function show_some_products(){
                            $limit = ($this->products_no <6)? $this->products_no:6;
                            for($i=0;$i<$limit;$i++){
                            $product=  mysqli_fetch_assoc($this->products);
                            echo    '<div class="dataunit">
                                        <img src="market/'. $product['product_pic'].'"><br>
                                        <a>'. $product['product_name'] . '</a>
                                    </div>';
                            }
                        }
                        function show_all_products($target_id){
                        if($this->seller_id == $target_id){
                            for($i=0;$i<$this->products_no;$i++){
                            $product=  mysqli_fetch_assoc($this->products);
                            echo' 
                             <div id="'.$product['id'].'">
                            <h3 style="padding:0;margin:0 10%;">'.$product['product_name'].'</h3>
                             <img src="market/'.$product['product_pic'].'"><div class="body"><p>'.$product['product_desc'].'</p></div>
                             <br><button onclick="x('.$product['id'].')" style="clear:left; margin:10px 0 2px 10%;">Remove Product</button>
                            </div>';
                            }
                        }
                        else{
                            for($i=0;$i<$this->products_no;$i++){
                                $product=  mysqli_fetch_assoc($this->products);
                                echo' 
                                 <div id="'.$product['id'].'">
                                <h3 style="padding:0;margin:0 10%;">'.$product['product_name'].'</h3>
                                 <img src="market/'.$product['product_pic'].'"><div class="body"><p>'.$product['product_desc'].'</p></div>
                                 <br><button name = '.$product['product_name'].' onclick="y(this.name)" style="clear:left; margin:10px 0 2px 10%;">Request Order</button>
                                </div>';
                                }
                        }
                    }
                }

                    

                    











                    //CLASS >> DYNAMIC VALIDATE THE INPUT 
                    class dynamic_validation{

                        private $errors = array("</br>");
                        private $name_c = "/^[A-Za-z-']+$/";
                        private $name_c2 = "/[A-Za-z-']{2,10}/";
                        private $email_c = "/[@]/";
                        private $pass_c = "/^[A-Za-z0-9]{8,16}$/";
                        private $pass_c2 = "/[0-9]+/";
                        private $pass_c3 = "/[A-Za-z]+/";
                        private $phone_c = "/^[0-9+][0-9]{8,15}$/";
                        private $error_1 = "The name must contains only Alphabet letters!";
                        private $error_2 = "Please Enter a real first name!";
                        private $error_1L = "The last name must contains only Alphabet letters!";
                        private $error_2L = "Please Enter a real last name!";
                        private $error_3 = "The email must be vaild!";
                        private $error_4 = "Email is already used!";
                        private $error_5 = "The password [8-16 Digits] must contains at least one letter and number!";
                        private $error_6 = "The Password doesnt match the Re-Password!";
                        private $error_7 = "The phone must be valid and contains only digits!";
                        private $error_8 = "Sorry, You shoud be at least 13 Years old to sign up!";
                        private $pw = "";
           
                        
                        function validate($input,$type,$conn){
                            switch ($type) {
                                case "f_name"   :
                                    $valid = preg_match($this->name_c,$input);
                                    if($valid==TRUE) $this->remove_error($this->error_1);
                                    else if($valid==FALSE) $this->add_error($this->error_1);
                                    $valid = preg_match($this->name_c2,$input);
                                    if($valid==TRUE) $this->remove_error($this->error_2);
                                    else if($valid==FALSE) $this->add_error($this->error_2);
                                break;
                                case "l_name":
                                    $valid = preg_match($this->name_c,$input);
                                    if($valid==TRUE) $this->remove_error($this->error_1L);
                                    else if($valid==FALSE) $this->add_error($this->error_1L);
                                    $valid = preg_match($this->name_c2,$input);
                                    if($valid==TRUE) $this->remove_error($this->error_2L);
                                    else if($valid==FALSE) $this->add_error($this->error_2L);
                                break;
                                case "email":
                                    $valid = preg_match($this->email_c,$input);
                                    if($valid) $this->remove_error($this->error_3);
                                    else $this->add_error($this->error_3);
                                    $result = $conn->query("SELECT * FROM users WHERE email='$input'");                         
                                    $valid = (mysqli_num_rows($result) == 0)? TRUE:FALSE;
                                    if($valid) $this->remove_error($this->error_4);
                                    else $this->add_error($this->error_4);
                                break;
                                case "password":
                                    $this->pw = $input;
                                    $valid = preg_match($this->pass_c,$input);
                                    $valid *= preg_match($this->pass_c2,$input);
                                    $valid *= preg_match($this->pass_c3,$input);
                                    if($valid) $this->remove_error($this->error_5);
                                    else $this->add_error($this->error_5);
                                break;
                                case "password2":
                                    $valid = ($input == $this->pw)? TRUE:False;
                                    if($valid) $this->remove_error($this->error_6);
                                    else $this->add_error($this->error_6);
                                break;
                                case "phone_num":
                                    $valid = preg_match($this->phone_c,$input);
                                    if($valid) $this->remove_error($this->error_7);
                                    else $this->add_error($this->error_7);
                                break;
                                case "birth_date":
                                    $datebirth = date_create($input);
                                    $datecurrent = date_create();
                                    $interval = date_diff($datecurrent, $datebirth);
                                    $valid = ($interval->format('%y') >= 13)? TRUE:False;
                                    if($valid) $this->remove_error($this->error_8);
                                    else $this->add_error($this->error_8);
                                break;
                            }    
                            return $this->errors;
                        }

                        private function add_error($str){
                            if(!in_array($str,$this->errors))
                            array_push($this->errors,$str);

                        }
                        private function remove_error($str){
                            if(in_array($str,$this->errors)){
                            $k = array_search($str,$this->errors);
                            unset($this->errors[$k]);
                            }
                        }
                        function get_errors(){
                            return $this->errors;
                        }
                    }
                    //FUNCTION >> CLEAR THE INPUT 
                    function test_input($data) {
                        $data = trim($data);
                        $data = stripslashes($data);
                        $data = htmlspecialchars($data);
                        return $data;
                    }

?>