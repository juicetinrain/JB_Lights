<?php
// includes/email_handler.php - Fixed version
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
        
        // Clean phone number for validation
        $clean_phone = preg_replace('/\D/', '', $phone);
        
        // Validate Philippine phone number (11 digits starting with 09)
        if (strlen($clean_phone) !== 11 || !preg_match('/^09\d{9}$/', $clean_phone)) {
            return ['success' => false, 'message' => 'Please enter a valid Philippine mobile number (09XXXXXXXXX).'];
        }
        
        // Save to database first
        $db_success = $this->saveToDatabase($first_name, $last_name, $clean_phone, $email, $subject, $message);
        
        // Send email
        $email_success = $this->sendEmail($first_name, $last_name, $phone, $email, $subject, $message);
        
        if ($db_success && $email_success) {
            return ['success' => true, 'message' => 'Thank you for your message! We will get back to you within 24 hours.'];
        } elseif ($db_success) {
            return ['success' => true, 'message' => 'Message saved but email delivery failed. We will still contact you soon.'];
        } else {
            return ['success' => false, 'message' => 'Sorry, there was an error processing your message. Please try again.'];
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
        
        // Validate name fields (letters and spaces only)
        if (!preg_match('/^[a-zA-Z\s]+$/', $data['first_name']) || !preg_match('/^[a-zA-Z\s]+$/', $data['last_name'])) {
            return false;
        }
        
        return true;
    }
    
    private function sanitize($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    private function saveToDatabase($first_name, $last_name, $phone, $email, $subject, $message) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO contact_submissions (first_name, last_name, phone, email, subject, message, submitted_at, ip_address) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)");
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
            $stmt->bind_param("sssssss", $first_name, $last_name, $phone, $email, $subject, $message, $ip_address);
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
            
            // Send using PHP's mail function
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