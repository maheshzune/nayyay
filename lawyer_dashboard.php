<?php
session_start();
if ($_SESSION['login'] == TRUE && $_SESSION['status'] == 'Active') {
    include("db_con/dbCon.php");
    $conn = connect();
    $lawyer_id = $_SESSION['u_id']; // Assuming lawyer logs in with u_id matching lawyer_id

    // Fetch lawyer's image
    $image_query = "SELECT image FROM lawyer WHERE lawyer_id = '$lawyer_id'";
    $image_result = mysqli_query($conn, $image_query);
    $lawyer = mysqli_fetch_assoc($image_result);
    $profile_image = !empty($lawyer['image']) ? $lawyer['image'] : 'images/upload/default-profile.png';

    // Count total active cases (status not Won, Lost, or Pending)
    $active_cases_query = "SELECT COUNT(*) as active_cases FROM `case` WHERE lawyer_id = '$lawyer_id' AND status NOT IN ('Won', 'Lost', 'Pending')";
    $active_cases_result = mysqli_query($conn, $active_cases_query);
    $active_cases_count = mysqli_fetch_assoc($active_cases_result)['active_cases'];

    // --- Calendar Events Query ---
    // Fetch cases with scheduled hearing dates for the calendar schedule
    $calendar_query = "SELECT case_id, hearing_date, case_type, location 
                         FROM `case` 
                         WHERE lawyer_id = '$lawyer_id' AND hearing_date IS NOT NULL";
    $calendar_result = mysqli_query($conn, $calendar_query);
    $calendarEvents = array();
    while ($row = mysqli_fetch_assoc($calendar_result)) {
        $calendarEvents[] = array(
            "title" => $row['case_type'] . " - " . $row['location'],
            "start" => $row['hearing_date'],
            "url"   => "lawyer_case_details.php?case_id=" . $row['case_id']
        );
    }

    // --- Simple Calendar Setup ---
    // Determine current month and year from GET parameters or default to current month/year
    $currentMonth = isset($_GET['month']) ? intval($_GET['month']) : date('m');
    $currentYear  = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

    // Calculate previous and next month values
    $prevMonth = $currentMonth - 1;
    $prevYear  = $currentYear;
    if ($prevMonth < 1) {
        $prevMonth = 12;
        $prevYear  = $currentYear - 1;
    }
    $nextMonth = $currentMonth + 1;
    $nextYear  = $currentYear;
    if ($nextMonth > 12) {
        $nextMonth = 1;
        $nextYear  = $currentYear + 1;
    }

    // First day index and total days for the specified month/year
    $firstDayIndex = date('w', strtotime("$currentYear-$currentMonth-01"));
    $totalDays     = date('t', strtotime("$currentYear-$currentMonth-01"));

    // Group events by date (formatted as Y-m-d)
    $groupedEvents = array();
    foreach ($calendarEvents as $event) {
        $dateKey = date('Y-m-d', strtotime($event['start']));
        if (!isset($groupedEvents[$dateKey])) {
            $groupedEvents[$dateKey] = array();
        }
        $groupedEvents[$dateKey][] = $event;
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" 
          integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/simple-sidebar.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/media.css">
    <title>Lawyer Management System - Dashboard</title>
    <style>
        :root {
            --primary-color: #00274D;
            --accent-color: #FFD700;
            --background-color: #F8F9FA;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--background-color);
            padding-top: 80px;
        }
        .neo-navbar {
            background: linear-gradient(135deg, rgba(248,249,250,0.95), rgba(248,249,250,0.85)) !important;
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
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
        /* Sidebar Styles - Make Sidebar Always Visible by setting margin-left to 0 */
        #sidebar-wrapper {
            min-height: 100vh;
            margin-left: 0;
            transition: margin .25s ease-out;
            background: #FFFFFF;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
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
        /* Page Content */
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
            transition: transform 0.3s ease;
            border-left: 5px solid var(--accent-color);
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .dashboard-card h4 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        .profile-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--primary-color);
            margin-right: 15px;
        }
        .profile-img-nav {
            width: 30px;
            height: 30px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--accent-color);
            margin-right: 8px;
        }
        .booking-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .booking-table th, .booking-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .booking-table th {
            background: var(--primary-color);
            color: var(--accent-color);
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
        /* Simple Calendar Styling */
        .calendar-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .calendar-table th, .calendar-table td {
            border: 1px solid #ccc;
            text-align: center;
            padding: 8px;
            vertical-align: top;
            height: 80px;
        }
        .calendar-table th {
            background: var(--primary-color);
            color: #fff;
        }
        .calendar-day {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .booking-event {
            font-size: 12px;
            background: #f0ad4e;
            color: #fff;
            padding: 2px 4px;
            border-radius: 3px;
            margin-top: 2px;
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
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg neo-navbar fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Lawyer Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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

    <!-- Main Content -->
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">My Profile</div>
            <div class="list-group list-group-flush">
                <a href="lawyer_dashboard.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'lawyer_dashboard.php' ? 'active' : ''; ?>">
                    Dashboard
                    <?php if (basename($_SERVER['PHP_SELF']) == 'lawyer_dashboard.php'): ?>
                        <i class="fas fa-arrow-right float-end"></i>
                    <?php endif; ?>
                </a>
                <a href="lawyer_edit_profile.php" class="list-group-item list-group-item-action bg-light">Edit Profile</a>
                <a href="lawyer_booking.php" class="list-group-item list-group-item-action bg-light">Booking Requests</a>
                <a href="lawyer_case_details.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'lawyer_case_details.php' ? 'active' : ''; ?>">
                    Case Details
                    <?php if (basename($_SERVER['PHP_SELF']) == 'lawyer_case_details.php'): ?>
                        <i class="fas fa-arrow-right float-end"></i>
                    <?php endif; ?>
                </a>
                <a href="lawyer_case_management.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'lawyer_case_management.php' ? 'active' : ''; ?>">
                    Case Management
                </a>
                <a href="update_password_admin.php" class="list-group-item list-group-item-action bg-light">Update Password</a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <?php if (isset($_GET['done'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                    <strong>Welcome!</strong> You are logged in as a Lawyer.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="container-fluid">
                <!-- Lawyer Profile Card -->
                <div class="dashboard-card">
                    <div class="d-flex align-items-center">
                        <img src="images/upload/<?php echo $profile_image; ?>" alt="Profile Image" class="profile-image">
                        <div>
                            <h4>Welcome, <?php echo $_SESSION['first_Name'] . ' ' . $_SESSION['last_Name']; ?></h4>
                            <p class="mb-0">Manage your bookings and profile details here.</p>
                        </div>
                    </div>
                </div>

                <!-- Total Active Cases -->
                <div class="active-cases">
                    <h5>Total Active Cases: <?php echo $active_cases_count; ?></h5>
                </div>

                <!-- Quick Stats -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="dashboard-card">
                            <?php
                            // Active cases from case table
                            $active_query = "SELECT COUNT(*) as active FROM `case` WHERE lawyer_id = '$lawyer_id' AND status NOT IN ('Won', 'Lost', 'Pending')";
                            $active_result = mysqli_query($conn, $active_query);
                            $active_count = mysqli_fetch_assoc($active_result)['active'];
                            ?>
                            <h4>Active Cases</h4>
                            <p><?php echo $active_count; ?> active cases</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-card">
                            <?php
                            // Completed cases (Won + Lost)
                            $completed_query = "SELECT COUNT(*) as completed FROM `case` WHERE lawyer_id = '$lawyer_id' AND status IN ('Won', 'Lost')";
                            $completed_result = mysqli_query($conn, $completed_query);
                            $completed_count = mysqli_fetch_assoc($completed_result)['completed'];
                            ?>
                            <h4>Completed Cases</h4>
                            <p><?php echo $completed_count; ?> resolved cases</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Active Cases -->
                <div class="dashboard-card">
                    <h4>Recent Active Cases</h4>
                    <?php
                    $case_query = "SELECT case_id, case_type, location, status FROM `case` WHERE lawyer_id = '$lawyer_id' AND status NOT IN ('Won', 'Lost', 'Pending') ORDER BY case_id DESC LIMIT 5";
                    $case_result = mysqli_query($conn, $case_query);
                    if (mysqli_num_rows($case_result) > 0) {
                        echo '<table class="booking-table">';
                        echo '<thead><tr><th>ID</th><th>Type</th><th>Location</th><th>Status</th></tr></thead>';
                        echo '<tbody>';
                        while ($row = mysqli_fetch_assoc($case_result)) {
                            echo "<tr>";
                            echo "<td>{$row['case_id']}</td>";
                            echo "<td>{$row['case_type']}</td>";
                            echo "<td>{$row['location']}</td>";
                            echo "<td>";
                            if ($row['status'] == 'Won') {
                                echo '<span class="text-success">Won</span>';
                            } elseif ($row['status'] == 'Lost') {
                                echo '<span class="text-danger">Lost</span>';
                            } elseif ($row['status'] == 'Pending') {
                                echo '<span class="text-warning">Pending</span>';
                            } else {
                                echo '<span class="text-primary">Active</span>';
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo '</tbody></table>';
                    } else {
                        echo '<p>No recent active cases found.</p>';
                    }
                    ?>
                </div>

                <!-- Simple Calendar with Schedule -->
                <div class="dashboard-card">
                    <h4>Schedule</h4>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>" class="btn btn-outline-primary btn-sm">« Previous Month</a>
                        <h5 class="mb-0"><?php echo date('F Y', strtotime("$currentYear-$currentMonth-01")); ?></h5>
                        <a href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>" class="btn btn-outline-primary btn-sm">Next Month »</a>
                    </div>
                    <table class="calendar-table">
                        <thead>
                            <tr>
                                <th>Sun</th>
                                <th>Mon</th>
                                <th>Tue</th>
                                <th>Wed</th>
                                <th>Thu</th>
                                <th>Fri</th>
                                <th>Sat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $day = 1;
                                $totalCells = ceil(($totalDays + $firstDayIndex) / 7) * 7;
                                for ($cell = 0; $cell < $totalCells; $cell++) {
                                    if ($cell % 7 == 0) {
                                        echo "<tr>";
                                    }
                                    if ($cell < $firstDayIndex || $day > $totalDays) {
                                        echo "<td></td>";
                                    } else {
                                        $dateStr = sprintf("%04d-%02d-%02d", $currentYear, $currentMonth, $day);
                                        echo "<td><div class='calendar-day'>{$day}</div>";
                                        if (isset($groupedEvents[$dateStr])) {
                                            foreach ($groupedEvents[$dateStr] as $event) {
                                                echo "<div class='booking-event'><a href='" . htmlspecialchars($event['url']) . "' style='color: #fff; text-decoration: none;'>" . htmlspecialchars($event['title']) . "</a></div>";
                                            }
                                        }
                                        echo "</td>";
                                        $day++;
                                    }
                                    if ($cell % 7 == 6) {
                                        echo "</tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h5>All rights reserved <?php echo date("Y"); ?></h5>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery and Bootstrap Bundle JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" 
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" 
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" 
            crossorigin="anonymous"></script>
    <script>
        // Auto-hide success alert after 3 seconds
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