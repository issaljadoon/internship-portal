<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Internship Registration</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2>Internship Registration</h2>
    <p class="subtitle">Please fill in your details</p>

    <form action="register.php" method="POST" enctype="multipart/form-data">

        <div class="field">
            <label>Student ID</label>
            <input type="text" name="student_id" required>
        </div>

        <div class="field">
            <label>Full Name</label>
            <input type="text" name="full_name" required>
        </div>

        <div class="field">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="field">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="field">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>
        </div>

        <div class="field">
            <label>CNIC</label>
            <input type="text" name="cnic" required>
        </div>

        <div class="field">
            <label>Phone</label>
            <input type="text" name="phone" required>
        </div>

        <div class="field">
            <label>CGPA</label>
            <input type="text" name="cgpa" required>
        </div>

        <div class="field">
            <label>Department</label>
            <select name="department">
                <option value="BCS">BCS</option>
                <option value="BSE">BSE</option>
            </select>
        </div>

        <div class="field">
            <label>Upload Resume (PDF)</label>
            <input type="file" name="resume" required>
        </div>

        <button type="submit">Register</button>

    </form>
</div>

</body>
</html>