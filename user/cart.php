<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
$pagetitle = "Cart";
include "ini.php";

if (!empty($_SESSION['cart'])) {
    ?>
    <h1 class="text-center">My Carts</h1>
<table>
		<thead>
			<tr>
				<th>ID</th>
				<th>Img</th>
				<th>Product</th>
				<th>Price</th>
				<th>Color</th>
				<th>Size</th>
				<th>Quantity</th>
				<th>Subtotal</th>
				<th>Control</th>
			</tr>
		</thead>
		<tbody>
            
<?php
$total = 0;
// print_r($_SESSION['cart']);
    foreach ($_SESSION['cart'] as $key => $value) {

        if (is_array($value)) {
            
                echo "<tr>";
                    echo "<td>" . $value['id'] . "</td>";
                    echo "<td>";
                    $stmt = $con->prepare("SELECT * FROM items WHERE id = ?");
                    $stmt->execute(array($value['id']));
                    $item = $stmt->fetch();
?>
                    <img src="../admin/uploads/images/<?php echo $item['image']; ?>" alt="" class="image-member">
<?php
                    $subtotal = $value['price'] * $value['quantity'];

                    echo "</td>";
                    echo "<td>" . $value['name'] . "</td>";
                    echo "<td>" . number_format($value['price'],2) . " EGP</td>";
                    echo "<td>" . $value['color'] . "</td>";
                    echo "<td>" . $value['size'] . "</td>";
                    echo "<td>" . $value['quantity'] . "</td>";
                    echo "<td>" . number_format($subtotal,2) ." EGP</td>";
                    echo "<td>";
                    echo '<a href="cart.php?action=delete&id=' . $value['id'] . '&color=' . $value['color'] . '&size=' . $value['size'] . '" class="btn btn-danger">Delete</a>';

                    echo "</td>";
                echo "</tr>";
        }else{
            switch ($key) {
                case "id":
                    echo "<td>" . $value . "</td>";
                    break;
                case "img":
                    $stmt = $con->prepare("SELECT * FROM items WHERE id = ?");
                    $stmt->execute(array($_SESSION['cart']['id']));
                    $item = $stmt->fetch();
                    echo "<td>";
?>
                    <img src="../admin/uploads/images/<?php echo $item['image']; ?>" alt="" class="image-member">
<?php
                    echo "</td>";
                    break;       
                case "name":
                    echo "<td>" . $value . "</td>";
                    break;     
                case "price":
                    echo "<td>" . number_format($value,2) . " EGP</td>";
                    break;     
                case "color":
                    echo "<td>" . $value . "</td>";
                    break;     
                case "size":
                    echo "<td>" . $value . "</td>";
                    break;     
                case "quantity":
                    echo "<td>" . $value . "</td>";
                    break;                
                    default:
                    echo "<td>" . $_SESSION['cart']['price'] * $_SESSION['cart']['quantity'] ." EGP</td>";
                    echo "<td>";
                    echo '<a href="cart.php?action=delete&id=' . $_SESSION['cart']['id'] . '&color=' . $_SESSION['cart']['color'] . '&size=' . $_SESSION['cart']['size'] . '" class="btn btn-danger del">Delete</a>';
                    echo "</td>";
            }
        }

        $total = $total + $subtotal;

    }
    ?>
    <tr>
        <td colspan="9" class="text-center">Toral Price : <?php if(isset($total)){ echo number_format($total,2); } ?> EGP</td>
    </tr>
</table>
<?php
echo '<a href="checkout.php" class="proceed">Procced To Checkout</a>';


}
if (isset($_SESSION['cart'])) {
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $color = $_GET['color'];
        $size = $_GET['size'];

        foreach ($_SESSION['cart'] as $key => $item) {
            if (is_array($item)) {
                if ($item['id'] === $_GET['id'] && $item['color'] === $color && $item['size'] === $size) {
                    unset($_SESSION['cart'][$key]);
                    header("Refresh:0");

                    // unset($_SESSION['cart'][$key]); // حذف المنتج المحدد من العربة
                }
            }else{
                if ($_SESSION['cart']['id'] === $_GET['id'] && $_SESSION['cart']['color'] === $color && $_SESSION['cart']['size'] === $size) {
                    if($_SESSION['cart']['id'] == $_GET['id']){
                        unset($_SESSION['cart'][$key]);
                        header("Refresh:0");

                    }
                    // unset($_SESSION['cart']); // حذف المنتج المحدد من العربة
                }
            }
        }

    }
}

