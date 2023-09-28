<?php
// So the session variables are available to unset.
session_start();

// Log the user out.
unset($_SESSION['email']);
unset($_SESSION['password']);
// Unset change user details sessions.
unset($_SESSION['user-id']);
unset($_SESSION['user-email']);
unset($_SESSION['user-phone']);

// Create a view for the logout page.
$view = new stdClass();

// Redirect to the home page.
$view->redirect = True;

require_once('Views/logout.phtml');
