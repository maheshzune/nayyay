<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

$mail = new PHPMailer(true);

try {
    // SMTP settings
    $mail->isSMTP();                         
    $mail->Host = 'smtp.gmail.com';          
    $mail->SMTPAuth = true;                  
    $mail->Username = 'maheshzune03@gmail.com'; 
    $mail->Password = 'ptde jhjc estl kcej';  
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // More secure
    $mail->Port = 587;                       

    // Sender and recipient settings
    $mail->setFrom('maheshzune03@gmail.com', 'Lawyer Platform');
    $mail->addAddress('padmakarzune@gmail.com'); 
    $mail->addReplyTo('maheshzune03@gmail.com', 'Lawyer Platform'); // Reply-To

    // Headers to improve deliverability
    $mail->addCustomHeader('MIME-Version', '1.0');
    $mail->addCustomHeader('Content-type', 'text/html; charset=utf-8');
    $mail->addCustomHeader('X-Mailer', 'PHP/' . phpversion());

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Booking Confirmation';
    $mail->Body = '
        <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; color: #333; line-height: 1.5; }
                    h2 { color: #007BFF; }
                    p { font-size: 16px; }
                </style>
            </head>
            <body>
                <h2>Booking Confirmed!</h2>
                <p>Your appointment with the lawyer is confirmed for <strong>March 20, 2025</strong>.</p>
            </body>
        </html>';
    $mail->AltBody = 'Your appointment with the lawyer is confirmed for March 20, 2025.';

    $mail->send();
    echo '✅ Email sent successfully!';
} catch (Exception $e) {
    echo "❌ Email sending failed: {$mail->ErrorInfo}";
}
