<?php
// PHPMailer imports
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load configuration
$config = require_once 'email-config.php';

// Set content type for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get form data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$honeypot = isset($_POST['website']) ? trim($_POST['website']) : '';

// Basic spam protection - if honeypot field is filled, it's likely spam
if (!empty($honeypot)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Spam detected. Please try again.']);
    exit;
}

// Validate required fields
$errors = [];
if (empty($name)) {
    $errors[] = 'Name is required';
}
if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
}
if (empty($message)) {
    $errors[] = 'Message is required';
}

// Return validation errors
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $config['messages']['validation_error']]);
    exit;
}

// Sanitize input data
$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$phone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

// Create PHPMailer instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = $config['smtp']['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $config['smtp']['username'];
    $mail->Password = $config['smtp']['password'];
    $mail->SMTPSecure = $config['smtp']['encryption'];
    $mail->Port = $config['smtp']['port'];

    // Recipients
    $mail->setFrom($config['from_email'], 'Immobilien Ghumman');
    $mail->addAddress($config['recipient_email']);
    $mail->addReplyTo($email, $name);

    // Content
    $mail->isHTML(true);
    $mail->Subject = $config['subject'];
    
    // Create HTML email body
    $mail->Body = "
        <h2>New Contact Request from Website</h2>
        <hr>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Phone:</strong> " . ($phone ? $phone : 'Not provided') . "</p>
        <p><strong>Message:</strong></p>
        <p>{$message}</p>
        <hr>
        <p><small>Sent from: " . $_SERVER['HTTP_HOST'] . "<br>
        Date: " . date('Y-m-d H:i:s') . "<br>
        IP Address: " . $_SERVER['REMOTE_ADDR'] . "</small></p>";

    // Plain text version
    $mail->AltBody = "New Contact Request from Website\n" .
                     "=====================================\n\n" .
                     "Name: " . $name . "\n" .
                     "Email: " . $email . "\n" .
                     "Phone: " . ($phone ? $phone : 'Not provided') . "\n" .
                     "Message:\n" . $message . "\n\n" .
                     "=====================================\n" .
                     "Sent from: " . $_SERVER['HTTP_HOST'] . "\n" .
                     "Date: " . date('Y-m-d H:i:s') . "\n" .
                     "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";

    // Send the email
    $mail->send();

    // Send auto-response if enabled
    if ($config['auto_response']['enabled']) {
        $autoResponse = new PHPMailer(true);
        
        // Auto-response server settings
        $autoResponse->isSMTP();
        $autoResponse->Host = $config['smtp']['host'];
        $autoResponse->SMTPAuth = true;
        $autoResponse->Username = $config['smtp']['username'];
        $autoResponse->Password = $config['smtp']['password'];
        $autoResponse->SMTPSecure = $config['smtp']['encryption'];
        $autoResponse->Port = $config['smtp']['port'];

        // Auto-response recipients
        $autoResponse->setFrom($config['auto_response']['from_email'], 'Immobilien Ghumman');
        $autoResponse->addAddress($email, $name);

        // Auto-response content
        $autoResponse->isHTML(true);
        $autoResponse->Subject = $config['auto_response']['subject'];
        
        $autoResponse->Body = "
            <h2>Thank you for contacting Immobilien Ghumman</h2>
            <p>Dear {$name},</p>
            <p>Thank you for your message. We have received your contact request and will get back to you within 24 hours.</p>
            <p><strong>Your message:</strong></p>
            <p>{$message}</p>
            <p>Best regards,<br>
            Immobilien Ghumman Team</p>
            <hr>
            <p><small>This is an automated response. Please do not reply to this email.</small></p>";

        $autoResponse->AltBody = "Dear " . $name . ",\n\n" .
                                "Thank you for your message. We have received your contact request and will get back to you within 24 hours.\n\n" .
                                "Your message:\n" . $message . "\n\n" .
                                "Best regards,\n" .
                                "Immobilien Ghumman Team\n\n" .
                                "---\n" .
                                "This is an automated response. Please do not reply to this email.";

        // Send auto-response (optional - will not affect main email success)
        try {
            $autoResponse->send();
        } catch (Exception $e) {
            // Log auto-response error but don't fail the main process
            error_log("Auto-response failed: " . $e->getMessage());
        }
    }

    // Return success response
    echo json_encode([
        'success' => true, 
        'message' => $config['messages']['success']
    ]);

} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => $config['messages']['error'] . " Error: " . $e->getMessage()
    ]);
}
?>