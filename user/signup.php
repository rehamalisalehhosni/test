<?php

$pagetitle = "Sign Up";
include "ini.php";
?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" class="signform">
<h1>Sign Up</h1>
<input type="text" id="name" name="username" placeholder="Type your Username" required>
<input type="text" id="name" name="fullname" placeholder="Type your Fullname" required>
<input type="email" id="email" name="email" placeholder="Type your Email" required>
<input type="password" id="password" name="password" placeholder="Type your Password" required>
<button type="submit">Signup</button>
<a href="login.php">Sign In</a>
<?php

?>
</form>

<?php

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $name = $_POST['username'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashpassword = sha1($password);

    $formerrors = array();

    if(empty($name)){

        $formerrors[] = "Your Name Can't Be Empty";

    }
    if(! empty($name) && strlen($name) < 3){

        $formerrors[] = "Your Name Should Be More Than 2 Characters";

    }
    if(empty($fullname)){

        $formerrors[] = "Your Fullname Can't Be Empty";

    }
    if(! empty($fullname) && strlen($fullname) < 6){

        $formerrors[] = "Your Name Should Be More Than 5 Characters";

    }
    if(empty($email)){

        $formerrors[] = "Your Email Can't Be Empty";

    }
    if(empty($password)){

        $formerrors[] = "Your Password Can't Be Empty";

    }
    if(! empty($password) && strlen($password) < 8){

        $formerrors[] = "Your Name Should Be More Than 7 Characters";

    }
    if(! empty($formerrors)){
        foreach($formerrors as $error){ 
?>
            <div class="alert alert-danger"><?php echo $error; ?> <br></div>
<?php 
        }
    }else{

        $stmt = $con->prepare("SELECT name FROM members WHERE name = ?");
        $stmt->execute(array($name));
        $check = $stmt->rowCount();
        if($check == 1){ 
?>
            <div class="alert alert-danger">Soory This Username Is Exist</div>
<?php
        }else{

        $stmt = $con->prepare("INSERT INTO 
                                `members` (`id`, `image`, `name`, `password`, `email`, `fullname`, `groupid`, `status`, `date`) 
                                VALUES (NULL, NULL, :zname, :zpassword, :zemail, :zfullname, '0', '0', now())");
        $stmt->execute(array(
            'zname' => $name ,
            'zpassword' => $hashpassword ,
            'zemail' => $email , 
            'zfullname' => $fullname ,
        ));
        $count = $stmt->rowCount();
        if($count > 0){ 
            //  Echo Success Message
            echo "<div class='alert alert-success'>Congrats You Have Signed Up</div><br>";
            header("refresh:3;url=signup.php");
        }
    }
}

}

include "footer.php";