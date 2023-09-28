<?php

session_start();
require_once('Models/ProjectDataSet.php');

// Unset change user details sessions.
unset($_SESSION['user-id']);
unset($_SESSION['user-email']);
unset($_SESSION['user-phone']);


// Create a view for the form page.
$view = new stdClass();
$view->pageTitle = 'Project Submission Form';


if(isset($_POST['submit'])) {
    // Fetch each project detail from the form.
    $projectName = $_POST['project-Name'];
    $customerName = $_POST['customer-Name'];
    $description = $_POST['description'];
    $budgetRange = $_POST['budget-range'];
    $deadline = $_POST['deadline'];
    $projectManager = $_POST['project-manager'];


    $ADS = new ProjectDataSet();
    // If the user is not logged in fetch the extra fields only displayed if the user is not logged in from the form.
    if (!isset($_SESSION['email'])) {
        $contactEmail = $_POST['contact-email'];
        $contactNumber = $_POST['contact-number'];

        $view->submissionMessage = $ADS->printSend($projectName, $customerName, $description, $budgetRange, $deadline, $projectManager, $contactEmail, $contactNumber);
    }
    // Only insert into the database if the user is logged in.
    else {
        require_once('Models/UserDataSet.php');
        // Fetch the logged in user's id.
        $userDataSet = new UserDataSet();
        $view->userDataSet = $userDataSet->fetchUserWithEmail($_SESSION['email']);

        // Get the extra unlogged in user fields from the user's profile instead.
        $userID = $view->userDataSet->getID();
        $userEmail = $view->userDataSet->getEmail();
        $userPhoneNumber = $view->userDataSet->getPhoneNumber();

        $view->submissionMessage = $ADS->printSend($projectName, $customerName, $description, $budgetRange, $deadline, $projectManager, $userEmail, $userPhoneNumber);

        // Insert project into the database.
        $view->ADS = $ADS->insertProj($projectName, $customerName, $description, $budgetRange, $deadline, $projectManager, $userID);
    }
}

require_once('Views/ProjectForm.phtml');
