<?php

require_once('Models/Database.php');
require_once('Models/ProjectData.php');


class ProjectDataSet {
    protected $_dbConnection, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();
    }


    public function insertProj($projectName, $customerName, $description, $budgetRange, $deadline, $projectManager, $userID){
        $sqlQuery = "INSERT INTO project (projectName, customerName, description, budget, deadline, projectManager, userID) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(array($projectName, $customerName, $description, $budgetRange, $deadline, $projectManager, $userID)); // execute the PDO statement
    }

    public function updateProject($projectName, $customerName, $description, $budgetRange, $deadline, $projectManager, $projectID){
        $sqlQuery = "UPDATE project SET projectName = ?, customerName = ?, description = ?, budget = ?, deadline = ?, projectManager = ? WHERE id = ?";

        $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(array($projectName, $customerName, $description, $budgetRange, $deadline, $projectManager, $projectID)); // execute the PDO statement
    }

    // Fetch ten project applications for a specific user depending on the pagination.
    public function fetchProjects($userID, $offset, $recordsPerPage) {
        $sqlQuery = 'SELECT * FROM project WHERE userID = ? ORDER BY id DESC LIMIT ?,?';

        $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement

        // Statements needed to bound manually to stop php from putting single quotes around the offset and records per page.
        $statement->bindParam(1, $userID);
        $statement->bindParam(2, $offset, PDO::PARAM_INT);
        $statement->bindParam(3, $recordsPerPage, PDO::PARAM_INT);

        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new ProjectData($row);
        }
        return $dataSet;
    }

    // Fetch the number of projects submitted by a user.
    public function fetchNumberOfProjects($requestedID) {
        $sqlQuery = "SELECT COUNT(*) FROM project WHERE userID = ?";

        $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(array($requestedID)); // execute the PDO statement

        return $statement->fetch();
    }

    // Fetch a specific project using it's project ID.
    public function fetchProject($projectID) {
        $sqlQuery = 'SELECT * FROM project WHERE id = ?';

        $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(array($projectID)); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new ProjectData($row);
        }
        return $dataSet;
    }

    // Return each project detail as formatted text and send the email containing the project.
    // Currently the mail part of this function is not working.
    public function printSend($projectName, $customerName, $description, $budgetRange, $deadline, $projectManager, $contactEmail, $contactNumber) {
        $msg = $msg = "New Project Submitted;<br>
        Project Name: {$projectName}<br>
        Customer Name: {$customerName}<br>
        Description: {$description}<br>
        Budget Range: {$budgetRange}<br>
        Deadline: {$deadline}<br>
        Project Manager: {$projectManager}<br>
        Contact Email: {$contactEmail}<br>
        Contact Number: {$contactNumber}";
        mail("p.j.hunter@edu.salford.ac.uk", "A New Project has Been Submitted", $msg);
        return $msg;
    }

}