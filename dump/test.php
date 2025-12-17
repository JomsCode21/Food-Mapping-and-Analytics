<?php
    require_once '../db_con.php';

    if (isset($_POST['submitForm'])) {
        $name = trim($_POST['name']);
        $age = $_POST['age'];
        $location = $_POST['location'];

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO test_lang (name, age, location) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $age, $location);

        if ($stmt->execute()) {
            echo "Data submitted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
    }
?>

<form method="post" name="testForm">
    <input type="text" name="name" placeholder="Name" required><br>
    <input type="number" name="age" id="age" placeholder="Age" ><br>
    <input type="text" name="location" id="location" placeholder="Location" ><br>
    <button type="submit" name="submitForm">Submit</button>
</form>