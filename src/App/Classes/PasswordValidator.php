<?php
namespace App\Classes;

use Exception;

/**
 *
 * @author Luka
 */
class PasswordValidator extends BaseValidator {
    
    /**
     * Validate the given password.
     *
     * @param string $value
     * @param string $value2
     * @return bool
     * @throws Exception
     */
    public function validate(string $value, string $value2): bool
    {
        $res = '';
        if (empty($value)) {
            $res .= 'Password cannot be empty. ';
        }
    
        if (mb_strlen($value) < 8) {
            $res .= 'Password is too short. ';
        }

        if ($value != $value2) {
            $res .= 'Passwords do not match. ';           
        }
        
        //works with php8.0+
        return $res ? throw new Exception($res) : true;       
    }
}

