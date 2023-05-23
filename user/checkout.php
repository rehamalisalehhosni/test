<?php
session_start();
$pagetitle = "Checkout";
include "ini.php";
if(isset($_SESSION['cart'])){
?>
<h1 class = "text-center check">Checkout</h1>
<h3>Your Order</h3>
<?php
}