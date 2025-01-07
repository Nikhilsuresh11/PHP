<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header('Location: ../login.php');
    exit;
}

// Fetch upcoming events
$sql = "SELECT title, event_date, description 
        FROM events 
        WHERE department_id = (SELECT department_id FROM students WHERE id = {$_SESSION['student_id']}) 
        AND event_date >= CURDATE() 
        ORDER BY event_date ASC";
$result = $conn->query($sql);
?>

<h1>Upcoming Events</h1>
<table>
    <tr>
        <th>Title</th>
        <th>Date</th>
        <th>Description</th>
    </tr>
    <?php while ($event = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $event['title']; ?></td>
            <td><?php echo $event['event_date']; ?></td>
            <td><?php echo $event['description']; ?></td>
        </tr>
    <?php endwhile; ?>
</table>
