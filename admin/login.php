<?php
require_once '../config/auth.php';
require_once '../config/database.php';

// Redirect if already logged in as admin
if (isLoggedIn() && isAdmin()) {
    header('Location: dashboard.php');
    exit();
}

$error = '';

if ($_POST) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username && $password) {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT id, username, email, password, role, full_name FROM users WHERE (username = ? OR email = ?) AND role = 'admin'";
        $stmt = $db->prepare($query);
        $stmt->execute([$username, $username]);
        
        if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];
                
                header('Location: dashboard.php');
                exit();
            } else {
                $error = 'Invalid admin credentials';
            }
        } else {
            $error = 'Invalid admin credentials';
        }
    } else {
        $error = 'Please fill in all fields';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Westway Express</title>
    <link href="../assets/css/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-700 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Logo Section -->
            <div class="text-center mb-8">
                <div class="bg-orange-500 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shipping-fast text-white text-2xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">Admin Portal</h2>
                <p class="text-blue-200">Westway Express Management System</p>
            </div>

            <!-- Login Form -->
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <form method="POST" class="space-y-6">
                    <?php if ($error): ?>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                <p class="text-red-800 text-sm"><?php echo htmlspecialchars($error); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-gray-400"></i>Username or Email
                        </label>
                        <input type="text" name="username" id="username" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               placeholder="Enter your username or email">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-gray-400"></i>Password
                        </label>
                        <input type="password" name="password" id="password" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                               placeholder="Enter your password">
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i>Sign In to Admin Panel
                    </button>
                </form>
                
                <div class="mt-6 text-center">
                    <a href="../index.php" class="text-blue-600 hover:text-blue-500 text-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Back to Main Website
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
