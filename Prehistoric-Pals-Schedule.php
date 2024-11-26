<html>
<head>
    <title>Instructor Schedule</title>
    <style>
        table, th, td {
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <h2>Prehistoric Pals Schedule</h2>

    <?php
    // Define some constants in this PHP code block
    $servername = 'localhost';
    $username = 'root'; // Flashline username
    $password = ''; // phpMyAdmin password
    $dbname = 'example'; // Flashline username

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the instructor ID from the URL
    $instructor_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Retrieve all courses taken by the instructor
    $sql = "SELECT student.id, student.name, takes.course_id, takes.semester, takes.year 
            FROM student
            INNER JOIN takes ON student.id = takes.id 
            WHERE student.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $instructor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Gather unique years for the dropdown menu
        $years = [];
        while ($row = $result->fetch_assoc()) {
            $years[] = $row['year'];
            $data[] = $row;
        }
        $years = array_unique($years);
        sort($years);

        // Display dropdown menu for filtering
        echo "<form method='GET' action='student-schedule.php'>
                <label for='filter_year'>Filter by Year:</label>
                <select name='filter_year' id='filter_year' onchange='this.form.submit()'>
                    <option value=''>All Years</option>";
        foreach ($years as $year) {
            $selected = (isset($_GET['filter_year']) && $_GET['filter_year'] == $year) ? "selected" : "";
            echo "<option value='$year' $selected>$year</option>";
        }
        echo "</select>
              <input type='hidden' name='id' value='$instructor_id'>
              </form>";

        // Apply year filter if selected
        $filtered_year = isset($_GET['filter_year']) ? intval($_GET['filter_year']) : null;

        // Display the table with results
        echo "<table>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Course ID</th>
                    <th>Semester</th>
                    <th>Year</th>
                </tr>";
        foreach ($data as $row) {
            if (!$filtered_year || $row['year'] == $filtered_year) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['course_id']}</td>
                        <td>{$row['semester']}</td>
                        <td>{$row['year']}</td>
                      </tr>";
            }
        }
        echo "</table>";
    } else {
        echo "<p>No courses found for this student.</p>";
    }

    $stmt->close();
    $conn->close();
    ?>

    <p><a href="test-student-search.php">Back to Student Search</a></p>
</body>
</html>
