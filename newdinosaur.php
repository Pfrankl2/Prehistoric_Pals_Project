<html>
<style>
table, th, td {
  border: 1px solid black;
}

<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'example';
?>

</style>
<body>
<p><h2>New Dinosaur Entry Form</h2></p>
<p>Employee form to enter a new dinosaur into the database and accep/deny an adoption request.</p>
<p>----------------------------------------------------------------------</p>
<form action="newdinosaur.php" method=get>
    Enter Dinosaur Name: <input type=text size=20 name="name">
    <p>Enter Dinosaur ID Number: <input type=text size=5 name="dinosaur_id">
    <p>Enter Dinosaur Species: <input type=text size=10 name="species">
    <p>Enter Dinosaur Age: <input type=text size=10 name="age">
    <p>Enter Dinosaur Gender: <input type=text size=10 name="gender">
    <p>Enter Dinosaur Size: <input type=text size=10 name="size">

    <p><label for="price">Enter Dinosaur Price:</label>
    <input type="number" name="price" value="0.0" required>

    <p>Enter Dinosaur Adoption Status: <input type=text size=10 name="adoption_status">
    <p>Enter Shelter ID Of Dinosaur: <input type=text size=10 name="shelter_id">

    <p><label for="request_id">Enter Request ID Of Dinosaur (Leave NULL if Available):</label>
    <input type=text size=10 name="request_id" value="NULL" required>

    <p><label for="user_id">Enter User ID Of Dinosaur (Leave NULL if Available):</label>
    <input type=text size=10 name="user_id" value="NULL" required>
    <p><input type=submit value="submit">
    <input type="hidden" name="form_submitted" value="1">
</form>
<p>----------------------------------------------------------------------</p>

<?php
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Implements/Handles logic for the "Accept" and "Deny" buttons of an adoption request on the employee's end.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) { // Once the form is submitted:
    $action = $_POST['action'];
    $dinosaur_id = $_POST['dinosaur_id'];
    $request_id = $_POST['request_id'];

    if ($action === 'accept') {
        // Accepts the dinosaur's adoption request: Sets "Shelter_ID" to NULL (Meaning the dino is no longer in a shelter)
        // and then updates the dino's "adoption_status" to "Adopted".
        $update_dinosaur_sql = "UPDATE dinosaur SET Shelter_ID = NULL, Adoption_Status = 'Adopted' WHERE Dinosaur_ID = ?";
        $stmt = $conn->prepare($update_dinosaur_sql);
        $stmt->bind_param("s", $dinosaur_id);

        if ($stmt->execute()) {
            $update_request_sql = "UPDATE adoption_request SET Request_Status = 'Completed' WHERE Request_ID = ?";
            $stmt_request = $conn->prepare($update_request_sql);
            $stmt_request->bind_param("s", $request_id);
            if ($stmt_request->execute()) {
                echo "<p>Adoption request for the selected dinosaur has been accepted!</p>";
            } else {
                echo "<p>Error updating adoption request: " . $stmt_request->error . "</p>";
            }
            $stmt_request->close();
        } else {
            echo "<p>Error updating dinosaur information: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } elseif ($action === 'deny') {
        // Denies the dinosaur's adoption request: Sets the dino's "User_ID" and "Request_ID" to NULL and then changes its
        // "adoption_status" to "Available" again. Also deletes the created "adoption_request" entry.
        $update_dinosaur_sql = "UPDATE dinosaur SET User_ID = NULL, Request_ID = NULL, Adoption_Status = 'Available' WHERE Dinosaur_ID = ?";
        $stmt = $conn->prepare($update_dinosaur_sql);
        $stmt->bind_param("s", $dinosaur_id);

        if ($stmt->execute()) {
            $delete_request_sql = "DELETE FROM adoption_request WHERE Request_ID = ?";
            $stmt_request = $conn->prepare($delete_request_sql);
            $stmt_request->bind_param("s", $request_id);
            if ($stmt_request->execute()) {
                echo "<p>Adoption request for the selected dinosaur has been denied. The dinosaur is now available for adoption again.</p>";
            } else {
                echo "<p>Error deleting adoption request: " . $stmt_request->error . "</p>";
            }
            $stmt_request->close();
        } else {
            echo "<p>Error updating dinosaur information: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}

// Takes inputted dinosaur information from "New Dinosaur Entry Form" and assigns its information to a new entry in the "dinosaur" table.
if (!isset($_GET["form_submitted"])) {
    echo "";
} else {
    if (!empty($_GET["name"]) && !empty($_GET["dinosaur_id"]) && !empty($_GET["species"]) && !empty($_GET["age"]) && !empty($_GET["gender"]) && !empty($_GET["size"]) && !empty($_GET["price"]) && !empty($_GET["adoption_status"]) && !empty($_GET["shelter_id"])) {
        $dinoName = $_GET["name"];
        $dinoID = $_GET["dinosaur_id"];
        $dinoSpecies = $_GET["species"];
        $dinoAge = $_GET["age"];
        $dinoGender = $_GET["gender"];
        $dinoSize = $_GET["size"];
        $dinoPrice = $_GET["price"];
        $dinoStatus = $_GET["adoption_status"];
        $dinoShelter = $_GET["shelter_id"];
        $dinoRequest = ($_GET["request_id"] === "NULL" || empty($_GET["request_id"])) ? NULL : $_GET["request_id"];
        $dinoUser = ($_GET["user_id"] === "NULL" || empty($_GET["user_id"])) ? NULL : $_GET["user_id"];

        $sqlstatement = $conn->prepare("INSERT INTO dinosaur VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $sqlstatement->bind_param("sssssssssds", $dinoUser, $dinoRequest, $dinoShelter, $dinoID, $dinoName, $dinoSpecies, $dinoAge, $dinoGender, $dinoSize, $dinoPrice, $dinoStatus);
        $sqlstatement->execute();
        echo $sqlstatement->error;
        echo "Your dinosaur has been added into the database!";
        $sqlstatement->close();
    } else {
        echo "<b>Error: Please enter all valid dinosaur information to proceed!</b>";
    }
}

// Displays the Pending Dinosaurs Table for all dinosaurs that have an adoption request pending for them.
echo "<p><h2>Pending Dinosaurs</h2></p>";
echo "<p>Table that shows all pending applications for an employee to accept/deny.</p>";
$pending_sql = "SELECT * FROM dinosaur WHERE Adoption_Status = 'Pending'";
$pending_result = $conn->query($pending_sql);

if ($pending_result->num_rows > 0) {
    echo "<table>
            <tr>
                <th>User_ID</th>
                <th>Request_ID</th>
                <th>Shelter_ID</th>
                <th>Dinosaur_ID</th>
                <th>Name</th>
                <th>Species</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Size</th>
                <th>Price</th>
                <th>Adoption_Status</th>
                <th>Actions</th>
            </tr>";

    while ($row = $pending_result->fetch_assoc()) {
        // I don't know if "htmlspecialchars" is necessary, but when looking things up this seemed
        // to be the recommended way to populate tables!
        echo "<tr>
                <td>" . htmlspecialchars($row["User_ID"] ?? "None") . "</td>
                <td>" . htmlspecialchars($row["Request_ID"] ?? "None") . "</td>
                <td>" . htmlspecialchars($row["Shelter_ID"] ?? "None") . "</td>
                <td>" . htmlspecialchars($row["Dinosaur_ID"]) . "</td>
                <td>" . htmlspecialchars($row["Name"]) . "</td>
                <td>" . htmlspecialchars($row["Species"]) . "</td>
                <td>" . htmlspecialchars($row["Age"]) . "</td>
                <td>" . htmlspecialchars($row["Gender"]) . "</td>
                <td>" . htmlspecialchars($row["Size"]) . "</td>
                <td>" . htmlspecialchars($row["Price"]) . "</td>
                <td>" . htmlspecialchars($row["Adoption_Status"]) . "</td>
                <td>
                    <form action='newdinosaur.php' method='post' style='display:inline;'>
                        <input type='hidden' name='action' value='accept'>
                        <input type='hidden' name='dinosaur_id' value='" . htmlspecialchars($row["Dinosaur_ID"]) . "'>
                        <input type='hidden' name='request_id' value='" . htmlspecialchars($row["Request_ID"]) . "'>
                        <button type='submit'>Accept</button>
                    </form>
                    <form action='newdinosaur.php' method='post' style='display:inline;'>
                        <input type='hidden' name='action' value='deny'>
                        <input type='hidden' name='dinosaur_id' value='" . htmlspecialchars($row["Dinosaur_ID"]) . "'>
                        <input type='hidden' name='request_id' value='" . htmlspecialchars($row["Request_ID"]) . "'>
                        <button type='submit'>Deny</button>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No dinosaurs with a pending adoption request at the moment.</p>";
}

$conn->close();
?>

<!-- Back to Catalog Button -->
<p><a href="Prehistoric-Pals-Project.php"><button type="button">Back to Catalog</button></a></p>

</body>
</html>
