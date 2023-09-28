<?php
// So we can check whether the user is logged in.
session_start();

// Create a view for the register page.
$view = new stdClass();

// If the user is not already logged in redirect back to the homepage.
$view->redirect = False;
if (!isset($_SESSION['email'])) {
    $view->redirect = True;
}
else {
    require_once('Models/UserDataSet.php');

    // Current user details from Profile.php
    if (isset($_POST['submit'])) {
        $view->userID = $_POST['user-id'];
        $view->userEmail = $_POST['user-email'];
        $view->userPhone = $_POST['user-phone'];
        $_SESSION['user-id'] = $_POST['user-id'];
        $_SESSION['user-email'] = $_POST['user-email'];
        $_SESSION['user-phone'] = $_POST['user-phone'];
    }

    // If the user details form has been submitted.
    if (isset($_POST['update'])) {
        $newEmail = $_POST['email'];
        $newPhone = $_POST['phone-number'];

        // Encrypt the typed in password.
        $newPassword = password_hash($_POST['new-password'], PASSWORD_DEFAULT);


        // Check whether any of the fields were left empty.
        if (empty($newEmail) || empty($newPhone) || empty($_POST['new-password']) || empty($_POST['confirm-new-password']) || empty($_POST['password'])) {
            $view->result = 'One or more of the fields were left empty!';
        } else {
            if ($_POST['new-password'] != $_POST['confirm-new-password']) {
                $view->result = 'The new passwords do not match!';
            } else {
                require_once('Models/UserDataSet.php');

                // Fetch all the login data so the typed in email can be checked to see if it already exists in the table.
                $userDataSet = new UserDataSet();
                $view->userDataSet = $userDataSet->fetchLoginData();

                $userExists = 0;

                // Check whether the typed in email already exists in the user table.
                foreach ($view->userDataSet as $loginData) {
                    if ($newEmail == $loginData->getEmail() && $newEmail != $_SESSION['user-email']) {
                        $userExists = 1;
                    }
                }

                // Inform the view that the email already exists.
                if ($userExists == 1) {
                    $view->result = 'An account with this email already exists!';
                } else {
                    // Otherwise, check that the correct password has been typed in.
                    // Check each email and password pair to see if the user's input matches any of them.
                    $loggedIn = 0;
                    foreach ($view->userDataSet as $loginData) {
                        // If the typed in email and password matches one of the pairs.
                        if ($_SESSION['user-email'] == $loginData->getEmail() && password_verify($_POST['password'], $loginData->getPassword())) {
                            // Create a session to show the user is logged in.
                            $_SESSION['email'] = $newEmail;
                            $_SESSION['password'] = $newPassword;

                            $loggedIn = 1;
                            // And redirect the user to the home page.
                            $view->redirect = True;
                        }
                    }
                    // If the user input doesn't match a pair, inform the view.
                    if ($loggedIn == 0) {
                        $view->result = 'Incorrect email or password!';
                    }
                    else {
                        // Otherwise, update the user's details.
                        $userDataSet->updateUser($newEmail, $newPassword, $newPhone, $_SESSION['user-id']);

                        // And redirect to the profile page.
                        $view->redirect = True;
                    }
                }
            }
        }
    }
}


require_once('Views/Update.phtml');