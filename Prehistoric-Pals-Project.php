<html>
<style>
table, th, td {
  border: 1px solid black;
}

<?php
//Define some constants in this PHP code block
$servername = 'localhost';
$username = 'root'; // Flashline username
$password = ''; // phpMyAdmin password
$dbname = 'example'; // Flashline username
?>

</style
<body>
<p><h2>Welcome to Prehistoric Pals!</h2></p>
<p>Client webpage to search the adoption center's catalog for dinosaurs to adopt!
<p>---------------------------------------------------------------------------------------------
<form action="Prehistoric-Pals-Project.php" method=get>
	Enter Dinosaur name: <input type=text size=20 name="name">
	<p>Enter Dinosaur ID: <input type=text size=5 name="id">
        <p> <input type=submit value="submit">
                <input type="hidden" name="form_submitted" value="1" >
</form>

<?php // .PHP code for searching for a studnet by name/ID.
if (!isset($_GET["form_submitted"]))
{
		echo "Hello! Please enter a Dinosaur Name or ID number to begin.";
}
else {
// Create connection

 $conn = new mysqli($servername, $username, $password, $dbname);
 // Check connection
 if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
 }
 if (!empty($_GET["name"]))
 {
   $profName = $_GET["name"]; //gets name from the form
   $sqlstatement = $conn->prepare("SELECT dinosaur_id, name, species, age, gender, size, price, adoption_status FROM dinosaur where name LIKE ?"); //prepare the statement
   $searchTerm = $profName . "%";
   $sqlstatement->bind_param("s", $searchTerm); //insert the String variable into the ? in the above statement
   $sqlstatement->execute(); //execute the query
   $result = $sqlstatement->get_result(); //return the results
   $sqlstatement->close();
 }
 elseif (!empty($_GET["id"]))
 {
   $profID = $_GET["id"]; //gets name from the form
   $sqlstatement = $conn->prepare("SELECT dinosaur_id, name, species, age, gender, size, price, adoption_status FROM dinosaur where dinosaur_id LIKE ?"); //prepare the statement
   $searchTerm = $profID . "%";
   $sqlstatement->bind_param("s", $searchTerm); //insert the integer variable into the ? in the above statement
   $sqlstatement->execute(); //execute the query
   $result = $sqlstatement->get_result(); //return the results
   $sqlstatement->close();
 }
 else {
	 echo "<b>Please enter a name or an ID number!</b>";
 }
   if ($result->num_rows > 0) {
     	// Setup the table and headers
	echo "<table><tr><th>Dinosaur_ID</th><th>Name</th><th>Species</th><th>Age</th><th>Gender</th><th>Size</th><th>Price</th><th>Adoption_Status</th></tr>";
	// output data of each row into a table row
	 while($row = $result->fetch_assoc()) {
		 echo "<tr><td>".$row["dinosaur_id"]."</td><td>".$row["name"]."</td><td> ".$row["species"]."</td><td>".$row["age"]."</td><td>".$row["gender"]."</td><td>".$row["size"]."</td><td>".$row["price"]."</td><td>".$row["adoption_status"]."</td></tr>";
   	}
	
	echo "</table>"; // close the table
	echo "There are ". $result->num_rows . " dinosaurs that match your search.";
	// Don't render the table if no results found
   	} else {
               echo "0 results found! Please enter a valid Name or ID!";
	} 
   $conn->close();
 } //end else condition where form is submitted
  ?> <!-- this is the end of our php code -->

<p>---------------------------------------------------------------------------------------------
<p>Thank you for choosing Prehistoric Pals as your adoption specialists! We hope you found what you were looking for!
</body>
</html>
