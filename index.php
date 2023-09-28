<?php
// So we can check whether the user is logged in.
session_start();

$view = new stdClass();
$view->pageTitle = 'Homepage';
$view->text="";

if(isset($_POST['sendMessageButton'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $message = $_POST['message'];

    $mailTo = "davidchiu71@gmail.com";
    $headers = "From: ".$email;
    $txt ="You have recieved an e-mail from ".$name.".\n\n".$message;
    mail($mailTo, $email ,$txt , $headers);
}
require_once('Views/index.phtml');