<?php
include 'connection.php';

// Order Status Data (Shipped, Pending)
$query = "SELECT status, COUNT(*) as count FROM orders WHERE status IN ('Shipped', 'Pending') GROUP BY status";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[$row['status']] = (int) $row['count'];
}
$shipped = isset($data['Shipped']) ? $data['Shipped'] : 0;
$pending = isset($data['Pending']) ? $data['Pending'] : 0;
$statusData = json_encode(array($shipped, $pending));

// Orders - Last 5 Days (including today)
$ordersQuery = "SELECT DATE(created_at) as order_date, COUNT(*) as total_orders 
                FROM orders 
                WHERE created_at >= CURDATE() - INTERVAL 4 DAY 
                GROUP BY DATE(created_at)";
$ordersResult = mysqli_query($conn, $ordersQuery);
if (!$ordersResult) {
    die("Query Failed: " . mysqli_error($conn));
}
$orderMap = array();
while ($row = mysqli_fetch_assoc($ordersResult)) {
    $orderMap[$row['order_date']] = (int) $row['total_orders'];
}

$finalOrdersData = array();
$finalLabels = array();
for ($i = 4; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $finalLabels[] = date('D', strtotime($date)); // 'Mon', 'Tue', etc.
    $finalOrdersData[] = isset($orderMap[$date]) ? $orderMap[$date] : 0;
}
$finalOrdersDataJson = json_encode($finalOrdersData);
$finalLabelsJson = json_encode($finalLabels);

// Dispatch Data - Last 6 Months
$dispatchQuery = "SELECT MONTH(created_at) as month, SUM(total_price) as total_dispatch FROM orders WHERE status = 'Shipped' AND created_at >= CURDATE() - INTERVAL 6 MONTH GROUP BY MONTH(created_at)";
$dispatchResult = mysqli_query($conn, $dispatchQuery);
if (!$dispatchResult) {
    die("Query Failed: " . mysqli_error($conn));
}
$dispatchData = array_fill(0, 6, 0);
while ($row = mysqli_fetch_assoc($dispatchResult)) {
    $dispatchData[$row['month'] - 1] = (int) $row['total_dispatch'];
}
$dispatchDataJson = json_encode($dispatchData);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: url('supp.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #333;
        }

        .dashboard {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .back-button {
            background-color: #ffffffcc;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            margin-bottom: 20px;
            transition: 0.3s ease;
        }

        .back-button:hover {
            background-color: #f0f0f0;
        }

        .cards-row {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
            text-align: center;
            width: 200px;
            transition: 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }

        .card h2 {
            margin: 0;
            color: #212529;
            font-size: 28px;
        }

        .card p {
            color: #6c757d;
            font-size: 14px;
            margin-top: 6px;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 16px;
            height: 400px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
            transition: 0.3s ease;
        }

        .chart-container:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }

        canvas {
            height: 100% !important;
            width: 100% !important;
        }
    </style>
</head>
<body>
    <div class="dashboard">

        <!-- Back Button -->
        <button class="back-button" onClick="history.back()"> Back</button>

        <!-- Cards Row -->
        <div class="cards-row">
            <div class="card">
                <h2><?php echo $shipped; ?></h2>
                <p>Shipped Orders</p>
            </div>
            <div class="card">
                <h2><?php echo $pending; ?></h2>
                <p>Pending Orders</p>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="charts-grid">
            <div class="chart-container">
                <canvas id="ordersChart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="dispatchChart"></canvas>
            </div>
            <div class="chart-container">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        var ordersData = <?php echo $finalOrdersDataJson; ?>;
        var ordersLabels = <?php echo $finalLabelsJson; ?>;
        var ordersCtx = document.getElementById('ordersChart').getContext('2d');
        new Chart(ordersCtx, {
            type: 'bar',
            data: {
                labels: ordersLabels,
                datasets: [{
                    label: 'Total Orders (Last 5 Days)',
                    data: ordersData,
                    backgroundColor: '#007bff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        var dispatchData = <?php echo $dispatchDataJson; ?>;
        var dispatchCtx = document.getElementById('dispatchChart').getContext('2d');
        new Chart(dispatchCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Total Dispatch',
                    data: dispatchData,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1000 }
                    }
                }
            }
        });

        var statusData = <?php echo $statusData; ?>;
        var statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: ['Shipped', 'Pending'],
                datasets: [{
                    label: 'Order Status',
                    data: statusData,
                    backgroundColor: ['#4CAF50', '#FF9800']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    </script>
</body>
</html>
