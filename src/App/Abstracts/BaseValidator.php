<?php

namespace App\Abstracts;

/**
 *
 * @author Luka
 */
abstract class BaseValidator implements Validatable {
    
    protected Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }
}