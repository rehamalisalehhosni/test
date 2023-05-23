<?php 
session_start();
$pagetitle = 'Login';
if(isset($_SESSION['username'])){  
    header("Location: home.php"); 
    exit();
}
include "ini.php";


    // check if user coming from http or post request
    if($_SERVER['REQUEST_METHOD'] == "POST"){

        $email = $_POST['email'];
        $password = $_POST['password'];
        $hashpass = sha1($password);

        // check if the admin exist in database
        $stmt = $con->prepare("SELECT * FROM members WHERE email = ? AND password = ? AND groupid = 0");
        $stmt->execute(array($email , $hashpass));
        $user = $stmt->fetch();
        $count = $stmt->rowCount();
        if($count > 0){
            $_SESSION['username'] = $user['name'];
            $_SESSION['userid'] = $user['id'];
            $_SESSION['image'] = $user['image'];
            header("Location: home.php");
            exit();
        }
    }else{
?>
<div class="container">
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
        <h1>Login</h1>
        <input type="email" name="email" placeholder="Type your Email" class="name" required>
        <input type="password" name="password" placeholder="Type your Password" class="password" required>
        <button type="submit">Login</button>
        <a href="signup.php">SignUp</a>
    </form>
</div>
<?php include "footer.php";
    }