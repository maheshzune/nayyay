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

    // Updated query to include lawyer name using LEFT JOIN on the user table
    $case_query = "SELECT c.case_id, c.case_type, c.status, c.location, c.fees, c.deadline, c.notes, c.hearing_date,
                          u.first_Name AS lawyer_first, u.last_Name AS lawyer_last
                   FROM `case` c
                   LEFT JOIN user u ON c.lawyer_id = u.u_id
                   WHERE c.client_id = '$client_id'
                   ORDER BY c.deadline ASC";
    $case_result = mysqli_query($conn, $case_query);
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
    <title>Lawyer Management System - Case Details</title>
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
            color: #333;
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
            transition: all 0.3s ease;
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
            background: #FFFFFF;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
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
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .dashboard-card h4 {
            color: var(--primary-color);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .case-card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }
        .case-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        .case-card h5 {
            color: var(--primary-color);
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .case-card p {
            margin: 5px 0;
            font-size: 0.95rem;
        }
        .case-card strong {
            color: var(--primary-color);
            font-weight: 500;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
            min-width: 70px;
            text-align: center;
        }
        .status-pending { background-color: #ffc107; color: #212529; }
        .status-accepted { background-color: #28a745; color: #fff; }
        .status-rejected { background-color: #dc3545; color: #fff; }
        .payment-options {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .payment-btn {
            background: linear-gradient(45deg, var(--secondary-color), var(--accent-color));
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .payment-btn:hover {
            background: linear-gradient(45deg, var(--accent-color), var(--secondary-color));
            transform: translateY(-2px);
        }
        .gpay-btn { background: #4285F4; }
        .phonepe-btn { background: #5F259F; }
        .paytm-btn { background: #00BAF2; }
        /* Highlight style for hearing date if case is Under Hearing */
        .highlight-hearing-date {
            background-color: #f7c948;
            color: #212529;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        footer {
            background: var(--primary-color);
            color: #FFFFFF;
            padding: 25px 0;
            margin-top: 50px;
            text-align: center;
            font-weight: 500;
        }
        @media (max-width: 992px) {
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
        @media (max-width: 768px) {
            .dashboard-card {
                padding: 20px;
            }
            .dashboard-card h4 {
                font-size: 1.5rem;
            }
            .case-card {
                padding: 15px;
            }
            .case-card h5 {
                font-size: 1.2rem;
            }
            .payment-options {
                flex-direction: column;
            }
        }
        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 1.4rem;
            }
            .nav-link {
                padding: 8px 10px;
            }
            .gradient-btn {
                padding: 6px 15px;
                font-size: 0.9rem;
            }
            .case-card p {
                font-size: 0.9rem;
            }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .dashboard-card, .case-card {
            animation: fadeIn 0.5s ease-in;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
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
                            <img src="/<?php echo $profile_image; ?>" alt="Profile" class="profile-img-nav" onerror="this.src='/images/upload/default-profile.png'" style="width: 35px; height: 35px; object-fit: cover; border-radius: 50%; border: 2px solid var(--accent-color); margin-right: 10px;">
                            <?php echo $_SESSION['first_Name'] . ' ' . $_SESSION['last_Name']; ?>
                        </a>
                    </li>
                    <li class="nav-item ms-3"><a class="nav-link btn gradient-btn" href="logout.php">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar and Main Content -->
    <div class="d-flex" id="wrapper">
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">My Profile</div>
            <div class="list-group list-group-flush">
                <a href="user_dashboard.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'user_dashboard.php' ? 'active' : ''; ?>">
                    Dashboard
                    <?php if (basename($_SERVER['PHP_SELF']) == 'user_dashboard.php'): ?>
                        <i class="fas fa-arrow-right float-end"></i>
                    <?php endif; ?>
                </a>
                <a href="user_profile.php" class="list-group-item list-group-item-action bg-light">Edit Profile</a>
                <a href="user_booking.php" class="list-group-item list-group-item-action bg-light">My Booking Requests</a>
                <a href="user_case_detail.php" class="list-group-item active list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'user_case_details.php' ? 'active' : ''; ?>">
                    Case Details
                    <?php if (basename($_SERVER['PHP_SELF']) == 'user_case_details.php'): ?>
                        <i class="fas fa-arrow-right float-end"></i>
                    <?php endif; ?>
                </a>
                <a href="user_document_management.php" class="list-group-item list-group-item-action bg-light">Upload Document</a>
                <a href="update_password.php" class="list-group-item list-group-item-action bg-light">Update Password</a>
            </div>
        </div>

        <!-- Main Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <!-- Case Details Card -->
                <div class="dashboard-card">
                    <h4><i class="fas fa-folder-open me-2"></i>Case Details</h4>
                    <p class="fw-medium">View the status and details of your cases provided by your lawyer.</p>
                    <?php
                    if (mysqli_num_rows($case_result) > 0) {
                        while ($row = mysqli_fetch_assoc($case_result)) {
                            $status_class = '';
                            switch ($row['status']) {
                                case 'Pending':
                                    $status_class = 'status-pending';
                                    break;
                                case 'Accepted':
                                    $status_class = 'status-accepted';
                                    break;
                                case 'Rejected':
                                    $status_class = 'status-rejected';
                                    break;
                                default:
                                    $status_class = 'status-pending';
                            }
                            echo '<div class="case-card">';
                            echo "<h5>Case #{$row['case_id']} - {$row['case_type']}</h5>";
                            // Display lawyer name if available
                            if (!empty($row['lawyer_first']) || !empty($row['lawyer_last'])) {
                                echo "<p><strong>Lawyer:</strong> " . htmlspecialchars($row['lawyer_first'] . ' ' . $row['lawyer_last']) . "</p>";
                            }
                            echo "<p><strong>Status:</strong> <span class='status-badge $status_class'>{$row['status']}</span></p>";
                            echo "<p><strong>Location:</strong> {$row['location']}</p>";
                            echo "<p><strong>Fees:</strong> ₹ " . number_format($row['fees'], 2) . "</p>";
                            echo "<p><strong>Deadline:</strong> " . date('M d, Y', strtotime($row['deadline'])) . "</p>";
                            // Always show Hearing Date if set; highlight if status is Under Hearing.
                            if (!empty($row['hearing_date'])) {
                                if ($row['status'] == 'Under Hearing') {
                                    echo "<p><strong>Hearing Date:</strong> <span class='highlight-hearing-date'>" . date('M d, Y', strtotime($row['hearing_date'])) . "</span></p>";
                                } else {
                                    echo "<p><strong>Hearing Date:</strong> " . date('M d, Y', strtotime($row['hearing_date'])) . "</p>";
                                }
                            } else {
                                echo "<p><strong>Hearing Date:</strong> Not Set</p>";
                            }
                            echo "<p><strong>Notes:</strong> " . (empty($row['notes']) ? 'No notes' : htmlspecialchars($row['notes'])) . "</p>";
                            
                            echo '<div class="payment-options">';
                            // Generic Payment Button (e.g., via Razorpay)
                            echo "<form action='process_payment.php' method='POST'>";
                            echo "<input type='hidden' name='case_id' value='{$row['case_id']}'>";
                            echo "<input type='hidden' name='amount' value='{$row['fees']}'>";
                            echo "<button type='submit' class='payment-btn'><i class='fas fa-credit-card me-2'></i>Credit/Debit Card</button>";
                            echo "</form>";
                            // GPay Button
                            echo "<form action='process_upi_payment.php' method='POST'>";
                            echo "<input type='hidden' name='case_id' value='{$row['case_id']}'>";
                            echo "<input type='hidden' name='amount' value='{$row['fees']}'>";
                            echo "<input type='hidden' name='upi_method' value='gpay'>";
                            echo "<button type='submit' class='payment-btn gpay-btn'><i class='fas fa-mobile-alt me-2'></i>Google Pay</button>";
                            echo "</form>";
                            // PhonePe Button
                            echo "<form action='process_upi_payment.php' method='POST'>";
                            echo "<input type='hidden' name='case_id' value='{$row['case_id']}'>";
                            echo "<input type='hidden' name='amount' value='{$row['fees']}'>";
                            echo "<input type='hidden' name='upi_method' value='phonepe'>";
                            echo "<button type='submit' class='payment-btn phonepe-btn'><i class='fas fa-phone me-2'></i>PhonePe</button>";
                            echo "</form>";
                            // Paytm Button
                            echo "<form action='process_upi_payment.php' method='POST'>";
                            echo "<input type='hidden' name='case_id' value='{$row['case_id']}'>";
                            echo "<input type='hidden' name='amount' value='{$row['fees']}'>";
                            echo "<input type='hidden' name='upi_method' value='paytm'>";
                            echo "<button type='submit' class='payment-btn paytm-btn'><i class='fas fa-wallet me-2'></i>Paytm</button>";
                            echo "</form>";
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p class="text-muted">No cases found.</p>';
                    }
                    ?>
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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    mysqli_close($conn);
} else {
    header('location:login.php?deactivate');
}
?>
