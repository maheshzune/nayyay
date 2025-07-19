<?php
session_start();
include("db_con/dbCon.php");
$conn = connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form inputs
    $client_id   = mysqli_real_escape_string($conn, $_POST['client_id'] ?? '');
    $lawyer_id   = mysqli_real_escape_string($conn, $_POST['lawyer_id'] ?? '');
    $subject     = mysqli_real_escape_string($conn, $_POST['subject'] ?? '');
    $description = mysqli_real_escape_string($conn, $_POST['description'] ?? '');
    
    // Process document upload (single file)
    $document_path = '';
    if (isset($_FILES['document'])) {
        if ($_FILES['document']['error'] === 0) {
            $uploadDir = "uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $document_path = $uploadDir . time() . "_" . basename($_FILES['document']['name']);
            if (!move_uploaded_file($_FILES['document']['tmp_name'], $document_path)) {
                // If the file couldn't be moved, you can log or handle the error
                $document_path = '';
            }
        } else {
            // Optional: Handle or log the error code
            // echo "File upload error (code " . $_FILES['document']['error'] . ") for file: " . htmlspecialchars($_FILES['document']['name']);
        }
    }
    
    // Set current date and default status
    $date   = date('Y-m-d H:i:s');
    $status = "Pending";
    
    // Insert booking record into the booking table with the new columns
    $sql = "INSERT INTO booking (date, subject, description, client_id, lawyer_id, status, document)
            VALUES ('$date', '$subject', '$description', '$client_id', '$lawyer_id', '$status', '$document_path')";
    
    if (mysqli_query($conn, $sql)) {
        // Output a complete HTML document with SweetAlert2 on success
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Booking Saved</title>
            <!-- Include SweetAlert2 from CDN -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Booking Saved!',
                text: 'Your booking request has been submitted successfully.',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'user_dashboard.php';
                }
            });
        </script>
        </body>
        </html>
        <?php
        exit();
    } else {
        echo "Error submitting booking request: " . mysqli_error($conn);
    }
} else {
    header("Location: user_dashboard.php");
    exit();
}
?>
