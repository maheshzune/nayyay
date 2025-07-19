<?php
session_start();
if ($_SESSION['login'] == TRUE && $_SESSION['status'] == 'Active') {
    include("db_con/dbCon.php");
    $conn = connect();
    $client_id = $_SESSION['client_id'];

    // Fetch client's image (fallback to default if not set)
    $image_query = "SELECT image FROM client WHERE client_id = ?";
    $stmt = $conn->prepare($image_query);
    $stmt->bind_param("s", $client_id);
    $stmt->execute();
    $image_result = $stmt->get_result();
    $client = $image_result->fetch_assoc();
    $profile_image = !empty($client['image']) ? $client['image'] : 'images/upload/default-profile.png';

    // Count active cases (exclude statuses: Pending, Won, Lose)
    $active_cases_query = "SELECT COUNT(*) as active_cases FROM `case` WHERE client_id = ? AND status NOT IN ('Pending', 'Won', 'Lose')";
    $stmt = $conn->prepare($active_cases_query);
    $stmt->bind_param("s", $client_id);
    $stmt->execute();
    $active_cases_result = $stmt->get_result();
    $active_cases_count = $active_cases_result->fetch_assoc()['active_cases'];

    // Fetch active cases with details
    $active_query = "SELECT case_id, case_type, status, location, fees, created_at 
                     FROM `case` 
                     WHERE client_id = ? AND status NOT IN ('Pending', 'Won', 'Lose') 
                     ORDER BY created_at DESC LIMIT 5";
    $stmt = $conn->prepare($active_query);
    $stmt->bind_param("s", $client_id);
    $stmt->execute();
    $active_result = $stmt->get_result();

    // Define color map for case types
    $color_map = array(
        'Civil'     => '#28a745',    // Green
        'Criminal'  => '#dc3545',    // Red
        'Family'    => '#ffc107',    // Yellow
        'Corporate' => '#007bff',    // Blue
        'Labor'     => '#6c757d'     // Gray
    );

    // Fetch cases with scheduled hearing date for the hearing schedule
    $calendar_query = "SELECT case_id, hearing_date, case_type, location 
                       FROM `case` 
                       WHERE client_id = ? AND hearing_date IS NOT NULL";
    $stmt = $conn->prepare($calendar_query);
    $stmt->bind_param("s", $client_id);
    $stmt->execute();
    $calendar_result = $stmt->get_result();
    $events = array();
    while ($row = $calendar_result->fetch_assoc()) {
        $case_type = $row['case_type'];
        $color = isset($color_map[$case_type]) ? $color_map[$case_type] : '#007bff';
        $events[] = array(
            'title'           => $row['case_type'] . " - " . $row['location'],
            'start'           => $row['hearing_date'],
            'url'             => 'user_case_detail.php?case_id=' . $row['case_id'],
            'backgroundColor' => $color
        );
    }
    // Group events by date (using Y-m-d format)
    $calendarEvents = array();
    foreach ($events as $event) {
        $datePart = date('Y-m-d', strtotime($event['start']));
        if (!isset($calendarEvents[$datePart])) {
            $calendarEvents[$datePart] = array();
        }
        $calendarEvents[$datePart][] = $event;
    }

    // --- Calendar Navigation ---
    // Use GET parameters "month" and "year"; if not provided, default to current month/year.
    $currentMonth = isset($_GET['month']) ? intval($_GET['month']) : date('m');
    $currentYear = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

    // Calculate previous and next month values
    $prevMonth = $currentMonth - 1;
    $prevYear = $currentYear;
    if ($prevMonth < 1) {
        $prevMonth = 12;
        $prevYear = $currentYear - 1;
    }
    $nextMonth = $currentMonth + 1;
    $nextYear = $currentYear;
    if ($nextMonth > 12) {
        $nextMonth = 1;
        $nextYear = $currentYear + 1;
    }

    // Calculate the first day index and total days for the specified month/year
    $firstDayIndex = date('w', strtotime("$currentYear-$currentMonth-01"));
    $totalDays = date('t', strtotime("$currentYear-$currentMonth-01"));
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Google Fonts, Bootstrap, Font Awesome, and Custom CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="css/simple-sidebar.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/media.css">
    <title>Lawyer Management System - Dashboard</title>
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
            padding-top: 90px;
            margin: 0;
        }
        .neo-navbar {
            background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(248,249,250,0.85));
            backdrop-filter: blur(12px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
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
            box-shadow: 2px 0 15px rgba(0,0,0,0.1);
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
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
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
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
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
        .profile-image {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid var(--secondary-color);
            margin-right: 20px;
        }
        .profile-img-nav {
            width: 35px;
            height: 35px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--accent-color);
            margin-right: 10px;
        }
        .booking-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
        }
        .booking-table th, .booking-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        .booking-table th {
            background: var(--primary-color);
            color: var(--accent-color);
            font-weight: 600;
        }
        .status-active {
            background-color: #28a745;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.85rem;
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
        footer {
            background: var(--primary-color);
            color: #FFFFFF;
            padding: 25px 0;
            margin-top: 50px;
            text-align: center;
            font-weight: 500;
        }
        .legend {
            margin-top: 20px;
        }
        .legend h5 {
            color: var(--primary-color);
            font-weight: 600;
        }
        .legend ul {
            list-style: none;
            padding: 0;
        }
        .legend li {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        .legend span {
            display: inline-block;
            width: 10px;
            height: 10px;
            margin-right: 5px;
        }
        /* Calendar Styles for Hearing Schedule */
        .calendar-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
        }
        .booking-event {
            margin-top: 4px;
            font-size: 12px;
            background: #f0ad4e;
            color: #fff;
            padding: 2px 4px;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg neo-navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-balance-scale me-2"></i>Lawyer Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
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

    <!-- Main Content -->
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">My Profile</div>
            <div class="list-group list-group-flush">
                <a href="user_dashboard.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'user_dashboard.php' ? 'active' : ''; ?>">Dashboard</a>
                <a href="user_profile.php" class="list-group-item list-group-item-action bg-light">Edit Profile</a>
                <a href="user_booking.php" class="list-group-item list-group-item-action bg-light">My Bookings</a>
                <a href="user_case_detail.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'user_case_detail.php' ? 'active' : ''; ?>">Case Details</a>
                <a href="user_document_management.php" class="list-group-item list-group-item-action bg-light">Upload Document</a>
                <a href="update_password.php" class="list-group-item list-group-item-action bg-light">Update Password</a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <?php if (isset($_GET['done'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                    <strong>Welcome!</strong> You are logged in as a Client.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="container-fluid">
                <!-- Client Profile Card -->
                <div class="dashboard-card">
                    <div class="d-flex align-items-center">
                        <img src="/<?php echo $profile_image; ?>" alt="Profile Image" class="profile-image">
                        <div>
                            <h4 class="text-gradient">Welcome, <?php echo $_SESSION['first_Name']; ?>!</h4>
                            <p class="mb-0 fw-medium">Manage your legal journey with ease.</p>
                        </div>
                    </div>
                </div>

                <!-- Active Cases -->
                <div class="dashboard-card">
                    <h4><i class="fas fa-briefcase me-2"></i>Active Cases</h4>
                    <p class="fw-bold"><?php echo $active_cases_count; ?> active cases</p>
                    <?php
                    if ($active_result->num_rows > 0) {
                        echo '<table class="booking-table">';
                        echo '<thead><tr><th>Case ID</th><th>Type</th><th>Status</th><th>Location</th><th>Fees</th><th>Created</th></tr></thead>';
                        echo '<tbody>';
                        while ($row = $active_result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row['case_id']}</td>";
                            echo "<td>{$row['case_type']}</td>";
                            echo "<td><span class='status-active'>Active</span></td>";
                            echo "<td>{$row['location']}</td>";
                            echo "<td>$" . number_format($row['fees'], 2) . "</td>";
                            echo "<td>" . date('M d, Y', strtotime($row['created_at'])) . "</td>";
                            echo "</tr>";
                        }
                        echo '</tbody></table>';
                    } else {
                        echo '<p class="text-muted">No active cases found.</p>';
                    }
                    ?>
                    <a href="user_booking.php" class="btn btn-success mt-3">View All Cases</a>
                </div>

                <!-- Hearing Schedule (Calendar View with Navigation) -->
                <div class="dashboard-card">
                    <h4 class="text-gradient"><i class="fas fa-calendar-alt me-2"></i>Hearing Schedule</h4>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="user_dashboard.php?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>" class="btn btn-outline-primary btn-sm">« Previous Month</a>
                        <h5 class="mb-0"><?php echo date('F Y', strtotime("$currentYear-$currentMonth-01")); ?></h5>
                        <a href="user_dashboard.php?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>" class="btn btn-outline-primary btn-sm">Next Month »</a>
                    </div>
                    
                    <?php if (empty($calendarEvents)): ?>
                        <p class="text-muted">No cases scheduled.</p>
                    <?php else: ?>
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
                                $rowsNeeded = ceil(($totalDays + $firstDayIndex) / 7); // Ensure we have enough rows
                                for ($i = 0; $i < $rowsNeeded; $i++) {
                                    echo "<tr>";
                                    for ($j = 0; $j < 7; $j++) {
                                        $cellIndex = ($i * 7) + $j;
                                        if ($cellIndex < $firstDayIndex || $day > $totalDays) {
                                            echo "<td></td>";
                                        } else {
                                            $dateStr = sprintf("%04d-%02d-%02d", $currentYear, $currentMonth, $day);
                                            echo "<td>";
                                            echo "<div class='calendar-day'>$day</div>";
                                            if (isset($calendarEvents[$dateStr])) {
                                                foreach ($calendarEvents[$dateStr] as $event) {
                                                    $color = $event['backgroundColor'];
                                                    echo "<div class='booking-event' style='background-color: $color;'><a href='" . htmlspecialchars($event['url']) . "' style='color: #fff; text-decoration: none;'>" . htmlspecialchars($event['title']) . "</a></div>";
                                                }
                                            }
                                            echo "</td>";
                                            $day++;
                                        }
                                    }
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                        <!-- Legend for Case Types -->
                        <div class="legend">
                            <h5>Legend</h5>
                            <ul>
                                <?php foreach ($color_map as $type => $color): ?>
                                    <li><span style="background-color: <?php echo $color; ?>;"></span> <?php echo $type; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h5>All rights reserved © <?php echo date("Y"); ?></h5>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
    $stmt->close();
    $conn->close();
} else {
    header('location:login.php?deactivate');
}
?>