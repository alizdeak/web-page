<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "aliz";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create subscribers table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    name VARCHAR(255),
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table subscribers created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST["name"];
    $email = $_POST["email"];

    // Prepare and bind SQL statement for subscriber registration
    $stmt = $conn->prepare("INSERT INTO subscribers (name, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $email);

    // Execute the statement
    if ($stmt->execute() === TRUE) {
        echo "New subscriber added successfully<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close statement
    $stmt->close();
}

// Fetch subscribers' emails from the database
$sql = "SELECT email FROM subscribers";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Compose email
    $subject = "Your Newsletter Subject";
    $message = "<html><body>";
    $message .= "<h1>Your Newsletter Content</h1>";
    // Add more content here as needed
    $message .= "</body></html>";

    // Set headers for HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // Send email to each subscriber
    while ($row = $result->fetch_assoc()) {
        $to = $row['email'];
        mail($to, $subject, $message, $headers);
        echo "Newsletter sent to: " . $to . "<br>";
    }
} else {
    echo "No subscribers found";
}

// Close connection
$conn->close();
?>
