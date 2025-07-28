<?php
include 'db_connect.php';
include 'sidebar.php';

$search = $_POST['search'] ?? '';
$filterMonth = $_POST['filter_month'] ?? '';
$filterYear = $_POST['filter_year'] ?? '';

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Count total rows for pagination
$countSql = "SELECT COUNT(*) FROM incoming WHERE 
    (trackingNum LIKE ? OR subj LIKE ? OR source LIKE ?)";
$params = ["%$search%", "%$search%", "%$search%"];
$types = "sss";

if (!empty($filterMonth) && !empty($filterYear)) {
    $countSql .= " AND MONTH(dateRecd) = ? AND YEAR(dateRecd) = ?";
    $params[] = $filterMonth;
    $params[] = $filterYear;
    $types .= "ii";
}

$countStmt = $conn->prepare($countSql);
$countStmt->bind_param($types, ...$params);
$countStmt->execute();
$countStmt->bind_result($totalRows);
$countStmt->fetch();
$countStmt->close();

$totalPages = ceil($totalRows / $limit);

// Main data fetch
$sql = "SELECT * FROM incoming WHERE 
    (trackingNum LIKE ? OR subj LIKE ? OR source LIKE ?)";
$bindParams = ["%$search%", "%$search%", "%$search%"];
$types = "sss";

if (!empty($filterMonth) && !empty($filterYear)) {
    $sql .= " AND MONTH(dateRecd) = ? AND YEAR(dateRecd) = ?";
    $bindParams[] = $filterMonth;
    $bindParams[] = $filterYear;
    $types .= "ii";
}

$sql .= " ORDER BY id DESC LIMIT ? OFFSET ?";
$bindParams[] = $limit;
$bindParams[] = $offset;
$types .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$bindParams);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Incoming Documents</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Century Gothic';
      padding: 40px;
      margin-left: 250px;
    }
    th, td {
      font-size: 13px;
      vertical-align: middle;
    }
    table {
      background-color: #fff;
    }
    .table-wrapper {
      background-color: #EDEEEB;
      padding: 20px;
      border-radius: 10px;
    }
    .search-box input, .search-box select {
      font-size: 13px;
    }
    .action-buttons {
      display: flex;
      justify-content: center;
      gap: 6px;
    }
  </style>
</head>
<body>

<div class="container table-wrapper shadow border">
  <h4 class="text-center mb-4 fw-bold">INCOMING DOCUMENTS</h4>

  <form method="POST" id="printForm">
    <div class="search-box d-flex justify-content-end mb-3 align-items-center flex-wrap gap-2">
      <input type="text" name="search" class="form-control w-auto" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">

      <select name="filter_month" class="form-select w-auto">
        <option value="">Month</option>
        <?php for ($m = 1; $m <= 12; $m++): ?>
          <option value="<?= $m ?>" <?= ($filterMonth == $m) ? 'selected' : '' ?>>
            <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
          </option>
        <?php endfor; ?>
      </select>

      <select name="filter_year" class="form-select w-auto">
        <option value="">Year</option>
        <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
          <option value="<?= $y ?>" <?= ($filterYear == $y) ? 'selected' : '' ?>><?= $y ?></option>
        <?php endfor; ?>
      </select>

      <button type="submit" class="btn btn-outline-dark">Search</button>
      <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#printModal">Print</button>
      <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-outline-secondary">Refresh</a>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered text-center align-middle">
        <thead class="table-light">
          <tr>
            <th><input type="checkbox" id="select-all"></th>
            <th>Control Number</th>
            <th>Source</th>
            <th>Date Received</th>
            <th>Received By</th>
            <th>Subject</th>
            <th>Action Unit</th>
            <th>Released To</th>
            <th>Date Released</th>
            <th>Tracking Number</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><input type="checkbox" name="selected_ids[]" value="<?= $row['id'] ?>"></td>
              <td><?= htmlspecialchars($row['ctrlNum']) ?></td>
              <td><?= htmlspecialchars($row['source']) ?></td>
              <td><?= htmlspecialchars($row['dateRecd']) ?></td>
              <td><?= htmlspecialchars($row['recipient']) ?></td>
              <td><?= htmlspecialchars($row['subj']) ?></td>
              <td><?= htmlspecialchars($row['actionUnit']) ?></td>
              <td><?= htmlspecialchars($row['recipient']) ?></td>
              <td><?= htmlspecialchars($row['dateRel']) ?></td>
              <td><?= htmlspecialchars($row['trackingNum']) ?></td>
              <td>
                <div class="action-buttons">
                  <a href="edit_incoming.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning">Edit</a>
                  <a href="delete_incoming.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </form>

  <!-- Pagination -->
  <nav aria-label="Page navigation" class="mt-3">
    <ul class="pagination justify-content-center">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>" onclick="event.preventDefault(); document.forms[0].action='?page=<?= $i ?>'; document.forms[0].submit();"><?= $i ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
</div>

<!-- Print Modal -->
<div class="modal fade" id="printModal" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="printModalLabel">Select Print Format</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <p>Please choose the format you want to use:</p>
        <div class="d-flex justify-content-center gap-3">
          <button type="button" class="btn btn-outline-primary" onclick="submitPrint('print_incoming.php')">🧾 Route Slip</button>
          <button type="button" class="btn btn-outline-success" onclick="submitPrint('print_incoming_log.php')">📦 Parcel Log</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.getElementById('select-all').addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
    checkboxes.forEach(cb => cb.checked = this.checked);
  });

  function submitPrint(actionUrl) {
    const selected = document.querySelectorAll('input[name="selected_ids[]"]:checked');
    if (selected.length === 0) {
      alert("Please select at least one row to print.");
      return;
    }

    const form = document.getElementById('printForm');
    form.action = actionUrl;
    form.target = "_blank";  // Open print in new tab
    form.submit();
    form.target = ""; // Reset target so search won't open in new tab
  }
</script>

</body>
</html>
