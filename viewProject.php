<?php

session_start();
require_once('Models/ProjectDataSet.php');

// Unset change user details sessions.
unset($_SESSION['user-id']);
unset($_SESSION['user-email']);
unset($_SESSION['user-phone']);


// Create a view for the form page.
$view = new stdClass();
$view->pageTitle = 'Edit Project';


// If the user is not logged in or there is no projectID in the GET variable redirect back to the project history page.
$view->redirect = False;
if (!isset($_SESSION['email']) || !isset($_GET['projectID'])) {
    $view->redirect = True;
}
else {
    // Fetch the logged in user's id and details so other uses can't edit each other's projects.
    require_once('Models/UserDataSet.php');
    $userDataSet = new UserDataSet();
    $view->userDataSet = $userDataSet->fetchUserWithEmail($_SESSION['email']);
    $userID = $view->userDataSet->getID();


    // Fetch the project ID from the GET hyperlink.
    $view->projectID = $_GET['projectID'];


    $projectDataSet = new ProjectDataSet();
    $view->projectDataSet = $projectDataSet->fetchProject($view->projectID);



    // If the project with this ID exists.
    if (isset($view->projectDataSet)) {
        // Transfer the current project's details to the view.
        $view->projectName = $view->projectDataSet[0]->getProjectName();
        $view->customerName = $view->projectDataSet[0]->getCustomerName();
        $view->description = $view->projectDataSet[0]->getDescription();
        $view->budget = $view->projectDataSet[0]->getBudgetRange();
        $view->deadline = $view->projectDataSet[0]->getDeadline();
        $view->projectManager = $view->projectDataSet[0]->getProjectManager();
        $view->email = $view->userDataSet->getEmail();
        $view->phone = $view->userDataSet->getPhoneNumber();

        // So users can't edit each others project applications.
        if ($userID != $view->projectDataSet[0]->getUserID()) {
            $view->redirect = True;
        }
    }
}


require_once('Views/viewProject.phtml');
