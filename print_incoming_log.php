<?php
include 'db_connect.php';

if (!isset($_POST['selected_ids']) || !is_array($_POST['selected_ids'])) {
    die("No records selected.");
}

$ids = array_map('intval', $_POST['selected_ids']);
$placeholders = implode(',', array_fill(0, count($ids), '?'));

$stmt = $conn->prepare("SELECT * FROM incoming WHERE id IN ($placeholders)");
$stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
$stmt->execute();
$result = $stmt->get_result();


date_default_timezone_set('Asia/Manila'); 
$dateNow = date("F d, Y");              

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Incoming Documents and Parcel Log</title>
  <style>
    body {
      font-family: 'Century Gothic';
      padding: 40px;
      font-size: 11px;
      color: #000;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
      font-size: 15px;
    }

    .header img {
      float: left;
      height: 70px;
    }

    .header h3, .header h4 {
      margin: 0;
    }

    .date {
      text-align: right;
      font-size: 10px;
      margin-bottom: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 10px;
    }

    th, td {
      border: 1px solid #000;
      padding: 4px;
      text-align: center;
      vertical-align: middle;
    }

    .footer {
      margin-top: 40px;
      font-size: 10px;
      text-align: right;
    }

    @media print {
      .no-print {
        display: none;
      }
    }

    button {
      font-family: 'Century Gothic';
    }

  </style>
</head>
<body>

<div class="header">
  <img src="prcLogo.png" alt="PRC Logo">
  <div>
    <h3>PROFESSIONAL REGULATION COMMISSION</h3>
    <h4>Cordillera Administrative Region</h4>
    <div>Incoming Documents and Parcel Log</div>
  </div>
</div>

<div class="date">
  <div><?= $dateNow ?></div>
  <div id="current-time"><?= date('h:i A') ?></div> 
</div>

<table>
  <thead>
    <tr>
      <th>CONTROL NUMBER</th>
      <th>SOURCE</th>
      <th>DATE RECEIVED</th>
      <th>RECEIVED BY</th>
      <th>SUBJECT</th>
      <th>ACTION UNIT</th>
      <th>RELEASED TO</th>
      <th>DATE</th>
      <th>TRACKING NUMBER</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['ctrlNum']) ?></td>
        <td><?= htmlspecialchars($row['source']) ?></td>
        <td><?= htmlspecialchars($row['dateRecd']) ?></td>
        <td><?= htmlspecialchars($row['recipient']) ?></td>
        <td><?= htmlspecialchars($row['subj']) ?></td>
        <td><?= htmlspecialchars($row['actionUnit']) ?></td>
        <td><?= htmlspecialchars($row['recipient']) ?></td>
        <td><?= htmlspecialchars($row['dateRel']) ?></td>
        <td><?= htmlspecialchars($row['trackingNum']) ?></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
    
    <div class="footer">
      BAG-FAD-REC-07<br>
      REV.00<br>
      JANUARY 08, 2025<br>
      PAGE 1 OF 1
      </div>

<br><br>
<div class="no-print mt-4">
  <button onclick="window.print()">🖨️ Print</button>
  <a href="incoming_table.php" class="btn">🔙 Back</a>
</div>



</body>
</html>
