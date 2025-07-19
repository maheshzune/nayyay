<?php
session_start();
require('db_con\dbCon.php');
$con = connect();

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}
$client_id = $_SESSION['client_id'];

// Check if lawyer_id is provided via GET
$lawyer_id = null;
if (isset($_GET['lawyer_id'])) {
    $lawyer_id = mysqli_real_escape_string($con, $_GET['lawyer_id']);
    $query_lawyer = "SELECT * FROM lawyer WHERE lawyer_id = '$lawyer_id'";
    $result_lawyer = mysqli_query($con, $query_lawyer);
    if (!$result_lawyer || mysqli_num_rows($result_lawyer) === 0) {
        $error = "Invalid lawyer selected";
        $lawyer_id = null;
    } else {
        $lawyer_details = mysqli_fetch_assoc($result_lawyer);
    }
}

// Fetch client details
$query_client = "SELECT * FROM client WHERE client_id = '$client_id'";
$result_client = mysqli_query($con, $query_client);
$client = mysqli_fetch_assoc($result_client);

// Fetch specialities only if no lawyer_id provided
if (!$lawyer_id) {
    $result_speciality = mysqli_query($con, "SELECT DISTINCT speciality FROM lawyer");
    $specialities = [];
    while($row = mysqli_fetch_assoc($result_speciality)) {
        $specialities[] = $row['speciality'];
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update client details
    $contact_number = mysqli_real_escape_string($con, $_POST['contact_number'] ?? '');
    $full_address   = mysqli_real_escape_string($con, $_POST['full_address'] ?? '');
    $city           = mysqli_real_escape_string($con, $_POST['city'] ?? '');
    $zip_code       = mysqli_real_escape_string($con, $_POST['zip_code'] ?? '');
    
    mysqli_query($con, "UPDATE client SET 
        contact_number = '$contact_number',
        full_address   = '$full_address',
        city           = '$city',
        zip_code       = '$zip_code'
        WHERE client_id = '$client_id'");

    // Refresh client details
    $result_client = mysqli_query($con, $query_client);
    $client = mysqli_fetch_assoc($result_client);

    // Process booking
    $subject = mysqli_real_escape_string($con, $_POST['subject'] ?? '');
    $case_description = mysqli_real_escape_string($con, $_POST['case_description'] ?? '');
    
    if ($lawyer_id) {
        // Use lawyer_id from GET parameter
        $selected_lawyer_id = $lawyer_id;
    } else {
        // Get lawyer from specialization
        $selected_specialization = mysqli_real_escape_string($con, $_POST['specialization'] ?? '');
        $result_lawyer = mysqli_query($con, 
            "SELECT lawyer_id FROM lawyer WHERE speciality = '$selected_specialization' LIMIT 1");
        if ($result_lawyer && mysqli_num_rows($result_lawyer) > 0) {
            $selected_lawyer_id = mysqli_fetch_assoc($result_lawyer)['lawyer_id'];
        } else {
            $error = "No lawyer found with the selected specialization.";
        }
    }

    if (!isset($error)) {
        $description = "Subject: $subject\nCase Description: $case_description";
        $date = date('Y-m-d H:i:s');
        $status = "Pending";
        
        $insert_booking = "INSERT INTO booking (date, description, client_id, lawyer_id, status)
                           VALUES ('$date', '$description', '$client_id', '$selected_lawyer_id', '$status')";
        if (mysqli_query($con, $insert_booking)) {
            $success = "Booking request submitted successfully!";
        } else {
            $error = "Error submitting request: " . mysqli_error($con);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hire a Lawyer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar { background-color: #00274D; }
        .footer { background-color: #00274D; position: fixed; bottom: 0; width: 100%; }
        .btn-primary { background-color: #FFD700; border-color: #FFD700; color: #00274D; }
        .btn-primary:hover { background-color: #FFC200; border-color: #FFC200; }
        .form-control:focus { border-color: #FFD700; box-shadow: 0 0 0 0.2rem rgba(255,215,0,0.25); }
        .lawyer-card { border: 2px solid #FFD700; border-radius: 10px; padding: 20px; margin-bottom: 20px; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#" style="color: #FFD700;">LegalConnect</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="client_dashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="bookings.php">Bookings</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5 mb-5">
    <h2 class="mb-4" style="color: #00274D;">Hire a Lawyer</h2>
    <?php if(isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if($lawyer_id): ?>
        <div class="lawyer-card">
            <h4 style="color: #00274D;">Selected Lawyer</h4>
            <p class="mb-1"><strong>Name:</strong> <?= $lawyer_details['name'] ?></p>
            <p class="mb-1"><strong>Specialization:</strong> <?= $lawyer_details['speciality'] ?></p>
            <p class="mb-0"><strong>Experience:</strong> <?= $lawyer_details['experience'] ?> years</p>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="card shadow">
            <div class="card-header" style="background-color: #00274D; color: white;">
                Client Information
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" 
                               value="<?= htmlspecialchars($client['contact_number']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control" 
                               value="<?= htmlspecialchars($client['city']) ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Full Address</label>
                        <input type="text" name="full_address" class="form-control" 
                               value="<?= htmlspecialchars($client['full_address']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Zip Code</label>
                        <input type="text" name="zip_code" class="form-control" 
                               value="<?= htmlspecialchars($client['zip_code']) ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mt-4">
            <div class="card-header" style="background-color: #00274D; color: white;">
                Case Details
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Case Subject</label>
                    <input type="text" name="subject" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Case Description</label>
                    <textarea name="case_description" class="form-control" rows="4" required></textarea>
                </div>
                <?php if(!$lawyer_id): ?>
                    <div class="mb-3">
                        <label class="form-label">Specialization Needed</label>
                        <select name="specialization" class="form-select" required>
                            <option value="">Select Specialization</option>
                            <?php foreach($specialities as $spec): ?>
                                <option value="<?= htmlspecialchars($spec) ?>"><?= htmlspecialchars($spec) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Submit Request</button>
        </div>
    </form>
</div>

<footer class="footer py-3">
    <div class="container text-center">
        <span style="color: #FFD700;">© 2023 LegalConnect. All rights reserved.</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>