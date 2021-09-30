<?php 
session_start();

if (isset($_POST['OK'])) {
    if (!$_SESSION['cookie']) { 
        $_SESSION['cookie']=1;
    } 
}
else if (isset($_POST['NOK'])) {
    if (!$_SESSION['cookie']) { 
        $_SESSION['cookie']=2;
    } 
}
header("location:".$_POST['page']);
?>