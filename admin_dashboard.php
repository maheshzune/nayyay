<?php
session_start();
if ($_SESSION['login'] == TRUE && $_SESSION['status'] == 'Active') {
    include("db_con/dbCon.php");
    $conn = connect();
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
    <title>Lawyer Management System - Admin Dashboard</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
    <!-- Google Charts Scripts -->
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            // Chart 1: User Roles
            var data1 = google.visualization.arrayToDataTable([
                ['Role', 'Number'],
                <?php
                $query1 = "SELECT count(*) as number, role FROM user WHERE NOT role='Admin' GROUP BY role";
                $result1 = mysqli_query($conn, $query1);
                while ($row = mysqli_fetch_array($result1)) {
                    echo "['" . $row["role"] . "', " . $row["number"] . "],";
                }
                ?>
            ]);
            var options1 = {
                title: 'Percentage of Registered Users & Lawyers',
                legend: 'left',
                is3D: true,
            };
            var chart1 = new google.visualization.PieChart(document.getElementById('chart1'));
            chart1.draw(data1, options1);

            // Chart 2: Lawyer Specialities
            var data2 = google.visualization.arrayToDataTable([
                ['Speciality', 'Number'],
                <?php
                $query2 = "SELECT count(speciality) as number, speciality FROM user, lawyer WHERE user.u_id = lawyer.lawyer_id GROUP BY speciality";
                $result2 = mysqli_query($conn, $query2);
                while ($row = mysqli_fetch_array($result2)) {
                    echo "['" . $row["speciality"] . "', " . $row["number"] . "],";
                }
                ?>
            ]);
            var options2 = {
                title: 'Percentage of Registered Lawyers by Speciality',
                legend: 'left',
                is3D: true,
            };
            var chart2 = new google.visualization.PieChart(document.getElementById('chart2'));
            chart2.draw(data2, options2);

            // Chart 3: Lawyer Degrees
            var data3 = google.visualization.arrayToDataTable([
                ['Degree', 'Number'],
                <?php
                $query3 = "SELECT count(degree) as number, degree FROM user, lawyer WHERE user.u_id = lawyer.lawyer_id GROUP BY degree";
                $result3 = mysqli_query($conn, $query3);
                while ($row = mysqli_fetch_array($result3)) {
                    echo "['" . $row["degree"] . "', " . $row["number"] . "],";
                }
                ?>
            ]);
            var options3 = {
                title: 'Percentage of Registered Lawyers by Degree',
                legend: 'left',
                is3D: true,
            };
            var chart3 = new google.visualization.PieChart(document.getElementById('chart3'));
            chart3.draw(data3, options3);

            // Chart 4: Practice Length
            var data4 = google.visualization.arrayToDataTable([
                ['Practise Length', 'Number'],
                <?php
                $query4 = "SELECT count(practise_Length) as number, practise_Length FROM user, lawyer WHERE user.u_id = lawyer.lawyer_id GROUP BY practise_Length";
                $result4 = mysqli_query($conn, $query4);
                while ($row = mysqli_fetch_array($result4)) {
                    echo "['" . $row["practise_Length"] . "', " . $row["number"] . "],";
                }
                ?>
            ]);
            var options4 = {
                title: 'Percentage of Lawyers by Experience',
                legend: 'left',
                is3D: true,
            };
            var chart4 = new google.visualization.PieChart(document.getElementById('chart4'));
            chart4.draw(data4, options4);

            // Chart 5: Most Booked Lawyers
            var data5 = google.visualization.arrayToDataTable([
                ['Name', 'Number'],
                <?php
                $query5 = "SELECT count(booking.lawyer_id) as number, user.first_name as name FROM booking, lawyer, user WHERE booking.lawyer_id = lawyer.lawyer_id AND user.u_id = lawyer.lawyer_id GROUP BY booking.lawyer_id";
                $result5 = mysqli_query($conn, $query5);
                while ($row = mysqli_fetch_array($result5)) {
                    echo "['" . $row["name"] . "', " . $row["number"] . "],";
                }
                ?>
            ]);
            var options5 = {
                title: 'Percentage of Most Booked Lawyers',
                legend: 'left',
                is3D: true,
            };
            var chart5 = new google.visualization.PieChart(document.getElementById('chart5'));
            chart5.draw(data5, options5);
        }
    </script>
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
                    <li class="nav-item"><a class="nav-link active" href="admin_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_lawyer.php">Lawyers</a></li>
                    <li class="nav-item"><a class="nav-link" href="admin_user.php">Users</a></li>
                    <li class="nav-item ms-3"><a class="nav-link btn gradient-btn" href="logout.php">Log Out</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex" id="wrapper">
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">Admin Panel</div>
            <div class="list-group list-group-flush">
                <a href="admin_dashboard.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php' ? 'active' : ''; ?>">
                    Dashboard
                    <?php if (basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php'): ?>
                        <i class="fas fa-arrow-right float-end"></i>
                    <?php endif; ?>
                </a>
                <a href="admin_lawyer.php" class="list-group-item list-group-item-action bg-light">See Lawyers</a>
                <a href="admin_user.php" class="list-group-item list-group-item-action bg-light">See Users</a>
                <a href="admin_case_details.php" class="list-group-item list-group-item-action bg-light <?php echo basename($_SERVER['PHP_SELF']) == 'admin_case.php' ? 'active' : ''; ?>">
                See Cases</a>
            </div>
        </div>

        <div id="page-content-wrapper">
            <?php if (isset($_GET['done'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                    <strong>Welcome!</strong> You are logged in as Admin.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="container-fluid">
                <div class="dashboard-card">
                    <h4 class="text-gradient">Admin Dashboard</h4>
                    <p class="mb-0 fw-medium">Analyze system statistics and manage operations.</p>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="dashboard-card">
                            <div id="chart1" style="width: 100%; height: 400px;"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-card">
                            <div id="chart2" style="width: 100%; height: 400px;"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-card">
                            <div id="chart3" style="width: 100%; height: 400px;"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-card">
                            <div id="chart4" style="width: 100%; height: 400px;"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-card">
                            <div id="chart5" style="width: 100%; height: 400px;"></div>
                        </div>
                    </div>
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