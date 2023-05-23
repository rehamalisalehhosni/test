<?php 
session_start();
$pagetitle = "Items";
include "ini.php";
    
    $do = '';

    if(isset($_GET['do'])){
        $do = $_GET['do'];
    }else{
        $do = "Manage";
    }
    
    if($do == "Manage"){ // Manage Items Page 
        
        $stmt = $con->prepare("SELECT 
                                    items.* , categories.name AS category 
                                FROM 
                                    items 
                                INNER JOIN 
                                    categories 
                                ON 
                                    categories.id = items.cat_id");
        $stmt->execute();
        $count = $stmt->rowCount();
        $items = $stmt->fetchAll();

        ?>

        <h1 class="text-center">Manage Items</h1>
        <table>
		<thead>
			<tr class="member-tr">
				<th>ID</th>
				<th>Image</th>
				<th>Name</th>
				<th>Description</th>
				<th>Price</th>
				<th>Date</th>
				<th>Country</th>
				<th>Status</th>
				<th>Size</th>
				<th>Color</th>
				<th>Available</th>
				<th>Category</th>
				<th>Control</th>
			</tr>
		</thead>
		<tbody>

<?php

        foreach($items as $item){
            echo "<tr>";
                echo "<td>" . $item['id'] . "</td>";
                echo "<td>";
                if(! empty($item['image'])){ 
?>
                <img src="uploads/images/<?php echo $item['image']; ?>" alt="" class="image-member">
<?php
                }else{
                    echo "No Image";
                }                         
                echo "</td>";
                echo "<td>" . $item['name'] . "</td>";
                echo "<td>" . $item['description'] . "</td>";
                echo "<td>" . $item['price'] . "</td>";
                echo "<td>" . $item['date'] . "</td>";
                echo "<td>" . $item['country'] . "</td>";
                if($item['status'] == 1){

                echo "<td>New</td>";

                }elseif($item['status'] == 2){

                echo "<td>Like New</td>";

                }elseif($item['status'] == 3){

                    echo "<td>Used</td>";

                }elseif($item['status'] == 2){

                    echo "<td>Old</td>";

                }
                echo "<td>" . $item['size'] . "</td>";
                echo "<td>" . $item['color'] . "</td>";
                echo "<td>" . $item['nums'] . "</td>";
                echo "<td>" . $item['category'] . "</td>";
                echo "<td>";
                echo "<a class='btn btn-primary' href='items.php?do=Edit&itemid=" . $item['id'] ."'>Edit</a>";
                echo "<a class='btn btn-danger del' href='items.php?do=Delete&itemid=" . $item['id'] ."'>Delete</a>";
                echo "</td>";
            echo "</tr>";
        }  ?>
        </tbody>
        </table>
        <div class="new-member">
        <a href="items.php?do=Add" class="btn btn-primary">New Item</a>
        </div>

<?php
    }elseif($do == "Add"){ // Add Items Page ?>

<h1 class="text-center">Add Item</h1>
<form action="items.php?do=Insert" class="form-horizontal" method="POST" enctype="multipart/form-data">
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

		<!-- Start Price Field -->
		<div class="posit">

		<div class="lab">
			<label class="">Price:</label>
			<div class="col-sm-10">
			<input type="text" name="price" class="form-control" required>
			</div>
		</div>
        </div>
		<!-- End Price Field -->

		<!-- Start Country Field -->
		<div class="posit">

		<div class="lab">
			<label class="">Country:</label>
			<div class="col-sm-10">
			<input type="text" name="country" class="form-control" required>
			</div>
		</div>
        </div>
		<!-- End Country Field -->

            <!-- Start 	Image Field -->
			<div class="form-group form-group-lg">
                <label class="col-sm-2 control-label">Item Image</label>
                <div class="col-sm-10">
                    <input type="file" name="image" class="form-control"  required="required">
                </div>
            </div>
			<!-- End Image Field -->

        <!-- Start Status Field -->
		<div class="posit">
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Status:</label>
            <div class="col-sm-10">
                <select class="form-control" name='status'>
                    <option value="1">New</option>
                    <option value="2">Like New</option>
                    <option value="3">Used</option>
                    <option value="4">Old</option>
                </select>
            </div>
        </div>
        </div>
        <!-- End Status Field -->

        <!-- Start Size Field -->
		<div class="posit">
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Size:</label>
            <div class="col-sm-10">
                <select class="form-control" name='size'>
                    <option value="M">M</option>
                    <option value="S">S</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                </select>
            </div>
        </div>
        </div>
        <!-- End Size Field -->

        <!-- Start Color Field -->
		<div class="posit">
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Color:</label>
            <div class="col-sm-10">
                <select class="form-control" name='color'>
                    <option value="Black">Black</option>
                    <option value="White">White</option>
                    <option value="Blue">Blue</option>
                </select>
            </div>
        </div>
        </div>
        <!-- End Color Field -->

        <!-- Start Number Field -->
		<div class="posit">
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Number:</label>
            <div class="col-sm-10">
                <select class="form-control" name='number'>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
        </div>
        </div>
        <!-- End Number Field -->

        <!-- Start Category Field -->
		<div class="posit">
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Category:</label>
            <div class="col-sm-10">
                <select class="form-control" name='category'>
                <?php 
                    $stmt = $con->prepare("SELECT * FROM categories");
                    $stmt->execute();
                    $categories = $stmt->fetchAll();
                    foreach($categories as $category){ ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name'] ?></option>
                <?php }
                ?>
                </select>
            </div>
        </div>
        </div>
        <!-- End Category Field -->

		<input type="submit" value="submit" name="submit" class="btn btn-primary">
        </div>
</form>

<?php
        
    }elseif($do == "Insert"){ // Add Items Page 

        if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
?>
            <h1 class="text-center">Insert Page</h1>
<?php
        
        $name           = $_POST['name'];
        $description    = $_POST['description'];
        $price          = $_POST['price'];
        $country        = $_POST['country'];
        $status         = $_POST['status'];
        $size           = $_POST['size'];
        $color          = $_POST['color'];
        $number         = $_POST['number'];
        $category       = $_POST['category'];
		$image          = $_FILES['image'];

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

            $formerrors[] = "The Name Can't Be <strong>Empty</strong>";

        }
        if(! empty($name) && strlen($name) < 3){

            $formerrors[] = "The Name Can't Be Less Than <strong>3 Characters</strong>";

        }
        if(empty($description)){

            $formerrors[] = "The Description Can't Be <strong>Empty</strong>";

        }
        if(! empty($description) && strlen($description) < 5){

            $formerrors[] = "The Description Can't Be Less Than <strong>5 Characters</strong>";

        }
        if(empty($price)){

            $formerrors[] = "The Price Can't Be <strong>Empty</strong>";

        }
        if(empty($country)){

            $formerrors[] = "The Country Can't Be <strong>Empty</strong>";

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

        if(empty($formerrors)){
        
            $image = rand(0, 100000) . '_' . $imagename;
			move_uploaded_file($imagetmp , "uploads\images\\" . $image);


            $stmt = $con->prepare("INSERT INTO 
                                            items(image , name , description , price , date , country , status , size, color, nums,cat_id)
                                    VALUES(:zimage ,:zname , :zdesc , :zprice , NOW() , :zcountry , :zstatus , :zsize, :zcolor, :znums,:zcatid)");
            $stmt->execute(array(

                'zimage'    => $image ,
                'zname'     => $name , 
                'zdesc'     => $description , 
                'zprice'    => $price , 
                'zcountry'  => $country , 
                'zstatus'   => $status ,
                'zsize'     => $size ,
                'zcolor'    => $color ,
                'znums'     => $number ,
                'zcatid'    => $category
            ));
            $count = $stmt->rowCount();
            if($count > 0){
                $themsg = "<div class='alert alert-success'>Item Inserted</div>";
				redirect($themsg);
            }
            
        }else{
            foreach($formerrors as $error){ ?>
            
                <div class="alert alert-danger"><?php echo $error; ?></div><br>

<?php

            }
        }
        
        ?>

<?php   }else{ 
            $themsg = "<div class='alert alert-danger'>You Have No Access To This Page</div>";
            redirect($themsg);
        }
        
    }elseif($do == "Edit"){ // Edit Items Page

        if(isset($_GET['itemid']) && intval($_GET['itemid'])){ 
?>
            <h1 class="text-center">Edit Item</h1>
<?php
            $itemid = $_GET['itemid'];
            $stmt = $con->prepare("SELECT * FROM items WHERE id = ?");
            $stmt->execute(array($itemid));
            $item = $stmt->fetch();
?>
<form action="items.php?do=Update" class="form-horizontal" method="POST">
	<input type="text" name="id" class="form-control"  value="<?php echo $item["id"]; ?>" hidden>
    <div class="container">
        <!-- Start Name Field -->
		<div class="posit">

			<label class="lab">Name:</label>
			<div class="col-sm-10">
			<input type="text" name="name" class="form-control"  value="<?php echo $item["name"]; ?>" >
			</div>
        </div>
		<!-- End Name Field -->

		<!-- Start Description Field -->
		<div class="posit">

		<div class="lab">
			<label class="">Description:</label>
			<div class="col-sm-10">
			<input type="text" name="description" class="form-control" value="<?php echo $item["description"]; ?>" >
			</div>
		</div>
        </div>
		<!-- End Description Field -->

		<!-- Start Price Field -->
		<div class="posit">

		<div class="lab">
			<label class="">Price:</label>
			<div class="col-sm-10">
			<input type="text" name="price" class="form-control" value="<?php echo $item["price"]; ?>" >
			</div>
		</div>
        </div>
		<!-- End Price Field -->

		<!-- Start Country Field -->
		<div class="posit">

		<div class="lab">
			<label class="">Country:</label>
			<div class="col-sm-10">
			<input type="text" name="country" class="form-control" value="<?php echo $item["country"]; ?>" >
			</div>
		</div>
        </div>
		<!-- End Country Field -->

        <!-- Start Status Field -->
		<div class="posit">
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Status:</label>
            <div class="col-sm-10">
                <select class="form-control" name='status'>
                    <option value="1" <?php if($item['status'] == 1){ echo "selected"; } ?>>New</option>
                    <option value="2" <?php if($item['status'] == 2){ echo "selected"; } ?>>Like New</option>
                    <option value="3" <?php if($item['status'] == 3){ echo "selected"; } ?>>Used</option>
                    <option value="4" <?php if($item['status'] == 4){ echo "selected"; } ?>>Old</option>
                </select>
            </div>
        </div>
        </div>
        <!-- End Status Field -->

        <!-- Start Size Field -->
		<div class="posit">
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Size:</label>
            <div class="col-sm-10">
                <select class="form-control" name='size'>
                    <option value="M" <?php if($item['size'] == "M"){ echo "selected"; } ?>>M</option>
                    <option value="S" <?php if($item['size'] == "S"){ echo "selected"; } ?>>S</option>
                    <option value="L" <?php if($item['size'] == "L"){ echo "selected"; } ?>>L</option>
                    <option value="XL" <?php if($item['size'] == "XL"){ echo "selected"; } ?>>XL</option>
                </select>
            </div>
        </div>
        </div>
        <!-- End Size Field -->

        <!-- Start Color Field -->
		<div class="posit">
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Color:</label>
            <div class="col-sm-10">
                <select class="form-control" name='color'>
                    <option value="Black" <?php if($item['color'] == "Black"){ echo "selected"; } ?>>Black</option>
                    <option value="White" <?php if($item['color'] == "White"){ echo "selected"; } ?>>White</option>
                    <option value="Blue" <?php if($item['color'] == "Blue"){ echo "selected"; } ?>>Blue</option>
                </select>
            </div>
        </div>
        </div>
        <!-- End Color Field -->

        <!-- Start Number Field -->
		<div class="posit">
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Available:</label>
            <div class="col-sm-10">
                <select class="form-control" name='nums'>
                    <option value="1" <?php if($item['nums'] == 1){ echo "selected"; } ?>>1</option>
                    <option value="2" <?php if($item['nums'] == 2){ echo "selected"; } ?>>2</option>
                    <option value="3" <?php if($item['nums'] == 3){ echo "selected"; } ?>>3</option>
                </select>
            </div>
        </div>
        </div>
        <!-- End Number Field -->

        <!-- Start Category Field -->
		<div class="posit">
        <div class="form-group form-group-lg">
            <label class="col-sm-2 control-label">Category:</label>
            <div class="col-sm-10">
                <select class="form-control" name='category'>
                <?php 
                    $stmt = $con->prepare("SELECT * FROM categories");
                    $stmt->execute();
                    $categories = $stmt->fetchAll();
                    foreach($categories as $category){ ?>
                        <option value="<?php echo $category['id']; ?>" <?php if($item['cat_id'] == $category['id']){ echo "selected"; } ?>><?php echo $category['name'] ?></option>
                <?php }
                ?>
                </select>
            </div>
        </div>
        </div>
        <!-- End Category Field -->

        <!-- Start Date Field -->
		<div class="posit">
            <label for="">Date:</label>
            <div class="col-sm-10">
            <input type="date" id="date" name="date" class="form-control" value="<?php echo $item["date"]; ?>">
            </div>
        </div>
		<!-- End Date Field -->

		<input type="submit" value="submit" name="submit" class="btn btn-primary">
        </div>
</form>
<?php

        }else{ 

            $themsg = "<div class='alert alert-danger'>You Have No Access To This Page</div>";
            redirect($themsg);

        }

    }elseif($do == "Update"){ // Update Items Page

        if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
?>

            <h1 class="text-center">Update Item</h1>
<?php

            $id          = $_POST['id'];
            $name        = $_POST['name'];
            $description = $_POST['description'];
            $price       = $_POST['price'];
            $country     = $_POST['country'];
            $status      = $_POST['status'];
            $size        = $_POST['size'];
            $color       = $_POST['color'];
            $number      = $_POST['nums'];
            $category    = $_POST['category'];
            $date        = $_POST['date'];

            $formerrors = [];

            if(empty($name)){
    
                $formerrors[] = "The Name Can't Be <strong>Empty</strong>";
    
            }
            if(! empty($name) && strlen($name) < 3){
    
                $formerrors[] = "The Name Can't Be Less Than <strong>3 Characters</strong>";
    
            }
            if(empty($description)){
    
                $formerrors[] = "The Description Can't Be <strong>Empty</strong>";
    
            }
            if(! empty($description) && strlen($description) < 5){
    
                $formerrors[] = "The Description Can't Be Less Than <strong>5 Characters</strong>";
    
            }
            if(empty($price)){
    
                $formerrors[] = "The Price Can't Be <strong>Empty</strong>";
    
            }
            if(empty($country)){
    
                $formerrors[] = "The Country Can't Be <strong>Empty</strong>";
    
            }
            if(empty($date)){
    
                $formerrors[] = "The Date Can't Be <strong>Empty</strong>";
    
            }
    
            if(empty($formerrors)){
                $stmt = $con->prepare("UPDATE 
                                            items
                                        SET 
                                            name = ? , 
                                            description = ? , 
                                            price = ? , 
                                            date = ? , 
                                            country = ? , 
                                            status = ? ,
                                            size = ? , 
                                            color = ? , 
                                            nums = ? , 
                                            cat_id = ? 
                                        WHERE 
                                            id = ?");
                $stmt->execute(array($name , $description , $price , $date , $country , $status , $size, $color, $number,$category , $id));
                $count = $stmt->rowCount();
                if($count >= 0){ 
                    $themsg = "<div class='alert alert-success'>Item Updated</div>";
                    redirect($themsg);
                }

            }else{ 
                foreach($formerrors as $error){
?>
                    <div class="alert alert-danger"><?php echo $error ?></div>
<?php           
                }       
            }
        }else{ 
            $themsg = "<div class='alert alert-danger'>You Have No Access To This Page</div>";
            redirect($themsg);
        }

    }elseif($do == "Delete"){ // Delete Items Page

        if(isset($_GET['itemid']) && intval($_GET['itemid'])){ 
?>
            <h1 class="text-center">Delete Item</h1>
<?php   
            $itemid = $_GET['itemid'];
            
            $stmt = $con->prepare("DELETE FROM items WHERE id = ?");
            $stmt->execute(array($itemid));
            $count = $stmt->rowCount();
            if($count > 0){
                $themsg = "<div class='alert alert-success'>Item Deleted</div>";
				redirect($themsg);
            }
        }else{  
            $themsg = "<div class='alert alert-danger'>You Have No Access To This Page</div>";
            redirect($themsg);
        }

    }


