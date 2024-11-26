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
<p><h2>Prehistoric Pals:</h2></p>
<form action="test-student-search.php" method=get>
	Enter Student name: <input type=text size=20 name="name">
	<p>Enter Student ID number: <input type=text size=5 name="id">
        <p> <input type=submit value="submit">
                <input type="hidden" name="form_submitted" value="1" >
</form>


<?php // .PHP code for searching for a studnet by name/ID.
if (!isset($_GET["form_submitted"]))
{
		echo "Hello. Please enter a student name or ID number and submit the form.";
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
   $sqlstatement = $conn->prepare("SELECT id, name, dept_name, tot_cred FROM student where name LIKE ?"); //prepare the statement
   $searchTerm = $profName . "%";
   $sqlstatement->bind_param("s", $searchTerm); //insert the String variable into the ? in the above statement
   $sqlstatement->execute(); //execute the query
   $result = $sqlstatement->get_result(); //return the results
   $sqlstatement->close();
 }
 elseif (!empty($_GET["id"]))
 {
   $profID = $_GET["id"]; //gets name from the form
   $sqlstatement = $conn->prepare("SELECT id, name, dept_name, tot_cred FROM student where id LIKE ?"); //prepare the statement
   $searchTerm = $profID . "%";
   $sqlstatement->bind_param("s", $searchTerm); //insert the integer variable into the ? in the above statement
   $sqlstatement->execute(); //execute the query
   $result = $sqlstatement->get_result(); //return the results
   $sqlstatement->close();
 }
 else {
	 echo "<b>Please enter a name or an ID number</b>";
 }
   if ($result->num_rows > 0) {
     	// Setup the table and headers
	echo "<table><tr><th>ID</th><th>Name</th><th>Department</th><th>tot_cred</th><th>Schedule</th></tr>";
	// output data of each row into a table row
	 while($row = $result->fetch_assoc()) {
		 echo "<tr><td>".$row["id"]."</td><td>".$row["name"]."</td><td> ".$row["dept_name"]."</td><td>".$row["tot_cred"]."</td><td><a href='student-schedule.php?id=".$row["id"]."' target='_blank'>View Schedule</a></td></tr>";
   	}
	
	echo "</table>"; // close the table
	echo "There are ". $result->num_rows . " results.";
	// Don't render the table if no results found
   	} else {
               echo "0 results found!";
	} 
   $conn->close();
 } //end else condition where form is submitted
  ?> <!-- this is the end of our php code -->


<?php // .PHP code to get all dept. names from the "department" table for dropdown menu.
// Create connection
 $conn = new mysqli($servername, $username, $password, $dbname);
 // Check connection
 if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
 }
//run a query to get all department names  
$sqlstatement = $conn->prepare("SELECT distinct dept_name FROM department order by dept_name asc"); //prepare the statement
$sqlstatement->execute(); //execute the query
$departments = $sqlstatement->get_result(); //return the results we'll use them in the web form
$sqlstatement->close();
?>


</style
<body>
<p><h2>New Student Entry Form:</h2></p>
<form action="test-student-search.php" method=get>
	Enter Student name: <input type=text size=20 name="name">
	<p>Enter Student ID number: <input type=text size=5 name="id">
	<p>Select Student Department: 
	<select name="department">
        <?php //iterate through the results of the department query to build the web form
	while($department = $departments->fetch_assoc()) {
	?>
		<option value="<?php echo $department["dept_name"]; ?>"><?php echo $department["dept_name"]; ?>
		</option>
	<?php } //end while loop ?>
	</select>
    <p><label for="credits">Initial Total Credits (Leave 0.0 if not a Transfer Student):</label>
    <input type="number" name="credits" value="0.0" required>
    <p> <input type=submit value="submit">
                <input type="hidden" name="form_submitted" value="1" >
</form>


<?php // .PHP code to implement adding a new student.
if (!isset($_GET["form_submitted"]))
{
		echo "Hello. Please enter new student information and submit the form.";
}
else {
  if (!empty($_GET["name"]) && !empty($_GET["id"]) && !empty($_GET["credits"]))
{
   $profName = $_GET["name"]; //gets name from the form
   $profID = $_GET["id"]; //gets id from the form
   $profDept = $_GET["department"]; //get department from the form
   $profCredits = $_GET["credits"]; //get salary from the form
   $sqlstatement = $conn->prepare("INSERT INTO student values(?, ?, ?, ?)"); //prepare the statement
   $sqlstatement->bind_param("sssd",$profID,$profName,$profDept,$profCredits); //insert the variables into the ? in the above statement
   $sqlstatement->execute(); //execute the query
   echo $sqlstatement->error; //print an error if the query fails
   $sqlstatement->close();
 }
 else {
	 echo "<b> Error: Please enter a name, an ID number and a total number of credits to proceed.</b>";
 }
   $conn->close();
 } //end else condition where form is submitted
  ?> <!-- this is the end of our php code -->


<p> Thanks for using the directory search! 
</body>
</html>
