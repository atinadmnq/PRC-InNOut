<?php
include 'db_connect.php';
include 'navbar.php';

$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$search = $_GET['search'] ?? '';
$searchSQL = "";
$params = [];
$types = "";

if (!empty($search)) {
    $searchSQL = "WHERE control_number LIKE ? OR division_section LIKE ? OR contents LIKE ? OR contact_person LIKE ? OR designation LIKE ? OR package_type LIKE ?";
    $term = "%$search%";
    $params = [$term, $term, $term, $term, $term, $term];
    $types = "ssssss";
}

$countSQL = "SELECT COUNT(*) as total FROM outgoing $searchSQL";
$stmt = $conn->prepare($countSQL);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$totalRows = $stmt->get_result()->fetch_assoc()['total'];
$stmt->close();
$totalPages = max(1, ceil($totalRows / $limit));

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

    <form method="POST" action="print_outgoing.php" target="_blank">
      <div class="search-bar d-flex justify-content-end mb-3">
        <input type="text" name="search" class="form-control w-25 me-2" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" formaction="" class="btn btn-outline-dark me-2">Search</button>
        <button type="submit" class="btn btn-outline-primary">Print</button>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead>
            <tr>
              <th><input type="checkbox" id="select-all"></th>
              <th>Date</th>
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
            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
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
