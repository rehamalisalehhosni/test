<?php
session_start();
$pagetitle = "MyProfile";
include "ini.php";
if(isset($_SESSION['admin'])){
    $stmt = $con->prepare("SELECT * FROM members WHERE groupid = 1 AND name = ?");
    $stmt->execute(array($_SESSION['admin']));
    $admin = $stmt->fetch();

?>
    <header>
        <h1>Admin Profile</h1>
    </header>
    <main>
        <section class="profile">
        <img src="uploads/images/<?php echo $admin['image']; ?>" alt="Admin Profile Picture" class="profile-img">
        <div class="details">
            <h2><?php echo $admin['fullname']; ?></h2>
            <p>Email: <?php echo $admin['email']; ?></p>
            <p>Role: Administrator</p>
        </div>
        </section>
    </main>
<?php
}else{
    $themsg = "<div class='alert alert-danger'>You Have No Access To This Page</div>";
	redirect($themsg);
}