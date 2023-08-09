<?php

// connect to database
require_once("configuration/dbinfo.php");

session_start();

$connectDB = mysqli_connect("localhost", "massa", "supersecret", "bcit");
// $connectDB = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$connectDB) {
  die("Connection Error");
}

$student = null; // initialize the variable

$messages = [];

// check GET request id param
if (isset($_GET['id'])) {

  // GET id param
  $id = mysqli_real_escape_string($connectDB, $_GET['id']);

  // MAKE sql
  $sql = "SELECT * FROM students WHERE id = '$id'";

  // GET THE QUERY RESULT
  $result = mysqli_query($connectDB, $sql);
  if ($result) {
    // fetch result in array format
    $student = mysqli_fetch_assoc($result);

    // free result from memory
    // mysqli_free_result($result);
  } else {
    echo "Error: " . mysqli_error($connectDB);
  }

  // close connection
}


// check if form was submitted
if (isset($_POST['submit'])) {

  // Check if 'yes' radio button is selected
  if (isset($_POST['choice']) && $_POST['choice'] == 'yes') {

    // get id param from POST request
    $id = mysqli_real_escape_string($connectDB, $_POST['id']);

    // make sql
    $sql = "DELETE FROM students WHERE id = '$id'";

    // execute query
    if (mysqli_query($connectDB, $sql)) {
      $messages[] = "Record deleted successfully: Student $firstname $lastname";
      $_SESSION['messages'] = $messages;
      header("Location: index.php");
      exit();
    } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($connectDB);
    }
  } else {
    // Here you can handle what happens when 'no' is selected
    header("Location: index.php");
    exit();
  }
}


mysqli_close($connectDB);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete a Student</title>
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
        <h2>Delete a student...</h2>

        <form method="post" action="">
          <fieldset>
            <legend>Delete a record - Are you sure?</legend>

            <div class="php">
              <div class="container">
                <p>Student number, first name, last name:</p>

                <?php if (isset($student)) : ?>
                  <p><?php echo htmlspecialchars($student['id']) . ", " . htmlspecialchars($student['firstname']) . " " . htmlspecialchars($student['lastname']); ?></p>
                  <input type="hidden" name="id" value="<?php echo htmlspecialchars($student['id']); ?>">
                <?php endif; ?>

              </div>
            </div>

            <fieldset>
              <p>Do you want to delete this student?</p>
              <div>
                <label for="yes">Yes</label>
                <input type="radio" name="choice" value="yes" id="yes">
              </div>
              <div>
                <label for="no">No</label>
                <input type="radio" name="choice" value="no" id="no">
              </div>
              <input type="submit" name="submit" value="Submit">
            </fieldset>

          </fieldset>
        </form>

      </section>
    </main>

    <footer></footer>
  </div>

</body>

</html>


