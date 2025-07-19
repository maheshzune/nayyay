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

    // Handle password update
    $message = '';
    if (isset($_POST['update'])) {
        $email = $_SESSION['email'];
        $current = mysqli_real_escape_string($conn, $_POST['current']);
        $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
        $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

        if (strlen($new_password) < 6) {
            $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            New password must be at least 6 characters long.
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
        } elseif ($new_password !== $confirm_password) {
            $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            New password and confirm password do not match.
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>";
        } else {
            $result = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email' AND password = '$current' AND role = 'Lawyer'");
            if (mysqli_num_rows($result) > 0) {
                $query = "UPDATE user SET password = '$new_password' WHERE email = '$email'";
                if (mysqli_query($conn, $query)) {
                    $message = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                    Password successfully updated.
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>";
                } else {
                    $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                    Error updating password: " . mysqli_error($conn) . "
                                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                                </div>";
                }
            } else {
                $message = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                Current password is incorrect. Please try again.
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                            </div>";
            }
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
    <title>Lawyer Management System - Update Password</title>
    <style>
        :root {
            --primary-color: #00274D;
            --accent-color: #FFD700;
            --background-color: #F8F9FA;
            --gradient-start: rgba(0, 39, 77, 0.85);
            --gradient-end: rgba(0, 39, 77, 0.65);
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
            transition: all 0.3s ease;
        }
        .gradient-btn:hover {
            background-color: var(--accent-color);
            color: var(--primary-color);
            transform: scale(1.05);
            text-decoration: none;
        }
        .navbar-toggler {
            border: none;
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%2300274D' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        #sidebar-wrapper {
            min-height: 100vh;
            margin-left: -15rem;
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
            transition: all 0.3s ease;
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
        .card {
            background: #FFFFFF;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-left: 5px solid var(--accent-color);
        }
        .card-title h4 {
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        .form-group label {
            color: var(--primary-color);
            font-weight: 500;
        }
        .form-control {
            border: 1px solid var(--primary-color);
            border-radius: 5px;
            transition: border-color 0.3s ease;
        }
        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 5px rgba(255, 215, 0, 0.5);
        }
        .btn-success {
            background-color: var(--primary-color);
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .btn-success:hover {
            background-color: var(--accent-color);
            color: var(--primary-color);
        }
        .profile-img-nav {
            width: 30px;
            height: 30px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--accent-color);
            margin-right: 8px;
        }
        footer {
            background-color: var(--primary-color);
            color: #FFFFFF;
            padding: 20px 0;
            margin-top: 40px;
            text-align: center;
        }
        footer h5 {
            margin: 0;
            font-size: 1rem;
        }
        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
            }
            #wrapper.toggled #sidebar-wrapper {
                margin-left: -15rem;
            }
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
                    <li class="nav-item">
                        <a class="nav-link" href="lawyer_booking.php">Bookings</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="nav-link btn gradient-btn" href="#">
                            <img src="images/upload/<?php echo $profile_image; ?>" alt="Profile" class="profile-img-nav">
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
        <div class="bg-light border-right" id="sidebar-wrapper">
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
                <a href="lawyer_case_management.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'lawyer_case_management.php' ? 'active' : ''; ?>">
                    Case Management</a>
                <a href="update_password_admin.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'update_password_admin.php' ? 'active' : ''; ?>">
                    Update Password
                    <?php if (basename($_SERVER['PHP_SELF']) == 'update_password_admin.php'): ?>
                        <i class="fas fa-arrow-right float-end"></i>
                    <?php endif; ?>
                </a>
            </div>
        </div>

        <div id="page-content-wrapper">
            <?php if (isset($_GET['done'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                    <strong>Welcome!</strong> You are logged in as a Lawyer.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php echo $message; ?>
            <div class="container-fluid">
                <div class="card">
                    <div class="card-title">
                        <h4>Update Password</h4>
                    </div>
                    <div class="card-body">
                        <form autocomplete="off" method="post" action="update_password_admin.php" class="needs-validation" novalidate>
                            <div class="form-group row">
                                <label for="current" class="col-sm-3 col-form-label">Current Password</label>
                                <div class="col-sm-8">
                                    <input type="password" name="current" class="form-control" id="current" placeholder="Enter Current Password" required>
                                    <div class="invalid-feedback">Please enter your current password.</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="new_password" class="col-sm-3 col-form-label">New Password</label>
                                <div class="col-sm-8">
                                    <input type="password" name="new_password" class="form-control" id="new_password" placeholder="Enter New Password" minlength="6" required>
                                    <div class="invalid-feedback">New password must be at least 6 characters long.</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="confirm_password" class="col-sm-3 col-form-label">Confirm Password</label>
                                <div class="col-sm-8">
                                    <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm New Password" required>
                                    <div class="invalid-feedback">Passwords do not match.</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-8 offset-sm-3">
                                    <input type="submit" name="update" value="Update" class="btn btn-success">
                                </div>
                            </div>
                        </form>
                    </div>
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
                    var password = document.getElementById('new_password').value;
                    var confirmPassword = document.getElementById('confirm_password').value;
                    if (password !== confirmPassword) {
                        document.getElementById('confirm_password').setCustomValidity("Passwords don't match");
                    } else {
                        document.getElementById('confirm_password').setCustomValidity('');
                    }
                    form.classList.add('was-validated');
                }, false);
            });

            var successAlert = document.getElementById('successAlert');
            if (successAlert) {
                setTimeout(function () {
                    var alert = bootstrap.Alert.getOrCreateInstance(successAlert);
                    alert.close();
                }, 3000);
            }
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