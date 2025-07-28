<?php
require 'db_connect.php';

$ids = $_POST['selected_ids'] ?? [];
if (empty($ids)) {
    echo "<script>alert('No rows selected.');history.back();</script>";
    exit;
}

$placeholders = implode(',', array_fill(0, count($ids), '?'));
$sql = "SELECT * FROM outgoing WHERE id IN ($placeholders) ORDER BY date_received ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Print Outgoing Mail</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
  body {
      font-family: "Century Gothic";
      font-size: 12px;
      margin: 40px;
  }

  .header-table {
      width: 100%;
      margin-bottom: 10px;
  }

  .logo-placeholder {
      width: 80px;
      height: 80px;
      border: 1px solid #000;
      text-align: center;
      vertical-align: middle;
      font-size: 10px;
      font-weight: bold;
  }

  .center-text {
      text-align: center;
  }

  .underline {
      text-decoration: underline;
  }

  table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 12px;
      table-layout: fixed;
  }

  th, td {
      border: 1px solid #000;
      padding: 6px;
      vertical-align: top;
  }

  th {
      background-color: #f0f0f0;
      text-align: center;
  }

  .divider-row td {
      font-weight: bold;
      background-color: #f9f9f9;
      text-align: left;
  }

  .total-row td {
      font-weight: bold;
      text-align: right;
  }

  .notes, .signature-block {
      margin-top: 20px;
  }

  .signature-block div {
      margin-top: 40px;
  }

  .checkbox {
      width: 14px;
      height: 14px;
      border: 1px solid #000;
      display: inline-block;
  }

  .input-box {
      border: none;
      border-bottom: 1px solid #000;
      width: 100%;
      font-family: "Century Gothic";
  }

  .output {
      display: none;
      white-space: pre-wrap;
      font-family: "Century Gothic";
  }

  .footer {
      margin-top: 50px;
      font-size: 11px;
      text-align: right;
      float: right;
  }

  .no-break {
      white-space: nowrap;
  }

  .no-print {
      display: block;
  }

  @media print {
      body {
          margin: 0;
          padding: 0;
      }

      .no-print {
          display: none !important;
      }

      .output {
          display: block;
      }

      .footer {
          position: running(footer);
          font-size: 11px;
          text-align: right;
      }

      table, tr, td, th {
          page-break-inside: avoid !important;
      }
  }
</style>

</head>
<body>

<table class="header-table">
  <tr>
    <td rowspan="2" class="logo-placeholder">
        <img src="prcLogo.png" alt="PRC Logo" style="height: 70px; width: 70px; object-fit: contain;">
    </td>
    <td class="center-text" style="font-weight: bold; font-size: 18px;">
        <strong>Professional Regulation Commission</strong>
    </td>
  </tr>
  <tr>
    <td class="center-text" style="font-weight: bold; font-size: 16px;">
        TRANSMITTAL FOR OUTGOING MAILS FOR REGIONAL OFFICES
    </td>
  </tr>
</table>

<div class="center-text">
  <strong>PROFESSIONAL REGULATION COMMISSION</strong><br>
  <span class="underline">CORDILLERA ADMINISTRATIVE REGION</span><br>
  (Office/Division/Section/Unit)
</div>

<table style="margin-top: 10px; width: 100%;">
  <tr>
    <td style="width: 50%; border: none;">
      <strong><?= date("F d, Y") ?></strong>
    </td>
    <td style="width: 50%; border: none; text-align: right;" class="no-break">
      <strong>Control No.</strong>
      <input type="text" name="ctrlNum" id="ctrlNum" class="input-box" placeholder="Enter Control No." style="margin-left: 10px; width: 150px;">
      <div id="ctrlNum_out" class="output" style="margin-left: 10px; width: 150px;"></div>
    </td>
  </tr>
</table>

<table>
  <thead>
    <tr>
      <th style="width: 20%;">Division/Section</th>
      <th style="width: 50%;">Contents</th>
      <th style="width: 20%;">Type of Packages</th>
      <th style="width: 10%;">Pieces</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $totalPieces = 0;
    foreach ($rows as $r):
        $totalPieces += (int)$r['pieces'];
    ?>
    <tr>
      <td><?= htmlspecialchars($r['division_section']) ?></td>
      <td>
        <?= htmlspecialchars($r['contact_person']) ?><br>
        <?= htmlspecialchars($r['designation']) ?><br>
        <input type="text" class="input-box" id="to<?= $r['id'] ?>" placeholder="Type here…">
        <div class="output" id="to<?= $r['id'] ?>_out"></div>
      </td>
      <td><?= htmlspecialchars($r['package_type']) ?></td>
      <td><?= htmlspecialchars($r['pieces']) ?></td>
    </tr>
    <?php endforeach; ?>
    <tr class="total-row">
      <td colspan="3">TOTAL</td>
      <td><?= $totalPieces ?></td>
    </tr>
  </tbody>
</table>

<div class="block"><br>
    <b>NOTE:</b><br>
    Type of Packages:<br>
    <?php foreach ($rows as $r): ?>
        • <?= htmlspecialchars($r['package_type']) ?><br>
    <?php endforeach; ?>
</div>

<div class="signature-block">
  <strong>Prepared by:</strong><br><br>
  <div style="border-bottom: 1px solid #000; width: 200px;"></div>
  <small>(Signature over Printed Name)</small>

  <div>
    <strong>Received by:</strong><br><br>
    <div style="border-bottom: 1px solid #000; width: 200px;"></div>
    <small>(Signature over Printed Name)</small>
  </div>
</div>

<div class="footer" id="footer">
  ARD 14<br>
  Rev.01<br>
  November 03, 2017<br>
  Page <span id="pageNumber">1</span> of <span id="totalPages">1</span>
</div>

<div class="no-print mt-4">
    <button type="button" class="btn btn-primary" onclick="prepareAndPrint()">🖨️ Print</button>
    <a href="outgoing_table.php" class="btn btn-secondary">🔙 Back</a>
</div>

<script>
function prepareAndPrint() {
    document.querySelectorAll('input[id^="to"]').forEach(input => {
        const output = document.getElementById(input.id + '_out');
        if (output) {
            output.textContent = input.value;
        }
    });

    const ctrlNum = document.getElementById('ctrlNum');
    const ctrlNumOut = document.getElementById('ctrlNum_out');
    if (ctrlNum && ctrlNumOut) {
        ctrlNumOut.textContent = ctrlNum.value;
    }

    setTimeout(() => {
        updatePageNumbers();
        window.print();
    }, 100);
}

function updatePageNumbers() {
    const totalHeight = document.body.scrollHeight;
    const pageHeight = 1123; // Approximate height of an A4 page at 96 DPI
    const totalPages = Math.ceil(totalHeight / pageHeight);

    document.getElementById("pageNumber").textContent = 1; // always page 1 for static HTML
    document.getElementById("totalPages").textContent = totalPages;
}
</script>

</body>
</html>
