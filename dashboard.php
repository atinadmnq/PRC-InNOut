<?php
include 'db_connect.php';
include 'sidebar.php';


$incoming = $conn->query("SELECT COUNT(*) AS total FROM incoming")->fetch_assoc()['total'];


$outgoing = $conn->query("SELECT COUNT(*) AS total FROM outgoing")->fetch_assoc()['total'];


$logs = $conn->query("SELECT * FROM activity_logs ORDER BY id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Century Gothic';
            background-color: #f4f6f9;
            margin-left: 250px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        
    </style>
</head>
<body>
<div class="container py-5">
    <h2 class="mb-4 text-left fw-bold">📊 Welcome to In-N-Out Dashboard</h2>

    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card p-4 d-flex flex-row align-items-center">
                <div class="icon-circle bg-primary text-white me-3">
                    <i class="bi bi-inbox-fill"></i>
                </div>
                <div>
                    <h4 class="mb-0"><?= $incoming ?></h4>
                    <small class="text-muted">Incoming Mails</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4 d-flex flex-row align-items-center">
                <div class="icon-circle bg-success text-white me-3">
                    <i class="bi bi-send-fill"></i>
                </div>
                <div>
                    <h4 class="mb-0"><?= $outgoing ?></h4>
                    <small class="text-muted">Outgoing Mails</small>
                </div>
            </div>
        </div>
    </div>

    <h5>📝 Recent Activity Logs</h5>
    <div class="card p-3">
        <ul class="list-group list-group-flush">
            <?php if ($logs->num_rows > 0): ?>
                <?php while ($log = $logs->fetch_assoc()): ?>
                    <li class="list-group-item">
                        <i class="bi bi-clock-history text-secondary me-2"></i>
                        <strong><?= ucfirst($log['action_type']) ?>:</strong>
                        <?= htmlspecialchars($log['description']) ?>
                        <br>
                        <small class="text-muted">Control No: <?= $log['reference_no'] ?></small>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li class="list-group-item text-muted">No Recent Activity</li>
            <?php endif; ?>
        </ul>
    </div>
    <br> <br>
    
    <h5>⚡ Quick Actions</h5>
    <div class="quick-actions d-flex gap-3">
        <a href="incoming_table.php" class="btn btn-outline-primary">
            <i class="bi bi-box-arrow-in-down me-1"></i> View Incoming Records
        </a>
        <a href="outgoing_table.php" class="btn btn-outline-success">
            <i class="bi bi-box-arrow-up-right me-1"></i> View Outgoing Records
        </a>
    </div>
</div>
</body>
</html>
