<?php
/**
 * Secure Mailer Utility using PHPMailer
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/phpmailer/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../vendor/phpmailer/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/PHPMailer-master/src/SMTP.php';

/**
 * Send a secure email
 * 
 * @param string $to Recipient email
 * @param string $subject Email subject
 * @param string $message Email body
 * @param string $fromEmail Sender email
 * @param string $fromName Sender name
 * @return bool
 */
function sendSecureEmail(string $to, string $subject, string $message, string $fromEmail, string $fromName): bool {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isMail(); // Using PHP mail() but via PHPMailer for secure header handling
        $mail->CharSet = 'UTF-8';

        // Recipients
        $mail->setFrom('noreply@robicodes.xyz', 'Portfolio System');
        $mail->addAddress($to);
        $mail->addReplyTo($fromEmail, $fromName);

        // Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = "New message from: $fromName ($fromEmail)\n\n" . $message;

        $mail->send();
        securityLog("Email sent successfully using PHPMailer to $to", "info");
        return true;
    } catch (Exception $e) {
        securityLog("PHPMailer Error: " . $mail->ErrorInfo, "danger");
        return false;
    }
}
