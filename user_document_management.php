<?php
session_start();
if ($_SESSION['login'] == TRUE && $_SESSION['status'] == 'Active') {
    include("db_con/dbCon.php");
    $conn = connect();
    $client_id = $_SESSION['client_id'];

    // Fetch client's image
    $image_query = "SELECT image FROM client WHERE client_id = '$client_id'";
    $image_result = mysqli_query($conn, $image_query);
    $client = mysqli_fetch_assoc($image_result);
    $profile_image = !empty($client['image']) ? $client['image'] : 'images/upload/default-profile.png';

    // Check if there’s a case with filled details for this client
    $case_check_query = "SELECT COUNT(*) as case_count 
                         FROM `case` c 
                         INNER JOIN booking b ON c.lawyer_id = b.lawyer_id AND c.client_id = b.client_id 
                         WHERE c.client_id = '$client_id' 
                         AND b.status = 'Accepted' 
                         AND c.case_type IS NOT NULL 
                         AND c.location IS NOT NULL 
                         AND c.fees IS NOT NULL";
    $case_check_result = mysqli_query($conn, $case_check_query);
    $case_count = mysqli_fetch_assoc($case_check_result)['case_count'];
    $has_case_details = ($case_count > 0);

    // Handle document upload
    $upload_message = '';
    if (isset($_POST['upload'])) {
        $booking_id = mysqli_real_escape_string($conn, $_POST['booking_id']);
        if (isset($_FILES['document']) && $_FILES['document']['error'] == UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/'; // Directory where files should be uploaded
            $full_upload_path = realpath(__DIR__) . '/' . $upload_dir; // Full server path

            // Ensure the uploads directory exists and is writable
            if (!file_exists($full_upload_path)) {
                if (!mkdir($full_upload_path, 0777, true)) {
                    $upload_message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                        Failed to create uploads directory: $full_upload_path.
                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                      </div>";
                }
            }
            if (!is_writable($full_upload_path)) {
                $upload_message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                    Uploads directory ($full_upload_path) is not writable. Check server permissions.
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                  </div>";
            } else {
                $file_name = basename($_FILES['document']['name']);
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $new_file_name = $booking_id . '_' . time() . '.' . $file_ext;
                $document_path = $upload_dir . $new_file_name; // Relative path for database

                // Full server path for moving the file
                $destination = $full_upload_path . $new_file_name;

                if (move_uploaded_file($_FILES['document']['tmp_name'], $destination)) {
                    // Update the `case` table instead of `booking`
                    $query = "UPDATE `case` c
                              INNER JOIN booking b ON c.lawyer_id = b.lawyer_id AND c.client_id = b.client_id
                              SET c.document_path = '$document_path'
                              WHERE b.booking_id = '$booking_id' AND c.client_id = '$client_id'";
                    if (mysqli_query($conn, $query)) {
                        if (mysqli_affected_rows($conn) > 0) {
                            $upload_message = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                                Document uploaded successfully to $destination and saved in case table.
                                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                              </div>";
                        } else {
                            $upload_message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                                No matching case found for booking ID $booking_id.
                                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                              </div>";
                        }
                    } else {
                        $upload_message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                            Error updating case table: " . mysqli_error($conn) . "
                                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                          </div>";
                    }
                } else {
                    $upload_message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                        Failed to move uploaded file to $destination. Error: " . error_get_last()['message'] . "
                                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                      </div>";
                }
            }
        } else {
            $upload_message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                Please select a document to upload. Error code: " . $_FILES['document']['error'] . "
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                              </div>";
        }
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/simple-sidebar.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/media.css">
    <title>Lawyer Management System - Document Management</title>
    <style>
        :root {
            --primary-color: #00274D;
            --accent-color: #FFD700;
            --secondary-color: #00A8A8;
            --background-color: #F8F9FA;
            --gradient-start: rgba(0, 39, 77, 0.9);
            --gradient-end: rgba(0, 168, 168, 0.7);
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--background-color);
            padding-top: 90px;
            margin: 0;
        }
        .neo-navbar {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 249, 250, 0.85));
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }
        .navbar-brand {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.8rem;
        }
        .navbar-brand:hover {
            color: var(--accent-color);
        }
        .nav-link {
            color: var(--primary-color);
            font-weight: 500;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            color: var(--accent-color);
            transform: scale(1.05);
        }
        .nav-link.active {
            color: var(--accent-color);
            border-bottom: 3px solid var(--accent-color);
        }
        .gradient-btn {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: #FFFFFF;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .gradient-btn:hover {
            background: linear-gradient(45deg, var(--secondary-color), var(--accent-color));
            color: #FFFFFF;
            transform: translateY(-2px);
            text-decoration: none;
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%2300274D' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        #sidebar-wrapper {
            position: fixed;
            top: 90px;
            left: 0;
            width: 250px;
            min-height: calc(100vh - 90px);
            background: #FFFFFF;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            overflow-y: auto;
        }
        .sidebar-heading {
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            color: #FFFFFF;
            padding: 15px;
            font-size: 1.4rem;
            font-weight: 700;
        }
        .list-group-item {
            color: var(--primary-color);
            font-weight: 500;
            padding: 15px 20px;
            transition: all 0.3s ease;
        }
        .list-group-item:hover {
            background: var(--background-color);
            color: var(--accent-color);
        }
        .list-group-item.active {
            background: var(--background-color);
            color: var(--accent-color);
            font-weight: 600;
        }
        .list-group-item.disabled {
            color: #6c757d;
            pointer-events: none;
            background-color: #e9ecef;
        }
        #wrapper {
            display: flex;
            width: 100%;
        }
        #page-content-wrapper {
            margin-left: 250px;
            padding: 40px 20px;
            width: calc(100% - 250px);
            min-height: 100vh;
        }
        .dashboard-card {
            background: linear-gradient(135deg, #FFFFFF 0%, #F8F9FA 100%);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: linear-gradient(to bottom, var(--accent-color), var(--secondary-color));
        }
        .dashboard-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        .dashboard-card h4 {
            color: var(--primary-color);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .text-gradient {
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            color: transparent;
        }
        .form-control, .form-select {
            border: 1px solid var(--primary-color);
            border-radius: 8px;
            transition: border-color 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 5px rgba(255, 215, 0, 0.5);
        }
        .form-label {
            color: var(--primary-color);
            font-weight: 500;
        }
        .btn-success {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            background: linear-gradient(45deg, var(--secondary-color), var(--accent-color));
            color: #FFFFFF;
            transform: translateY(-2px);
        }
        .profile-img-nav {
            width: 35px;
            height: 35px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--accent-color);
            margin-right: 10px;
        }
        footer {
            background: var(--primary-color);
            color: #FFFFFF;
            padding: 25px 0;
            margin-top: 50px;
            text-align: center;
            font-weight: 500;
        }
        @media (max-width: 768px) {
            #sidebar-wrapper {
                margin-left: -250px;
            }
            #page-content-wrapper {
                margin-left: 0;
                width: 100%;
            }
            #wrapper.toggled #sidebar-wrapper {
                margin-left: 0;
            }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .dashboard-card {
            animation: fadeIn 0.5s ease-in;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg neo-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-balance-scale me-2"></i>Lawyer Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="lawyers.php">Lawyers</a></li>
                    <li class="nav-item"><a class="nav-link" href="user_booking.php">My Bookings</a></li>
                    <li class="nav-item ms-3">
                        <a class="nav-link btn gradient-btn" href="#">
                            <img src="/<?php echo $profile_image; ?>" alt="Profile" class="profile-img-nav" onerror="this.src='/images/upload/default-profile.png'">
                            <?php echo $_SESSION['first_Name'] . ' ' . $_SESSION['last_Name']; ?>
                        </a>
                    </li>
                    <li class="nav-item ms-3"><a class="nav-link btn gradient-btn" href="logout.php">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex" id="wrapper">
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">My Profile</div>
            <div class="list-group list-group-flush">
                <a href="user_dashboard.php" class="list-group-item list-group-item-action bg-light">Dashboard</a>
                <a href="user_profile.php" class="list-group-item list-group-item-action bg-light">Edit Profile</a>
                <a href="user_booking.php" class="list-group-item list-group-item-action bg-light">My Booking Requests</a>
                <a href="user_case_detail.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'user_case_detail.php' ? 'active' : ''; ?>">
                    Case Details
                    <?php if (basename($_SERVER['PHP_SELF']) == 'user_case_detail.php'): ?>
                        <i class="fas fa-arrow-right float-end"></i>
                    <?php endif; ?>
                </a>
                <?php if ($has_case_details): ?>
                    <a href="user_document_management.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'user_document_management.php' ? 'active' : ''; ?>">
                        Document Upload
                        <?php if (basename($_SERVER['PHP_SELF']) == 'user_document_management.php'): ?>
                            <i class="fas fa-arrow-right float-end"></i>
                        <?php endif; ?>
                    </a>
                <?php else: ?>
                    <span class="list-group-item list-group-item-action bg-light disabled">Document Management (Awaiting Case Details)</span>
                <?php endif; ?>
                <a href="update_password.php" class="list-group-item list-group-item-action bg-light">Update Password</a>
            </div>
        </div>

        <div id="page-content-wrapper">
            <?php if (!empty($upload_message)) echo $upload_message; ?>

            <div class="container-fluid">
                <?php if ($has_case_details): ?>
                    <div class="dashboard-card">
                        <h4 class="text-gradient"><i class="fas fa-file-upload me-2"></i>Upload Document</h4>
                        <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="lawyer_id" class="form-label">Select Lawyer</label>
                                <select name="lawyer_id" id="lawyer_id" class="form-select" onchange="filterSubjects()" required>
                                    <option value="">Choose a Lawyer...</option>
                                    <?php
                                    $lawyer_query = "SELECT DISTINCT l.lawyer_id, u.first_Name, u.last_Name 
                                                    FROM booking b 
                                                    INNER JOIN lawyer l ON b.lawyer_id = l.lawyer_id 
                                                    INNER JOIN user u ON l.lawyer_id = u.u_id 
                                                    WHERE b.client_id = '$client_id' AND b.status = 'Accepted'";
                                    $lawyer_result = mysqli_query($conn, $lawyer_query);
                                    if (mysqli_num_rows($lawyer_result) > 0) {
                                        while ($lawyer = mysqli_fetch_assoc($lawyer_result)) {
                                            echo "<option value='{$lawyer['lawyer_id']}'>{$lawyer['first_Name']} {$lawyer['last_Name']}</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No lawyers with accepted bookings found</option>";
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">Please select a lawyer.</div>
                            </div>
                            <div class="mb-3">
                                <label for="booking_id" class="form-label">Select Subject</label>
                                <select name="booking_id" id="booking_id" class="form-select" required>
                                    <option value="">Choose a Subject...</option>
                                    <?php
                                    $subject_query = "SELECT b.booking_id, b.subject, b.lawyer_id 
                                                     FROM booking b 
                                                     WHERE b.client_id = '$client_id' AND b.status = 'Accepted'";
                                    $subject_result = mysqli_query($conn, $subject_query);
                                    if (mysqli_num_rows($subject_result) > 0) {
                                        while ($subject = mysqli_fetch_assoc($subject_result)) {
                                            echo "<option value='{$subject['booking_id']}' data-lawyer='{$subject['lawyer_id']}'>{$subject['subject']}</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No subjects found</option>";
                                    }
                                    ?>
                                </select>
                                <div class="invalid-feedback">Please select a subject.</div>
                            </div>
                            <div class="mb-3">
                                <label for="document" class="form-label">Upload Document</label>
                                <input type="file" name="document" id="document" class="form-control" accept=".pdf,.doc,.docx" required>
                                <div class="invalid-feedback">Please upload a document (PDF, DOC, or DOCX).</div>
                            </div>
                            <button type="submit" name="upload" class="btn btn-success">Upload</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="dashboard-card">
                        <h4 class="text-gradient"><i class="fas fa-info-circle me-2"></i>Document Management</h4>
                        <p class="text-muted">This feature will be available once a lawyer fills in case details for one of your accepted bookings.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <h5>All rights reserved © <?php echo date("Y"); ?></h5>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });

            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function (alert) {
                setTimeout(function () {
                    var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                    bsAlert.close();
                }, 3000);
            });
        });

        function filterSubjects() {
            var lawyerId = document.getElementById('lawyer_id').value;
            var subjectSelect = document.getElementById('booking_id');
            var options = subjectSelect.getElementsByTagName('option');

            for (var i = 0; i < options.length; i++) {
                var optionLawyer = options[i].getAttribute('data-lawyer');
                if (lawyerId === '' || optionLawyer === lawyerId) {
                    options[i].style.display = '';
                } else {
                    options[i].style.display = 'none';
                }
            }
            subjectSelect.value = '';
        }
    </script>
</body>
</html>
<?php
    mysqli_close($conn);
} else {
    header('location:login.php?deactivate');
}
?>