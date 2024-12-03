<html>
<body>
<h2>Dinosaur Adoption Form</h2>
<p>If you are interested in sending an adoption request in for the selected dinosuar,
    please fill out the form below!
</p>
<p>----------------------------------------------------------------------</p>

<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'example';

// Checks to see if the dinosaur is available from both the "GET" and "POST" forms (For when the form is first created and then submitted).
$dinosaur_id = isset($_GET['dinosaur_id']) ? htmlspecialchars($_GET['dinosaur_id']) : (isset($_POST['dinosaur_id']) ? htmlspecialchars($_POST['dinosaur_id']) : null);

if (!$dinosaur_id) { // If no "Dinosuar_ID" has been brought over from "Prehistoric_Pals_Project.php":
    die("Dinosaur ID is required!");
}
?>

<form action="adopt-dinosaur.php" method="post">
    <p>Dinosaur ID: <b><?php echo $dinosaur_id; ?></b></p>
    <input type="hidden" name="dinosaur_id" value="<?php echo $dinosaur_id; ?>">
    <p>Enter Your User ID: <input type="text" name="user_id" required></p>
    <p><input type="submit" value="Submit Adoption Request"></p>
</form>

<!-- Back to Catalog Button -->
<p><a href="Prehistoric-Pals-Project.php"><button type="button">Back to Catalog</button></a></p>

<?php
// Takes inputted "User_ID" to update the selected dinosaur's "User_ID" and then creates a new adoption request.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_id = $_POST['user_id'];
    $dinosaur_id = $_POST['dinosaur_id'];

    // Uses the "date()" function to get the current date for the "Date_Of_Request" of the new adoption request.
    $date_of_request = date('Y-m-d');

    // Creates a unique, random 5-Digit "Request_ID" for the new adoption request (will be added to the selected dinosaur afterwards).
    $request_id = null;
    do {
        $request_id = str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT); // "str_pad" function that adds 0's to the front of any generated values less than 5 digits long.

        // Prepares & Executes an SQL statement that checks to see if the generated "Request_ID" matches any other ones currently in the "adoption_request" table.
        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM adoption_request WHERE Request_ID = ?");
        $check_stmt->bind_param("s", $request_id);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();
    } while ($count > 0); // Repeats this process until a unique "Request_ID" has been generated.

    // Inserts the new adoption request into the "adoption_request" table using the inputted "User_ID" and generated "Date" and "Request_ID".
    $stmt = $conn->prepare("INSERT INTO adoption_request (User_ID, Request_ID, Date_Of_Request, Request_Status) VALUES (?, ?, ?, 'Pending')");
    $stmt->bind_param("sss", $user_id, $request_id, $date_of_request);
    if ($stmt->execute()) {
        echo "<p>Adoption request submitted successfully!";

        // Update the dinosaur's "User_ID", "Request_ID" and "adoption_status" to be the new values of the created request.
        $update_stmt = $conn->prepare("UPDATE dinosaur SET User_ID = ?, Request_ID = ?, Adoption_Status = 'Pending' WHERE Dinosaur_ID = ?");
        $update_stmt->bind_param("sss", $user_id, $request_id, $dinosaur_id);

        if ($update_stmt->execute()) {
            echo "";
        } else {
            echo "<p>Error updating dinosaur information: " . $update_stmt->error . "</p>";
        }
        $update_stmt->close();
    } else {
        echo "<p>Error submitting adoption request: " . $stmt->error . "</p>";
    }
    $stmt->close();
    $conn->close();
}
?>
</body>
</html>
