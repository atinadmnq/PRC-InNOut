<?php
include 'db_connect.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die("Invalid ID.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ctrlNum     = $_POST['ctrlNum'];
    $source      = $_POST['source'];
    $dateRecd    = $_POST['dateRecd'];
    $timeRe      = $_POST['timeRe'];
    $subj        = $_POST['subj'];
    $attachment  = $_POST['attachment'];
    $stat        = $_POST['stat'];
    $actionUnit  = $_POST['actionUnit'];
    $dateRel     = $_POST['dateRel'];
    $recipient   = $_POST['recipient'];
    $intial      = $_POST['intial'];
    $trackingNum = $_POST['trackingNum'];

    $stmt = $conn->prepare("UPDATE incoming SET 
        ctrlNum=?, source=?, dateRecd=?, timeRe=?, subj=?, 
        attachment=?, stat=?, actionUnit=?, dateRel=?, 
        recipient=?, intial=?, trackingNum=?
        WHERE id=?");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssssssssssi",
        $ctrlNum, $source, $dateRecd, $timeRe, $subj,
        $attachment, $stat, $actionUnit, $dateRel,
        $recipient, $intial, $trackingNum, $id
    );

    if ($stmt->execute()) {
        header("Location: incoming_table.php?updated=1");
        exit;
    } else {
        die("Update failed: " . $stmt->error);
    }
}


$stmt = $conn->prepare("SELECT * FROM incoming WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    die("Record not found.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Incoming</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #EDEEEB;
            color: #31393C;
            font-family: 'Century Gothic';
            padding: 2rem;
        }

        .form-container {
            background-color: #FFFFFF;
            padding: 2rem;
            border-radius: 1rem;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 10px rgba(49, 57, 60, 0.1);
        }

        h3 {
            text-align: center;
            color: #3E96F4;
            margin-bottom: 2rem;
        }

        label {
            font-weight: bold;
            color: #31393C;
        }

        .form-control {
            border: 1px solid #CCC7BF;
        }

        .btn-success {
            background-color: #3E96F4;
            border-color: #3E96F4;
        }

        .btn-success:hover {
            background-color: #2c7ed6;
            border-color: #2c7ed6;
        }

        .btn-secondary {
            background-color: #CCC7BF;
            border-color: #CCC7BF;
            color: #31393C;
        }

        .btn-secondary:hover {
            background-color: #b2ada6;
            border-color: #b2ada6;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-container mx-auto col-md-8 shadow border p-4">
        <h3 class="mb-4 fw-bold text-center">EDIT INCOMING RECORD</h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Control Number</label>
                <input type="text" name="ctrlNum" class="form-control" value="<?= htmlspecialchars($data['ctrlNum']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Source</label>
                <input type="text" name="source" class="form-control" value="<?= htmlspecialchars($data['source']) ?>" required>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Date Received</label>
                    <input type="date" name="dateRecd" class="form-control" value="<?= htmlspecialchars($data['dateRecd']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Time Received</label>
                    <input type="time" name="timeRe" class="form-control" value="<?= htmlspecialchars($data['timeRe']) ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Subject Matter</label>
                <input type="text" name="subj" class="form-control" value="<?= htmlspecialchars($data['subj']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Attachment</label>
                <input type="text" name="attachment" class="form-control" value="<?= htmlspecialchars($data['attachment']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="stat" class="form-control" required>
                    <option value="">-- Select Status --</option>
                    <option value="Pending" <?= $data['stat'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Released" <?= $data['stat'] === 'Released' ? 'selected' : '' ?>>Released</option>
                    <option value="In Progress" <?= $data['stat'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Action Unit</label>
                <input type="text" name="actionUnit" class="form-control" value="<?= htmlspecialchars($data['actionUnit']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Date Released</label>
                <input type="date" name="dateRel" class="form-control" value="<?= htmlspecialchars($data['dateRel']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Recipient</label>
                <input type="text" name="recipient" class="form-control" value="<?= htmlspecialchars($data['recipient']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Receiver Initial</label>
                <input type="text" name="intial" class="form-control" value="<?= htmlspecialchars($data['intial']) ?>" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Tracking Number</label>
                <input type="text" name="trackingNum" class="form-control" value="<?= htmlspecialchars($data['trackingNum']) ?>" required>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-success">Update</button>
                <a href="incoming_table.php" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
</body>

</html>
