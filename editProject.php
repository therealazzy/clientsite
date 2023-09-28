<?php

use PHPMailer\PHPMailer\PHPMailer;

session_start();
require_once('Models/ProjectDataSet.php');


// Create a view for the form page.
$view = new stdClass();
$view->pageTitle = 'Edit Project';


// If the user is not logged in or there is no projectID in the GET variable redirect back to the project history page.
$view->redirect = False;
if (!isset($_SESSION['email'])) {
    $view->redirect = True;
} else {
    // Fetch the logged in user's id and details so other uses can't edit each other's projects.
    require_once('Models/UserDataSet.php');
    $userDataSet = new UserDataSet();
    $view->userDataSet = $userDataSet->fetchUserWithEmail($_SESSION['email']);
    $userID = $view->userDataSet->getID();

    if (true) {
        if (isset($_POST['submit'])) {
            // New project details.
            $projectName = $_POST['project-Name'];
            $customerName = $_POST['customer-Name'];
            $description = $_POST['description'];
            $budgetRange = $_POST['budget-range'];
            $deadline = $_POST['deadline'];
            $projectManager = $_POST['project-manager'];
            $contactEmail = $_POST['contact-email'];
            $contactNumber = $_POST['contact-number'];
            $projectID = $_POST['project-id'];

            // Non-working mail code a new printSend function in ProjectDataSet.php is required to send an update email
/**
            require_once 'phpmailer/Exception.php';
            require_once 'phpmailer/PHPMailer.php';
            require_once 'phpmailer/SMTP.php';

            $mail = new PHPMailer(true);

            $alert = '';

            try {
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
                $mail->Subject = 'A Project has Been Edited';
                $mail->Body = "<h3> New Project </h3> <br> <p> Project ID : $projectID <br> Project Name : $projectName <br> Customer Name : $customerName <br> Description : $description <br> Budget Range : $budgetRange <br> Deadline : $deadline <br> Project Manager : $projectManager <br> Contact Email : $contactEmail <br> Contact Number : $contactNumber </p> ";

                $mail->send();
                $alert = '<div class="alert-success">
                 <span>Message Sent! Thank you for contacting us.</span>
                </div>';
            } catch (Exception $e) {
                $alert = '<div class="alert-error">
                <span>' . $e->getMessage() . '</span>
              </div>';
            }
            // UPDATE EMAIL CODE TO BE INSERTED HERE.
            //$view->submissionMessage = $ADS->printSendUpdate($projectName, $customerName, $description, $budgetRange, $deadline, $projectManager, $email, $phone);
**/
            // Update the project row in the database.
            $ADS = new ProjectDataSet();
            $ADS->updateProject($projectName, $customerName, $description, $budgetRange, $deadline, $projectManager, $projectID);

            $view->redirect = True;
        }
    }

}

require_once('Views/editProject.phtml');
