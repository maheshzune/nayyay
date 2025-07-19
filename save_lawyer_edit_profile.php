<?php
session_start();
include("db_con/dbCon.php");
$conn = connect();

// Check if the lawyer is logged in; if not, redirect.
if (!isset($_SESSION['login']) || $_SESSION['login'] !== TRUE) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve lawyer ID from session.
    $lawyer_id = $_SESSION['lawyer_id'];

    // Sanitize input fields.
    $first_Name         = mysqli_real_escape_string($conn, $_POST['first_Name']);
    $last_Name          = mysqli_real_escape_string($conn, $_POST['last_Name']);
    $contact_number     = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $university_College = mysqli_real_escape_string($conn, $_POST['university_College']);
    $degree             = mysqli_real_escape_string($conn, $_POST['degree']);
    $passing_year       = mysqli_real_escape_string($conn, $_POST['passing_year']);
    $full_address       = mysqli_real_escape_string($conn, $_POST['full_address']);
    $city               = mysqli_real_escape_string($conn, $_POST['city']);
    $zip_code           = mysqli_real_escape_string($conn, $_POST['zip_code']);
    $practise_Length    = mysqli_real_escape_string($conn, $_POST['practise_Length']);
    $speciality         = mysqli_real_escape_string($conn, $_POST['speciality']);

    // Initialize variable for new image filename.
    $newImage = "";

    // Process file upload if a new image is provided.
    if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {
        $target_dir    = "images/upload/";
        $newImageName  = date('YmdHis_') . basename($_FILES["image"]["name"]);
        $target_file   = $target_dir . $newImageName;
        $uploadOk      = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verify the file is an image.
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $uploadOk = 0;
        }

        // Allowed extensions.
        $allowed = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowed)) {
            $uploadOk = 0;
        }

        // Check file size (limit: 2MB).
        if ($_FILES["image"]["size"] > 2 * 1024 * 1024) {
            $uploadOk = 0;
        }

        // If all checks pass, move the file.
        if ($uploadOk === 1) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $newImage = $newImageName;
            }
        }
    }

    // Begin database transaction.
    mysqli_begin_transaction($conn);
    try {
        // Update the 'user' table (update name fields).
        $updateUser = "UPDATE user SET 
                           first_Name = '$first_Name',
                           last_Name  = '$last_Name'
                       WHERE u_id = '$lawyer_id'";
        if (!mysqli_query($conn, $updateUser)) {
            throw new Exception(mysqli_error($conn));
        }

        // Construct the update query for the 'lawyer' table.
        if ($newImage != "") {
            $updateLawyer = "UPDATE lawyer SET 
                              contact_Number       = '$contact_number',
                              university_College   = '$university_College',
                              degree               = '$degree',
                              passing_year         = '$passing_year',
                              full_address         = '$full_address',
                              city                 = '$city',
                              zip_code             = '$zip_code',
                              practise_Length      = '$practise_Length',
                              speciality           = '$speciality',
                              image                = '$newImage'
                           WHERE lawyer_id = '$lawyer_id'";
        } else {
            $updateLawyer = "UPDATE lawyer SET 
                              contact_Number       = '$contact_number',
                              university_College   = '$university_College',
                              degree               = '$degree',
                              passing_year         = '$passing_year',
                              full_address         = '$full_address',
                              city                 = '$city',
                              zip_code             = '$zip_code',
                              practise_Length      = '$practise_Length',
                              speciality           = '$speciality'
                           WHERE lawyer_id = '$lawyer_id'";
        }
        if (!mysqli_query($conn, $updateLawyer)) {
            throw new Exception(mysqli_error($conn));
        }

        // Commit the transaction.
        mysqli_commit($conn);

        // Update session variables with new values.
        $_SESSION['first_Name'] = $first_Name;
        $_SESSION['last_Name']  = $last_Name;
        if ($newImage != "") {
            $_SESSION['profile_image'] = $newImage;
        }

        // Redirect back to the edit profile page with a success flag.
        header("Location: lawyer_edit_profile.php?ok=1");
        exit();
    } catch (Exception $e) {
        // Rollback if an error occurs.
        mysqli_rollback($conn);
        // Uncomment the line below for debugging:
        // echo "Update failed: " . $e->getMessage();
        header("Location: lawyer_edit_profile.php?error=1");
        exit();
    }
}
mysqli_close($conn);
?>
