<?php

function gettitle(){
    
    global $pagetitle;

    if(isset($pagetitle)){

        echo $pagetitle;

    }else{

        echo "default";

    }

}

function checkMember($select , $from , $value){

    global $con;

    $stmt = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
    $stmt->execute(array($value));
    $count = $stmt->rowCount();     
    return $count;

}

function redirect($themsg , $url = null , $seconds = 5){

    if($url == null){

        $url = "dash.php";
        $link = "HomePage";

    }
    elseif(isset($_SERVER['HTTP_REFERER'])){

        $url = $_SERVER['HTTP_REFERER'];
        $link = "Previous Page";
        
    }
    echo $themsg;
    echo "<div class = 'alert alert-info'>You Will Be Redirected to $link After $seconds Seconds.</div>";
    header("refresh:$seconds;url=$url");
}