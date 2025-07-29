<?php
include 'db_connect.php';

if (!isset($_POST['selected_ids']) || !is_array($_POST['selected_ids'])) {
    die("No records selected.");
}

$ids = array_map('intval', $_POST['selected_ids']);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$types = str_repeat('i', count($ids));

$stmt = $conn->prepare("SELECT * FROM incoming WHERE id IN ($placeholders)");
$stmt->bind_param($types, ...$ids);
$stmt->execute();
$result = $stmt->get_result();

$slips = [];
while ($row = $result->fetch_assoc()) {
    $slips[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Route Slips</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Century Gothic';
            padding: 60px;
            font-size: 12px;
        }

        .slip {
            width: 100%;
            border: 1px solid #000;
            padding: 20px;
            box-sizing: border-box;
            margin-bottom: 20px;
            page-break-inside: avoid;
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
            border: none;
            background: transparent;
        }

        textarea.input-box {
            resize: none;
            background: transparent;
        }

        .output {
            display: none;
            white-space: pre-wrap;
        }

        .page-wrapper {
            page-break-after: always;
        }

        .footer {
            font-size: 8px;
            text-align: right;
            padding-top: 20px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .output {
                display: block !important;
            }

            .page-wrapper {
                page-break-inside: avoid;
            }
        }

        .logo-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .logo-header img {
            height: 60px;
        }
    </style>
</head>
<body>

<div class="no-print text-end mb-4">
    <button class="btn btn-primary" onclick="prepareAndPrint()">🖨️ Print All</button>
    <a href="incoming_table.php" class="btn btn-secondary">🔙 Back</a>
</div>

<form id="multiPrintForm">
<?php
$page = 1;
$total = count($slips);
for ($i = 0; $i < count($slips); $i += 2):
?>
<div class="page-wrapper">
    <?php for ($j = $i; $j < $i + 2 && $j < count($slips); $j++):
        $data = $slips[$j];
        $uid = "slip_" . $data['id']; ?>
    <div class="slip">
        <div class="logo-header">
            <img src="prcLogo.png" alt="PRC Logo">
            <div style="text-align: center; flex: 1;">
                <div style="font-weight: bold; font-size: 18px;">PROFESSIONAL REGULATION COMMISSION</div>
                <div style="font-weight: bold; font-size: 16px;">Cordillera Administrative Region</div>
                <div style="font-weight: bold; font-size: 14px;">Route Slip</div>
            </div>
            <div style="width: 60px;"></div>
        </div>

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
                <td>
                    <input type="text" class="form-control input-box" id="<?= $uid ?>_to1">
                    <div class="output" id="<?= $uid ?>_to1_out"></div>
                </td>
                <td colspan="3" rowspan="3">
                    <textarea class="form-control input-box" id="<?= $uid ?>_remarks" rows="5"></textarea>
                    <div class="output" id="<?= $uid ?>_remarks_out"></div>
                </td>
            </tr>
            <tr><td><input type="text" class="form-control input-box" id="<?= $uid ?>_to2"><div class="output" id="<?= $uid ?>_to2_out"></div></td></tr>
            <tr><td><input type="text" class="form-control input-box" id="<?= $uid ?>_to3"><div class="output" id="<?= $uid ?>_to3_out"></div></td></tr>
        </table>

        <div class="footer">
            BAG-ORD-01<br>
            Rev.0<br>
            May 24, 2019<br>
            Page <?= $page ?> of <?= $total ?>
        </div>
    </div>
    <?php $page++; endfor; ?>
</div>
<?php endfor; ?>
</form>

<script>
function prepareAndPrint() {
    const ids = <?= json_encode(array_column($slips, 'id')) ?>;
    ids.forEach(id => {
        const uid = `slip_${id}`;
        for (let i = 1; i <= 3; i++) {
            const val = document.getElementById(`${uid}_to${i}`).value;
            document.getElementById(`${uid}_to${i}_out`).textContent = val;
        }
        const remarksVal = document.getElementById(`${uid}_remarks`).value;
        document.getElementById(`${uid}_remarks_out`).textContent = remarksVal;
    });

    window.print();
}
</script>

</body>
</html>
