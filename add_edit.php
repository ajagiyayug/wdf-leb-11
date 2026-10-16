<?php
include 'db.php';

$name = "";
$email = "";
$course = "";
$id = "";
$is_update = false;

// If Edit request comes, fetch existing data
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $is_update = true;
    $result = mysqli_query($conn, "SELECT * FROM students WHERE id = $id");
    if ($row = mysqli_fetch_assoc($result)) {
        $name = $row['name'];
        $email = $row['email'];
        $course = $row['course'];
    }
}

// Form Submission (INSERT or UPDATE)
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $student_id = $_POST['id'];

    if (!empty($student_id)) {
        // UPDATE Operation using MySQLi
        $query = "UPDATE students SET name='$name', email='$email', course='$course' WHERE id=$student_id";
        if (mysqli_query($conn, $query)) {
            header("Location: index.php?status=updated");
            exit();
        } else {
            $error = "Error updating record: " . mysqli_error($conn);
        }
    } else {
        // INSERT Operation using MySQLi
        $query = "INSERT INTO students (name, email, course) VALUES ('$name', '$email', '$course')";
        if (mysqli_query($conn, $query)) {
            header("Location: index.php?status=added");
            exit();
        } else {
            $error = "Error adding record: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $is_update ? 'Edit Student' : 'Add Student'; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container" style="max-width: 500px;">
    <div class="header-flex">
        <h3><?php echo $is_update ? 'Edit Student Record' : 'Add New Student'; ?></h3>
        <a href="index.php" class="btn" style="background-color: #6c757d;">Back</a>
    </div>

    <?php if(!empty($error)): ?>
        <div class="msg msg-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        
        <div class="form-group">
            <label>Student Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
        </div>

        <div class="form-group">
            <label>Email Address:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>

        <div class="form-group">
            <label>Course:</label>
            <select name="course" required>
                <option value="">Select Course</option>
                <option value="Computer Engineering" <?php if($course == 'Computer Engineering') echo 'selected'; ?>>Computer Engineering</option>
                <option value="Information Technology" <?php if($course == 'Information Technology') echo 'selected'; ?>>Information Technology</option>
                <option value="Cyber Security" <?php if($course == 'Cyber Security') echo 'selected'; ?>>Cyber Security</option>
            </select>
        </div>

        <button type="submit" name="submit" class="btn btn-success" style="width: 100%; padding: 12px;"><?php echo $is_update ? 'Update Student' : 'Save Student'; ?></button>
    </form>
</div>

</body>
</html>