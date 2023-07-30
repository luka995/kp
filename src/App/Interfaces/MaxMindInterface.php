<?php

namespace App\Interfaces;

/**
 * Interface MaxMindInterface
 *
 * @author Luka
 */
interface MaxMindInterface {
    
    /**
     * Verify the provided email and IP address.
     *
     * @param string $email The email address to verify.
     * @param string $ip The IP address to verify.
     *
     * @return bool Verification result.
     *
     * @throws \Exception Throws an exception if the user is maybe a fraud.
     */
    public function verify(string $email, string $ip): bool;
}
