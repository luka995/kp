<?php
namespace App\Classes;

use Exception;

/**
 *
 * @author Luka
 */
class EmailValidator extends BaseValidator {
    
    /**
     * Validate the given email value.
     *
     * @param mixed $value
     * @return bool
     * @throws Exception
     */
    public function validate(mixed $value): bool {
        
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format!');            
        }

        $result = $this->db->getByCol('user', 'email', $value);

        if ($result) {
            throw new Exception('Email exists!');
        }
        
        return true;
    }
}
