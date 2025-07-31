<?php
include 'db_connect.php';
include 'sidebar.php';

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

    $stmt = $conn->prepare("INSERT INTO outgoing (date_received, control_number, division_section, contents, contact_person, designation, package_type, pieces) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssi", $date, $ctrlNum, $sec, $content, $contact_person, $designation, $pType, $pieces);

    $log_stmt = $conn->prepare("INSERT INTO activity_logs (action_type, reference_no, description) VALUES (?, ?, ?)");
    $action_type = "outgoing";
    $description = "New Outgoing Mail Added.";
    $log_stmt->bind_param("sss", $action_type, $ctrlNum, $description);
    $log_stmt->execute();

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Data saved successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Outgoing Mails</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
  body {
    background-color: #EDEEEB; 
    color: #31393C;
    font-family: 'Century Gothic';
    margin-left: 250px;
  }

  .form-wrapper {
    background-color: #FFFFFF; 
    padding: 2rem;
    border-radius: 1rem;
    max-width: 600px;
    margin: 5vh auto;
    box-shadow: 0 0 10px rgba(49, 57, 60, 0.1); 
  }

  .form-label {
    font-weight: 600;
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

  .submit-btn {
    background-color: #3E96F4;
    color: #FFFFFF; 
    font-weight: 600;
    border: none;
    border-radius: 0.5rem;
    padding: 0.6rem 1.5rem;
    width: 100%;
  }

  .submit-btn:hover {
    background-color: #3379C7;
  }
</style>
</head>
<body>
  <div class="form-wrapper shadow border">
    <h3 class="mb-4 fw-bold text-center">OUTGOING MAILS</h3>
    <form method="POST" action="">
      <div class="mb-3">
        <label for="date" class="form-label">Date Received</label>
        <input type="date" class="form-control" id="date" name="date" required>
      </div>

      <div class="mb-3">
        <label for="ctrlNum" class="form-label">Control Number</label>
        <input type="text" class="form-control" id="ctrlNum" name="ctrlNum" required>
      </div>

      <div class="mb-3">
        <label for="sec" class="form-label">Division/Section</label>
        <input type="text" class="form-control" id="sec" name="sec" required>
      </div>

      <div class="mb-3">
        <label for="content" class="form-label">Contents (Regional Office)</label>
        <select class="form-control" name="content" id="contentSelect" required>
          <option value="">-- Select Regional Office --</option>
        <?php foreach ($regional_offices as $office): ?>
          <option value="<?= htmlspecialchars($office['regional_office']) ?>"
          data-contact="<?= htmlspecialchars($office['contact_person']) ?>"
          data-designation="<?= htmlspecialchars($office['designation']) ?>">
        <?= htmlspecialchars($office['regional_office']) ?>
          </option>
        <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label for="contact_person" class="form-label">Contact Person</label>
        <input type="text" class="form-control" name="contact_person" id="contactPerson">
      </div>

      <div class="mb-3">
        <label for="designation" class="form-label">Position</label>
        <input type="text" class="form-control" name="designation" id="designation">
      </div>

      <div class="mb-3">
        <label for="pType" class="form-label">Type of Packages</label>
        <input type="text" class="form-control" id="pType" name="pType" required>
      </div>

      <div class="mb-4">
        <label for="pieces" class="form-label">Pieces</label>
        <input type="number" class="form-control" id="pieces" name="pieces" required>
      </div>

      <button type="submit" class="submit-btn">Submit</button>
    </form>
  </div>

  <script>
    const contentSelect = document.getElementById('contentSelect');
    const contactPersonInput = document.getElementById('contactPerson');
    const designationInput = document.getElementById('designation');

    contentSelect.addEventListener('change', function () {
      const selectedOption = this.options[this.selectedIndex];
      const contact = selectedOption.getAttribute('data-contact') || '';
      const designation = selectedOption.getAttribute('data-designation') || '';

      contactPersonInput.value = contact;
      designationInput.value = designation;
    });
  </script>
</body>
</html>
