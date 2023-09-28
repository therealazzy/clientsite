<?php

session_start();

// Unset change user details sessions.
unset($_SESSION['user-id']);
unset($_SESSION['user-email']);
unset($_SESSION['user-phone']);


require_once('Models/UserDataSet.php');

$view = new stdClass();
$view->pageTitle = 'My Profile';

// If the user is not logged in redirect back to the homepage.
$view->redirect = False;
if (!isset($_SESSION['email'])) {
    $view->redirect = True;
}
else {
    // Fetch the logged in user's id.
    require_once('Models/UserDataSet.php');
    $userDataSet = new UserDataSet();
    $view->userDataSet = $userDataSet->fetchUserWithEmail($_SESSION['email']);
    
    $data = array('name' => $view->userDataSet->getFullName(), 'email' => $view->userDataSet->getEmail(), 'phone' => $view->userDataSet->getPhoneNumber(), 'company' => $view->userDataSet->getCompany());

    // Send the user's details to the view for this page.
    $view->userID = $view->userDataSet->getID();
    $view->userEmail = $view->userDataSet->getEmail();
    $view->userPhone = $view->userDataSet->getPhoneNumber();
}


require_once('Views/Profile.phtml');