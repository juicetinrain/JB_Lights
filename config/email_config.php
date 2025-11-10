<?php
// config/email_config.php
// Gmail API Configuration

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Gmail SMTP Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'designmanagem3nt@gmail.com'); // Your Gmail
define('SMTP_PASSWORD', 'vmbm ktgt trqb nbzg'); // Use App Password, not regular password
define('SMTP_FROM_EMAIL', 'designmanagem3nt@gmail.com');
define('SMTP_FROM_NAME', 'Design Management');

// Contact form settings
define('CONTACT_RECIPIENT', 'designmanagem3nt@gmail.com');
define('CONTACT_SUBJECT_PREFIX', 'JB Lights Contact: ');
?>