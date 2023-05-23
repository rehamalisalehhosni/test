<?php
session_start();
$pagetitle = "Dashboard";
    include "ini.php";
?>
<a href="../user/home.php">front_end-user</a>
        <h1 class="text-center">Dashboard</h1>
        </header>
        <main>
        <!-- Start Total Members Field -->
        <div class="widget total-member">
            <div>Total Members</div>
            <h1><a href="members.php">
<?php
            $stmt = $con->prepare("SELECT * FROM members WHERE groupid = 0");
            $stmt->execute();
            $members= $stmt->rowCount();
            echo $members;
?>
            </a></h1>
        </div>
        <!-- End Total Members Field -->

        <!-- Start Pending Members Field -->
        <div class="widget pending-member">
            <div>Pending Members</div>
            <h1><a href="members.php?do=Pending">
<?php
            $stmt = $con->prepare("SELECT * FROM members WHERE status = 0");
            $stmt->execute();
            $pending= $stmt->rowCount();
            echo $pending;
?>
            </a></h1>
        </div>
        <!-- End Pending Members Field -->

        <!-- Start Total Items Field -->
        <div class="widget total-item">
            <div>Total Items</div>
            <h1><a href="items.php">
<?php
            $stmt = $con->prepare("SELECT * FROM items");
            $stmt->execute();
            $items= $stmt->rowCount();
            echo $items;
?>
            </a></h1>
        </div>
        <!-- End Total Items Field -->

        <!-- Start Total Comments Field -->
        <div class="widget total-comment">
            <div>Total Comments</div>
            <h1><a href="comments.php">
<?php
            $stmt = $con->prepare("SELECT * FROM comments");
            $stmt->execute();
            $comments= $stmt->rowCount();
            echo $comments;
?>
            </a></h1>
        </div>
        <!-- End Total Comments Field -->

        <!-- Start Total Oreders Field -->
        <div class="widget total-comment">
        <div>Total Oreders</div>
        <h1><a href="#">1</a></h1>
    </div>
        <!-- End Total Oreders Field -->

<?php
    



include "footer.php";

?>