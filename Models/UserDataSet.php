<?php

require_once 'Models/Database.php';
require_once 'Models/LoginData.php';
require_once 'Models/UserData.php';

class UserDataSet {

    protected $_dbConnection, $_dbInstance;
    
    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();
    }

    // Fetch each user's email and password.
    public function fetchLoginData() {
        $sqlQuery = 'SELECT email, password FROM user';

        $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];
        while ($row = $statement->fetch()) {
            $dataSet[] = new LoginData($row);
        }
        return $dataSet;
    }

    // Add a new user to the user table.
    public function createUser($email, $password, $company, $fullName, $phoneNumber) {
        $sqlQuery = "INSERT INTO user (email, password, company, fullName, phoneNumber) VALUES (?, ?, ?, ?, ?)";

        $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(array($email, $password, $company, $fullName, $phoneNumber)); // execute the PDO statement
    }

    // Fetch a row from the user table given their email.
    public function fetchUserWithEmail($requestedEmail) {
        $sqlQuery = "SELECT * FROM user WHERE email = ?";

        $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(array($requestedEmail)); // execute the PDO statement

        return new UserData($statement->fetch());
    }

    // Update a user's details.
    public function updateUser($email, $password, $phone, $id){
        $sqlQuery = "UPDATE user SET email = ?, password = ?, phoneNumber = ? WHERE id = ?";

        $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
        $statement->execute(array($email, $password, $phone, $id)); // execute the PDO statement
    }

}
