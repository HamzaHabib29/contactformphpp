<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require '/ideakarachi/PHPMailer/PHPMailer/src/Exception.php';
require '/ideakarachi/PHPMailer/PHPMailer/src/PHPMailer.php';
require '/ideakarachi/PHPMailer/PHPMailer/src/SMTP.php';

header('Content-Type: application/json'); // Set response type to JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    if (empty($_POST['name']) || empty($_POST['time']) || empty($_POST['phone']) || empty($_POST['service']) || empty($_POST['message'])) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill all required fields.']);
        exit();
    }

    $mail = new PHPMailer();

    try {
        // SMTP Settings
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Host = "mail.ideakarachi.com";
        $mail->SMTPAuth = true;
        $mail->Username = "email@ideakarachi.com";  // Your SMTP username
        $mail->Password = "njebu?&~Y;3b";          // Your SMTP password
        $mail->SMTPSecure = 'ssl';                // Enable SSL encryption
        $mail->Port = 465;

        // Sender and Recipient Settings
        $mail->setFrom("contact@ideakarachi.com", "Idea Karachi");
        $mail->addAddress("hamza.sewani@gmail.com", "Idea Karachi"); // Primary recipient
        if (!empty($_POST['email'])) {
            $mail->addAddress($_POST['email']); // Optional: User's email
        }

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = "Query from Contact Us";
        $message = '
            <p>Here are the contact details:</p>
            <table>
                <tr>
                    <td><strong>Full Name:</strong></td>
                    <td>' . htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') . '</td>
                </tr>
                <tr>
                    <td><strong>Time:</strong></td>
                    <td>' . htmlspecialchars($_POST['time'], ENT_QUOTES, 'UTF-8') . '</td>
                </tr>
                <tr>
                    <td><strong>Contact Number:</strong></td>
                    <td>' . htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8') . '</td>
                </tr>
                <tr>
                    <td><strong>Service:</strong></td>
                    <td>' . htmlspecialchars($_POST['service'], ENT_QUOTES, 'UTF-8') . '</td>
                </tr>
                <tr>
                    <td><strong>Short Text:</strong></td>
                    <td>' . htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8') . '</td>
                </tr>
            </table>
        ';
        $mail->Body = $message;

        // Send Email
        if ($mail->send()) {
            echo json_encode(['status' => 'success', 'message' => 'Your message has been sent successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error occurred while sending email. Please try again later.']);
        }
    } catch (Exception $e) {
        // Log or display the error message
        error_log("Mailer Error: " . $mail->ErrorInfo);
        echo json_encode(['status' => 'error', 'message' => 'Error occurred while sending email. Please try again later.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

?>
