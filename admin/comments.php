<?php


session_start();
$pagetitle = "Comments";
include "ini.php";
    $do = '';
    if(isset($_GET['do'])){
        $do = $_GET['do'];
    }else{
		$do = "Manage";
	}

    if($do == 'Manage'){ // Manage Members Page 
	
	$stmt = $con->prepare("SELECT 
                                comments.* , 
                                items.name AS item ,
                                members.name AS member
                            FROM
                                comments
                            INNER JOIN 
                                items
                            ON
                                items.id = comments.item_id
                            INNER JOIN
                                members
                            ON
                                members.id = comments.member_id");
	$stmt->execute();
	$comments = $stmt->fetchAll();
	$count = $stmt->rowCount();
    if(! empty($comments)){
	?>

        <h1 class="text-center">Manage Comments</h1>
        <table>
		<thead>
			<tr class="member-tr">
				<th>ID</th>
				<th>Comment</th>
				<th>Item</th>
				<th>Mmeber</th>
				<th>Date</th>
				<th>Control</th>
			</tr>
		</thead>
		<tbody>
		
				<?php
				foreach($comments as $comment){
					echo "<tr>";
						echo "<td>" . $comment['id'] . "</td>";
						echo "<td>" . $comment['comment'] . "</td>";
						echo "<td>" . $comment['item'] . "</td>";
						echo "<td>" . $comment['member'] . "</td>";
						echo "<td>" . $comment['date'] . "</td>";
						echo "<td>";
						echo "<a class='btn btn-danger del' href='comments.php?do=Delete&commentid=" . $comment['id'] ."'>Delete</a>";
						echo "</td>";
					echo "</tr>";
				} 
        
?>
		</tbody>
		</table>
<?php
    }else{ 
        $themsg = "<div class='alert alert-danger'>There's No Comments To Show</div>";
        redirect($themsg);
    }

    }elseif($do == 'Delete'){ // Delete Items Page 
    
        if(isset($_GET['commentid']) && intval($_GET['commentid'])){
			$commentid = $_GET['commentid'];
            $stmt = $con->prepare("DELETE FROM comments WHERE id = ?");
            $stmt->execute(array($commentid));
            $count = $stmt->rowCount();
            if($count > 0){
                $themsg = "<div class='alert alert-success'>Comment Deleted</div>";
				redirect($themsg);
            }
            
        }else{
            $themsg = "<div class='alert alert-danger'>You Have No Access To This Page</div>";
            redirect($themsg);
        }
    
    
    }
