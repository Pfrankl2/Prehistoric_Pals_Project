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

</style
<body>
<p><h2>Welcome to Prehistoric Pals!</h2></p>
<p>Client webpage to search the adoption center's catalog for dinosaurs to adopt.
<p>---------------------------------------------------------------------------------------------

<p><h2>Create A New Account</h2></p>
<p>If you are not yet a member of the Prehistoric Pals Pack (PPP), then please create a new account
to adopt one of our dinosaurs!
<form action="Prehistoric-Pals-Project.php" method=get>
    Enter New User ID (5 Numbers): <input type=text size=5 name="user_id" required>
    <p>Enter Your Full Name: <input type=text size=20 name="name" required>
    <p>Enter Your Email Address: <input type=text size=30 name="email" required>
    <p>Enter Your State of Residency: <input type=text size=20 name="state_of_residency" required>
    <p><input type=submit value="Create Account">
    <input type="hidden" name="new_client_form" value="1">
</form>

<?php
// Handles adding a new client into the "client" table after taking user input.
if (isset($_GET["new_client_form"])) {
    if (!empty($_GET["user_id"]) && !empty($_GET["name"]) && !empty($_GET["email"]) && !empty($_GET["state_of_residency"])) {
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Assigns inputted information from the form to varibles.
        $userID = $_GET["user_id"];
        $clientName = $_GET["name"];
        $email = $_GET["email"];
        $state = $_GET["state_of_residency"];

        // Prepares SQL statement using the created variables to insert new "client" entry.
        $sql = $conn->prepare("INSERT INTO client (User_ID, name, email, State_Of_Residency) VALUES (?, ?, ?, ?)");
        $sql->bind_param("ssss", $userID, $clientName, $email, $state);

        // Executes the prepared SQL statement and tells the user if their input submitted successfully or not.
        if ($sql->execute()) {
            echo "<p>New client account created successfully! Welcome, " . htmlspecialchars($clientName) . "!</p>";
        } else {
            echo "<p>Error: " . $sql->error . "</p>";
        }

        $sql->close();
        $conn->close();
    } else {
        echo "<p><b>Error: Please fill in all fields to create a new account.</b></p>";
    }
}
?>

<p>---------------------------------------------------------------------------------------------
<form action="Prehistoric-Pals-Project.php" method=get>
  <p><h2>Dino-Search</h2>
  <p>Please enter the Name/ID of a dinosaur from our catalog to view their information!</p>
	Enter Dinosaur name: <input type=text size=20 name="name">
	<p>Enter Dinosaur ID: <input type=text size=5 name="id">
        <p> <input type=submit value="submit">
                <input type="hidden" name="form_submitted" value="1" >
</form>

<?php
// Searches the "dinosaur" table for any dinosaurs matching the inputted "name" or "Dinosaur_ID".
if (!isset($_GET["form_submitted"]))
{
		echo "";
}
else {
 $conn = new mysqli($servername, $username, $password, $dbname);
 if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
 }

 if (!empty($_GET["name"]))
 {
   // Takes the inputted "name" from the form to prepare and execute an SQL query to find the dinosaur.
   $dinoName = $_GET["name"];
   $sqlstatement = $conn->prepare("SELECT dinosaur_id, name, species, age, gender, size, price, adoption_status FROM dinosaur where name LIKE ?");
   $searchTerm = $dinoName . "%";
   $sqlstatement->bind_param("s", $searchTerm);
   $sqlstatement->execute();
   $result = $sqlstatement->get_result();
   $sqlstatement->close();
 }
 elseif (!empty($_GET["id"]))
 {
   // Takes the inputted "Dinosaur_ID" from the form to prepare and execute an SQL query to find the dinosaur.
   $dinoID = $_GET["id"];
   $sqlstatement = $conn->prepare("SELECT dinosaur_id, name, species, age, gender, size, price, adoption_status FROM dinosaur where dinosaur_id LIKE ?");
   $searchTerm = $dinoID . "%";
   $sqlstatement->bind_param("s", $searchTerm);
   $sqlstatement->execute();
   $result = $sqlstatement->get_result();
   $sqlstatement->close();
 }
 else {
	 echo "<b>Please enter a name or an ID number!</b>";
 }
 if ($result->num_rows > 0) {
 // Setups up the table for showing searched dinosaur information.
 echo "<table><tr><th>Dinosaur_ID</th><th>Name</th><th>Species</th><th>Age</th><th>Gender</th><th>Size</th><th>Price</th><th>Adoption_Status</th></tr>";

 // Outputs the dinosaur information into each row of the table.
 //
 // I don't know if "htmlspecialchars" is necessary, but when looking things up this seemed
 // to be the recommended way to populate tables!
 while ($row = $result->fetch_assoc()) {
  echo "<tr>
            <td>" . htmlspecialchars($row["dinosaur_id"]) . "</td>
            <td>" . htmlspecialchars($row["name"]) . "</td>
            <td>" . htmlspecialchars($row["species"]) . "</td>
            <td>" . htmlspecialchars($row["age"]) . "</td>
            <td>" . htmlspecialchars($row["gender"]) . "</td>
            <td>" . htmlspecialchars($row["size"]) . "</td>
            <td>" . htmlspecialchars($row["price"]) . "</td>
            <td>" . htmlspecialchars($row["adoption_status"]) . "</td>
            <td><a href='adopt-dinosaur.php?dinosaur_id=" . urlencode($row["dinosaur_id"]) . "'>Interested in Adopting?</a></td>
        </tr>";
}
 echo "</table>";
 echo "There are ". $result->num_rows . " dinosaurs that match your search.";
 } else {
               echo "0 results found! Please enter a valid Name or ID!";
 } 

   $conn->close();
 }
  ?>

<!-- Dinosaur Catalog Section -->
<p>---------------------------------------------------------------------------------------------</p>
<p><h2>Dinosaur Catalog</h2></p>
<p>Below is a list of all dinosaurs currently housed in the trusted shelters of Prehistoric Pals!</p>

<?php
// Displays every dinosaur in the "dinosaur" table that has an "adoption_status" of either 'Available' or 'Pending'.
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// SQL Query that selects ONLY dinosaurs that do not have an "adoption_status" of 'Adopted'.
$sql = "SELECT * FROM dinosaur WHERE adoption_status != 'Adopted'";
$result = $conn->query($sql);

// Displays that table for any unadopted dinosaurs.
if ($result->num_rows > 0) {
    echo "<table><tr><th>User_ID</th><th>Request_ID</th><th>Shelter_ID</th><th>Dinosaur_ID</th><th>Name</th><th>Species</th><th>Age</th><th>Gender</th><th>Size</th><th>Price</th><th>Adoption_Status</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                  <td>" . htmlspecialchars($row["User_ID"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Request_ID"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Shelter_ID"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Dinosaur_ID"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Name"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Species"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Age"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Gender"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Size"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Price"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Adoption_Status"] ?? "None") . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No available dinosaurs in the catalog!</p>";
}

// SQL Query that selects all dinosaurs with an "adoption_status" of 'Adopted' to display them separately.
$sqlAdopted = "SELECT * FROM dinosaur WHERE adoption_status = 'Adopted'";
$resultAdopted = $conn->query($sqlAdopted);

// Displays the table for any adopted dinosaurs.
if ($resultAdopted->num_rows > 0) {
    echo "<br>";
    echo "---------------------------------------------------------------------------------------------";
    echo "<p><h2>Adopted Dinosaurs</h2></p>";
    echo "<p>Below are the dinosaurs that have successfully found homes through Prehistoric Pals in the past!</p>";
    echo "<table><tr><th>User_ID</th><th>Request_ID</th><th>Shelter_ID</th><th>Dinosaur_ID</th><th>Name</th><th>Species</th><th>Age</th><th>Gender</th><th>Size</th><th>Price</th><th>Adoption_Status</th></tr>";
    while ($row = $resultAdopted->fetch_assoc()) {
        echo "<tr>
                  <td>" . htmlspecialchars($row["User_ID"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Request_ID"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Shelter_ID"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Dinosaur_ID"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Name"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Species"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Age"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Gender"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Size"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Price"] ?? "None") . "</td>
                  <td>" . htmlspecialchars($row["Adoption_Status"] ?? "None") . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No adopted dinosaurs found!</p>";
}

$conn->close();
?>
<p>---------------------------------------------------------------------------------------------</p>
<p>Thank you for choosing Prehistoric Pals as your adoption specialists! We hope you found what you were looking for!</p>

<!-- Employee Mode Button -->
<p><a href="newdinosaur.php"><button type="button">Employee Mode</button></a></p>

</body>
</html>
