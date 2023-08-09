<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Database Administration Functionality</title>
  <link rel="stylesheet" href="dist/output.css" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" integrity="sha512-Q75EhKvVWTIg+RGkt9Qom0JjBfOwFbPZ/lA+qO3qNkkJH5Q5dp5N5+WAbA40EidJtdzqr/5klSZOcwzrKq3GKA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100 font-poppins mx-auto my-8">

  <div id="wrapper" class="container mx-auto px-4">
    <header class="text-center py-4 bg-gray-800 text-white">
      <h1 class="text-3xl font-bold">Administering DB From a Form</h1>
    </header>

    <main class="mx-auto px-4 sm:w-3/4 md:w-2/3 lg:w-1/2 xl:w-2/5">
      <?php
      session_start();
      if (isset($_SESSION['messages'])) {
        foreach ($_SESSION['messages'] as $message) {
          echo $message;
        }
        unset($_SESSION['messages']);
      }
      ?>
      <h2 class="text-2xl font-bold mb-4">Students</h2>
      <a href="add-student.php" class="add-student-btn">Add a Student</a>

      <div class="table-responsive">
        <div class="container mx-auto">
          <?php
          // connect to DB
          require_once("configuration/dbinfo.php");
          $connectDB = mysqli_connect("localhost", "massa", "supersecret", "bcit");

          if (!$connectDB) {
            die("Connection Error");
          }

          // write query for all students
          $sql = "SELECT id, firstname, lastname FROM students";

          // make query & get result
          $result = mysqli_query($connectDB, $sql);

          // display DB
          if (mysqli_num_rows($result) > 0) {
            echo '<div class="overflow-x-auto">
              <table class="mx-auto w-full whitespace-no-wrap rounded-lg shadow-lg">
                <thead class="text-white">
                  <tr class="bg-gray-800 rounded-lg">
                    <th class="py-2 pl-2 pr-1">#</th>
                    <th class="px-4 py-2">First Name</th>
                    <th class="px-4 py-2">Last Name</th>
                    <th class="px-4 py-2"></th>
                    <th class="px-4 py-2"></th>
                  </tr>
                </thead>
                <tbody class="bg-gray-200">';
            while ($row = mysqli_fetch_assoc($result)) {
              echo '<tr class="hover:bg-gray-100 border-b border-gray-200 py-4">
                      <td class="pl-2 pr-1">' . $row['id'] . '</td>
                      <td class="px-4 py-2">' . $row['firstname'] . '</td>
                      <td class="px-4 py-2">' . $row['lastname'] . '</td>
                      <td class="px-4 py-2"><a href="delete-student.php?id=' . $row['id'] . '" class="text-red-500 hover:text-red-700">Delete</a></td>
                      <td class="px-4 py-2"><a href="update-student.php?id=' . $row['id'] . '" class="text-blue-500 hover:text-blue-700">Update</a></td>
                    </tr>';
            }
            echo '</tbody>
                </table>
              </div>';
          }

          // free result from memory
          mysqli_free_result($result);

          // close connection
          mysqli_close($connectDB);
          ?>
        </div>
      </div>
    </main>

  </div>
</body>

</html>