<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #f4ede6; /* Light brown background color */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
        }
        .button {
            background-color: #8b5e3c; /* Brown button color */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>

<body>
<div class="container">
    <br>
    <a href="main.html" class="button">Főoldal</a>
</div>

</body>
</html>
<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "aliz"; 


ini_set("SMTP", "smtp.gmail.com");
ini_set("smtp_port", "587");

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create subscribers table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS subscriber (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table subscribers created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

// Ellenőrizd, hogy elküldték-e az űrlapot
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $email = $_POST["email"];

    // az email ha megvan
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
    } else {

        // ha letezik e mar az adatbazisban
        $check_sql = "SELECT * FROM subscriber WHERE email = '$email'";
        $result = $conn->query($check_sql);

        if ($result->num_rows > 0) {
            echo "Email already subscribed";
        } else {
            // uj feliratkozo
            $insert_sql = "INSERT INTO subscriber (email) VALUES ('$email')";
            if ($conn->query($insert_sql) === TRUE) {
                echo "New subscriber added successfully<br>";

                // Send welcome email
                $subject = "Welcome to Our Website!";
                $message = "Thank you for subscribing to our website newsletter!";
                $headers = "From: alyzdeak@gmail.com"; // Change this to your Gmail address

				if (@mail($email, $subject, $message, $headers)) {
                    echo "Welcome email sent successfully to: " . $email . "<br>";
                } else {
                    echo "Failed to send welcome email to: " . $email . "<br>";
                    error_log("Failed to send welcome email to: " . $email); // Log error
                } 

            } else {
                echo "Error: " . $insert_sql . "<br>" . $conn->error;
            }
        }
    }
}


// Close connection
$conn->close();
?>
