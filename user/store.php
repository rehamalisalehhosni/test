<?php
session_start();

$pagetitle = "Store";
include "ini.php";

// Select The Item Depend On ItemID
if(isset($_GET['id']) && intval($_GET['id'])){
    $itemid = $_GET['id'];
    $stmt = $con->prepare("SELECT items.* , categories.name AS category_name
                            FROM 
                                items
                        INNER JOIN
                                categories
                        ON 
                            categories.id = items.cat_id
                        WHERE
                            items.id = ?");
    $stmt->execute(array($itemid));
    $item = $stmt->fetch();

    // Start Sesstion Carts Field
    if($_SERVER['REQUEST_METHOD'] == "POST"){

        $id = $_GET['id'];
        $name = $_POST['name'];
        $color = $_POST['color'];
        $size = $_POST['size'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $control = "delete";
        if(isset($_SESSION['cart'])){

        $item_key = array_search($id, array_column($_SESSION['cart'], 'id'));

        if($item_key !== false){
            if($_SESSION['cart'][$item_key]['color'] == $color && $_SESSION['cart'][$item_key]['size'] == $size){
            $_SESSION['cart'][$item_key]['quantity'] += $quantity;
            
            }else{
                $item = array(
                    "id" => $id,
                    "img" => '', 
                    "name" => $name, 
                    "price" => $price, 
                    "color" => $color, 
                    "size" => $size, 
                    "quantity" => $quantity ,
                    "control" => $id
                );
                $_SESSION['cart'][] = $item;
                header("Refresh:0");
            }
    }else{
        $session_array_id = array_column($_SESSION['cart'] , "id");
        if(!in_array($_GET['id'] , $session_array_id)){

            $item = array(
                "id" => $id,
                "img" => '', 
                "name" => $name, 
                "price" => $price, 
                "color" => $color, 
                "size" => $size, 
                "quantity" => $quantity ,
                "control" => $id
            );
            $_SESSION['cart'][] = $item;
            header("Refresh:0");
        
        }
    }
    }else{            
            $item = array(
                "id" => $id,
                "img" => '', 
                "name" => $name, 
                "price" => $price, 
                "color" => $color, 
                "size" => $size, 
                "quantity" => $quantity,
                "control" => $id
            );
        
            $_SESSION['cart'][] = $item;
            header("Refresh:0");

        }

    }
    if(!empty($_SESSION['cart'])){
        ?>
        <div style="display:flex;justify-content:center;">
        <a href="cart.php" style="text-decoration:none;"><button style="width: 100%;">View Carts</button></a>
        </div>
        <?php 
    }



?>
<form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $item['id'] ?>" method="POST" enctype="multipart/form-data">
<div class="container">
<h1 class="text-center"><?php echo $item['name']; ?></h1>
<div class="info-item">
    <div class="row">
        <div class="col-md-3">
            <img src="../admin/uploads/images/<?php echo $item['image']; ?>" class="card-img-top img-responsive img-thumbnail center-block">
        </div>
        <div class="col-md-9">
            <!-- Start Name Field -->
            <h2><?php echo $item['name'] ?></h2>
            <input type="hidden" value="<?php echo $item['name']; ?>" name="name">
            <!-- End Name Field -->

            <!-- Start Description Field -->
            <p><?php echo $item['description'] ?></p>
            <!-- End Description Field -->

            <ul class="list-unstyled">
                <!-- Start Price Field -->
                <li>
                    <input type="hidden" value="<?php echo $item['price']; ?>" name="price">
                    <i class="fa fa-money-bill fa-fw"></i>
                    <span>Price</span> : <?php echo number_format($item['price'],2) ?> EGP 
                </li>
                <!-- End Price Field -->

                <!-- Start Color Field -->
                <li class="colors">
                    <i class="fa fa-paint-brush fa-fw"></i>
                    <span>Color</span> :
                    <select name="color" class="color">
                        <option value="black" class="black">Black</option>
                        <option value="white" class="white">White</option>
                        <option value="blue" class="blue">Blue</option>
                    </select>
                </li>
                <!-- End Color Field -->

                <!-- Start Size Field -->
                <li>
                    <i class="fa fa-tshirt fa-fw"></i>
                    <span>Size</span> :
                    <select name="size" class="size">
                        <option value="M" class="m">M</option>
                        <option value="L" class="l">L</option>
                        <option value="XL" class="xl">XL</option>
                        <option value="XXL" class="xxl">XXL</option>
                    </select>
                </li>

                <!-- End Size Field -->

                <!-- Start Quantity Field -->

                <li>
                    <input type="number" name="quantity" class="input-text qty text" value="1" size="4" min="1" max step="1">
                </li>
                <!-- End Quantity Field -->

                <button>Add To Cart</button>
            </ul>
            
        </div>
    </div>
</div>
</form>
<hr>

<?php
    // End Sesstion Carts Field

    // Start Comments T-shirt Field

if(isset($_SESSION['username'])){
?>
    <div class="row">
        <div class="col-md-offset-3">
            <div> 
                <h3>Add Your Comment</h3>
                <form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $item['id'] ?>" method="POST" style="margin: 0;" class="form-horizontal">
                    <textarea name="comment" class="form-control" required></textarea>
                    <input class="pull" type="submit" value="Add Comment" style="color:white;margin:5px 0px 0 0;background-color: #CF3030;width:150px">
                </form>
            </div>
        </div>
    </div>
<hr>
<?php
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $comment = $_POST['comment'];
    $itemid = $item['id'];
    $memberid = $_SESSION['userid'];
    if(empty($comment)){
        echo "Comment Can't Be <strong>Empty</strong>";
    }else{
        $stmt = $con->prepare("INSERT INTO 
                                    comments(comment , item_id , member_id , date)
                                VALUES(:zcomment , :zitemid , :zmemberid , NOW())");
        $stmt->execute(array(
            'zcomment' => $comment , 
            'zitemid' => $itemid ,
            'zmemberid' => $memberid
        ));
        $count = $stmt->rowCount();

    }
}
}else{
?>
<a href="login.php">Login</a> or <a href="login.php">Registers</a> To Add Comment
<hr>
<?php
}
$stmt = $con->prepare("SELECT comments.* , members.name AS member_name , members.image AS member_image
                        FROM comments
                        INNER JOIN 
                            members
                        ON 
                            members.id = comments.member_id
                        WHERE
                            item_id = ?");
$stmt->Execute(array($itemid));
$comments = $stmt->fetchAll();
foreach($comments as $comment){
?>
<div class="comment-box" style="margin-bottom:20px;">
    <div class="row">
        <div class="col-sm-2 text-center">
            <img src="../admin/uploads/images/<?php echo $comment['member_image']; ?>" class="card-img-top img-responsive img-thumbnail img-circle center-block" style="width:70px;height:70px;border-radius:50%;">
            <?php echo $comment['member_name']; ?>
        </div>
        <div class="col-md-10" style="margin-top: 10px;">
            <p style="background-color:#EEE;padding:10px;"><?php echo $comment['comment']?></p>
        </div>
    </div>
</div>
<hr>
<?php
}
?>
</div>
<?php
    // End Comments T-shirt Field

    // Start Order Details Field

}elseif(isset($_GET['order']) && is_string($_GET['order'])){ // Order Page

    $order = $_GET['order'];
?>
<div class="container">
<?php
    $stmt = $con->prepare("SELECT * FROM items WHERE name = ?");
    $stmt->execute(array($order));
    $item = $stmt->fetch();
?>
    <div class="order-details">
        <h1 class="text-center">Order Details</h1>
        <p>Product Name : <?php echo $item['name']; ?></p>
        <span>Product Price : <?php echo $item['price']; ?> EGP</p>
        <p>Shipping : Free</span>
        <p>Total Price : <?php echo $item['price']; ?> EGP</p>
    </div>
    <P>Cash on delivery</P>
    <div class="cash">
    <P>Pay with cash upon delivery</P>
    </div>
    <hr>

    <!-- End Order Details Field -->

    <!-- Start Bill Details Field -->

    <form action="<?php echo $_SERVER['PHP_SELF'] . '?order=' . $item['name'] ?>" method="POST">
    <h2>Bill Details</h2>

		<!-- Start Name Field -->
		<label class="col-sm-6 control-label">FullName:</label>
		<div class="col-sm-6">
			<input type="text" name="name" class="form-control" required autocomplete="off-set">
		</div>
		<!-- End Name Field -->

		<!-- Start Street Address Field -->
		<label class="col-sm-6 control-label">street Address:</label>
		<div class="col-sm-6">
			<input type="text" name="street" class="form-control" required autocomplete="off-set">
		</div>
		<!-- End Street Address Field -->

		<!-- Start Town Field -->
		<label class="col-sm-6 control-label">Town/City:</label>
		<div class="col-sm-6">
			<input type="text" name="town" class="form-control" required autocomplete="off-set">
		</div>
		<!-- End Town Field -->

		<!-- Start Country Field -->
		<label class="col-sm-6 control-label">State/Country:</label>
		<div class="col-sm-6">
			<input type="text" name="country" class="form-control" required autocomplete="off-set">
		</div>
		<!-- End Country Field -->

		<!-- Start Phone Field -->
		<label class="col-sm-6 control-label">phone:</label>
		<div class="col-sm-6">
			<input type="text" name="phone" class="form-control" required autocomplete="off-set">
		</div>
		<!-- End Phone Field -->

		<!-- Start 	Email Field -->
		<label class="col-sm-6 control-label">Email:</label>
		<div class="col-sm-6">
			<input type="email" name="email" class="form-control" required autocomplete="off-set">
		</div>
		<!-- End Email Field -->
        
        <!-- Start Size Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-6 control-label">Size:</label>
            <div class="col-sm-6">
                <select class="form-control" name='size'>
                    <option value="M" <?php if($item['size'] == "M"){ echo "selected"; } ?>>M</option>
                    <option value="S" <?php if($item['size'] == "S"){ echo "selected"; } ?>>S</option>
                    <option value="L" <?php if($item['size'] == "L"){ echo "selected"; } ?>>L</option>
                    <option value="XL" <?php if($item['size'] == "XL"){ echo "selected"; } ?>>XL</option>
                </select>
            </div>
        </div>
        <!-- End Size Field -->

        <!-- Start Color Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-6 control-label">Color:</label>
            <div class="col-sm-6">
                <select class="form-control" name='color'>
                    <option value="Black" <?php if($item['color'] == "Black"){ echo "selected"; } ?>>Black</option>
                    <option value="White" <?php if($item['color'] == "White"){ echo "selected"; } ?>>White</option>
                    <option value="Blue" <?php if($item['color'] == "Blue"){ echo "selected"; } ?>>Blue</option>
                </select>
            </div>
        </div>
        <!-- End Color Field -->

        <!-- Start Number Field -->
        <div class="form-group form-group-lg">
            <label class="col-sm-6 control-label">Amount:</label>
            <div class="col-sm-6">
                <select class="form-control" name='nums'>
                    <option value="1" selected>1</option>
<?php
                    if($item['nums'] == 2 ){ 
?>
                    <option value="2">2</option>
<?php
                    }elseif($item['nums'] == 3){ ?>
                    <option value="2">2</option>
                    <option value="3">3</option>

<?php
                    }
?>
                </select>
            </div>
        </div>
        <!-- End Number Field -->

		<!-- Start 	Submit Field -->
        <div class="order-confirm">
        <input type="submit" value="Confirm" name="submit">
        </div>
		<!-- End Submit Field -->

    </form>
</div>

<?php

if($_SERVER['REQUEST_METHOD'] == "POST"){
    echo $_POST['name'];
}
//  End Bill Details Field 

//  Start Store Page
}else{
    if(!empty($_SESSION['cart'])){
        ?>
        <div style="display:flex;justify-content:center;">
        <a href="cart.php" style="text-decoration:none;"><button style="width: 100%;">View Carts</button></a>
        </div>
        <?php 
    }
$stmt = $con->prepare("SELECT * FROM items");
$stmt->execute();
$items = $stmt->fetchAll();
    foreach($items as $item){
?>
<div class="parent">
<div class="card">
<img src="../admin/uploads/images/<?php echo $item['image']; ?>" class="card-img-top img-responsive img-thumbnail center-block" style="height: 200px;">
    <div class="card-body">
    <h5 class="card-title"><a href="store.php?id=<?php echo $item['id']; ?>"><?php echo $item['name']; ?></a></h5>
    <p class="card-text" style="font-weight: bold;"><?php echo $item['description']; ?></p>
    <span class="date"><?php echo $item['date'] ?></span>
    <span class="price"><?php echo number_format($item['price'],2) . " EGP"; ?></span>
    <div class="buy" style="color: white;">
    <a href="store.php?order=<?php echo $item['name']; ?>" class="btn">Buy Now</a>
    </div>
</div>
</div>
</div>
<?php

}
}
// End Store Page

