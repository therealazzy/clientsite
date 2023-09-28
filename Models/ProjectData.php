<?php

class ProjectData {

    protected $projectID, $projectName, $customerName, $description, $budgetRange, $deadline, $projectManager, $userID;

    public function __construct($dbRow) {
        $this->projectID = $dbRow['id'];
        $this->projectName = $dbRow['projectName'];
        $this->customerName = $dbRow['customerName'];
        $this->description = $dbRow['description'];
        $this->budgetRange = $dbRow['budget'];
        $this->deadline = $dbRow['deadline'];
        $this->projectManager = $dbRow['projectManager'];
        $this->userID = $dbRow['userID'];
    }

    // Get methods for each project data variable.

    public function getID() {
        return $this->projectID;
    }


    public function getProjectName() {
        return $this->projectName;
    }


    public function getCustomerName() {
        return $this->customerName;
    }


    public function getDescription() {
        return $this->description;
    }


    public function getBudgetRange() {
        return $this->budgetRange;
    }


    public function getDeadline() {
        return $this->deadline;
    }


    public function getProjectManager() {
        return $this->projectManager;
    }


    public function getUserID() {
        return $this->userID;
    }

}