<?php
require_once 'config/auth.php';
require_once 'config/database.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

if ($_POST) {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    
    if ($username && $email && $password && $confirm_password && $full_name) {
        if ($password !== $confirm_password) {
            $error = 'Passwords do not match';
        } else {
            $database = new Database();
            $db = $database->getConnection();
            
            // Check if username or email already exists
            $query = "SELECT id FROM users WHERE username = ? OR email = ?";
            $stmt = $db->prepare($query);
            $stmt->execute([$username, $email]);
            
            if ($stmt->fetch()) {
                $error = 'Username or email already exists';
            } else {
                // Create new user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO users (username, email, password, full_name, phone) VALUES (?, ?, ?, ?, ?)";
                $stmt = $db->prepare($query);
                
                if ($stmt->execute([$username, $email, $hashed_password, $full_name, $phone])) {
                    $success = 'Registration successful! You can now login.';
                } else {
                    $error = 'Registration failed. Please try again.';
                }
            }
        }
    } else {
        $error = 'Please fill in all required fields';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Westway Express</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Create your account
                </h2>
            </div>
            <form class="mt-8 space-y-6" method="POST">
                <?php if ($error): ?>
                    <div class="bg-red-50 border border-red-200 rounded-md p-4">
                        <p class="text-red-800"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="bg-green-50 border border-green-200 rounded-md p-4">
                        <p class="text-green-800"><?php echo htmlspecialchars($success); ?></p>
                    </div>
                <?php endif; ?>
                
                <div class="space-y-4">
                    <div>
                        <input type="text" name="full_name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="Full Name">
                    </div>
                    <div>
                        <input type="text" name="username" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="Username">
                    </div>
                    <div>
                        <input type="email" name="email" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="Email">
                    </div>
                    <div>
                        <input type="tel" name="phone" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="Phone Number">
                    </div>
                    <div>
                        <input type="password" name="password" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="Password">
                    </div>
                    <div>
                        <input type="password" name="confirm_password" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="Confirm Password">
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Register
                    </button>
                </div>
                
                <div class="text-center">
                    <a href="login.php" class="text-blue-600 hover:text-blue-500">
                        Already have an account? Login here
                    </a>
                </div>
                
                <div class="text-center">
                    <a href="index.php" class="text-gray-600 hover:text-gray-500">
                        Back to Home
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
