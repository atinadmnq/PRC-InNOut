<?php
include 'db_connect.php';
include 'navbar.php'; 


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid ID");
}

$id = (int)$_GET['id'];


$stmt = $conn->prepare("SELECT * FROM outgoing WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Record not found.");
}


$regional_offices = [];
$result = $conn->query("SELECT * FROM contents");
while ($row = $result->fetch_assoc()) {
    $regional_offices[] = $row;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $ctrlNum = $_POST['ctrlNum'];
    $sec = $_POST['sec'];
    $content = $_POST['content'];
    $contact_person = $_POST['contact_person'];
    $designation = $_POST['designation'];
    $pType = $_POST['pType'];
    $pieces = $_POST['pieces'];

    $update = $conn->prepare("UPDATE outgoing SET date_received=?, control_number=?, division_section=?, contents=?, contact_person=?, designation=?, package_type=?, pieces=? WHERE id=?");
    $update->bind_param("sssssssii", $date, $ctrlNum, $sec, $content, $contact_person, $designation, $pType, $pieces, $id);

    if ($update->execute()) {
        header("Location: outgoing_table.php?message=updated");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Update failed: " . $update->error . "</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Outgoing Mail</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #EDEEEB;
      font-family: 'Century Gothic', sans-serif;
      color: #31393C;
      margin-left: 250px;
    }

    .card {
      background-color: #FFFFFF;
      border: 1px solid #CCC7BF;
      border-radius: 1rem;
      padding: 2rem;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    h4 {
      font-weight: bold;
      color: #31393C;
    }

    label {
      font-weight: 500;
      color: #31393C;
    }

    .form-control {
      border: 1px solid #CCC7BF;
      border-radius: 0.5rem;
    }

    .form-control:focus {
      border-color: #3E96F4;
      box-shadow: 0 0 0 0.2rem rgba(62, 150, 244, 0.25);
    }

    .btn-success {
      background-color: #3E96F4;
      border-color: #3E96F4;
    }

    .btn-success:hover {
      background-color: #2d82d5;
      border-color: #2d82d5;
    }

    .btn-secondary {
      background-color: #CCC7BF;
      border-color: #CCC7BF;
      color: #31393C;
    }

    .btn-secondary:hover {
      background-color: #b3afa7;
      border-color: #b3afa7;
      color: #FFFFFF;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <div class="card shadow p-4">
      <h4 class="mb-4 fw-bold text-center">Edit Outgoing Mail</h4>
      <form method="POST">
        <div class="mb-3">
          <label for="date" class="form-label">Date Received</label>
          <input type="date" class="form-control" id="date" name="date"
            value="<?= htmlspecialchars($data['date_received']) ?>" required>
        </div>

        <div class="mb-3">
          <label for="ctrlNum" class="form-label">Control Number</label>
          <input type="text" class="form-control" id="ctrlNum" name="ctrlNum"
            value="<?= htmlspecialchars($data['control_number']) ?>" required>
        </div>

        <div class="mb-3">
          <label for="sec" class="form-label">Division/Section</label>
          <input type="text" class="form-control" id="sec" name="sec"
            value="<?= htmlspecialchars($data['division_section']) ?>" required>
        </div>

        <div class="mb-3">
          <label for="content" class="form-label">Contents (Regional Office)</label>
          <select class="form-control" name="content" id="contentSelect" required>
            <option value="">-- Select Regional Office --</option>
            <?php foreach ($regional_offices as $office): 
              $selected = ($office['regional_office'] === $data['contents']) ? 'selected' : '';
            ?>
              <option value="<?= htmlspecialchars($office['regional_office']) ?>"
                data-contact="<?= htmlspecialchars($office['contact_person']) ?>"
                data-designation="<?= htmlspecialchars($office['designation']) ?>"
                <?= $selected ?>>
                <?= htmlspecialchars($office['regional_office']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="contact_person" class="form-label">Contact Person</label>
          <input type="text" class="form-control" id="contactPerson" name="contact_person"
            value="<?= htmlspecialchars($data['contact_person']) ?>" readonly>
        </div>

        <div class="mb-3">
          <label for="designation" class="form-label">Position</label>
          <input type="text" class="form-control" id="designation" name="designation"
            value="<?= htmlspecialchars($data['designation']) ?>" readonly>
        </div>

        <div class="mb-3">
          <label for="pType" class="form-label">Type of Packages</label>
          <input type="text" class="form-control" id="pType" name="pType"
            value="<?= htmlspecialchars($data['package_type']) ?>" required>
        </div>

        <div class="mb-4">
          <label for="pieces" class="form-label">Pieces</label>
          <input type="number" class="form-control" id="pieces" name="pieces"
            value="<?= htmlspecialchars($data['pieces']) ?>" required>
        </div>

        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-success">Update</button>
          <a href="outgoing_table.php" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    
    document.getElementById('contentSelect').addEventListener('change', function () {
      const selectedOption = this.options[this.selectedIndex];
      const contact = selectedOption.getAttribute('data-contact');
      const designation = selectedOption.getAttribute('data-designation');

      document.getElementById('contactPerson').value = contact || '';
      document.getElementById('designation').value = designation || '';
    });

   
    window.addEventListener('DOMContentLoaded', () => {
      document.getElementById('contentSelect').dispatchEvent(new Event('change'));
    });
  </script>
</body>

</html>
