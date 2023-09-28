<?php

session_start();
require_once('Models/ProjectDataSet.php');

// Unset change user details sessions.
unset($_SESSION['user-id']);
unset($_SESSION['user-email']);
unset($_SESSION['user-phone']);


// Create a view for the form page.
$view = new stdClass();
$view->pageTitle = 'Project History';


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
    $userID = $view->userDataSet->getID();


    // Retrieve the current page number from GET.
    $pageNumber = 1;
    if (isset($_GET['pageNumber'])) {
        $pageNumber = $_GET['pageNumber'];
    }

    // Transfer the current page number to the view.
    $view->currentPageNumber = $pageNumber;

    // There are ten projects per page.
    $recordsPerPage = 10;
    // Calculate the offset used in the sql query.
    $offset = ($pageNumber - 1) * $recordsPerPage;

    // Fetch the specified ten of the projects and transfer this data set to the view.
    $projectDataSet = new ProjectDataSet();
    $view->projectDataSet = $projectDataSet->fetchProjects($userID, $offset, $recordsPerPage);

    // Fetch the total amount of projects made by the user.
    $totalProjects = $projectDataSet->fetchNumberOfProjects($userID);
    // And calculate how many pages are required to display them all.
    $view->totalPages = ceil($totalProjects[0] / $recordsPerPage);

    // If this is not the final page or higher.
    if ($pageNumber < $view->totalPages) {
        // Calculate the next page number and do not disable the next and final page options in the view.
        $view->nextPageNumber = $pageNumber + 1;
        $view->disableNext = False;
        $view->disableFinal = False;
    }
    else {
        // Otherwise disable the page options.
        $view->nextPageNumber = $pageNumber;
        $view->disableNext = True;
        $view->disableFinal = True;
    }

    // If this is not the first page or lower.
    if ($pageNumber > 1) {
        // Calculate the previous page number and do not disable the previous and first page options in the view.
        $view->previousPageNumber = $pageNumber - 1;
        $view->disablePrevious = False;
        $view->disableFirst = False;
    }
    else {
        // Otherwise disable the page options.
        $view->previousPageNumber = 1;
        $view->disablePrevious = True;
        $view->disableFirst = True;
    }

    // If it is the second page remove the first page option as the previous page option will have this covered.
    if ($pageNumber == 2) {
        $view->disableFirst = True;
    }

    // If it is the second to last page remove the final page option as the next page option will have this covered.
    if ($pageNumber == $view->totalPages - 1) {
        $view->disableFinal = True;
    }


    // Transfer each project's details to the view.
    $view->numberOfProjects = 0;
    if(isset($view->projectDataSet)) {
        foreach ($view->projectDataSet as $fetchedProject) {
            $view->projects[$view->numberOfProjects]["projectID"] = $fetchedProject->getID();
            $view->projects[$view->numberOfProjects]["projectName"] = $fetchedProject->getProjectName();
            $view->projects[$view->numberOfProjects]["customerName"] = $fetchedProject->getCustomerName();
            $view->projects[$view->numberOfProjects]["description"] = $fetchedProject->getDescription();
            $view->projects[$view->numberOfProjects]["budget"] = $fetchedProject->getBudgetRange();
            $view->projects[$view->numberOfProjects]["deadline"] = $fetchedProject->getDeadline();
            $view->projects[$view->numberOfProjects]["projectManager"] = $fetchedProject->getProjectManager();

            $view->numberOfProjects++;
        }
    }
}


require_once('Views/ProjectHistory.phtml');
