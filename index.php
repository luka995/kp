<?php

/**
 *
 * @author Luka
 */

use App\Classes\EmailValidator;
use App\Classes\MaxMind;
use App\Classes\PasswordValidator;
use App\Classes\UserLog;
use App\Classes\User;
use App\Http\Request;
use App\Classes\Database;

$emailValidator = new EmailValidator();
$passwordValidator = new PasswordValidator();
$maxMind = new MaxMind();
$user = new User();
$userLog = new UserLog();

$request = new Request();

$email = $request->post('email');
$password = $request->post('password');
$password2 = $request->post('password2');
$ip = $request->clientIp();

try {
    $emailValidator->validate($email);
    $passwordValidator->validate($password, $password2);
    $maxMind->verify($email, $ip);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    exit;
}

try {
    
    $db = Database::getInstance();
    $db->beginTransaction();

    
    $userId = $user->create($email, $password);
    $userLog->log($userId, 'register');
    
    $mailSender = new MailSender('adm@kupujemprodajem.com');
    $mailSender->sendWelcomeEmail($email);
    
    $db->commit();

    $request->setSession('userId', $userId);
    
    echo json_encode(['success' => true, 'userId' => $userId]);
} catch (Exception $e) {
    $db->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}