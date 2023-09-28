<?php
// So we can check whether the user is logged in.
session_start();

// Unset change user details sessions.
unset($_SESSION['user-id']);
unset($_SESSION['user-email']);
unset($_SESSION['user-phone']);

// Create a view for the register page.
$view = new stdClass();

$view->redirect = False;
// If the user is already logged in redirect back to the homepage.
if (isset($_SESSION['email'])) {
    $view->redirect = True;
}
else {
    require_once('Models/UserDataSet.php');

    // Fetch all the login data so the typed in email can be checked to see if it already exists in the table.
    $userDataSet = new UserDataSet();
    $view->userDataSet = $userDataSet->fetchLoginData();

    // If the register form has been submitted and the anti-spam field has been left empty.
    if (isset($_POST['submit']) && $_POST['url'] == '') {
        $email = $_POST['email'];
        $company = $_POST['company-name'];
        $fullName = $_POST['full-name'];
        $phoneNumber = $_POST['phone-number'];

        // Encrypt the typed in password.
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Check whether any of the fields were left empty.
        if (empty($email) || empty($password) || empty($_POST['confirm-password']) || empty($company) || empty($fullName) || empty($phoneNumber)) {
            $view->result = 'One or more of the fields were left empty!';
        } else {
            if($_POST['password'] != $_POST['confirm-password']) {
                $view->result = 'The passwords do not match!';
            } else {
                $userExists = 0;

                // Check whether the typed in email already exists in the user table.
                foreach ($view->userDataSet as $loginData) {
                    if ($email == $loginData->getEmail()) {
                        $userExists = 1;
                    }
                }

                // Inform the view that the email already exists.
                if ($userExists == 1) {
                    $view->result = 'An account with this email already exists!';
                } else {
                    // Otherwise, create the new user.
                    $userDataSet->createUser($email, $password, $company, $fullName, $phoneNumber);

                    // Log the new user in.
                    $_SESSION['email'] = $email;
                    $_SESSION['password'] = $password;

                    // And redirect to the home page.
                    $view->redirect = True;
                }
            }
        }
    }
}

require_once('Views/register.phtml');