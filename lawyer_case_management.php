<?php
session_start();
if ($_SESSION['login'] == TRUE && $_SESSION['status'] == 'Active') {
    include("db_con/dbCon.php");
    $conn = connect();
    $lawyer_id = $_SESSION['lawyer_id'];

    // Fetch lawyer's image for navbar
    $image_query = "SELECT image FROM lawyer WHERE lawyer_id = '$lawyer_id'";
    $image_result = mysqli_query($conn, $image_query);
    $lawyer = mysqli_fetch_assoc($image_result);
    $profile_image = !empty($lawyer['image']) ? $lawyer['image'] : 'images/upload/default-profile.png';

    // Process Edit Details update (all details except status and notes)
    if (isset($_POST['update_case_details'])) {
        $case_id      = mysqli_real_escape_string($conn, $_POST['case_id']);
        $case_type    = mysqli_real_escape_string($conn, $_POST['case_type']);
        $location     = mysqli_real_escape_string($conn, $_POST['location']);
        $fees         = mysqli_real_escape_string($conn, $_POST['fees']);
        $update_query = "UPDATE `case` 
                         SET case_type = '$case_type', 
                             location = '$location', 
                             fees = '$fees'
                         WHERE case_id = '$case_id' AND lawyer_id = '$lawyer_id'";
        if (mysqli_query($conn, $update_query)) {
            $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            Case details updated successfully.
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
        } else {
            $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            Error updating case details: " . mysqli_error($conn) . "
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
        }
    }

    // Process Update Status, Notes, and Hearing Date
    if (isset($_POST['update_status_note'])) {
        $case_id      = mysqli_real_escape_string($conn, $_POST['case_id']);
        $status       = mysqli_real_escape_string($conn, $_POST['status']);
        $note         = mysqli_real_escape_string($conn, $_POST['note']);
        $hearing_date = mysqli_real_escape_string($conn, $_POST['hearing_date']);
        
        $update_status_query = "UPDATE `case` 
                                SET status = '$status', 
                                    notes = '$note',
                                    hearing_date = '$hearing_date'
                                WHERE case_id = '$case_id' AND lawyer_id = '$lawyer_id'";
        if (mysqli_query($conn, $update_status_query)) {
            $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                            Case status, note and hearing date updated successfully.
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
        } else {
            $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            Error updating status, note and hearing date: " . mysqli_error($conn) . "
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
        }
    }

    // Query to fetch case details (including hearing_date)
    $case_query = "SELECT c.case_id, c.client_id, c.case_type, c.location, c.fees, c.hearing_date, c.document_path, c.status, c.notes,
                          b.booking_id, b.subject, u.first_Name, u.last_Name
                   FROM `case` c
                   INNER JOIN booking b ON c.lawyer_id = b.lawyer_id AND c.client_id = b.client_id
                   INNER JOIN user u ON c.client_id = u.u_id
                   WHERE c.lawyer_id = '$lawyer_id'";
    $case_result = mysqli_query($conn, $case_query);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lawyer Management System - Case Management</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS Files -->
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/simple-sidebar.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/media.css">
    <style>
        :root {
            --primary-color: #00274D;
            --accent-color: #FFD700;
            --secondary-color: #00A8A8;
            --background-color: #F8F9FA;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--background-color);
            padding-top: 80px;
        }
        .neo-navbar {
            background: linear-gradient(135deg, rgba(248, 249, 250, 0.95), rgba(248, 249, 250, 0.85)) !important;
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 15px 0;
            transition: background 0.3s ease;
        }
        .neo-navbar.fixed-top {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }
        .navbar-brand {
            color: var(--primary-color) !important;
            font-weight: 600;
            font-size: 1.5rem;
            transition: color 0.3s ease;
        }
        .navbar-brand:hover {
            color: var(--accent-color) !important;
        }
        .nav-link {
            color: var(--primary-color);
            font-weight: 500;
            padding: 10px 15px;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: var(--accent-color);
        }
        .nav-link.active {
            color: var(--accent-color) !important;
            font-weight: 600;
            border-bottom: 2px solid var(--accent-color);
        }
        .gradient-btn {
            background-color: var(--primary-color);
            color: #FFFFFF;
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        .gradient-btn:hover {
            background-color: var(--accent-color);
            color: var(--primary-color);
            text-decoration: none;
        }
        .profile-img-nav {
            width: 30px;
            height: 30px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--accent-color);
            margin-right: 8px;
        }
        #sidebar-wrapper {
            min-height: 100vh;
            margin-left: 0;  
            transition: margin .25s ease-out;
            background: #FFFFFF;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }
        #sidebar-wrapper .sidebar-heading {
            background-color: var(--primary-color);
            color: #FFFFFF;
            padding: 15px;
            font-size: 1.2rem;
            font-weight: 600;
        }
        #sidebar-wrapper .list-group-item {
            color: var(--primary-color);
            font-weight: 500;
            border: none;
            padding: 15px 20px;
            transition: background-color 0.3s ease;
        }
        #sidebar-wrapper .list-group-item:hover {
            background-color: var(--background-color);
            color: var(--accent-color);
        }
        #sidebar-wrapper .list-group-item.active {
            background-color: var(--background-color);
            color: var(--accent-color);
            font-weight: 600;
        }
        #wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
        }
        #page-content-wrapper {
            padding: 40px 20px;
            width: 100%;
        }
        .dashboard-card {
            background: #FFFFFF;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-left: 5px solid var(--accent-color);
        }
        .btn-view {
            background-color: var(--secondary-color);
            color: #FFFFFF;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn-view:hover {
            background-color: #007777;
            color: #FFFFFF;
            text-decoration: none;
        }
        .btn-edit {
            background-color: #007bff;
            color: #FFFFFF;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }
        .btn-edit:hover {
            background-color: #0056b3;
            color: #FFFFFF;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg neo-navbar fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Lawyer Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#navbarNav" aria-controls="navbarNav" 
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="lawyer_booking.php">Bookings</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="nav-link btn gradient-btn" href="#">
                            <img src="images/upload/<?php echo $profile_image; ?>" 
                                 alt="Profile" class="profile-img-nav">
                            <?php echo $_SESSION['first_Name'] . ' ' . $_SESSION['last_Name']; ?>
                        </a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="nav-link btn gradient-btn" href="logout.php">Log Out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-light border-right" id="sidebar-wrapper">
            <div class="sidebar-heading">My Profile</div>
            <div class="list-group list-group-flush">
                <a href="lawyer_dashboard.php" class="list-group-item list-group-item-action bg-light">Dashboard</a>
                <a href="lawyer_edit_profile.php" class="list-group-item list-group-item-action bg-light">Edit Profile</a>
                <a href="lawyer_booking.php" class="list-group-item list-group-item-action bg-light">Booking Requests</a>
                <a href="lawyer_case_details.php" class="list-group-item list-group-item-action bg-light">Case Details</a>
                <a href="lawyer_case_management.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'lawyer_case_management.php' ? 'active' : ''; ?>">
                    Case Management
                    <?php if (basename($_SERVER['PHP_SELF']) == 'lawyer_case_management.php'): ?>
                        <i class="fas fa-arrow-right float-end"></i>
                    <?php endif; ?>
                </a>
                <a href="update_password_admin.php" class="list-group-item list-group-item-action bg-light">Update Password</a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <?php if (isset($message)) echo $message; ?>
            <div class="container-fluid">
                <div class="dashboard-card">
                    <h4>Case Management</h4>
                    <?php
                    $displayedCases = array();
                    if (mysqli_num_rows($case_result) > 0) {
                        echo '<div class="row row-cols-1 row-cols-md-2 g-4">';
                        while ($row = mysqli_fetch_assoc($case_result)) {
                            if (in_array($row['case_id'], $displayedCases)) {
                                continue;
                            }
                            $displayedCases[] = $row['case_id'];
                            
                            echo '<div class="col">';
                            echo '  <div class="card h-100">';
                            echo '      <div class="card-body">';
                            echo '          <h5 class="card-title">Case ID: ' . $row['case_id'] . '</h5>';
                            echo '          <p class="card-text"><strong>Client:</strong> ' . $row['first_Name'] . ' ' . $row['last_Name'] . '</p>';
                            echo '          <p class="card-text"><strong>Subject:</strong> ' . $row['subject'] . '</p>';
                            echo '          <p class="card-text"><strong>Case Type:</strong> ' . $row['case_type'] . '</p>';
                            echo '          <p class="card-text"><strong>Location:</strong> ' . $row['location'] . '</p>';
                            echo '          <p class="card-text"><strong>Fees:</strong> ' . $row['fees'] . '</p>';
                            echo '          <p class="card-text"><strong>Hearing Date:</strong> ' . ($row['hearing_date'] ? $row['hearing_date'] : 'Not Set') . '</p>';
                            echo '          <p class="card-text"><strong>Status:</strong> ' . $row['status'] . '</p>';
                            echo '          <p class="card-text"><strong>Notes:</strong> ' . ($row['notes'] ?? 'N/A') . '</p>';
                            
                            if (!empty($row['document_path']) && file_exists($row['document_path'])) {
                                echo "          <a href='{$row['document_path']}' class='btn btn-view btn-sm' target='_blank'><i class='fas fa-eye'></i> View Document</a>";
                            } else {
                                echo "          <p class='mt-2 text-muted'>No document uploaded</p>";
                            }
                            
                            echo '          <div class="mt-3">';
                            echo "              <button class='btn btn-edit btn-sm me-1' data-bs-toggle='modal' data-bs-target='#editDetailsModal{$row['case_id']}'><i class='fas fa-edit'></i> Edit Details</button>";
                            echo "              <button class='btn btn-edit btn-sm' data-bs-toggle='modal' data-bs-target='#updateStatusModal{$row['case_id']}'><i class='fas fa-sync-alt'></i> Update Status</button>";
                            echo '          </div>';
                            echo '      </div>';
                            echo '  </div>';
                            echo '</div>';
                            
                            // Modal for editing case details
                            echo "<div class='modal fade' id='editDetailsModal{$row['case_id']}' tabindex='-1' aria-labelledby='editDetailsModalLabel{$row['case_id']}' aria-hidden='true'>
                                    <div class='modal-dialog'>
                                        <div class='modal-content'>
                                            <form method='post' action='' class='needs-validation' novalidate>
                                                <div class='modal-header'>
                                                    <h5 class='modal-title' id='editDetailsModalLabel{$row['case_id']}'>Edit Case Details</h5>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                </div>
                                                <div class='modal-body'>
                                                    <input type='hidden' name='case_id' value='{$row['case_id']}'>
                                                    <div class='mb-3'>
                                                        <label for='case_type{$row['case_id']}' class='form-label'>Case Type</label>
                                                        <input type='text' name='case_type' id='case_type{$row['case_id']}' class='form-control' value='{$row['case_type']}' required>
                                                        <div class='invalid-feedback'>Please enter the case type.</div>
                                                    </div>
                                                    <div class='mb-3'>
                                                        <label for='location{$row['case_id']}' class='form-label'>Location</label>
                                                        <input type='text' name='location' id='location{$row['case_id']}' class='form-control' value='{$row['location']}' required>
                                                        <div class='invalid-feedback'>Please enter the location.</div>
                                                    </div>
                                                    <div class='mb-3'>
                                                        <label for='fees{$row['case_id']}' class='form-label'>Fees</label>
                                                        <input type='number' name='fees' id='fees{$row['case_id']}' class='form-control' value='{$row['fees']}' required>
                                                        <div class='invalid-feedback'>Please enter the fees.</div>
                                                    </div>
                                                </div>
                                                <div class='modal-footer'>
                                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                                    <button type='submit' name='update_case_details' class='btn btn-success'>Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                  </div>";
                            
                            // Modal for updating status, notes, and hearing date
                            echo "<div class='modal fade' id='updateStatusModal{$row['case_id']}' tabindex='-1' aria-labelledby='updateStatusModalLabel{$row['case_id']}' aria-hidden='true'>
                                    <div class='modal-dialog'>
                                        <div class='modal-content'>
                                            <form method='post' action='' class='needs-validation' novalidate>
                                                <div class='modal-header'>
                                                    <h5 class='modal-title' id='updateStatusModalLabel{$row['case_id']}'>Update Case Status, Notes & Hearing Date</h5>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                </div>
                                                <div class='modal-body'>
                                                    <input type='hidden' name='case_id' value='{$row['case_id']}'>
                                                    <div class='mb-3'>
                                                        <label for='status{$row['case_id']}' class='form-label'>Case Status</label>
                                                        <select name='status' id='status{$row['case_id']}' class='form-select' required>
                                                            <option value=''>Choose...</option>
                                                            <option value='Pending' " . ($row['status'] == 'Pending' ? 'selected' : '') . ">Pending</option>
                                                            <option value='Accepted' " . ($row['status'] == 'Accepted' ? 'selected' : '') . ">Accepted</option>
                                                            <option value='Under hearing' " . ($row['status'] == 'Under hearing' ? 'selected' : '') . ">Under hearing</option>
                                                            <option value='Won' " . ($row['status'] == 'Won' ? 'selected' : '') . ">Won</option>
                                                            <option value='Lost' " . ($row['status'] == 'Lost' ? 'selected' : '') . ">Lost</option>
                                                        </select>
                                                        <div class='invalid-feedback'>Please select a case status.</div>
                                                    </div>
                                                    <div class='mb-3'>
                                                        <label for='note{$row['case_id']}' class='form-label'>Case Note</label>
                                                        <textarea name='note' id='note{$row['case_id']}' class='form-control' rows='3' required>" . ($row['notes'] ?? '') . "</textarea>
                                                        <div class='invalid-feedback'>Please enter a note.</div>
                                                    </div>
                                                    <div class='mb-3'>
                                                        <label for='hearing_date{$row['case_id']}' class='form-label'>Hearing Date</label>
                                                        <input type='date' name='hearing_date' id='hearing_date{$row['case_id']}' class='form-control' value='" . ($row['hearing_date'] ? $row['hearing_date'] : "") . "' min='<?php echo date('Y-m-d', strtotime('+1 day')); ?>'' 
                                                        <div class='invalid-feedback'>Hearing date must be after today.</div>
                                                    </div>
                                                </div>
                                                <div class='modal-footer'>
                                                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                                    <button type='submit' name='update_status_note' class='btn btn-success'>Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                  </div>";
                        }
                        echo '</div>'; // end row
                    } else {
                        echo '<p class="text-muted">No case details or documents found.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h5>All rights reserved <?php echo date("Y"); ?></h5>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        // Bootstrap validation
        (function () {
            'use strict';
            window.addEventListener('load', function () {
                var forms = document.getElementsByClassName('needs-validation');
                Array.prototype.filter.call(forms, function (form) {
                    form.addEventListener('submit', function (event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Auto-close alerts after 3 seconds
        document.addEventListener('DOMContentLoaded', function () {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function (alert) {
                setTimeout(function () {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 3000);
            });

            // Validate hearing date to be after today
            var today = new Date('2025-03-16'); // Current date as per instruction
            var tomorrow = new Date(today);
            tomorrow.setDate(today.getDate() + 1);
            var minDate = tomorrow.toISOString().split('T')[0];

            document.querySelectorAll('input[type="date"][name="hearing_date"]').forEach(function (hearingDateInput) {
                hearingDateInput.setAttribute('min', minDate);

                hearingDateInput.addEventListener('change', function () {
                    var selectedDate = new Date(this.value);
                    if (selectedDate <= today) {
                        this.setCustomValidity('Hearing date must be after today.');
                        this.reportValidity();
                    } else {
                        this.setCustomValidity('');
                    }
                });
            });
        });
    </script>
</body>
</html>
<?php
    mysqli_close($conn);
} else {
    header('location:login.php?deactivate');
}
?>