<?php

namespace App\Classes;

use Exception;

class User 
{
    /**
     * Create a new user.
     *
     * @param string $email
     * @param string $password
     * @return int
     * @throws Exception
     */
    public function create(string $email, string $password): int
    {
        $db = Database::getInstance();

        $data = [
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        $insertId = $db->insert('user', $data, 'posted = NOW()');

        if ($insertId == 0) {            
            throw new Exception('Failed to create user.');
        }

        return $insertId;
    }
}
