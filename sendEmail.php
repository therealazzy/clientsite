<?php
use PHPMailer\PHPMailer\PHPMailer;

require_once 'phpmailer/Exception.php';
require_once 'phpmailer/PHPMailer.php';
require_once 'phpmailer/SMTP.php';

$mail = new PHPMailer(true);

$alert = '';

if(isset($_POST['submit'])){
    $proName = $_POST['project-Name'];
    $cusName = $_POST['customer-Name'];
    $desc = $_POST['description'];
    $budgRange = $_POST['budget-range'];
    $deadln = $_POST['deadline'];
    $proMan = $_POST['project-manager'];

    $conEmail = $_POST['contact-email'];
    $conNum = $_POST['contact-number'];

    try{
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'krmproject.mail@gmail.com';
        $mail->Password = 'group21B';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = '587';

        $mail->setFrom('krmproject.mail@hmail.com');
        $mail->addAddress('p.j.hunter@edu.salford.ac.uk');

        $mail->isHTML(true);
        $mail->Subject = 'A New Project Has Been Submitted';
        $mail->Body = "<h3> New Project </h3> <br> <p>  Project Name : $proName <br> Customer Name : $cusName <br> Description : $desc <br> Budget Range : $budgRange <br> Deadline : $deadln <br> Project Manager : $proMan <br> Contact Email : $conEmail <br> Contact Number : $conNum </p> ";

        $mail->send();
        $alert = '<div class="alert-success">
                 <span>Message Sent! Thank you for contacting us.</span>
                </div>';
    } catch (Exception $e){
        $alert = '<div class="alert-error">
                <span>'.$e->getMessage().'</span>
              </div>';
    }
}


