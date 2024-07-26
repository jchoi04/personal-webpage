<?php
// Set content-type for HTML email
header('Content-Type: text/html; charset=UTF-8');

// Define variables and initialize with empty values
$name = $email = $subject = $message = "";
$name_err = $email_err = $subject_err = $message_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter your name.";
    } else {
        $name = htmlspecialchars(trim($_POST["name"])); // Sanitize input
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $email = htmlspecialchars(trim($_POST["email"])); // Sanitize input
    }

    // Validate subject
    if (empty(trim($_POST["subject"]))) {
        $subject_err = "Please enter a subject.";
    } else {
        $subject = htmlspecialchars(trim($_POST["subject"])); // Sanitize input
    }

    // Validate message
    if (empty(trim($_POST["message"]))) {
        $message_err = "Please enter a message.";
    } else {
        $message = htmlspecialchars(trim($_POST["message"])); // Sanitize input
    }

    // Check input errors before sending email
    if (empty($name_err) && empty($email_err) && empty($subject_err) && empty($message_err)) {
        // Recipient email address
        $to = 'blueracecar649@gmail.com'; // Replace with your real email address

        // Email subject
        $mail_subject = "Contact Form Submission: $subject";

        // Email content
        $mail_body = "
        <html>
        <head>
            <title>Contact Form Submission</title>
        </head>
        <body>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Subject:</strong> $subject</p>
            <p><strong>Message:</strong><br>$message</p>
        </body>
        </html>";

        // Email headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: no-reply@yourdomain.com" . "\r\n"; // Use a verified email address
        $headers .= "Reply-To: $email" . "\r\n";

        // Send email
        if (mail($to, $mail_subject, $mail_body, $headers)) {
            echo json_encode(['status' => 'success', 'message' => 'Message sent successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error sending email. Please try again later.']);
        }
    } else {
        // Show validation errors
        $errors = [
            'name' => $name_err,
            'email' => $email_err,
            'subject' => $subject_err,
            'message' => $message_err
        ];
        echo json_encode(['status' => 'error', 'errors' => $errors]);
    }
}
?>