<?php
/**
 * Secure Mailer Utility
 * 
 * Provides a hardened wrapper for email delivery with strict header injection protection.
 */

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
    // 1. Strict Sanitization
    $to = filter_var($to, FILTER_SANITIZE_EMAIL);
    $fromEmail = filter_var($fromEmail, FILTER_SANITIZE_EMAIL);
    
    // Remove any newlines from headers to prevent injection
    $subject = str_replace(["\r", "\n"], '', $subject);
    $fromName = str_replace(["\r", "\n"], '', $fromName);
    
    // 2. Validation
    if (!filter_var($to, FILTER_VALIDATE_EMAIL) || !filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
        securityLog("Email validation failed for: $fromEmail", "warning");
        return false;
    }

    // 3. Construct Headers securely
    // We use a fixed structure and avoid passing raw user input into the header string
    $headers = [
        'From' => "$fromName <$fromEmail>",
        'Reply-To' => $fromEmail,
        'X-Mailer' => 'PHP/' . phpversion(),
        'MIME-Version' => '1.0',
        'Content-Type' => 'text/plain; charset=UTF-8'
    ];

    $headerString = '';
    foreach ($headers as $key => $value) {
        $headerString .= "$key: $value\r\n";
    }

    // 4. Send and Log
    try {
        $result = mail($to, $subject, $message, $headerString);
        if ($result) {
            securityLog("Email sent successfully from $fromEmail to $to", "info");
        } else {
            securityLog("Failed to send email from $fromEmail", "error");
        }
        return $result;
    } catch (Exception $e) {
        securityLog("Mailer Exception: " . $e->getMessage(), "danger");
        return false;
    }
}
