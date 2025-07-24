<?php
include 'db_connect.php';
include 'sidebar.php';

$search = isset($_POST['search']) ? $_POST['search'] : '';

$sql = "SELECT * FROM incoming WHERE trackingNum LIKE ? OR subj LIKE ? OR source LIKE ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$searchParam = "%$search%";
$stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
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
    .search-box input {
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

  <form method="POST" id="printForm" target="_blank">
    <div class="search-box d-flex justify-content-end mb-3">
      <input type="text" name="search" class="form-control w-25 me-2" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
      <button type="submit" formaction="" class="btn btn-outline-dark me-2">Search</button>
      <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#printModal">Print</button>
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
            <th>Date</th>
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
</div>


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
    form.submit();
  }
</script>

</body>
</html>
