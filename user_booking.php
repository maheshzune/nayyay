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

    // Handle booking cancellation
    if (isset($_GET['cancel_booking_id'])) {
        $booking_id = $_GET['cancel_booking_id'];
        $query = "DELETE FROM booking WHERE booking_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $booking_id);
        if ($stmt->execute()) {
            header("Location: user_booking.php?message=Booking cancelled successfully");
            exit;
        } else {
            echo "Error cancelling booking: " . $conn->error;
        }
        $stmt->close();
    }

    // Handle booking update (when edit form is submitted)
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_booking'])) {
        $booking_id  = $_POST['booking_id'];
        $date        = $_POST['date'];
        $subject     = $_POST['subject'];
        $description = $_POST['description'];

        $query = "UPDATE booking SET date = ?, subject = ?, description = ? WHERE booking_id = ?";
        $stmt  = $conn->prepare($query);
        $stmt->bind_param("sssi", $date, $subject, $description, $booking_id);
        if ($stmt->execute()) {
            header("Location: user_booking.php?message=Booking updated successfully");
            exit;
        } else {
            echo "Error updating booking: " . $conn->error;
        }
        $stmt->close();
    }

    // If editing, fetch booking data for modal form
    $edit_booking = null;
    if (isset($_GET['edit_booking_id'])) {
        $edit_booking_id = $_GET['edit_booking_id'];
        $query = "SELECT date, subject, description FROM booking WHERE booking_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $edit_booking_id);
        $stmt->execute();
        $result_edit = $stmt->get_result();
        $edit_booking = $result_edit->fetch_assoc();
        $stmt->close();
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
    <title>Lawyer Management System - My Booking Requests</title>
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
        .booking-table tbody tr:nth-child(even) {
            background-color: #F8F9FA;
        }
        .booking-table tbody tr:hover {
            background-color: rgba(0, 168, 168, 0.1);
        }
        .cancel-btn {
            background: linear-gradient(45deg, #dc3545, #c82333);
            color: #FFFFFF;
            border: none;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        .cancel-btn:hover {
            background: linear-gradient(45deg, #c82333, #dc3545);
            color: #FFFFFF;
            transform: translateY(-2px);
            text-decoration: none;
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
            .booking-table {
                display: block;
                overflow-x: auto;
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="lawyers.php">Lawyers</a></li>
                    <li class="nav-item"><a class="nav-link active" href="user_booking.php">My Bookings</a></li>
                    <li class="nav-item ms-3">
                        <a class="nav-link btn gradient-btn" href="#">
                            <img src="/<?php echo $profile_image; ?>" alt="Profile" class="profile-img-nav" 
                                 onerror="this.src='/images/upload/default-profile.png'">
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
                <a href="user_dashboard.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'user_dashboard.php' ? 'active' : ''; ?>">
                    Dashboard
                </a>
                <a href="user_profile.php" class="list-group-item list-group-item-action bg-light">Edit Profile</a>
                <a href="user_booking.php" class="list-group-item list-group-item-action bg-light active">My Booking Requests</a>
                <?php if (basename($_SERVER['PHP_SELF']) == 'user_booking.php'): ?>
                    <i class="fas fa-arrow-right float-end"></i>
                <?php endif; ?>
                <a href="user_case_detail.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'user_case_detail.php' ? 'active' : ''; ?>">
                    Case Details
                    <?php if (basename($_SERVER['PHP_SELF']) == 'user_case_detail.php'): ?>
                        <i class="fas fa-arrow-right float-end"></i>
                    <?php endif; ?>
                </a>
                <a href="user_document_management.php" class="list-group-item list-group-item-action bg-light">Upload Document</a>
                <a href="update_password.php" class="list-group-item list-group-item-action bg-light">Update Password</a>
            </div>
        </div>

        <div id="page-content-wrapper">
            <?php if (isset($_GET['message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                    <?php echo $_GET['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="container-fluid">
                <div class="dashboard-card">
                    <h4 class="text-gradient"><i class="fas fa-calendar-check me-2"></i>My Booking Requests</h4>

                    <!-- Booking Table -->
                    <table class="booking-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Date</th>
                                <th>Subject</th>
                                <th>Description</th>
                                <th>Lawyer Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $a = $_SESSION['client_id'];
                            $result = mysqli_query($conn, "SELECT booking.booking_id, booking.date, booking.subject, booking.description, booking.status AS statuss, user.first_Name, user.last_Name
                                FROM booking
                                INNER JOIN lawyer ON booking.lawyer_id = lawyer.lawyer_id
                                INNER JOIN user ON lawyer.lawyer_id = user.u_id
                                WHERE booking.client_id = '$a'");
                            $counter = 0;
                            while ($row = mysqli_fetch_array($result)) {
                            ?>
                                <tr>
                                    <td><?php echo ++$counter; ?></td>
                                    <td><?php echo date('d M Y', strtotime($row["date"])); ?></td>
                                    <td><?php echo $row["subject"]; ?></td>
                                    <td><?php echo $row["description"]; ?></td>
                                    <td><?php echo $row["first_Name"] . ' ' . $row["last_Name"]; ?></td>
                                    <td>
                                        <?php
                                        $status = $row["statuss"];
                                        $badge_class = $status == 'Accepted' ? 'bg-success' : ($status == 'Pending' ? 'bg-warning' : 'bg-danger');
                                        echo "<span class='badge $badge_class rounded-pill'>$status</span>";
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($row["statuss"] != 'Cancelled'): ?>
                                            <!-- Option to Edit Booking (opens floating modal) -->
                                            <a href="user_booking.php?edit_booking_id=<?php echo $row['booking_id']; ?>" class="btn btn-primary btn-sm me-1">Edit</a>
                                            <!-- Option to Cancel Booking -->
                                            <a href="user_booking.php?cancel_booking_id=<?php echo $row['booking_id']; ?>" class="cancel-btn" onclick="return confirm('Are you sure you want to cancel this booking?');">Cancel</a>
                                        <?php else: ?>
                                            <span class="text-muted">Cancelled</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            mysqli_close($conn);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Edit Modal -->
    <?php if (isset($_GET['edit_booking_id']) && $edit_booking): ?>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit Booking Request</h5>
            <a href="user_booking.php" class="btn-close"></a>
          </div>
          <div class="modal-body">
            <form method="post" action="user_booking.php">
              <input type="hidden" name="booking_id" value="<?php echo $_GET['edit_booking_id']; ?>">
              <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="text" class="form-control" id="date" name="date" value="<?php echo htmlspecialchars($edit_booking['date']); ?>">
              </div>
              <div class="mb-3">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" value="<?php echo htmlspecialchars($edit_booking['subject']); ?>">
              </div>
              <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($edit_booking['description']); ?></textarea>
              </div>
              <div class="modal-footer">
                <button type="submit" name="update_booking" class="btn btn-success">Update Booking</button>
                <a href="user_booking.php" class="btn btn-secondary">Close</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <footer>
        <div class="container">
            <h5>All rights reserved © <?php echo date("Y"); ?></h5>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" 
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" 
            crossorigin="anonymous"></script>
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

            // If the edit modal exists (GET parameter was set), show the modal automatically
            <?php if (isset($_GET['edit_booking_id']) && $edit_booking): ?>
                var editModal = new bootstrap.Modal(document.getElementById('editModal'));
                editModal.show();
            <?php endif; ?>
        });
    </script>
</body>
</html>
<?php
} else {
    header('location:login.php?deactivate');
}
?>
