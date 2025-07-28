<?php
include 'db_connect.php';
include 'sidebar.php';

$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$search = $_GET['search'] ?? '';
$filterMonth = $_GET['month'] ?? '';
$filterYear = $_GET['year'] ?? '';

$searchSQL = "";
$where = [];
$params = [];
$types = "";

// Search conditions
if (!empty($search)) {
    $where[] = "(control_number LIKE ? OR division_section LIKE ? OR contents LIKE ? OR contact_person LIKE ? OR designation LIKE ? OR package_type LIKE ?)";
    $term = "%$search%";
    $params = array_merge($params, [$term, $term, $term, $term, $term, $term]);
    $types .= "ssssss";
}

// Month/year filter
if (!empty($filterMonth)) {
    $where[] = "MONTH(date_received) = ?";
    $params[] = $filterMonth;
    $types .= "i";
}
if (!empty($filterYear)) {
    $where[] = "YEAR(date_received) = ?";
    $params[] = $filterYear;
    $types .= "i";
}

if (!empty($where)) {
    $searchSQL = "WHERE " . implode(" AND ", $where);
}

// Count total for pagination
$countSQL = "SELECT COUNT(*) as total FROM outgoing $searchSQL";
$stmt = $conn->prepare($countSQL);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$totalRows = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();
$totalPages = max(1, ceil($totalRows / $limit));

// Fetch paginated results
$dataSQL = "SELECT * FROM outgoing $searchSQL ORDER BY date_received DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($dataSQL);
if (!empty($params)) {
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
  <title>Outgoing Mails Table</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #EDEEEB; 
      color: #31393C;
      font-family: 'Century Gothic', sans-serif;
      margin-left: 250px;
    }

    .table-wrapper {
      padding: 2rem;
      background-color: #FFFFFF;
      margin: 2rem auto;
      border-radius: 1rem;
      max-width: 95%;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    .table th {
      background-color: #3E96F4; 
      color: #FFFFFF;
    }

    .pagination .page-link {
      color: #3E96F4;
    }

    .pagination .active .page-link {
      background-color: #3E96F4; 
      border-color: #3E96F4;
      color: #FFFFFF;
    }

    .btn-outline-primary {
      border-color: #3E96F4;
      color: #3E96F4;
    }

    .btn-outline-primary:hover {
      background-color: #3E96F4;
      color: #FFFFFF;
    }

    #select-all {
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="table-wrapper shadow border">
    <h4 class="text-center mb-4 fw-bold">OUTGOING MAIL RECORDS</h4>

    <form method="GET" action="">
      <div class="search-bar d-flex justify-content-end mb-3 align-items-center flex-wrap gap-2">
        <input type="text" name="search" class="form-control w-auto" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">

        <select name="month" class="form-select w-auto">
          <option value="">All Months</option>
          <?php for ($m = 1; $m <= 12; $m++): ?>
            <option value="<?= $m ?>" <?= ($filterMonth == $m) ? 'selected' : '' ?>>
              <?= date("F", mktime(0, 0, 0, $m, 10)) ?>
            </option>
          <?php endfor; ?>
        </select>

        <select name="year" class="form-select w-auto">
          <option value="">All Years</option>
          <?php
          $yearNow = date("Y");
          for ($y = $yearNow; $y >= $yearNow - 10; $y--): ?>
            <option value="<?= $y ?>" <?= ($filterYear == $y) ? 'selected' : '' ?>><?= $y ?></option>
          <?php endfor; ?>
        </select>

        <button type="submit" class="btn btn-outline-dark">Search</button>
        <button type="submit" formaction="print_outgoing.php" formmethod="POST" target="_blank" class="btn btn-outline-primary">Print</button>
        <a href="outgoing_table.php" class="btn btn-outline-secondary">Refresh</a>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead>
            <tr>
              <th><input type="checkbox" id="select-all"></th>
              <th>Date Received</th>
              <th>Control No.</th>
              <th>Division/Section</th>
              <th>Contents</th>
              <th>Contact Person</th>
              <th>Position</th>
              <th>Package Type</th>
              <th>Pieces</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><input type="checkbox" name="selected_ids[]" value="<?= $row['id'] ?>"></td>
                <td><?= htmlspecialchars($row['date_received']) ?></td>
                <td><?= htmlspecialchars($row['control_number']) ?></td>
                <td><?= htmlspecialchars($row['division_section']) ?></td>
                <td><?= htmlspecialchars($row['contents']) ?></td>
                <td><?= htmlspecialchars($row['contact_person']) ?></td>
                <td><?= htmlspecialchars($row['designation']) ?></td>
                <td><?= htmlspecialchars($row['package_type']) ?></td>
                <td><?= htmlspecialchars($row['pieces']) ?></td>
                <td class="d-flex gap-1 flex-wrap">
                  <a href="edit_outgoing.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                  <a href="delete_outgoing.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="10" class="text-center">No records found.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </form>

    <?php if ($totalRows > 0 && $totalPages > 1): ?>
    <nav>
      <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&month=<?= urlencode($filterMonth) ?>&year=<?= urlencode($filterYear) ?>">
              <?= $i ?>
            </a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
    <?php endif; ?>
  </div>

  <script>
    document.getElementById('select-all').addEventListener('change', function () {
      const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
      checkboxes.forEach(cb => cb.checked = this.checked);
    });
  </script>
</body>
</html>
