<?php

namespace App\Classes;

use Exception;

class UserLog 
{
    /**
     * Log user action.
     *
     * @param int $userId
     * @param string $action
     * @return int
     * @throws Exception
    */
    public function log(int $userId, string $action): int 
    {
        $db = Database::getInstance();

        $data = [
            'action' => $action,
            'user_id' => $userId,
        ];

        $insertId = $db->insert('user_log', $data, 'log_time = NOW()');
        
        if ($insertId == 0) {            
            throw new Exception('Failed to log user action.');
        }

        return $insertId;
    }
}
