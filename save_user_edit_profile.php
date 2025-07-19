<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SESSION['login'] != TRUE || $_SESSION['status'] != 'Active') {
    header('location:login.php?deactivate');
    exit;
}

include("db_con/dbCon.php");
$conn = connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_id = $_SESSION['client_id'];
    $first_name = $_POST['first_Name'];
    $last_name = $_POST['last_Name'];
    $full_address = $_POST['full_address'];
    $city = $_POST['city'];
    $zip_code = $_POST['zip_code'];
    $contact_number = $_POST['contact_number'];

    // Handle file upload
    $image_name = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/images/upload/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $image_name = basename($_FILES['image']['name']);
        $target_file = "images/upload/" . time() . "_" . $image_name; // Relative path for DB
        $full_target_file = $target_dir . time() . "_" . $image_name; // Absolute path for upload

        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $image_file_type = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        if (!in_array($image_file_type, $allowed_types)) {
            echo "Error: Only JPG, JPEG, PNG, and GIF files are allowed.";
            exit;
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $full_target_file)) {
            // File uploaded successfully
        } else {
            echo "Failed to upload file: " . $_FILES['image']['error'];
            exit;
        }
    }

    // Update database
    $query = "UPDATE user u
              INNER JOIN client c ON u.u_id = c.client_id
              SET u.first_Name = ?, u.last_Name = ?, c.full_address = ?, c.city = ?, c.zip_code = ?, c.contact_number = ?";
    if (!empty($image_name)) {
        $query .= ", c.image = ?";
    }
    $query .= " WHERE u.u_id = ? AND u.status = 'Active'";

    $stmt = $conn->prepare($query);
    if (!empty($image_name)) {
        $stmt->bind_param("sssssssi", $first_name, $last_name, $full_address, $city, $zip_code, $contact_number, $target_file, $client_id);
    } else {
        $stmt->bind_param("ssssssi", $first_name, $last_name, $full_address, $city, $zip_code, $contact_number, $client_id);
    }

    if ($stmt->execute()) {
        $_SESSION['first_Name'] = $first_name;
        $_SESSION['last_Name'] = $last_name;
        header("location:user_profile.php?ok");
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>