<?php
namespace App\Classes;

use Exception;

/**
 *
 * @author Luka
 */
class MailSender 
{
    private string $adminEmail;

    /**
     * MailSender constructor.
     *
     * @param string $adminEmail
     */
    public function __construct(string $adminEmail)
    {
        $this->adminEmail = $adminEmail;
    }

    /**
     * Send welcome email to the specified address.
     *
     * @param string $email
     * @return bool
     * @throws Exception
     */
    public function sendWelcomeEmail(string $email): bool
    {
        $subject = 'Dobro došli';
        $message = 'Dobro dosli na nas sajt. Potrebno je samo da potvrdite email adresu ...';
        $headers = 'From: ' . $this->adminEmail . "\r\n" .
            'Reply-To: ' . $this->adminEmail . "\r\n";
        
        if (!mail($email, $subject, $message, $headers)) {
            throw new Exception('Mail was not sent successfully');
        }
                
        return true;
    }
}
