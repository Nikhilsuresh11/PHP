<?php
include '../includes/db.php'; // Include the database connection

// List of students data to insert

$students = [
    ['Isla Martin', 'AIML12347', 2, 'isla.martin@example.com', 'password123', '2001-06-15', '555-234-5678', '321 Pinewood Blvd, Springfield'],
    ['James Lee', 'DC12347', 3, 'james.lee@example.com', 'password123', '1998-03-12', '555-345-6789', '654 Elmwood Ave, Springfield'],
    ['Kara Wilson', 'SS12347', 4, 'kara.wilson@example.com', 'password123', '1999-12-20', '555-456-7890', '123 Willowbrook Rd, Springfield'],
    ['Liam Harris', 'DS12349', 1, 'liam.harris@example.com', 'password123', '2000-11-05', '555-567-8901', '456 Oakwood Dr, Springfield'],
    ['Mia Clark', 'AIML12348', 2, 'mia.clark@example.com', 'password123', '2001-04-17', '555-678-9012', '789 Maple Ln, Springfield'],
    ['Noah Walker', 'DC12348', 3, 'noah.walker@example.com', 'password123', '1998-01-22', '555-789-0123', '654 Birchwood Rd, Springfield'],
    ['Olivia Scott', 'SS12348', 4, 'olivia.scott@example.com', 'password123', '1999-10-30', '555-890-1234', '321 Pinewood Ave, Springfield'],
    ['Paul Adams', 'DS12350', 1, 'paul.adams@example.com', 'password123', '2001-02-08', '555-901-2345', '123 Cedar Ln, Springfield'],
    ['Quinn Baker', 'AIML12349', 2, 'quinn.baker@example.com', 'password123', '2000-07-19', '555-234-5678', '456 Birchwood Rd, Springfield'],
    ['Rachel Moore', 'DC12349', 3, 'rachel.moore@example.com', 'password123', '1997-09-10', '555-345-6789', '789 Oakwood Blvd, Springfield'],
    ['Samuel Taylor', 'SS12349', 4, 'samuel.taylor@example.com', 'password123', '2001-03-28', '555-456-7890', '654 Pinewood Ave, Springfield'],
    ['Tessa Allen', 'DS12351', 1, 'tessa.allen@example.com', 'password123', '2000-01-05', '555-567-8901', '987 Maplewood Ave, Springfield'],
    ['Ursula King', 'AIML12350', 2, 'ursula.king@example.com', 'password123', '2001-11-25', '555-678-9012', '321 Cedar St, Springfield'],
    ['Victor Young', 'DC12350', 3, 'victor.young@example.com', 'password123', '1999-04-15', '555-789-0123', '654 Elmwood Blvd, Springfield'],
    ['Wendy Hill', 'SS12350', 4, 'wendy.hill@example.com', 'password123', '2000-08-13', '555-890-1234', '123 Oakwood Ln, Springfield'],
    ['Xander Davis', 'DS12352', 1, 'xander.davis@example.com', 'password123', '2001-09-03', '555-234-5678', '456 Willow St, Springfield'],
    ['Yvonne Lopez', 'AIML12351', 2, 'yvonne.lopez@example.com', 'password123', '2000-12-19', '555-345-6789', '789 Birchwood Rd, Springfield'],
    ['Zachary Martin', 'DC12351', 3, 'zachary.martin@example.com', 'password123', '1998-06-23', '555-456-7890', '654 Maplewood Ave, Springfield'],
    ['Amelia Walker', 'SS12351', 4, 'amelia.walker@example.com', 'password123', '2000-05-11', '555-567-8901', '123 Pinewood Blvd, Springfield'],
    ['Benjamin Scott', 'DS12353', 1, 'benjamin.scott@example.com', 'password123', '2001-10-10', '555-876-5432', '123 Pinewood Ave, Springfield'],
    ['Catherine Green', 'AIML12352', 2, 'catherine.green@example.com', 'password123', '2000-09-04', '555-234-5678', '456 Pinewood Blvd, Springfield'],
    ['Daniel Moore', 'DC12352', 3, 'daniel.moore@example.com', 'password123', '2001-03-19', '555-987-6543', '789 Cedarwood Rd, Springfield'],
    ['Eliza Harris', 'SS12352', 4, 'eliza.harris@example.com', 'password123', '1999-12-29', '555-345-6789', '654 Oakwood Blvd, Springfield'],
    ['Fiona Robinson', 'DS12354', 1, 'fiona.robinson@example.com', 'password123', '2000-02-22', '555-456-7890', '321 Willow St, Springfield'],
    ['Gina Walker', 'AIML12353', 2, 'gina.walker@example.com', 'password123', '1999-05-17', '555-678-9012', '654 Maplewood Ave, Springfield']
];


foreach ($students as $student) {
    // Hash the password for each student
    $hashed_password = password_hash($student[4], PASSWORD_DEFAULT);

    // Prepare SQL query to insert student into the database
    $sql = "INSERT INTO students (name, roll_number, department_id, email, password, dob, contact_number, address) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare and bind
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssisssss", 
            $student[0], // name
            $student[1], // roll_number
            $student[2], // department_id
            $student[3], // email
            $hashed_password, // password
            $student[5], // dob
            $student[6], // contact_number
            $student[7]  // address
        );

        // Execute the statement
        if ($stmt->execute()) {
            echo "Student " . $student[0] . " inserted successfully.<br>";
        } else {
            echo "Error inserting student " . $student[0] . ": " . $stmt->error . "<br>";
        }
    } else {
        echo "Error preparing statement: " . $conn->error . "<br>";
    }
}

// Close the connection
$conn->close();
?>
