
        <a href="#"></a>

<div class="navbar">
		<div class="brand">
			<img class="brand-img" src="layout/imags/Peaky Zone.png" alt="">
		</div>
		<div class="nav-links">
			<a href="home.php">Home</a>
			<a href="store.php">Store</a>
			<a href="#">Contact Us</a>


<?php
		if(isset($_SESSION['username'])){
?>
			<div class="dropdown">
				<a href="profile.php"><?php echo $_SESSION['username']; ?></a>
				<div class="dropdown-content">
					<a href="members.php?do=Edit&userid=<?php echo $_SESSION['userid']; ?>">Edit Profile</a>
					<a href="#">Setting</a>
					<a href="logout.php">Logout</a>
				</div>
			</div>
<?php
		}
?>
		</div>
	</div>



