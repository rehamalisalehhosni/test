<?php
session_start();
$pagetitle = "Categories";
include "ini.php";

    $do = '';
    if(isset($_GET['do'])){
        $do = $_GET['do'];
    }else{
        $do = "Manage";
    }
    if($do == "Manage"){  // Mange Categories Page

        $stmt = $con->prepare("SELECT * FROM categories");
        $stmt->execute();
        $categories = $stmt->fetchAll();
        $count = $stmt->rowCount();
        ?>

        <h1 class="text-center">Manage Categories</h1>
        <table>
		<thead>
			<tr class="member-tr">
				<th>ID</th>
				<th>Name</th>
				<th>Description</th>
				<th>Visibility</th>
				<th>Comments</th>
				<th>Ads</th>
				<th>Control</th>
			</tr>
		</thead>
		<tbody>
        
        <?php
        if(! empty($categories)){
            foreach($categories as $category){
                echo "<tr>";
                    echo "<td>" . $category['id'] . "</td>";
                    echo "<td>" . $category['name'] . "</td>";
                    echo "<td>" . $category['description'] . "</td>";
                    if($category['visibility'] == 0){
                        echo "<td>" . "Yes" . "</td>";
                    }else{
                        echo "<td>" . "No" . "</td>";
                    }
                    if($category['allow_comment'] == 0){
                        echo "<td>" . "Yes" . "</td>";
                    }else{
                        echo "<td>" . "No" . "</td>";
                    }
                    if($category['allow_ads'] == 0){
                        echo "<td>" . "Yes" . "</td>";
                    }else{
                        echo "<td>" . "No" . "</td>";
                    }
                    echo "<td>"; 
                        echo "<a class='btn btn-primary' href='categories.php?do=Edit&catid=" . $category['id'] ."'>Edit</a>";
                        echo "<a class='btn btn-danger del' href='categories.php?do=Delete&catid=" . $category['id'] ."'>Delete</a>";
                    echo"</td>"; 
                echo "</tr>";
            } ?>
            </tbody>
            </table>
            <a href="categories.php?do=Add" class="btn btn-primary">New Category</a>

<?php
    }else{ 
?>
        <div class='alert alert-danger'>There's No Categories To Show</div>
<?php
    }
    
    }elseif($do == "Add"){ // Add Categories Page ?>
    <h1 class="text-center">Add Category</h1>
<form action="categories.php?do=Insert" class="form-horizontal" method="POST">
<div class="container">
        <!-- Start Name Field -->
		<div class="posit">

			<label class="lab">Name:</label>
			<div class="col-sm-10">
			<input type="text" name="name" class="form-control" required>
			</div>
        </div>
		<!-- End Name Field -->

		<!-- Start Description Field -->
		<div class="posit">

		<div class="lab">
			<label class="">Description:</label>
			<div class="col-sm-10">
			<input type="text" name="description" class="form-control" required>
			</div>
		</div>
        </div>
		<!-- End Description Field -->

        <!-- Start Visibility Field -->
        <div class="form-group form-group-lg">
            <div class="posit">
                    <label class="col-sm-2 control-label">Visibility:</label>
            </div>
            <div class="col-sm-10">
                <input id="Visibility0" type="radio" name="visibility" value="0" checked>
                    <label for="Visibility0">Yes</label>
            </div>
            <div>
                <input id="Visibility1" type="radio" name="visibility" value="1">
                    <label for="Visibility1">No</label>
            </div>
        </div>
		<!-- End Visibility Field -->

		<!-- Start Comment Field -->
        <div class="form-group form-group-lg">
            <div class="posit">
                    <label class="col-sm-2 control-label">Comment:</label>
            </div>
            <div class="col-sm-10">
                <input id="Comment0" type="radio" name="comment" value="0" checked>
                    <label for="Comment0">Yes</label>
            </div>
            <div>
                <input id="Comment1" type="radio" name="comment" value="1">
                    <label for="Comment1">No</label>
            </div>
        </div>
		<!-- End Comment Field -->

		<!-- Start Ads Field -->
        <div class="form-group form-group-lg">
		<div class="posit">
            <label class="col-sm-2 control-label">Ads:</label>
        </div>
                <div class="">
                <input id="Ads0" type="radio" name="ads" value="0" checked>
                    <label for="Ads0">Yes</label>
                </div>
                <div>
                    <input id="Ads1" type="radio" name="ads" value="1">
                    <label for="Ads1">No</label>
                </div>
        </div>
			<!-- End Ads Field -->


		<input type="submit" value="submit" name="submit" class="btn btn-primary">
        </div>
</form>



<?php
    }elseif($do == "Insert"){ // Insert Categories Page

        if($_SERVER["REQUEST_METHOD"] == "POST"){
?>
            <h1 class="text-center">Insert Category</h1>
<?php 
            $name           = $_POST['name'];
            $description    = $_POST['description'];
            $visibility     = $_POST['visibility'];
            $comment        = $_POST['comment'];
            $ads            = $_POST['ads'];

            $formerroes = [];

            if(empty($name)){
                $formerroes[] = "<div class='alert alert-danger'>The Name Can't Be <strong>Empty</strong></div>";
            }
            if(! empty($name) && strlen($name) < 3){
                $formerroes[] = "<div class='alert alert-danger'>The Name Shouldn't Be Less Than <strong> 3 Characters</strong></div>";
            }
            if(empty($description)){
                $formerroes[] = "<div class='alert alert-danger'>The Description Can't Be <strong>Empty</strong></div>";
            }
            if(! empty($description) && strlen($description) < 6){
                $formerroes[] = "<div class='alert alert-danger'>The Description Shouldn't Be Less Than <strong>5 Characters</strong></div>";
            }

            if(empty($formerroes)){
            $stmt = $con->prepare("INSERT INTO 
                                        categories(name , description , visibility , allow_comment , allow_ads)
                                    VALUES(:zname , :zdesc , :zvis , :zcom , :zads)");
            $stmt->execute(array(
                'zname' => $name ,
                'zdesc' => $description ,
                'zvis'  => $visibility ,
                'zcom'  => $comment ,
                'zads'  => $ads
            ));
            $count = $stmt->rowCount();

            if($count > 0){ 
                $themsg = "<div class='alert alert-success'>Category Inserted</div>";
				redirect($themsg);    
            }

            }else{ 
                foreach($formerroes as $error){ ?>
                <div><?php echo $error ?></div><br>
        <?php }
            }
    ?>
    
<?php   
    }else{
        $themsg = "<div class='alert alert-danger'>You Have No Access To This Page</div>";
        redirect($themsg);
    }

    }elseif($do == "Edit"){ // Edit Category Page

        if(isset($_GET['catid']) && intval($_GET['catid'])){ 

            $catid = $_GET['catid']; 
            
            $stmt= $con->prepare("SELECT * FROM categories WHERE id = ?");
            $stmt->execute(array($catid));
            $category = $stmt->fetch();
            
            
            ?>

<form action="categories.php?do=Update" class="form-horizontal" method="POST">
<div class="container">
			<input type="text" name="id" class="form-control" value="<?php echo $category['id'] ?>" hidden>
        <!-- Start Name Field -->
		<div class="posit">

			<label class="lab">Name:</label>
			<div class="col-sm-10">
			<input type="text" name="name" class="form-control" value="<?php echo $category['name'] ?>" required>
			</div>
        </div>
		<!-- End Name Field -->

		<!-- Start Description Field -->
		<div class="posit">

		<div class="lab">
			<label class="">Description:</label>
			<div class="col-sm-10">
			<input type="text" name="description" class="form-control" value="<?php echo $category['description'] ?>" required>
			</div>
		</div>
        </div>
		<!-- End Description Field -->

        <!-- Start Comment Field -->
        <div class="form-group form-group-lg">
            <div class="posit">
                    <label class="col-sm-2 control-label">Visibility:</label>
            </div>
            <div class="col-sm-10">
                <input id="Visibility0" type="radio" name="visibility" value="0" <?php if($category['visibility'] == 0){ echo "checked"; } ?>>
                    <label for="Visibility0">Yes</label>
            </div>
            <div>
                <input id="Visibility1" type="radio" name="visibility" value="1" <?php if($category['visibility'] == 1){ echo "checked"; } ?>>
                    <label for="Visibility1">No</label>
            </div>
        </div>
		<!-- End Comment Field -->

		<!-- Start Comment Field -->
        <div class="form-group form-group-lg">
            <div class="posit">
                    <label class="col-sm-2 control-label">Comment:</label>
            </div>
            <div class="col-sm-10">
                <input id="Comment0" type="radio" name="comment" value="0" <?php if($category['allow_comment'] == 0){ echo "checked"; } ?>>
                    <label for="Comment0">Yes</label>
            </div>
            <div>
                <input id="Comment1" type="radio" name="comment" value="1" <?php if($category['allow_comment'] == 1){ echo "checked"; } ?>>
                    <label for="Comment1">No</label>
            </div>
        </div>
		<!-- End Comment Field -->

		<!-- Start Visibility Field -->
        <div class="form-group form-group-lg">
		<div class="posit">
            <label class="col-sm-2 control-label">Ads:</label>
        </div>
                <div class="">
                <input id="Ads0" type="radio" name="ads" value="0" <?php if($category['allow_ads'] == 0){ echo "checked"; } ?>>
                    <label for="Ads0">Yes</label>
                </div>
                <div>
                    <input id="Ads1" type="radio" name="ads" value="1" <?php if($category['allow_ads'] == 1){ echo "checked"; } ?>>
                    <label for="Ads1">No</label>
                </div>
        </div>
			<!-- End Visibility Field -->

		<input type="submit" value="submit" name="submit" class="btn btn-primary">
        </div>
</form>
<?php
        }else{ 
            $themsg = "<div class='alert alert-danger'>You Have No Access To This Page</div>";
            redirect($themsg);
        }

    }elseif($do == "Update"){ // Update Category Page

        if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
?>
            <h1 class="text-center">Update Category</h1>
<?php
            $id          = $_POST['id'];
            $name        = $_POST['name'];
            $description = $_POST['description'];
            $visibility  = $_POST['visibility'];
            $comment     = $_POST['comment'];
            $ads         = $_POST['ads'];

            $formerroes = [];

            if(empty($name)){
                $formerroes[] = "<div class='alert alert-danger'>The Name Can't Be <strong>Empty</strong></div>";
            }
            if(! empty($name) && strlen($name) < 3){
                $formerroes[] = "<div class='alert alert-danger'>The Name Shouldn't Be Less Than <strong> 3 Characters</strong></div>";
            }
            if(empty($description)){
                $formerroes[] = "<div class='alert alert-danger'>The Description Can't Be <strong>Empty</strong></div>";
            }
            if(! empty($description) && strlen($description) < 6){
                $formerroes[] = "<div class='alert alert-danger'>The Description Shouldn't Be Less Than <strong>5 Characters</strong></div>";
            }

            if(empty($formerroes)){
            $stmt = $con->prepare("UPDATE 
                                        categories
                                    SET 
                                        name = ? , 
                                        description = ? , 
                                        visibility = ? , 
                                        allow_comment = ? , 
                                        allow_ads = ?
                                    WHERE 
                                        id = ?");
            $stmt->execute(array($name , $description , $visibility , $comment , $ads , $id));
            $count = $stmt->rowCount();
            
            // Echo Success Message 
            $themsg = "<div class='alert alert-success'>Category Updated</div>";
            redirect($themsg);

        }else{
            foreach($formerroes as $error){ 
                echo $error . "<br>";
            }
        }
    }else{
        $themsg = "<div class='alert alert-danger'>You Have No Access To This Page</div>";
        redirect($themsg);
    }

    }elseif($do == "Delete"){

        if(isset($_GET['catid']) && intval($_GET['catid'])){

            $catid = $_GET['catid'];
            $stmt = $con->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute(array($catid));
            $count = $stmt->rowCount();
            if($count > 0){ 
                $themsg = "<div class='alert alert-success'>Category Deleted</div>";
				redirect($themsg);
            }

        }else{
            $themsg = "<div class='alert alert-danger'>You Have No Access To This Page</div>";
            redirect($themsg);
        }

    }

