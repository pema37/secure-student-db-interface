<?php

// connect to DB
require_once("configuration/dbinfo.php");

session_start();

$connectDB = mysqli_connect("localhost", "massa", "supersecret", "bcit");

if (!$connectDB) {
  die("Connection Error");
}

$messages = [];

// if form submitted
if (isset($_POST['submit'])) {

  // validate student number
  if (empty($_POST['studentnumber'])) {
    $messages[] = "A student id is required <br />";
  } else {
    $studentnumber = mysqli_real_escape_string($connectDB, $_POST['studentnumber']);

    // validate student number pattern
    if (!preg_match("/^A\d{8}$/", $studentnumber)) {
      echo "Invalid student number format <br />";
      $studentnumber = "";
    }
  }

  // check first name
  if (empty($_POST['firstname'])) {
    echo "A student first name is required <br />";
  } else {
    $firstname = mysqli_real_escape_string($connectDB, $_POST['firstname']);
  }

  // check last name
  if (empty($_POST['lastname'])) {
    echo "A student last name is required <br />";
  } else {
    $lastname = mysqli_real_escape_string($connectDB, $_POST['lastname']);
  }

  // if all required fields are filled and student number is valid
  if (!empty($studentnumber) && !empty($firstname) && !empty($lastname)) {

    // SQL query to insert data into the database
    $sql = "INSERT INTO students (id, firstname, lastname) VALUES ('$studentnumber', '$firstname', '$lastname')";

    // execute the query
    if (mysqli_query($connectDB, $sql)) {
      $messages[] = "New record created successfully: Student $firstname $lastname";
      $_SESSION['messages'] = $messages;
      header("Location: index.php");
    } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($connectDB);
    }

    // close the database connection
    mysqli_close($connectDB);
  }
}
// this is the end of the form

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add a Student</title>
  <!-- <link rel="stylesheet" href="dist/output.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"> -->
  <link rel="stylesheet" href="dist/output.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" integrity="sha512-Q75EhKvVWTIg+RGkt9Qom0JjBfOwFbPZ/lA+qO3qNkkJH5Q5dp5N5+WAbA40EidJtdzqr/5klSZOcwzrKq3GKA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

</head>

<body>

  <div id="wrapper">
    <header>
      <h1>Administering DB From a Form</h1>
    </header>

    <main>
      <section class="addstudent-section">
        <h2>Add a student...</h2>
        <div class="containerphp">

          <form action="add-student.php" method="POST">
            <fieldset>
              <legend for="">Add a record</legend>

              <fieldset>
                <div>
                  <label for="studentnumber">Student number</label>
                  <input type="text" id="studentnumber" name="studentnumber">
                </div>

                <div>
                  <label for="firstname">First name</label>
                  <input type="text" id="firstname" name="firstname">
                </div>

                <div>
                  <label for="lastname">Last name</label>
                  <input type="text" id="lastname" name="lastname">
                </div>

                <input type="submit" value="submit" name="submit">
              </fieldset>

            </fieldset>
          </form>

        </div>
      </section>
    </main>

    <footer></footer>
  </div>

</body>

</html>