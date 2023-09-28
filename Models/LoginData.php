<?php

class LoginData {
    
    protected $_email, $_password;

    // Construct one row of data from the user table containing only the email and password each row.
    public function __construct($dbRow) {
        $this->_email = $dbRow['email'];
        $this->_password = $dbRow['password'];
    }

    // Get methods for each variable.

    public function getEmail() {
        return $this->_email;
    }

    public function getPassword() {
        return $this->_password;
    }

}