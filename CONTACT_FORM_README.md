# Contact Form Setup Documentation

## Overview
The website now has a fully functional server-side contact form that sends emails directly from the website without opening external mail clients.

## Features Implemented
✅ **Server-side PHP email processing with PHPMailer**
✅ **SMTP email delivery** (more reliable than basic mail())
✅ **AJAX form submission** (no page refresh)
✅ **Email sent to:** info@immobilienghumman.de
✅ **Auto-response email** to form submitters
✅ **Success/Error message display**
✅ **Form validation** (client and server-side)
✅ **Responsive design** with loading states
✅ **Dark mode support** for messages
✅ **HTML and plain text emails**
✅ **Honeypot spam protection**

## Files Modified/Created

### 1. `send-email.php` (New)
- Main PHP script that processes form submissions
- Validates form data
- Sends email to business and auto-response to customer
- Returns JSON responses for AJAX handling

### 2. `email-config.php` (New)
- Configuration file for easy email settings management
- Contains recipient emails, subjects, and messages
- Easy to modify without touching main PHP code

### 3. `2.html` (Modified)
- Updated form action from `mailto:` to `send-email.php`
- Added CSS styles for success/error messages
- Added disabled button styles

### 4. `app.js` (Modified)
- Added AJAX form submission handling
- Added success/error message display
- Added loading states for submit button

## Form Fields Included
- **Name** (required)
- **Email** (required, validated)
- **Phone** (optional)
- **Message** (required)

## Email Configuration
- **Recipient:** info@immobilienghumman.de
- **Subject:** "New Contact Request via Website"
- **Auto-response:** Enabled by default

## Customization
To modify email settings, edit `email-config.php`:
```php
'recipient_email' => 'your-email@domain.com',
'subject' => 'Your Custom Subject',
'auto_response' => ['enabled' => true/false]
```

## Server Requirements
- PHP 5.6+ with PHPMailer support
- PHPMailer library installed (see PHPMailer_SETUP.md)
- SMTP server credentials
- Web server (Apache/Nginx)

## Installation
1. **Install PHPMailer** - Follow the guide in `PHPMailer_SETUP.md`
2. **Configure SMTP** - Update settings in `email-config.php`
3. **Test the form** - Submit a test message and verify delivery

## Testing
1. Fill out the contact form on the website
2. Submit the form
3. Check for success message: "Thank you for your message. We have received it and will now process your request."
4. Verify email received at info@immobilienghumman.de
5. Check customer received auto-response

## Security Features
- Input sanitization (XSS protection)
- Email validation
- CSRF protection via server-side processing
- Honeypot field for basic spam protection
- Rate limiting can be added if needed

## Troubleshooting
If emails are not being sent:
1. Check server's PHP mail() configuration
2. Verify SMTP settings on the server
3. Check spam folders
4. Review PHP error logs
5. Ensure domain email addresses are properly configured

## Future Enhancements
- Add CAPTCHA for spam protection
- Implement rate limiting
- Add email templates with HTML formatting
- Add file attachment support
- Integration with email marketing services
