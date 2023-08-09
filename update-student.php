<?php
// connect to DB
require_once("configuration/dbinfo.php");

session_start();

$connectDB = mysqli_connect("localhost", "massa", "supersecret", "bcit");

if (!$connectDB) {
  die("Connection Error");
}

// initialize variables
$student = array();
$id = "";
$firstname = "";
$lastname = "";
$errors = array();
$updatedID = "";
$updatedFirstname = "";
$updatedLastname = "";

$messages = [];

// check if form has been submitted
if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['id'])) {
  // sanitize input data
  $id = mysqli_real_escape_string($connectDB, $_POST['id']);
  $firstname = mysqli_real_escape_string($connectDB, $_POST['firstname']);
  $lastname = mysqli_real_escape_string($connectDB, $_POST['lastname']);

  // validate input data
  if (empty($id)) {
    $errors[] = "Student ID is required";
  }
  if (empty($firstname)) {
    $errors[] = "First name is required";
  }
  if (empty($lastname)) {
    $errors[] = "Last name is required";
  }

  // if input data is valid, update the record
  if (empty($errors)) {
    $stmt = mysqli_prepare($connectDB, "UPDATE students SET id=?, firstname=?, lastname=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ssss", $id, $firstname, $lastname, $id);

    if (mysqli_stmt_execute($stmt)) {
      $messages[] = "Record updated successfully: Student $firstname $lastname";
      $_SESSION['messages'] = $messages;
      header("Location: index.php");
      exit();
    };
  } else {
    $errors[] = "Error updating student: " . mysqli_error($connectDB);
  }
} else {

  // get student data
  if (isset($_GET['id'])) {

    $id = mysqli_real_escape_string($connectDB, $_GET['id']);

    $sql = "SELECT id, firstname, lastname FROM students WHERE id = ?";
    $stmt = mysqli_prepare($connectDB, $sql);
    mysqli_stmt_bind_param($stmt, "s", $id);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
      $student = mysqli_fetch_assoc($result);
      $id = $student['id'];
      $firstname = $student['firstname'];
      $lastname = $student['lastname'];
    } else {
      $errors[] = "Student not found";
    }
  }
}


// close connection
mysqli_close($connectDB);
?>








<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update a Student</title>
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
      <section>
        <h2>Update a student...</h2>

        <div class="container-form">
          <form action="update-student.php" method="POST">
            <fieldset>
              <legend for="">Update a record</legend>
              <!-- 2nd -->
              <fieldset>
                <!-- 3rd -->
                <fieldset>
                  <legend for="">New data</legend>

                  <label for="firstname">First Name:</label>
                  <input type="text" id="firstname" name="firstname" value="<?php if (!is_null($student)) {
                                                                              $firstname = $updatedFirstname;
                                                                            }
                                                                            echo htmlspecialchars($student['firstname'] ?? ''); ?>"><br>

                  <label for="lastname">Last Name:</label>
                  <input type="text" id="lastname" name="lastname" value="<?php if (!is_null($student)) {
                                                                            $lastname = $updatedLastname;
                                                                          }
                                                                          echo htmlspecialchars($student['lastname'] ?? ''); ?>"><br>

                  <label for="id">Student number</label>
                  <input type="text" id="id" name="id" value="<?php if (!is_null($student)) {
                                                                $id = $updatedID;
                                                              }
                                                              echo htmlspecialchars($student['id'] ?? ''); ?>"><br>



                  <input type="submit" value="Update">

                </fieldset>
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