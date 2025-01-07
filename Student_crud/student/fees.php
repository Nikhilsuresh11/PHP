<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header('Location: ../login.php');
    exit;
}

$student_id = $_SESSION['student_id'];
$sql = "SELECT amount, payment_date, status 
        FROM payments 
        WHERE student_id = $student_id";
$result = $conn->query($sql);
?>

<h1>Your Fees</h1>
<table>
    <tr>
        <th>Amount</th>
        <th>Payment Date</th>
        <th>Status</th>
    </tr>
    <?php while ($payment = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $payment['amount']; ?></td>
            <td><?php echo $payment['payment_date']; ?></td>
            <td><?php echo $payment['status']; ?></td>
        </tr>
    <?php endwhile; ?>
</table>
