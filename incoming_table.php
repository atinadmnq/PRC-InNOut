<?php
include 'db_connect.php';
include 'navbar.php';

$limit = 20; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchQuery = "";
$params = [];
$types = "";

if (!empty($search)) {
    $searchQuery = "WHERE ctrlNum LIKE ? OR source LIKE ? OR subj LIKE ? OR recipient LIKE ?";
    $searchTerm = "%$search%";
    $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
    $types = "ssss";
}

$countSQL = "SELECT COUNT(*) as total FROM incoming $searchQuery";
$stmt = $conn->prepare($countSQL);
if (!empty($searchQuery)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$countResult = $stmt->get_result()->fetch_assoc();
$totalRows = $countResult['total'];
$totalPages = ceil($totalRows / $limit);
$stmt->close();

$dataSQL = "SELECT * FROM incoming $searchQuery ORDER BY dateRecd DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($dataSQL);
if (!empty($searchQuery)) {
    $types .= "ii";
    $params[] = $limit;
    $params[] = $offset;
    $stmt->bind_param($types, ...$params);
} else {
    $stmt->bind_param("ii", $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Incoming Data Table</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #EDEEEB; 
        color: #31393C; 
        font-family: 'Century Gothic';
        margin-left: 250px;
    }

    .table-container {
        margin: 2rem auto;
        background-color: #FFFFFF; 
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 0 10px rgba(49, 57, 60, 0.1); 
    }

    .table th {
        vertical-align: middle;
        background-color: #3E96F4; 
        color: #FFFFFF; 
    }

    .search-box {
        margin-bottom: 1rem;
    }

    .pagination .page-link {
        color: #3E96F4; 
    }

    .pagination .active .page-link {
        background-color: #3E96F4; 
        border-color: #3E96F4;
        color: #FFFFFF; 
    }

    .action-buttons a {
        margin-right: 4px;
    }
    </style>
</head>
<body>
<div class="container table-container shadow border">
    <h4 class="mb-4 text-center fw-bold">Incoming Mail Records</h4>

    <form method="get" class="search-box d-flex justify-content-end">
        <input type="text" name="search" class="form-control w-25 me-2" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn btn-outline-dark">Search</button>
    </form>

    <div class="table-responsive text-center">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Control No.</th>
                    <th>Source</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Subject</th>
                    <th>Attachment</th>
                    <th>Status</th>
                    <th>Action Unit</th>
                    <th>Release Date</th>
                    <th>Recipient</th>
                    <th>Receiver Initial</th>
                    <th>Tracking No.</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['ctrlNum']) ?></td>
                            <td><?= htmlspecialchars($row['source']) ?></td>
                            <td><?= htmlspecialchars($row['dateRecd']) ?></td>
                            <td><?= htmlspecialchars($row['timeRe']) ?></td>
                            <td><?= htmlspecialchars($row['subj']) ?></td>
                            <td><?= htmlspecialchars($row['attachment']) ?></td>
                            <td><?= htmlspecialchars($row['stat']) ?></td>
                            <td><?= htmlspecialchars($row['actionUnit']) ?></td>
                            <td><?= htmlspecialchars($row['dateRel']) ?></td>
                            <td><?= htmlspecialchars($row['recipient']) ?></td>
                            <td><?= htmlspecialchars($row['intial']) ?></td>
                            <td><?= htmlspecialchars($row['trackingNum']) ?></td>
                            <td class="text-nowrap action-buttons">
                                <a href="print_incoming.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm">Print</a>
                                <a href="edit_incoming.php?id=<?= $row['id'] ?>" class="btn btn-outline-success btn-sm">Edit</a>
                                <a href="delete_incoming.php?id=<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="13" class="text-center">No records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalRows > 0): ?>
        <nav>
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>
</body>
</html>
