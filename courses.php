<?php
include_once ".env.php";

$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DATABASE);
if(!$con)
    exit("<p>Connection Error: " . mysqli_connect_error() . "</p>");

echo "
<html lang=\"US English\">

<head>
    <title>
        Courses A5
    </title>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"styles/main.css\">
</head>

<body>
    <div>
        <p>
        <h1>List of Courses Taken</h1><br>
        <a href=\"index.php\">Back to index</a>
        </p>
        <hr>
    </div>
    <div id=\"Courses taken\">
        <table>
            <tr>
                <th>
                    <h1>Courses Taken</h1>
                </th>
                <th>
                    <h1>Add New Course</h1>
                </th>
            </tr>
            <tr>
                <td>
                    <table>
                        <tr>
                            <!--Table headers-->
                            <th>
                                <h3>Course Name</h3>
                            </th>
                            <th>
                                <h3>Course Number</h3>
                            </th>
                            <th>
                                <h3>Description</h3>
                            </th>
                            <td>
                                <h3>Final Grade</h3>
                            </td>
                        </tr>";
//Insert into DB
if (is_array($_POST) && !empty($_POST))
{
    //echo "post works<br>";
    $inStmt = mysqli_stmt_init($con);
    if (!$inStmt)
        exit("<p>Failed to initialize statement " . mysqli_stmt_error($inStmt) . "</p>");

    $inQuery = "INSERT INTO `Courses` (`id`, `course name`, `course number`, 
    `description`, `final grade`, `enrolled`) VALUES (NULL, ?,?,?,?,?)";

    if (!mysqli_stmt_prepare($inStmt, $inQuery))
        exit("<p>Failed to prepare statement: " . mysqli_stmt_error($inQuery) . "</p>");
    
    mysqli_stmt_bind_param($inStmt, "sissi", $courseName, $courseNum, $courseDesc,
    $courseFinalG, $courseEnr);
    
    //Sanitize input
    $error = 0;

    //Ensure Course name isn't blank
    if(strlen($_POST['courseName']) > 0)
        $courseName = $_POST['courseName'];
    else
    {
        echo "<p class=\"error\">Error! Course name cannot be blank.
            Please try again!</p>";
        $error = 1;
    }

    //Make sure Course num is a number and between 0-65535
    if (is_numeric($_POST['courseNum'])) //is number
    {
        if ($_POST['courseNum'] >= 0 && $_POST['courseNum'] <= 65535)
            $courseNum = $_POST['courseNum'];
        else //out of range
        {
            echo "<p class=\"error\">Error! course number must be between 0 and 65535.
            Please try again!</p>";
            $error = 1;
        }
    }
    else //not number
    {
        echo "<p class=\"error\">Error! Course num must be an integer! Please try again.";
        $error = 1;
    }

    //make sure description is less than 256 characters
    if(strlen($_POST['Description']) < 256)
        $courseDesc = $_POST['Description'];
    else
        $courseDesc = substr($_POST['Description'],0,255);

    //Ensure final grade entered is one of the accepted letter grades or X,
    //followed by an optional + or -, or a number up to 3 digits
    if(preg_match("/^[aAbBcCdDfFxX][+-]?$/",$_POST['finalGrade']) || 
    (preg_match("/^\d{1,3}$/",$_POST['finalGrade'])))
        $courseFinalG = strtoupper($_POST['finalGrade']);
    else if(strlen($_POST['finalGrade']) == 0)
        $courseFinalG = $_POST['finalGrade'];
    else
    {
        $error = 1;
        echo "<p class=\"error\">Error! Final grade must be a valid letter grade,
        followed by an optional + or -, a number up to 3 digits, or blank.
        Please try again!</p>";
    }

    if (isset($_POST['currentlyEnrolled']))
        $courseEnr = 1;
    else
        $courseEnr = 0;

    //If input is invalid, exit the program and prompt to try again.
    if ($error)
    {
        echo "<p class = \"error\">Failed to insert into database</p>";
        echo "<a href=\"courses.php\">Return to courses page</a>";
        exit("");
    }
    
    if (!mysqli_stmt_execute($inStmt))
    {
        echo"An error occured! ";
        echo "<a href=\"courses.php\">Return to courses page</a>";
        exit("<p>Failed to execute statement: ". mysqli_stmt_error($inStmt)."</p>");
    }
    
    mysqli_stmt_close($inStmt);
}

//Delete entry from DB
if(isset($_GET['del']))
{
    $delStmt = mysqli_stmt_init($con);
    if (!$delStmt)
        exit("<p>Failed to initialize statement " . mysqli_stmt_error($delStmt) . "</p>");

    $delQuery = "DELETE FROM `Courses` WHERE `Courses`.`id` = $_GET[del]";
    if (!mysqli_stmt_prepare($delStmt, $delQuery))
        exit("<p>Failed to prepare statement: " . mysqli_stmt_error($delStmt) . "</p>");
    
    if (!mysqli_stmt_execute($delStmt))
        exit("<p>Failed to execute statement". mysqli_stmt_error($delStmt)."</p>");
    
    mysqli_stmt_close($delStmt);
}

//Get all current entries in DB
// initialize the statement
$stmt = mysqli_stmt_init($con);
if (!$stmt)
    exit("<p>Failed to initialize statement " . mysqli_stmt_error($stmt) . "</p>");

// prepare the statement
$query = "SELECT * FROM Courses";

if (!mysqli_stmt_prepare($stmt, $query))
    exit("<p>Failed to prepare statement: " . mysqli_stmt_error($stmt) . "</p>");

// execute a SINGLE query
if (!mysqli_stmt_execute($stmt))
    exit("<p>Failed to execute statement</p>");

mysqli_stmt_bind_result($stmt, $id, $cName, $cNum, $description, $finalGrade, $enrolled);
while(mysqli_stmt_fetch($stmt))
{
    if ($enrolled)
        echo "<tr class=\"enrolled\">";
    else
        echo "<tr class=\"notEnrolled\">";
    echo "
            <td>$cName</td>
            <td>$cNum</td>
            <td>$description</td>";
    echo "<td>$finalGrade</td>";
    echo "<td><a href=\"courses.php?del=$id\">Delete Course</a></td></tr>";
}
mysqli_stmt_close($stmt);

echo"
                    </table>
                </td>
                <td>
                    <!--Forms-->
                    <form action=\"courses.php\" method=\"post\">
                        <div>
                            <table>
                                <tr>
                                    <td><label for=\"courseName\">Course name</label>
                                        <input type=\"Text\" id=\"courseName\" name=\"courseName\">
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for=\"courseNum\">Course number</label>
                                        <input type=\"Text\" id=\"courseNum\" name=\"courseNum\">
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for=\"Description\">Description</label>
                                        <input type=\"Text\" id=\"Description\" name=\"Description\">
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for=\"finalGrade\">Final grade</label>
                                        <input type=\"Text\" id=\"finalGrade\" name=\"finalGrade\">
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for=\"currentlyEnrolled\">Currently Enrolled?</label>
                                        <input type=\"checkbox\" id=\"currentlyEnrolled\" name=\"currentlyEnrolled\">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type=\"submit\" value=\"Submit\" >
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </form>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>";
mysqli_close($con);