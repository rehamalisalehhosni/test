<?php 
session_start();
$pagetitle = 'Login';
if(isset($_SESSION['admin'])){  
    header("Location: dash.php"); 
    exit();
}
include "ini.php";


    // check if user coming from http or post request
    if($_SERVER['REQUEST_METHOD'] == "POST"){

        $name = $_POST['name'];
        $password = $_POST['password'];
        $hashpass = sha1($password);

        // check if the admin exist in database
        $stmt = $con->prepare("SELECT * FROM members WHERE name = ? AND password = ? AND groupid = 1");
        $stmt->execute(array($name , $hashpass));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if($count > 0){
            $_SESSION['admin'] = $name;
            $_SESSION['id'] = $row['id'];
            header("Location: dash.php");
            exit();
        }
    }
?>
<div class="container">
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>">
        <h1>Admin</h1>
        <input type="text" name="name" placeholder="Type Your Name" class="name" required>
        <input type="password" name="password" placeholder="Type Your Password" class="password" required>
        <button type="submit">Login</button>
    </form>
</div>
<?php include "footer.php";