<?php
namespace App\Classes;

use MinFraud;
use MinFraud\Request;
use Exception;

/**
 *
 * @author Luka
 */
class MaxMind implements MaxMindInterface {
    
    /**
     * Verify the given email and IP address.
     *
     * @param string $email
     * @param string $ip
     * @return bool
     * @throws Exception
     */
    public function verify(string $email, string $ip): bool {
        
        $config = include __DIR__ . '/../config.php';

        $client = new MinFraud($config['maxmind']['account_id'], $config['maxmind']['license_key']);
        $request = new Request([
            'device' => [
                'ip_address' => $ip,
            ],
            'email' => [
                'address' => $email,
            ],
        ]);

        $response = $client->score($request);

        if ($response->riskScore > 80) {
            throw new Exception('User is maybe fraud.');
        }

        return true;
    }
}
