<?php
include 'db_connect.php';

if (!isset($_GET['id'])) {
    die("Missing ID parameter.");
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM incoming WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("No record found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Route Slip - <?= htmlspecialchars($data['trackingNum']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Century Gothic';
            padding: 60px;
            font-size: 16px;
        }

        .doc-table {
            width: 100%;
            border-collapse: collapse;
        }

        .doc-table td {
            border: 1px solid #000;
            padding: 6px 10px;
            vertical-align: top;
        }

        .doc-table .label {
            width: 160px;
            font-weight: bold;
            text-align: center;
            background-color: #f8f8f8;
        }

        .input-box {
            width: 100%;
            height: 40px;
            border: none;
            background: transparent;
        }

        textarea.input-box {
            width: 100%;
            height: 100%;
            border: none;
            resize: none;
            background: transparent;
        }

        .output {
            display: none;
            white-space: pre-wrap;
        }

        .footer {
            font-size: 12px;
            text-align: right;
            padding-top: 40px;
        }

        @media print {
            .output {
                display: none !important;
            }
            .no-print {
            display: none !important;
        }
        }
    </style>
</head>
<body>


<div class="d-flex align-items-center justify-content-between mb-4" style="border: 1px solid black; padding: 10px;">
    <div style="flex: 0 0 auto;">
        <img src="prcLogo.png" alt="PRC Logo" style="height: 80px; width: 80px; object-fit: contain;">
    </div>
    <div style="flex: 1; text-align: center;">
        <div style="font-weight: bold; font-size: 18px;">Professional Regulation Commission</div>
        <div style="font-weight: bold; font-size: 16px;">ROUTE SLIP</div>
    </div>
    <div style="flex: 0 0 auto; width: 80px;"></div>
</div>

<form id="printForm">
<table class="doc-table">
    <tr>
        <td class="label">Reference No.</td>
        <td colspan="3"><?= htmlspecialchars($data['trackingNum']) ?></td>
    </tr>
    <tr>
        <td class="label">Subject</td>
        <td colspan="3"><?= htmlspecialchars($data['subj']) ?></td>
    </tr>
    <tr>
        <td class="label">Received By</td>
        <td><?= htmlspecialchars($data['recipient']) ?></td>
        <td class="label">Source/Origin</td>
        <td><?= htmlspecialchars($data['source']) ?></td>
    </tr>
    <tr>
        <td class="label">Date Received</td>
        <td colspan="3"><?= htmlspecialchars($data['dateRecd']) ?></td>
    </tr>
    <tr>
        <td class="label">Attachment</td>
        <td colspan="3"><?= htmlspecialchars($data['attachment']) ?></td>
    </tr>

   
    <tr>
        <td class="label"><strong>TO</strong></td>
        <td colspan="3" class="label"><strong>ACTION REQUIRED / REMARKS</strong></td>
    </tr>

   
    <tr>
        <td><input type="text" class="form-control input-box" id="to1"><div class="output" id="to1_out"></div></td>
        <td colspan="3" rowspan="3">
            <textarea class="form-control input-box" id="remarks" rows="5"></textarea>
            <div class="output" id="remarks_out"></div>
        </td>
    </tr>
    <tr>
        <td><input type="text" class="form-control input-box" id="to2"><div class="output" id="to2_out"></div></td>
    </tr>
    <tr>
        <td><input type="text" class="form-control input-box" id="to3"><div class="output" id="to3_out"></div></td>
    </tr>
</table>
</form>

<div class="footer">
    BAG-ORD-01<br>
    Rev.0<br>
    May 24, 201<br> 
    Page 1 of 1 
</div>

<div class="no-print mt-4">
    <button type="button" class="btn btn-primary" onclick="prepareAndPrint()">🖨️ Print</button>
    <a href="incoming_table.php" class="btn btn-secondary">🔙 Back</a>
</div>

<script>
    function prepareAndPrint() {
        for (let i = 1; i <= 3; i++) {
            const toVal = document.getElementById(`to${i}`).value;
            document.getElementById(`to${i}_out`).textContent = toVal;
        }

        const remarksVal = document.getElementById('remarks').value;
        document.getElementById('remarks_out').textContent = remarksVal;

        window.print();
    }
</script>

</body>
</html>
