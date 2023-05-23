
<a href="#"></a>

<div class="navbar">
		<div class="brand">
			<img class="brand-img" src="layout/imags/Peaky Zone.png" alt="">
		</div>
		<div class="nav-links">
			<a href="dash.php">Home</a>
			<a href="members.php">Members</a>
			<a href="categories.php">Categories</a>
			<a href="items.php">Items</a>
			<a href="comments.php">Comment</a>

<?php
		if(isset($_SESSION['admin'])){
?>
			<div class="dropdown">
				<a href="profile.php"><?php echo $_SESSION['admin']; ?></a>
				<div class="dropdown-content">
					<a href="members.php?do=Edit&userid=<?php echo $_SESSION['id']; ?>">Edit Profile</a>
					<a href="#">Setting</a>
					<a href="logout.php">Logout</a>
				</div>
			</div>
<?php
		}
?>
		</div>
	</div>



