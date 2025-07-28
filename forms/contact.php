<?php
/**
 * Requires the "PHP Email Form" library.
 * The "PHP Email Form" library should be uploaded to: vendor/php-email-form/php-email-form.php
 * For more info and help: https://bootstrapmade.com/php-email-form/
 */

// IMPORTANT: Replace this with your actual receiving email address
$receiving_email_address = 'mbouandagnanga18@gmail.com';

// Ensure the PHP Email Form library exists and is loaded
if (file_exists($php_email_form = __DIR__ . '/../assets/vendor/php-email-form/php-email-form.php')) {
    include($php_email_form);
} else {
    // Log the error and provide a user-friendly message
    error_log('Unable to load the "PHP Email Form" Library! Path: ' . __DIR__ . '/../assets/vendor/php-email-form/php-email-form.php');
    die('An error occurred. Please try again later or contact support.');
}

$contact = new PHP_Email_Form;
$contact->ajax = true; // Keep this true if you are using AJAX for submission

// Set the recipient email address
$contact->to = $receiving_email_address;

// Get sender's name and email from the form submission
$contact->from_name = $_POST['name'] ?? ''; // Use null coalescing operator for safer access
$contact->from_email = $_POST['email'] ?? '';
$contact->subject = $_POST['subject'] ?? 'New Contact Form Submission'; // Default subject if none provided

// --- SMTP Configuration (Mandatory for reliable email delivery) ---
// You MUST fill in these details with your actual SMTP server settings.
// These details are provided by your email service provider (e.g., Gmail, Outlook, cPanel, etc.).
$contact->smtp = array(
    'host' => 'smtp.gmail.com', // E.g., 'smtp.gmail.com' for Gmail, 'smtp.office365.com' for Outlook
    'username' => 'mbouandagnanga18@gmail.com', // Your full email address used to send emails
    // 'password' => 'your-email-password',   // The password for the email account above
    'port' => '587',                       // Common ports: 587 (TLS), 465 (SSL)
    'encryption' => 'tls'                  // 'tls' or 'ssl' depending on your host's requirement
);
// --- End SMTP Configuration ---

// Add form fields to the email message
$contact->add_message($contact->from_name, 'From');
$contact->add_message($contact->from_email, 'Email');
$contact->add_message($_POST['message'] ?? '', 'Message', 10); // Message content, with a default empty string

// Attempt to send the email and echo the result (for AJAX response)
try {
    echo $contact->send();
} catch (Exception $e) {
    // Catch any exceptions during sending and return an error message
    error_log('Email sending failed: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Failed to send your message. Please try again later.']);
}
?>