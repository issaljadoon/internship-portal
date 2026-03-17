<?php
include 'db_connect.php';

function clean_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_id = clean_input($_POST['student_id']);
    $full_name = clean_input($_POST['full_name']);
    $email = clean_input($_POST['email']);
    $raw_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $cnic = clean_input($_POST['cnic']);
    $phone = clean_input($_POST['phone']);
    $cgpa = floatval($_POST['cgpa']);
    $department = clean_input($_POST['department']);

    // Student ID validation
    if (!preg_match("/^FA\d{2}-[A-Z]{3}-\d{3}$/", $student_id)) {
        die("Invalid Student ID format");
    }

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Password strength validation
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $raw_password)) {
        die("Weak password");
    }

    // Confirm password check
    if ($raw_password !== $confirm_password) {
        die("Passwords do not match");
    }

    // CNIC validation
    if (!preg_match("/^\d{5}-\d{7}-\d{1}$/", $cnic)) {
        die("Invalid CNIC format");
    }

    // Phone validation
    if (!preg_match("/^03\d{9}$/", $phone)) {
        die("Invalid phone number format");
    }

    // CGPA validation
    if ($cgpa < 0 || $cgpa > 4) {
        die("CGPA must be between 0.00 and 4.00");
    }

    // Duplicate check
    $checkStmt = $conn->prepare("SELECT id FROM students WHERE student_id = ? OR email = ?");
    $checkStmt->bind_param("ss", $student_id, $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        die("Student ID or Email already exists");
    }
    $checkStmt->close();

    // File upload validation
    if (!isset($_FILES['resume']) || $_FILES['resume']['error'] != 0) {
        die("Resume upload is required");
    }

    $fileTmp = $_FILES['resume']['tmp_name'];
    $fileName = basename($_FILES['resume']['name']);
    $fileSize = $_FILES['resume']['size'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($fileExt !== "pdf") {
        die("Only PDF files are allowed");
    }

    if ($fileSize > 2 * 1024 * 1024) {
        die("Resume must be less than 2MB");
    }

    $mimeType = mime_content_type($fileTmp);
    if ($mimeType !== "application/pdf") {
        die("Fake PDF file detected");
    }

    $safeFileName = time() . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "", $fileName);
    $uploadPath = "uploads/" . $safeFileName;

    if (!move_uploaded_file($fileTmp, $uploadPath)) {
        die("File upload failed");
    }

    // Hash password
    $password = password_hash($raw_password, PASSWORD_DEFAULT);

    // Insert data
    $stmt = $conn->prepare("INSERT INTO students (student_id, full_name, email, password, cnic, phone, cgpa, department, resume_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssdss", $student_id, $full_name, $email, $password, $cnic, $phone, $cgpa, $department, $uploadPath);

    if ($stmt->execute()) {
        echo "<h2 style='color:green; text-align:center;'>Registration Successful!</h2>";
        echo "<p style='text-align:center;'><a href='index.php'>Go Back</a></p>";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>