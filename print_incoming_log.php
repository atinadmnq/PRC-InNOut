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
    counter-reset: page 1; /* Start counter from 1 */
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
    position: fixed;
    bottom: 0;
    right: 0;
    left: 0;
    text-align: right;
    font-size: 10px;
    padding-right: 40px;
  }

  .page-number::before {
    content: "PAGE " counter(page) " OF " counter(page);
  }

  @media print {
    body {
      counter-reset: page 1; 
    }

    .no-print {
      display: none !important;
    }

    .footer {
      position: fixed;
      bottom: 0;
      right: 0;
      left: 0;
      text-align: right;
      font-size: 10px;
      padding-right: 40px;
    }

    .page-number::before {
      content: "PAGE " counter(page) " OF " counter(page);
    }
  }

  .no-print-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    margin-top: 20px;
  }

  .btn-custom {
    font-family: 'Century Gothic';
    padding: 8px 16px;
    font-size: 12px;
    border: none;
    border-radius: 6px;
    color: white;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
  }

  .btn-print {
    background-color: #0d6efd;
  }

  .btn-back {
    background-color: #6c757d;
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
      <th>DATE RELEASED</th>
      <th>TRACKING NUMBER</th>
    </tr>
  </thead>
  <tbody>
    
    <?php 
    $count = 0;
    while ($row = $result->fetch_assoc()): 
        $count++;
    ?>
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

    <tr style="font-weight: bold; background-color: #f0f0f0;">
      <td colspan="9" style="text-align: right;">Total: <?= $count ?> entr<?= $count === 1 ? 'y' : 'ies' ?></td>
    </tr>
  </tbody>
</table>

<div class="footer">
  BAG-FAD-REC-07<br>
  REV.00<br>
  JANUARY 08, 2025<br>
  <span class="page-number"></span>
</div>

<div class="no-print no-print-buttons">
  <button onclick="window.print()" class="btn-custom btn-print">
    🖨️ Print All
  </button>
  <a href="incoming_table.php" class="btn-custom btn-back">
    🔙 Back
  </a>
</div>

</body>
</html>
