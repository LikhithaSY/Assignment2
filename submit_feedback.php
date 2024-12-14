<?php
// Database connection settings
$servername = "localhost";
$username = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password
$dbname = "feedback_db"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize the input to prevent XSS and other security issues
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $feedback = htmlspecialchars($_POST['feedback']);
    $rating = isset($_POST['rating']) ? htmlspecialchars($_POST['rating']) : 'No rating given';
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }
    
    // Handle the "liked about our service" checkboxes
    $liked = isset($_POST['liked']) ? $_POST['liked'] : [];

    // Convert the liked array to a JSON string to store in the database
    $liked_json = json_encode($liked);

    // Prepare an SQL statement to insert the feedback into the database
    $stmt = $conn->prepare("INSERT INTO feedbacks (name, email, feedback, rating, liked) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $feedback, $rating, $liked_json);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<div class='container'>";
        echo "<h2>Thank you for your feedback!</h2>";
        echo "<p><strong>Name:</strong> $name</p>";
        echo "<p><strong>Email:</strong> $email</p>";
        echo "<p><strong>Feedback:</strong> $feedback</p>";
        echo "<p><strong>Rating:</strong> $rating</p>";
        
        // Display the liked services
        if (!empty($liked)) {
            echo "<p><strong>What you liked about our service:</strong></p>";
            echo "<ul>";
            foreach ($liked as $like) {
                echo "<li>$like</li>";
            }
            echo "</ul>";
        } else {
            echo "<p><strong>What you liked about our service:</strong> No selections made</p>";
        }
        echo "</div>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
