# PHPMailer Installation Guide

## Download PHPMailer

### Option 1: Direct Download
1. Go to https://github.com/PHPMailer/PHPMailer/releases
2. Download the latest release zip file
3. Extract it to your website directory
4. Create a folder called `PHPMailer` in your website root
5. Copy the `src` folder from the extracted files to `PHPMailer/src`

### Option 2: Using Composer (Recommended)
If you have Composer installed:
```bash
composer require phpmailer/phpmailer
```

## Directory Structure
Your website should have this structure:
```
website-root/
├── PHPMailer/
│   └── src/
│       ├── Exception.php
│       ├── PHPMailer.php
│       └── SMTP.php
├── send-email.php
├── email-config.php
└── 2.html
```

## SMTP Configuration
Update the SMTP settings in `email-config.php`:

### For Gmail:
```php
'smtp' => [
    'host' => 'smtp.gmail.com',
    'username' => 'your-email@gmail.com',
    'password' => 'your-app-password',  // Use App Password, not regular password
    'encryption' => 'tls',
    'port' => 587
]
```

### For Common German Hosts:

#### Alfahosting:
```php
'smtp' => [
    'host' => 'smtp.alfahosting.de',
    'username' => 'your-email@yourdomain.de',
    'password' => 'your-password',
    'encryption' => 'tls',
    'port' => 587
]
```

#### 1&1 IONOS:
```php
'smtp' => [
    'host' => 'smtp.ionos.de',
    'username' => 'your-email@yourdomain.de',
    'password' => 'your-password',
    'encryption' => 'tls',
    'port' => 587
]
```

#### Strato:
```php
'smtp' => [
    'host' => 'smtp.strato.de',
    'username' => 'your-email@yourdomain.de',
    'password' => 'your-password',
    'encryption' => 'tls',
    'port' => 587
]
```

## Important Security Notes
1. **Never commit passwords to version control**
2. **Use environment variables for sensitive data in production**
3. **For Gmail, use App Passwords instead of your regular password**
4. **Enable 2-factor authentication on your email account**

## Testing
1. Update the SMTP settings in `email-config.php`
2. Test the contact form on your website
3. Check your email and spam folder
4. Verify the auto-response is sent to the form submitter

## Troubleshooting
- **"Could not authenticate"**: Check username/password
- **"Connection failed"**: Check host and port settings
- **"Certificate verify failed"**: Your host may need SSL verification disabled
- **Emails in spam**: Configure SPF, DKIM, and DMARC records for your domain
