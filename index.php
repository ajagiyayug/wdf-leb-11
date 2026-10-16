<?php
include 'db.php';

$msg = "";
$msg_type = "";

// Handle Delete Operation with Success/Failure feedback
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $query = "DELETE FROM students WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        $msg = "Student record deleted successfully!";
        $msg_type = "success";
    } else {
        $msg = "Error deleting record: " . mysqli_error($conn);
        $msg_type = "error";
    }
}

// Check for redirect messages from Add/Edit
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'added') {
        $msg = "New student added successfully!";
        $msg_type = "success";
    } elseif ($_GET['status'] == 'updated') {
        $msg = "Student record updated successfully!";
        $msg_type = "success";
    }
}

// Search and Filter Logic using MySQLi
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$filter_course = isset($_GET['filter_course']) ? mysqli_real_escape_string($conn, $_GET['filter_course']) : '';

$sql = "SELECT * FROM students WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (name LIKE '%$search%' OR email LIKE '%$search%' OR course LIKE '%$search%')";
}

if (!empty($filter_course)) {
    $sql .= " AND course = '$filter_course'";
}

$sql .= " ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Practical 11: Student Management CRUD</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <div class="header-flex">
        <h2>Student Management System (CRUD with Search & Filter)</h2>
        <a href="add_edit.php" class="btn btn-success">+ Add New Student</a>
    </div>

    <!-- Clear Success/Failure UI Feedback Messages -->
    <?php if(!empty($msg)): ?>
        <div class="msg msg-<?php echo $msg_type; ?>"><?php echo $msg; ?></div>
    <?php endif; ?>

    <!-- Search and Filter Form -->
    <form method="GET" action="index.php" class="filter-bar">
        <input type="text" name="search" placeholder="Search by name, email or course..." value="<?php echo htmlspecialchars($search); ?>">
        <select name="filter_course">
            <option value="">Filter by Course</option>
            <option value="Computer Engineering" <?php if($filter_course == 'Computer Engineering') echo 'selected'; ?>>Computer Engineering</option>
            <option value="Information Technology" <?php if($filter_course == 'Information Technology') echo 'selected'; ?>>Information Technology</option>
            <option value="Cyber Security" <?php if($filter_course == 'Cyber Security') echo 'selected'; ?>>Cyber Security</option>
        </select>
        <button type="submit" class="btn">Search</button>
        <a href="index.php" class="btn" style="background-color: #6c757d; text-align: center; line-height: 20px;">Reset</a>
    </form>

    <!-- Students Data Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Course</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['course']); ?></td>
                        <td class="actions">
                            <a href="add_edit.php?id=<?php echo $row['id']; ?>" class="btn-warning">Edit</a>
                            <!-- Delete Operation Confirmed before execution via JavaScript -->
                            <a href="index.php?delete=<?php echo $row['id']; ?>" class="btn-danger" onclick="return confirm('Are you sure you want to delete this student record?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #777;">No student records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>