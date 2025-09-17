<?php

$servername = "localhost";
$username = "u852215096_admin";
$password = "dhiMath$1947";
$dbname = "u852215096_dhimath";

$email = NULL;
$stmt = NULL;
$conn = NULL;

// Function to validate email using regular expression
function isValidEMail($str) {
    return (!preg_match(
"^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $str))
        ? FALSE : TRUE;
}

// Function to send HTTP Code and Response to the client
function apiResponse($data, $statusCode = 200) {
    global $stmt;
    global $conn;

    if (!is_null($stmt))
	$stmt->close();
    if (!is_null($conn))
	$conn->close();

    http_response_code($statusCode);
    echo json_encode($data);
    exit(); // Terminate script execution after sending response
}

header('Content-Type: application/json'); // Set header for JSON response

if (!($_SERVER['REQUEST_METHOD'] === 'GET')) {
    // Handle unsupported methods
    apiResponse(['status' => 'error', 'message' => 'Method not allowed'], 405);
}

// Fetch the email GET Parameter if set
if (isset($_GET['email'])) {
    $email = trim(htmlspecialchars($_GET['email']));
} else {
    apiResponse(['status' => 'error', 'message' => 'EMail ID Parameter is missing'], 400);
}

if (!isValidEMail($email)) {
    apiResponse(['status' => 'error', 'message' => 'Invalid EMail Address'], 400);
}

date_default_timezone_set('Asia/Kolkata');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    apiResponse(['status' => 'error', 'message' => 'Internal Server Error'], 500);
}

// Prepare and Bind
$stmt = $conn->prepare("SELECT email, endDate FROM Subscriptions WHERE email=?");
$stmt->bind_param("s", $email);

// Set parameters and Execute
$stmt->execute();

if ($stmt->error) {
    apiResponse(['status' => 'error', 'message' => 'Internal Server Error'], 500);
}

$result = $stmt->get_result();
$num_rows = $result->num_rows;

if ($num_rows <= 0) {
    apiResponse(['status' => 'error', 'message' => 'Not Subscribed'], 404);
}

$row = $result->fetch_assoc(); // Fetch the 1st row
apiResponse(['status' => 'success', 'endDate' => $row["endDate"]], 200);

?>
