<?php
session_start();

// Handle AJAX request to fetch case_type
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fetch_case_type']) && isset($_POST['client_id'])) {
    include("db_con/dbCon.php");
    $conn = connect();
    $client_id = $_POST['client_id'];
    $lawyer_id = $_SESSION['lawyer_id'] ?? $_SESSION['u_id'];

    $stmt = $conn->prepare("SELECT b.subject 
                            FROM booking b 
                            WHERE b.client_id = ? AND b.lawyer_id = ? AND b.status = 'Accepted' 
                            LIMIT 1");
    $stmt->bind_param("ss", $client_id, $lawyer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode(['success' => true, 'case_type' => $row['subject']]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $conn->close();
    exit;
}

// Redirect if not logged in or not an active lawyer
if (!isset($_SESSION['login']) || $_SESSION['login'] !== TRUE || !isset($_SESSION['status']) || $_SESSION['status'] !== 'Active') {
    header('Location: login.php?deactivate');
    exit;
}

// Determine lawyer ID
$lawyer_id = isset($_SESSION['lawyer_id']) ? $_SESSION['lawyer_id'] : (isset($_SESSION['u_id']) ? $_SESSION['u_id'] : null);
if (!$lawyer_id) {
    header('Location: login.php?error=no_lawyer_id');
    exit;
}

include("db_con/dbCon.php");
$conn = connect();
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Verify lawyer role and fetch profile image
$stmt = $conn->prepare("SELECT l.image FROM lawyer l INNER JOIN user u ON l.lawyer_id = u.u_id WHERE l.lawyer_id = ? AND u.role = 'Lawyer'");
$stmt->bind_param("s", $lawyer_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    header('Location: login.php?error=not_lawyer');
    exit;
}
$lawyer = $result->fetch_assoc();
$profile_image = !empty($lawyer['image']) ? htmlspecialchars($lawyer['image']) : 'images/upload/default-profile.png';
$stmt->close();

// Handle form submission for adding a new case
$message = '';
if (isset($_POST['submit'])) {
    $client_id = $_POST['client_id'];
    $case_type = $_POST['case_type'];
    $location = $_POST['location'];
    $fees = $_POST['fees'];
    $deadline = $_POST['deadline'] ?: null;
    $hearing_date = $_POST['hearing_date'] ?: null;
    $document_path = $_FILES['document_path']['name'] ? uploadFile($_FILES['document_path']) : null;
    $notes = $_POST['notes'] ?? '';
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO `case` (client_id, lawyer_id, case_type, status, location, fees, document_path, notes, deadline, hearing_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssdssss", $client_id, $lawyer_id, $case_type, $status, $location, $fees, $document_path, $notes, $deadline, $hearing_date);
    if ($stmt->execute()) {
        $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'>Case saved successfully!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Error saving case: " . htmlspecialchars($conn->error) . "<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
    $stmt->close();
}

// Handle status update for an existing case
if (isset($_POST['update_status'])) {
    $case_id = $_POST['case_id'];
    $new_status = $_POST['new_status'];

    $stmt = $conn->prepare("UPDATE `case` SET `status` = ? WHERE `case_id` = ? AND `lawyer_id` = ?");
    $stmt->bind_param("sis", $new_status, $case_id, $lawyer_id);
    if ($stmt->execute()) {
        $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'>Status updated successfully!<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    } else {
        $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Error updating status: " . htmlspecialchars($conn->error) . "<button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
    }
    $stmt->close();
}

// File upload function
function uploadFile($file) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($file["name"]);
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    }
    return null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/simple-sidebar.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/media.css">
    <title>Lawyer Management System - Case Details</title>
    <style>
        :root {
            --primary-color: #00274D;
            --accent-color: #FFD700;
            --background-color: #F8F9FA;
            --text-color: #333;
            --shadow-light: rgba(0, 0, 0, 0.05);
            --shadow-medium: rgba(0, 0, 0, 0.1);
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--background-color);
            padding-top: 80px;
            margin: 0;
            color: var(--text-color);
            font-size: 16px;
        }
        .neo-navbar {
            background: linear-gradient(135deg, rgba(248, 249, 250, 0.95), rgba(248, 249, 250, 0.85));
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 20px var(--shadow-light);
            padding: 15px 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }
        .navbar-brand {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.5rem;
            transition: color 0.3s ease;
        }
        .navbar-brand:hover {
            color: var(--accent-color);
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
            color: var(--accent-color);
            font-weight: 600;
            border-bottom: 2px solid var(--accent-color);
        }
        .btn-custom {
            background: var(--primary-color);
            color: #fff;
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
        }
        .btn-custom:hover {
            background: var(--accent-color);
            color: var(--primary-color);
            transform: scale(1.05);
        }
        .navbar-toggler {
            border: none;
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%2300274D' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        #sidebar-wrapper {
            position: fixed;
            top: 80px;
            left: 0;
            width: 250px;
            min-height: calc(100vh - 80px);
            background: #fff;
            box-shadow: 2px 0 10px var(--shadow-light);
            transition: margin 0.25s ease-out;
            z-index: 1000;
            overflow-y: auto;
        }
        .sidebar-heading {
            background: var(--primary-color);
            color: #fff;
            padding: 15px;
            font-size: 1.25rem;
            font-weight: 600;
        }
        .list-group-item {
            color: var(--primary-color);
            font-weight: 500;
            padding: 15px 20px;
            transition: all 0.3s ease;
            border: none;
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
        #wrapper {
            display: flex;
            width: 100%;
        }
        #page-content-wrapper {
            margin-left: 250px;
            padding: 30px 15px;
            width: calc(100% - 250px);
            min-height: 100vh;
        }
        .dashboard-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px var(--shadow-light);
            border-left: 4px solid var(--accent-color);
            transition: transform 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px var(--shadow-medium);
        }
        .dashboard-card h4 {
            color: var(--primary-color);
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 5px;
            display: block;
        }
        .form-control {
            border: 1px solid var(--primary-color);
            border-radius: 5px;
            padding: 8px 12px;
            font-size: 0.95rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 5px rgba(255, 215, 0, 0.4);
            outline: none;
        }
        .btn-success {
            background: var(--primary-color);
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            color: #fff;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            background: var(--accent-color);
            color: var(--primary-color);
        }
        .profile-img-nav {
            width: 30px;
            height: 30px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--accent-color);
            margin-right: 8px;
            vertical-align: middle;
        }
        .case-card {
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 6px var(--shadow-light);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .case-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px var(--shadow-medium);
        }
        .case-card h5 {
            color: var(--primary-color);
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 12px;
        }
        .case-card p {
            margin: 6px 0;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        .case-card strong {
            color: var(--primary-color);
            font-weight: 500;
        }
        footer {
            background: var(--primary-color);
            color: #fff;
            padding: 20px 0;
            text-align: center;
            margin-top: 40px;
        }
        footer h5 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 400;
        }
        @media (max-width: 992px) {
            #sidebar-wrapper { margin-left: -250px; }
            #page-content-wrapper { margin-left: 0; width: 100%; }
            #wrapper.toggled #sidebar-wrapper { margin-left: 0; }
        }
        @media (max-width: 768px) {
            body { padding-top: 70px; }
            .neo-navbar { padding: 10px 0; }
            .navbar-brand { font-size: 1.25rem; }
            .nav-link { padding: 8px 10px; }
            .btn-custom { padding: 6px 15px; font-size: 0.9rem; }
            #page-content-wrapper { padding: 20px 10px; }
            .dashboard-card { padding: 15px; }
            .dashboard-card h4 { font-size: 1.25rem; }
            .form-control { font-size: 0.9rem; }
            .btn-success { padding: 8px 15px; }
            .case-card { padding: 12px; }
            .case-card h5 { font-size: 1.1rem; }
            .case-card p { font-size: 0.85rem; }
        }
        @media (max-width: 576px) {
            .form-group { margin-bottom: 12px; }
            .form-control { padding: 6px 10px; }
            .btn-success, .btn-custom { width: 100%; padding: 8px; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg neo-navbar fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">Lawyer Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="lawyer_booking.php">Bookings</a></li>
                    <li class="nav-item ms-3">
                        <a class="nav-link btn-custom" href="#">
                            <img src="<?php echo $profile_image; ?>" alt="Profile" class="profile-img-nav">
                            <?php echo htmlspecialchars($_SESSION['first_Name'] . ' ' . $_SESSION['last_Name']); ?>
                        </a>
                    </li>
                    <li class="nav-item ms-3"><a class="nav-link btn-custom" href="logout.php">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex" id="wrapper">
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">My Profile</div>
            <div class="list-group list-group-flush">
                <a href="lawyer_dashboard.php" class="list-group-item list-group-item-action bg-light">Dashboard</a>
                <a href="lawyer_edit_profile.php" class="list-group-item list-group-item-action bg-light">Edit Profile</a>
                <a href="lawyer_booking.php" class="list-group-item list-group-item-action bg-light">Booking Requests</a>
                <a href="lawyer_case_details.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'lawyer_case_details.php' ? 'active' : ''; ?>">
                    Case Details
                    <?php if (basename($_SERVER['PHP_SELF']) == 'lawyer_case_details.php'): ?>
                        <i class="fas fa-arrow-right float-end"></i>
                    <?php endif; ?>
                </a>
                <a href="lawyer_case_management.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'lawyer_case_management.php' ? 'active' : ''; ?>">Case Management</a>
                <a href="update_password_admin.php" class="list-group-item list-group-item-action bg-light">Update Password</a>
            </div>
        </div>

        <div id="page-content-wrapper">
            <?php if ($message) echo $message; ?>
            <div class="container-fluid">
                <!-- Case Details Form -->
                <div class="dashboard-card">
                    <h4>Fill Case Details</h4>
                    <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="form-group">
                            <label for="client_id">Select Client</label>
                            <select name="client_id" id="client_id" class="form-control" required>
                                <option value="">Choose a Client...</option>
                                <?php
                                $stmt = $conn->prepare("SELECT DISTINCT b.client_id, u.first_Name, u.last_Name 
                                                        FROM booking b 
                                                        INNER JOIN user u ON b.client_id = u.u_id 
                                                        WHERE b.lawyer_id = ? AND b.status = 'Accepted'");
                                $stmt->bind_param("s", $lawyer_id);
                                $stmt->execute();
                                $client_result = $stmt->get_result();
                                while ($client = $client_result->fetch_assoc()) {
                                    echo "<option value='{$client['client_id']}'>" . htmlspecialchars("{$client['first_Name']} {$client['last_Name']}") . "</option>";
                                }
                                $stmt->close();
                                ?>
                            </select>
                            <div class="invalid-feedback">Please select a client.</div>
                        </div>
                        <div class="form-group">
                            <label for="case_type">Case Type</label>
                            <input type="text" name="case_type" id="case_type" class="form-control" required readonly>
                            <div class="invalid-feedback">Please wait for case type to load after selecting a client.</div>
                        </div>
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" name="location" id="location" class="form-control" required>
                            <div class="invalid-feedback">Please enter the case location.</div>
                        </div>
                        <div class="form-group">
                            <label for="fees">Fees</label>
                            <input type="number" name="fees" id="fees" class="form-control" step="0.01" required>
                            <div class="invalid-feedback">Please enter the fees.</div>
                        </div>
                        <div class="form-group">
                            <label for="deadline">Deadline</label>
                            <input type="date" name="deadline" id="deadline" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="hearing_date">Hearing Date</label>
                            <input type="date" name="hearing_date" id="hearing_date" class="form-control" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                            <div class="invalid-feedback">Hearing date must be after today.</div>
                        </div>
                        <div class="form-group">
                            <label for="document_path">Upload Document</label>
                            <input type="file" name="document_path" id="document_path" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="Pending">Pending</option>
                                <option value="Accepted">Accepted</option>
                                <option value="Under Hearing">Under Hearing</option>
                                <option value="Won">Won</option>
                                <option value="Lost">Lost</option>
                                <option value="Disposed">Disposed</option>
                            </select>
                            <div class="invalid-feedback">Please select a status.</div>
                        </div>
                        <button type="submit" name="submit" class="btn btn-success mt-3">Save Case Details</button>
                    </form>
                </div>

             

                <!-- View Existing Cases -->
                <div class="dashboard-card">
                    <h4>My Cases</h4>
                    <?php
                    $stmt = $conn->prepare("SELECT case_id, case_type, status, location, fees, document_path, notes, deadline, hearing_date, created_at, updated_at 
                                            FROM `case` WHERE lawyer_id = ? ORDER BY deadline ASC");
                    $stmt->bind_param("s", $lawyer_id);
                    $stmt->execute();
                    $cases = $stmt->get_result();
                    if ($cases->num_rows > 0) {
                        while ($case = $cases->fetch_assoc()) {
                            echo '<div class="case-card">';
                            echo "<h5>Case #{$case['case_id']} - " . htmlspecialchars($case['case_type']) . "</h5>";
                            echo "<p><strong>Status:</strong> " . htmlspecialchars($case['status']) . "</p>";
                            echo "<p><strong>Location:</strong> " . htmlspecialchars($case['location']) . "</p>";
                            echo "<p><strong>Fees:</strong> $" . number_format($case['fees'], 2) . "</p>";
                            echo $case['deadline'] ? "<p><strong>Deadline:</strong> " . date('M d, Y', strtotime($case['deadline'])) . "</p>" : '';
                            echo $case['hearing_date'] ? "<p><strong>Hearing Date:</strong> " . date('M d, Y', strtotime($case['hearing_date'])) . "</p>" : '';
                            echo $case['document_path'] ? "<p><strong>Document:</strong> <a href='" . htmlspecialchars($case['document_path']) . "' target='_blank'>View</a></p>" : '';
                            echo "<p><strong>Notes:</strong> " . (empty($case['notes']) ? 'No notes' : htmlspecialchars($case['notes'])) . "</p>";
                            echo "<p><strong>Created At:</strong> " . date('M d, Y H:i', strtotime($case['created_at'])) . "</p>";
                            echo "<p><strong>Updated At:</strong> " . date('M d, Y H:i', strtotime($case['updated_at'])) . "</p>";
                            echo '</div>';
                        }
                    } else {
                        echo '<p class="text-muted">No cases found.</p>';
                    }
                    $stmt->close();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h5>All rights reserved © <?php echo date("Y"); ?></h5>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
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

            // AJAX to fetch case type based on client selection
            $('#client_id').change(function () {
                var clientId = $(this).val();
                if (clientId) {
                    $.ajax({
                        url: '<?php echo $_SERVER['PHP_SELF']; ?>',
                        type: 'POST',
                        data: { fetch_case_type: true, client_id: clientId },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success && response.case_type) {
                                $('#case_type').val(response.case_type);
                            } else {
                                $('#case_type').val('');
                                alert('No case type found for this client.');
                            }
                        },
                        error: function () {
                            $('#case_type').val('');
                            alert('Error fetching case type.');
                        }
                    });
                } else {
                    $('#case_type').val('');
                }
            });

            // Validate hearing date to be after today
            var today = new Date('2025-03-16'); // Current date as per instruction
            var tomorrow = new Date(today);
            tomorrow.setDate(today.getDate() + 1);
            var minDate = tomorrow.toISOString().split('T')[0];
            var hearingDateInput = document.getElementById('hearing_date');

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
    </script>
</body>
</html>
<?php
$conn->close();
?>