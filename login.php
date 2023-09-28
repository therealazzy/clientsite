<?php
// So we can check whether the user is logged in.
session_start();

// Unset change user details sessions.
unset($_SESSION['user-id']);
unset($_SESSION['user-email']);
unset($_SESSION['user-phone']);

// Create a view for the register page.
$view = new stdClass();

// If the user is already logged in redirect back to the homepage.
$view->redirect = False;
if (isset($_SESSION['email'])) {
    $view->redirect = True;
}
else {
    require_once('Models/UserDataSet.php');

    // Fetch every email and password pair so the user's login can be checked.
    $userDataSet = new UserDataSet();
    $view->userDataSet = $userDataSet->fetchLoginData();

    // If the login button is clicked.
    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $loggedIn = 0;


        // Check each email and password pair to see if the user's input matches any of them.
        foreach ($view->userDataSet as $loginData) {
            // If the typed in email and password matches one of the pairs.
            if ($email == $loginData->getEmail() && password_verify($password, $loginData->getPassword())) {
                // Create a session to show the user is logged in.
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;

                $loggedIn = 1;
                // And redirect the user to the home page.
                $view->redirect = True;
            }
        }
        // If the user input doesn't match a pair, inform the view.
        if ($loggedIn == 0) {
            $view->result = 'Incorrect email or password!';
        }
    }
}

require_once('Views/login.phtml');