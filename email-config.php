<?php
// Email Configuration File
// Update these settings as needed

return [
    // Main recipient email
    'recipient_email' => 'info@immobilienghumman.de',
    
    // From email (should be from your domain)
    'from_email' => 'noreply@immobilienghumman.de',
    
    // Subject line for contact form emails
    'subject' => 'New Contact Request via Website',
    
    // SMTP Configuration for PHPMailer
    'smtp' => [
        'host' => 'smtp.yourhost.com',                    // e.g. smtp.alfahosting.de
        'username' => 'noreply@immobilienghumman.de',     // SMTP username
        'password' => 'YOUR_SMTP_PASSWORD',               // SMTP password - UPDATE THIS!
        'encryption' => 'tls',                            // 'tls' or 'ssl'
        'port' => 587                                     // 587 for TLS, 465 for SSL
    ],
    
    // Auto-response settings
    'auto_response' => [
        'enabled' => true,
        'subject' => 'Thank you for contacting Immobilien Ghumman',
        'from_email' => 'info@immobilienghumman.de'
    ],
    
    // Response messages
    'messages' => [
        'success' => 'Thank you for your message. We have received it and will now process your request.',
        'error' => 'Sorry, there was an error sending your message. Please try again or contact us directly.',
        'validation_error' => 'Please fill in all required fields correctly.'
    ]
];
?>
