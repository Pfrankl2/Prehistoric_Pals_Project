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



<?php
// Create connection
 $conn = new mysqli($servername, $username, $password, $dbname);
 // Check connection
 if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
 }
?>



</style
<body>
<p><h2>New Dinosaur Entry Form</h2></p>
<p>Employee form to enter a new dinosaur into the database.
<p>----------------------------------------------------------------------
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
    <p>----------------------------------------------------------------------
	<p> <input type=submit value="submit">
                <input type="hidden" name="form_submitted" value="1" >
</form>



<?php //starting php code again!
if (!isset($_GET["form_submitted"]))
{
		echo "Hello! Please enter new dinosaur information and submit the form.";
}
else {
  if (!empty($_GET["name"]) && !empty($_GET["dinosaur_id"]) && !empty($_GET["species"]) && !empty($_GET["age"]) && !empty($_GET["gender"]) && !empty($_GET["size"]) && !empty($_GET["price"]) && !empty($_GET["adoption_status"]) && !empty($_GET["shelter_id"]))
{
   $dinoName = $_GET["name"]; //gets name from the form
   $dinoID = $_GET["dinosaur_id"]; //gets id from the form
   $dinoSpecies = $_GET["species"]; //get department from the form
   $dinoAge = $_GET["age"]; //get salary from the form
   $dinoGender = $_GET["gender"]; //get salary from the form
   $dinoSize = $_GET["size"]; //get salary from the form
   $dinoPrice = $_GET["price"]; //get salary from the form
   $dinoStatus = $_GET["adoption_status"]; //get salary from the form
   $dinoShelter = $_GET["shelter_id"]; //get salary from the form
   $dinoRequest = ($_GET["request_id"] === "NULL" || empty($_GET["request_id"])) ? NULL : $_GET["request_id"];
   $dinoUser = ($_GET["user_id"] === "NULL" || empty($_GET["user_id"])) ? NULL : $_GET["user_id"];
   $sqlstatement = $conn->prepare("INSERT INTO dinosaur values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"); //prepare the statement
   $sqlstatement->bind_param("sssssssssds",$dinoUser,$dinoRequest,$dinoShelter,$dinoID,$dinoName,$dinoSpecies,$dinoAge,$dinoGender,$dinoSize,$dinoPrice,$dinoStatus); //insert the variables into the ? in the above statement
   $sqlstatement->execute(); //execute the query
   echo $sqlstatement->error; //print an error if the query fails
   echo "Your dinosaur has been added into the database!";
   $sqlstatement->close();
 }
 else {
	 echo "<b> Error: Please enter all valid dinosuar information to proceed!</b>";
 }
   $conn->close();
 } //end else condition where form is submitted
  ?> <!-- this is the end of our php code -->
</body>
</html>
