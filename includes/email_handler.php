<?php
// includes/email_handler.php
require_once 'config/email_config.php';

class EmailHandler {
    private $conn;
    
    public function __construct($database_connection) {
        $this->conn = $database_connection;
    }
    
    public function sendContactEmail($data) {
        // Validate required fields
        if (!$this->validateContactData($data)) {
            return ['success' => false, 'message' => 'Please fill in all required fields.'];
        }
        
        // Sanitize data
        $first_name = $this->sanitize($data['first_name']);
        $last_name = $this->sanitize($data['last_name']);
        $phone = $this->sanitize($data['phone']);
        $email = $this->sanitize($data['email']);
        $subject = $this->sanitize($data['subject']);
        $message = $this->sanitize($data['message']);
        
        // Save to database first
        $db_success = $this->saveToDatabase($first_name, $last_name, $phone, $email, $subject, $message);
        
        // Send email
        $email_success = $this->sendEmail($first_name, $last_name, $phone, $email, $subject, $message);
        
        if ($email_success) {
            return ['success' => true, 'message' => 'Thank you for your message! We will get back to you within 24 hours.'];
        } else {
            return ['success' => false, 'message' => 'Message saved but email delivery failed. We will still contact you soon.'];
        }
    }
    
    private function validateContactData($data) {
        $required = ['first_name', 'last_name', 'phone', 'email', 'subject', 'message'];
        
        foreach ($required as $field) {
            if (empty(trim($data[$field] ?? ''))) {
                return false;
            }
        }
        
        // Validate email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        // Validate phone (basic Philippine format)
        $phone = preg_replace('/\D/', '', $data['phone']);
        if (strlen($phone) < 10) {
            return false;
        }
        
        return true;
    }
    
    private function sanitize($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    private function saveToDatabase($first_name, $last_name, $phone, $email, $subject, $message) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO contact_submissions (first_name, last_name, phone, email, subject, message, submitted_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssssss", $first_name, $last_name, $phone, $email, $subject, $message);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
    
    private function sendEmail($first_name, $last_name, $phone, $email, $subject, $message) {
        try {
            // Email headers
            $to = CONTACT_RECIPIENT;
            $email_subject = CONTACT_SUBJECT_PREFIX . $subject;
            $from = SMTP_FROM_EMAIL;
            
            // Email content
            $email_message = $this->buildEmailTemplate($first_name, $last_name, $phone, $email, $subject, $message);
            
            // Headers
            $headers = $this->buildEmailHeaders($from, $email);
            
            // Send using PHP's mail function (most reliable)
            return mail($to, $email_subject, $email_message, $headers);
            
        } catch (Exception $e) {
            error_log("Email error: " . $e->getMessage());
            return false;
        }
    }
    
    private function buildEmailTemplate($first_name, $last_name, $phone, $email, $subject, $message) {
        return "
NEW CONTACT FORM SUBMISSION - JB LIGHTS & SOUND

CONTACT DETAILS:
===============
NAME: {$first_name} {$last_name}
PHONE: {$phone}
EMAIL: {$email}
SUBJECT: {$subject}

MESSAGE:
========
{$message}

SUBMITTED ON: " . date('Y-m-d H:i:s') . "
IP ADDRESS: {$_SERVER['REMOTE_ADDR']}
        ";
    }
    
    private function buildEmailHeaders($from, $reply_to) {
        $headers = "From: JB Lights & Sound <{$from}>" . "\r\n";
        $headers .= "Reply-To: {$reply_to}" . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8" . "\r\n";
        
        return $headers;
    }
}
?>