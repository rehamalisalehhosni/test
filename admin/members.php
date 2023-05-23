<?php 
session_start();
$pagetitle = "Members";
include "ini.php";


    $do = '';
    if(isset($_GET['do'])){
        $do = $_GET['do'];
    }else{
		$do = "Manage";
	}

    if($do == 'Manage'){ // Manage Members Page 
	
	$stmt = $con->prepare("SELECT * FROM members WHERE groupid = 0");
	$stmt->execute();
	$members = $stmt->fetchAll();
	$count = $stmt->rowCount();
	?>

        <h1 class="text-center">Manage Members</h1>
        <table>
		<thead>
			<tr class="member-tr">
				<th>ID</th>
				<th>Image</th>
				<th>Full Name</th>
				<th>Email</th>
				<th>Date</th>
				<th>Control</th>
			</tr>
		</thead>
		<tbody>
		
<?php
				if(! empty($members)){
					foreach($members as $member){
						echo "<tr>";
							echo "<td>" . $member['id'] . "</td>";
							echo "<td>";
							if(! empty($member['image'])){ 
	?>
							<img src="uploads/images/<?php echo $member['image']; ?>" alt="" class="image-member">
	<?php
							}else{
								echo "No Image";
							}                         
							echo "</td>";
							echo "<td>" . $member['fullname'] . "</td>";
							echo "<td>" . $member['email'] . "</td>";
							echo "<td>" . $member['date'] . "</td>";
							echo "<td>";
							echo "<a class='btn btn-primary' href='members.php?do=Edit&userid=" . $member['id'] ."'>Edit</a>";
							echo "<a class='btn btn-danger del' href='members.php?do=Delete&userid=" . $member['id'] ."'>Delete</a>";
							if($member['status'] == 0){
							echo "<a class='btn btn-info' href='members.php?do=Approve&userid=" . $member['id'] ."'>Approve</a>";
							}
							echo "</td>";
						echo "</tr>";
					}  
?>
			</tbody>
			</table>
			<div class="new-member">
				<a href="members.php?do=Add" class="btn btn-primary">New Member</a>
			</div>
<?php
		}else{ ?>
			<div class="alret alert-success">There's No Members To Show</div>
<?php
		}
	}elseif($do == 'Pending'){ // Pending Members Page

		// Select Mmebers That Waiting To Approve
		$stmt = $con->prepare("SELECT * FROM members WHERE status = 0");
		$stmt->execute();
		$pendings = $stmt->fetchAll();
		$count = $stmt->rowCount();
		?>
	
			<h1 class="text-center">Manage Members</h1>
			<table>
			<thead>
				<tr class="member-tr">
					<th>ID</th>
					<th>Full Name</th>
					<th>Email</th>
					<th>Date</th>
					<th>Control</th>
				</tr>
			</thead>
			<tbody>
			
<?php
					if(!empty($pendings)){
						foreach($pendings as $pending){
							echo "<tr>";
								echo "<td>" . $pending['id'] . "</td>";
								echo "<td>" . $pending['fullname'] . "</td>";
								echo "<td>" . $pending['email'] . "</td>";
								echo "<td>" . $pending['date'] . "</td>";
								echo "<td>";
								echo "<a class='btn btn-primary' href='members.php?do=Edit&userid=" . $pending['id'] ."'>Edit</a>";
								echo "<a class='btn btn-danger del' href='members.php?do=Delete&userid=" . $pending['id'] ."'>Delete</a>";
								if($pending['status'] == 0){
								echo "<a class='btn btn-info' href='members.php?do=Approve&userid=" . $pending['id'] ."'>Approve</a>";
								}
								echo "</td>";
							echo "</tr>";
						}  
?>
			</tbody>
			</table>
			<div class="new-member">
				<a href="members.php?do=Add" class="btn btn-primary">New Member</a>
			</div>
<?php
			}

    }elseif($do == 'Add'){ // Add Members Page ?>

        <h1 class="text-center">Add Member</h1>
		<form action="members.php?do=Insert" class="form-horizontal" method="POST" enctype="multipart/form-data">
<div class="container">
		<div class="posit">
			<!-- Start Name Field -->
			<label class="lab">Name:</label>
			<div class="col-sm-10">
			<input type="text" name="name" class="form-control" required>
			</div>
		</div>
			<!-- End Name Field -->

			<!-- Start Password Field -->
		<div class="posit">
			<label class="">password:</label>
			<div class="col-sm-10">
			<input type="password" name="password" class="form-control" required>
			</div>
		</div>
			<!-- End Password Field -->

			<!-- Start 	Email Field -->
		<div class="posit">
			<label class="lab">Email:</label>
			<div class="col-sm-10">
			<input type="email" name="email" class="form-control" required>
			</div>
		</div>
			<!-- End Email Field -->

			<!-- Start 	Fullname Field -->
		<div class="posit">
			<label class="lab">FullName:</label>
			<div class="col-sm-10">
			<input type="text" name="fullname" class="form-control" required>
			</div>
		</div>
			<!-- End Fullname Field -->

			<!-- Start 	Image Field -->
			<div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">User Image</label>
                <div class="col-sm-10">
                    <input type="file" name="image" class="form-control"  required="required">
                </div>
            </div>
			<!-- End Image Field -->

		<button class="btn btn-primary">Save</button>
</div>
</form>

<?php 
	}elseif($do == 'Insert'){ // Insert Members Page 


    if($_SERVER['REQUEST_METHOD'] == "POST"){ 
?>
		<h1 class="text-center">Insert Member</h1>
		<?php

		$name 		= $_POST['name'];
		$password 	= $_POST['password'];
		$hashpass	= sha1($password);
		$email 		= filter_var($_POST['email'] , FILTER_VALIDATE_EMAIL);
		$fullname 	= $_POST['fullname'];
		$image = $_FILES['image'];

        // Upload Varriables
		$imagename = $image['name'];
		$imagesize = $image['size'];
		$imagetmp  = $image['tmp_name'];
		$imagetype = $image['type'];

        // List Of Allowed File Typed To Upload
        $imageallowedextension = array("jpeg" , "jpg" , "png");

		// Get Image Extension
		$imageextension = strtolower(end(explode("." , $imagename)));


		$formerrors = [];
		if(empty($name)){ 
			$formerrors[] = "<div>The Name Can't Be <strong>Empty</strong></div>";
		}
		if(!empty($name) && strlen($name) < 3) {
			$formerrors[] = "<div>The Name Should Be More Than<strong> 3 Characters</strong></div>";
		}
		if(!empty($name) && strlen($name) > 10){
			$formerrors[] = "<div>The Name Should Be Less Than<strong> 10 Characters</strong></div>";
		}
		if(empty($password)){
			$formerrors[] = "<div>The Password Can't Be <strong>Empty</strong></div>";
		}
		if(!empty($password) && strlen($password) < 8){
			$formerrors[] = "<div>The Password Should Be More Than<strong> 7 Characters</strong></div>";
		}
		if(!empty($password) && strlen($password) > 20){
			$formerrors[] = "<div>The Password Should Be Less Than<strong> 20 Characters</strong></div>";
		}
		if(empty($email)){ 
			$formerrors[] = "<div>The Email Can't Be <strong>Empty</strong></div>";
		}
		if(empty($fullname)){ 
			$formerrors[] = "<div>The FullName Can't Be <strong>Empty</strong></div>";
		}
		if(!empty($fullname) && strlen($fullname) < 5) {
			$formerrors[] = "<div>The FullName Should Be More Than<strong> 4 Characters</strong></div>";
		}
		if(! empty($imagename) && ! in_array($imageextension , $imageallowedextension)){
			$formerrors[] = "This Extension Is Not <strong>Allowed</strong>" ; 
		}
		if(empty($imagename)){
			$formerrors[] = "Image Is <strong>Required</strong>" ; 
		}
		if($imagesize > 4194304){
			$formerrors[] = "Image Cant Be Larger Than <strong>4MB</strong>" ; 
		}

		if(! empty($formerrors)){
			foreach($formerrors as $error){ ?>
					<div class="alert alert-danger"><?php echo $error; ?><br></div>
<?php 			
				}
		}

		if(empty($formerrors)){
			$check  = checkMember("name" , "members" , $name);
			if($check == 1){ 

				$themsg = "<div class='alert alert-danger'>Soory This User Is Exist</div>";
				redirect($themsg);
				
			}else{

			$image = rand(0, 100000) . '_' . $imagename;
			move_uploaded_file($imagetmp , "uploads\images\\" . $image);

			$stmt = $con->prepare("INSERT INTO 
			`members` (`image` , `name`, `password`, `email`, `fullname`, `groupid`, `status`, `date`)
			VALUES (:zimage, :zname, :zpass, :zemail, :zfull, '0', '1', now())");
				
			$stmt->execute(array(
				'zimage' => $image ,
				'zname' => $name ,
				'zpass' => $hashpass ,
				'zemail' => $email ,
				'zfull' => $fullname
			));
			$count = $stmt->rowCount();

			if($count > 0){ 

				$themsg = "<div class='alert alert-success'>Member Inserted</div>";
				redirect($themsg);

			}
			
		}
	}
}else{

	$themsg = "<div class='alert alert-danger'>You Have No Access To This Page</div>";
	echo redirect($themsg);
}

    }elseif($do == "Edit"){ // Edit Members Page
		if(isset($_GET['userid']) && intval($_GET['userid'])){ ?>
			<h1 class="text-center">Edit Member</h1>	
			<?php
			$userid = $_GET['userid'];
			$stmt = $con->prepare("SELECT * FROM members WHERE id = ?");
			$stmt->execute(array($userid));
			$member = $stmt->fetch(); ?>
			
		<form action="members.php?do=Update" class="form-horizontal" method="POST">
<div class="container">
		<input type="text" name="id" value="<?php echo $member['id'] ?>" hidden>
		<div class="posit">
			<!-- Start Name Field -->
			<label class="lab">Name:</label>
			<div class="col-sm-10">
			<input type="text" name="name" class="form-control" value="<?php echo $member["name"]; ?>">
			</div>
		</div>
			<!-- End Name Field -->

			<!-- Start Password Field -->
		<div class="posit">
			<label class="">password:</label>
			<div class="col-sm-10">
			<input type="password"  name="new-password" class="form-control">
			<input type="password" hidden name="password" class="form-control" value="<?php echo $member["password"]; ?>">
			</div>
		</div>
			<!-- End Password Field -->

			<!-- Start Email Field -->
		<div class="posit">
			<label class="lab">Email:</label>
			<div class="col-sm-10">
			<input type="email" name="email" class="form-control" value="<?php echo $member["email"]; ?>">
			</div>
		</div>
			<!-- End Email Field -->

			<!-- Start Fullname Field -->
		<div class="posit">
			<label class="lab">FullName:</label>
			<div class="col-sm-10">
			<input type="text" name="fullname" class="form-control" value="<?php echo $member["fullname"]; ?>">
			</div>
		</div>
			<!-- End Fullname Field -->

			<!-- Start Date Field -->
		<div class="col-sm-10">
		<input type="date" id="date" name="date" class="form-control" value="<?php echo $member["date"]; ?>">
		</div>
			<!-- End Date Field -->

			<!-- Start Groupid Field -->
		<div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">groupid</label>
            <div >
                <div class="col-sm-10">
                <input id="groupid0" type="radio" name="groupid" value="0" <?php if($member["groupid"] == 0){ echo "checked"; } ?>>
                    <label for="groupid0">0</label>
                </div>
                    <div>
                        <input id="groupid1" type="radio" name="groupid" value="1" <?php if($member["groupid"] == 1){ echo "checked"; } ?>>
                        <label for="groupid1">1</label>
                        </div>
                    </div>
			<!-- End Groupid Field -->

			<!-- Start Status Field -->
					<div class="form-group form-group-lg">
                    <label class="group">status</label>
                    <div >
                        <div class="col-sm-10">
                            <input id="status0" type="radio" name="status" value="0" <?php if($member["status"] == 0){ echo "checked"; } ?>>
                            <label for="status0">0</label>
                        </div>
                        <div>
                            <input id="status1" type="radio" name="status" value="1" <?php if($member["status"] == 1){ echo "checked"; } ?>>
                            <label for="status1 ">1</label>
                        </div>
                    </div>
			<!-- End Status Field -->

		<button class="btn btn-primary">Save</button>
</div>
</form>
			<?php
		}else{ 
				$themsg = "There's No Such ID";
				redirect($themsg);
			}
	}elseif($do == "Update"){	// Update Members Page

		if($_SERVER['REQUEST_METHOD'] == 'POST'){ ?>
			<h1 class="text-center">Update Member</h1>
<?php
			$id 		= $_POST['id'];
			$name 		= $_POST['name'];

			if(empty($_POST['new-password'])){
				$password 	= $_POST['password'];
			}else{
				$password = sha1($_POST['new-password']);
			}

			$email 		= $_POST['email'];
			$fullname 	= $_POST['fullname'];
			$date 		= $_POST['date'];
			$groupid 	= $_POST['groupid'];
			$status 	= $_POST['status'];

			$formerrors = [];
			if(empty($name)){ 
				$formerrors[] = "<div>The Name Can't Be <strong>Empty</strong></div>";
			}
			if(!empty($name) && strlen($name) < 3) {
				$formerrors[] = "<div>The Name Should Be More Than<strong> 3 Characters</strong></div>";
			}
			if(!empty($name) && strlen($name) > 10){
				$formerrors[] = "<div>The Name Should Be Less Than<strong> 10 Characters</strong></div>";
			}
			if(!empty($_POST['new-password']) && strlen($_POST['new-password']) < 8){
				$formerrors[] = "<div>The Password Should Be More Than<strong> 7 Characters</strong></div>";
			}
			if(!empty($_POST['new-password']) && strlen($_POST['new-password']) > 20){
				$formerrors[] = "<div>The Password Should Be Less Than<strong> 20 Characters</strong></div>";
			}
			if(empty($email)){ 
				$formerrors[] = "<div>The Email Can't Be <strong>Empty</strong></div>";
			}
			if(empty($fullname)){ 
				$formerrors[] = "<div>The FullName Can't Be <strong>Empty</strong></div>";
			}
			if(!empty($fullname) && strlen($fullname) < 5) {
				$formerrors[] = "<div>The FullName Should Be More Than<strong> 4 Characters</strong></div>";
			}
			if(empty($date)){
				$formerrors[] = "<div>The Date Can't Be <strong>Empty</strong></div>";
			}
			if(! empty($formerrors)){
				foreach($formerrors as $error){ 
?>
						<div class="alert alert-danger"><?php echo $error; ?><br></div>
<?php 
				}
			}
			if(empty($formerrors)){

				$stmt = $con->prepare("UPDATE 
											members
										SET 
											name = ? ,
											password = ? , 
											email = ? , 
											fullname = ? ,
											date = ? ,
											groupid = ? ,
											status = ? 
										WHERE id = ?");

				$stmt->execute(array($name , $password , $email , $fullname , $date , $groupid , $status , $id));
				$count = $stmt->rowCount();
				if($count >= 0){ 
					$themsg = "<div class='alert alert-success'>Member Updated</div>";
					redirect($themsg);
				}
			}

		}else{
			$themsg = "<div class='alert alert-danger'>You Have No Access To This Page</div>";
			redirect($themsg);
		}
	}elseif($do == "Delete"){
		
		if(isset($_GET['userid']) && intval($_GET['userid'])){
			$userid = $_GET['userid'];
			$stmt = $con->prepare("DELETE FROM members WHERE id = ?");
			$stmt->execute(array($userid));
			$count = $stmt->rowCount();
			if($count > 0){ 
				$themsg = "<div class='alert alert-success'>Member Deleted</div>";
				redirect($themsg);
			}

		}else{
			$themsg = "<div class='alert alert-danger'>You Have No Access To This Page</div>";
			redirect($themsg);
		}
	}elseif($do == "Approve"){

		if(isset($_GET['userid']) && intval($_GET['userid'])){

			$userid = $_GET['userid'];
			$stmt = $con->prepare("UPDATE members SET status = 1 WHERE id = ?");
			$stmt->execute(array($userid));
			$count = $stmt->rowCount();
			if($count > 0){ 
				$themsg = "<div class='alert alert-success'>Member Approved</div>";                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              
				redirect($themsg);
			}
		}else{
			$themsg = "<div class='alert alert-danger'>You Have No Access To This Page</div>";
			redirect($themsg);
		}
	}



