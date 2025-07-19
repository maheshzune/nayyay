<?php
session_start();
if ($_SESSION['login'] == TRUE && $_SESSION['status'] == 'Active') {
    include("db_con/dbCon.php");
    $conn = connect();

    // Handle Accept action
    if (isset($_GET['accept_id'])) {
        $id = $_GET['accept_id'];
        $sql = "UPDATE `booking` SET `status` = 'Accepted' WHERE booking_id = '$id'";
        $conn->query($sql);
        header("Location: lawyer_booking.php?message=Booking accepted as Active");
        exit;
    }

    // Handle Reject action
    if (isset($_GET['reject_id'])) {
        $id = $_GET['reject_id'];
        $sql = "UPDATE `booking` SET `status` = 'Rejected' WHERE booking_id = '$id'";
        $conn->query($sql);
        header("Location: lawyer_booking.php?message=Booking rejected");
        exit;
    }

    // Fetch lawyer's image and active case count
    $lawyer_id = $_SESSION['lawyer_id'];
    $image_query = "SELECT image FROM lawyer WHERE lawyer_id = '$lawyer_id'";
    $image_result = mysqli_query($conn, $image_query);
    $lawyer = mysqli_fetch_assoc($image_result);
    $profile_image = !empty($lawyer['image']) ? $lawyer['image'] : 'images/default-profile.png';

    // Count total active cases (Accepted status)
    $active_query = "SELECT COUNT(*) as active_cases FROM booking WHERE lawyer_id = '$lawyer_id' AND status = 'Accepted'";
    $active_result = mysqli_query($conn, $active_query);
    $active_count = mysqli_fetch_assoc($active_result)['active_cases'];
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/simple-sidebar.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/media.css">
    <title>Lawyer Management System - Booking Requests</title>
    <style>
        :root {
            --primary-color: #00274D;
            --accent-color: #FFD700;
            --background-color: #F8F9FA;
            --gradient-start: rgba(0, 39, 77, 0.85);
            --gradient-end: rgba(0, 39, 77, 0.65);
        }
        body {
            font-family: 'Inter', sans-serif;
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
        .bookingrqst {
            padding: 40px 20px;
            width: 100%;
        }
        .widget-header {
            background-color: var(--primary-color);
            color: #FFFFFF;
            padding: 15px;
            border-radius: 8px 8px 0 0;
            display: flex;
            align-items: center;
        }
        .widget-header i {
            margin-right: 10px;
        }
        .widget-content {
            background: #FFFFFF;
            padding: 20px;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .table {
            background-color: #FFFFFF;
            border-radius: 8px;
            overflow: hidden;
        }
        .table thead {
            background-color: var(--primary-color);
            color: var(--accent-color);
        }
        .table tbody tr {
            transition: background-color 0.3s ease;
        }
        .table tbody tr:hover {
            background-color: var(--background-color);
        }
        .btn-accept {
            background-color: #28a745;
            color: #FFFFFF;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn-accept:hover {
            background-color: #218838;
            color: #FFFFFF;
            text-decoration: none;
        }
        .btn-reject {
            background-color: #dc3545;
            color: #FFFFFF;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn-reject:hover {
            background-color: #c82333;
            color: #FFFFFF;
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
        .active-cases {
            background: #FFFFFF;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-left: 5px solid var(--accent-color);
        }
        .active-cases h5 {
            color: var(--primary-color);
            margin: 0;
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
            <a class="navbar-brand" href="#">Lawyer Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="lawyer_booking.php">Bookings</a>
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
                <a href="lawyer_booking.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'lawyer_booking.php' ? 'active' : ''; ?>">
                    Booking Requests
                    <?php if (basename($_SERVER['PHP_SELF']) == 'lawyer_booking.php'): ?>
                        <i class="fas fa-arrow-right float-end"></i>
                    <?php endif; ?>
                </a>
                <a href="lawyer_case_details.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'lawyer_case_details.php' ? 'active' : ''; ?>">
                    Case Details
                    <?php if (basename($_SERVER['PHP_SELF']) == 'lawyer_case_details.php'): ?>
                        <i class="fas fa-arrow-right float-end"></i>
                    <?php endif; ?>
                </a>
                <a href="lawyer_case_management.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'lawyer_case_management.php' ? 'active' : ''; ?>">
                    Case Management</a>
                <a href="update_password_admin.php" class="list-group-item list-group-item-action bg-light">Update Password</a>
            </div>
        </div>

        <section class="bookingrqst">
            <div class="container">
                <?php if (isset($_GET['message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                        <?php echo $_GET['message']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <!-- Total Active Cases -->
                <div class="active-cases">
                    <h5>Total Active Cases: <?php echo $active_count; ?></h5>
                </div>
                <div class="span7">
                    <div class="widget stacked widget-table action-table">
                        <div class="widget-header">
                            <i class="fas fa-th-list"></i>
                            <h3>Booking Requests</h3>
                        </div>
                        <div class="widget-content">
                            <table class="table table-striped table-bordered table-responsive">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Client Name</th>
                                        <th>Date</th>
                                        <th>Subject</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="myTable">
                                    <?php
                                    $a = $_SESSION['lawyer_id'];
                                    $result = mysqli_query($conn, "SELECT booking.booking_id, user.first_Name, user.last_Name, booking.date, booking.subject, booking.description, booking.status AS statuss 
                                        FROM booking 
                                        INNER JOIN client ON booking.client_id = client.client_id 
                                        INNER JOIN user ON client.client_id = user.u_id 
                                        WHERE booking.lawyer_id = '$a'");
                                    $counter = 0;
                                    while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                        <tr>
                                            <td><?php echo ++$counter; ?></td>
                                            <td><?php echo $row["first_Name"] . ' ' . $row["last_Name"]; ?></td>
                                            <td><?php echo $row["date"]; ?></td>
                                            <td><?php echo $row["subject"]; ?></td>
                                            <td><?php echo $row["description"]; ?></td>
                                            <td>
                                                <?php if ($row['statuss'] == 'Pending'): ?>
                                                    <a class="btn btn-sm btn-accept" href="lawyer_booking.php?accept_id=<?php echo $row['booking_id']; ?>" onclick="return confirm('Accept this booking?');">
                                                        <i class="fas fa-check"></i> Accept
                                                    </a>
                                                    <a class="btn btn-sm btn-reject" href="lawyer_booking.php?reject_id=<?php echo $row['booking_id']; ?>" onclick="return confirm('Reject this booking?');">
                                                        <i class="fas fa-times"></i> Reject
                                                    </a>
                                                <?php elseif ($row['statuss'] == 'Accepted'): ?>
                                                    <span class="text-success">Active</span>
                                                <?php elseif ($row['statuss'] == 'Rejected'): ?>
                                                    <span class="text-danger">Rejected</span>
                                                <?php else: ?>
                                                    <?php echo $row['statuss']; ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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