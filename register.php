<?php
require_once 'db.php';
session_start();

if (isset($_SESSION['email'])) {
    header("Location: user/dashboard.php");
    exit;
}

$error = "";
$success = false;

// Initialize variables for value persistence
$firstName = $lastName = $option = $relativeName = $relationship = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    function clean_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $firstName = clean_input($_POST['firstName'] ?? '');
    $lastName = clean_input($_POST['lastName'] ?? '');
    $option = clean_input($_POST['option'] ?? '');
    $relativeName = clean_input($_POST['relativeName'] ?? '');
    $relationship = clean_input($_POST['relationship'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if ($option === 'relative') {
        if (empty($relativeName) || empty($relationship)) {
            $error = "Relative name and relationship are required.";
        }
    }

    if (!$error && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }

    if (!$error && $password !== $confirmPassword) {
        $error = "Passwords do not match.";
    }

    if (!$error && strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    }

    if (!$error) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $error = "Email already registered.";
        }
    }

    if (!$error) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users 
            (first_name, last_name, role, relative_name, relationship, email, password_hash)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $firstName,
            $lastName,
            $option,
            $option === 'relative' ? $relativeName : null,
            $option === 'relative' ? $relationship : null,
            $email,
            $passwordHash
        ]);
        $success = true;

        // Optional: redirect immediately
        // header("Location: index.php?registered=1");
        // exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
    <main>
        <?php if ($error): ?>
            <p style="color:red"><?= htmlspecialchars($error) ?></p>
        <?php elseif ($success): ?>
            <p style="color:green">Registered</p>
        <?php endif; ?>

        <form id="mainForm" method="post">
            <span>Register</span>

            <section id="personalInfo">
                <input type="text" name="firstName" id="firstName" placeholder="First Name" required
                       value="<?= htmlspecialchars($firstName) ?>">
                <input type="text" name="lastName" id="lastName" placeholder="Last Name" required
                       value="<?= htmlspecialchars($lastName) ?>">

                <label for="option">Select Role:</label>
                <select name="option" id="option">
                    <option value="personal" <?= $option === 'personal' ? 'selected' : '' ?>>Personal</option>
                    <option value="relative" <?= $option === 'relative' ? 'selected' : '' ?>>w/ Relative</option>
                </select>

                <input type="text" name="relativeName" id="relativeName" placeholder="Relative Name"
                       value="<?= htmlspecialchars($relativeName) ?>"
                       <?= $option === 'relative' ? '' : 'hidden' ?>>
                <input type="text" name="relationship" id="relationship" placeholder="Relationship (e.g. Brother)"
                       value="<?= htmlspecialchars($relationship) ?>"
                       <?= $option === 'relative' ? '' : 'hidden' ?>>

                <button type="button" id="nextBtn">Next</button>
                <a href="index.php">Login</a>
            </section>

            <section id="accountInfo" hidden>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required>

                <button type="button" id="backBtn">Back</button>
                <button type="submit">Register</button>
            </section>
        </form>
    </main>

    <script>
        const option = document.getElementById('option');
        const relativeName = document.getElementById('relativeName');
        const relationship = document.getElementById('relationship');
        const nextBtn = document.getElementById('nextBtn');
        const backBtn = document.getElementById('backBtn');
        const accountInfo = document.getElementById('accountInfo');
        const personalInfo = document.getElementById('personalInfo');

        function updateRelativeFields() {
            const isRelative = option.value === 'relative';
            relativeName.hidden = !isRelative;
            relationship.hidden = !isRelative;
            relativeName.required = isRelative;
            relationship.required = isRelative;
        }

        option.addEventListener('change', updateRelativeFields);
        window.addEventListener('load', updateRelativeFields);

        nextBtn.addEventListener('click', function () {
            const inputs = personalInfo.querySelectorAll('input:not([hidden]), select');
            for (let input of inputs) {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    return;
                }
            }
            personalInfo.hidden = true;
            accountInfo.hidden = false;
        });

        backBtn.addEventListener('click', function () {
            accountInfo.hidden = true;
            personalInfo.hidden = false;
        });
    </script>
</body>
</html>
