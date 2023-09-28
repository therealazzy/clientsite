<?php

class UserData {
    
    protected $_id, $_email, $_password, $_company, $_fullName, $_phoneNumber;

    // Construct one row of data from the user table.
    public function __construct($dbRow) {
        $this->_id = $dbRow['id'];
        $this->_email = $dbRow['email'];
        $this->_password = $dbRow['password'];
        $this->_company = $dbRow['company'];
        $this->_fullName = $dbRow['fullName'];
        $this->_phoneNumber = $dbRow['phoneNumber'];
    }

    // Get methods for each variable.

    public function getID() {
        return $this->_id;
    }

    public function getEmail() {
        return $this->_email;
    }

    public function getPassword() {
        return $this->_password;
    }

    public function getCompany() {
        return $this->_company;
    }

    public function getFullName() {
        return $this->_fullName;
    }

    public function getPhoneNumber() {
        return $this->_phoneNumber;
    }

}