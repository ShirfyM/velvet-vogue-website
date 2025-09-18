<?php
session_start();
require_once 'config.php';
require_once 'csrf_helper.php';

$errors = [
    'login' => $_SESSION['login_error'] ?? '',
    'signup' => $_SESSION['signup_error'] ?? ''
];

$activeForm = $_SESSION['active_form'] ?? 'login';

unset($_SESSION['login_error'], $_SESSION['signup_error'], $_SESSION['active_form']);

function showError($error) {
    return !empty($error) ? "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4'>{$error}</div>" : '';
}

function isActive($formName, $activeForm) {
    return $formName === $activeForm ? 'active' : '';
}

if (isset($_POST['signup'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Please enter a full name.";
    }
    
    if (empty($email)) {
        $errors[] = "Please enter an email address.";
    }
    
    if (empty($password)) {
        $errors[] = "Please enter a password.";
    }
    
    if (!empty($errors)) {
        $_SESSION['signup_error'] = implode("<br>", $errors);
        $_SESSION['active_form'] = 'signup';
        header("Location: login_register.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['signup_error'] = "Email already exists.";
        $_SESSION['active_form'] = 'signup';
        header("Location: login_register.php");
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);
        
        if ($stmt->execute()) {
            $_SESSION['active_form'] = 'login';
            $_SESSION['signup_success'] = "Account created successfully! Please log in.";
            header("Location: login_register.php");
        } else {
            $_SESSION['signup_error'] = "Error creating account. Please try again.";
            $_SESSION['active_form'] = 'signup';
            header("Location: login_register.php");
        }
    }
    exit();
}

if (isset($_POST['signin'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    $errors = [];
    if (empty($email)) {
        $errors[] = "Please enter an email address.";
    }
    if (empty($password)) {
        $errors[] = "Please enter a password.";
    }
    
    if (!empty($errors)) {
        $_SESSION['login_error'] = implode("<br>", $errors);
        $_SESSION['active_form'] = 'login';
        header("Location: login_register.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'] ?? 'user';
            if (($_SESSION['role'] ?? 'user') === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        }
    }
    
    $_SESSION['login_error'] = "Invalid email or password.";
    $_SESSION['active_form'] = 'login';
    header("Location: login_register.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register - Velvet Vogue</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-white p-3 sm:p-4 shadow-md sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-xl sm:text-2xl font-bold">Velvet Vogue</a>
            <nav class="hidden md:flex space-x-4 lg:space-x-6 text-gray-600 font-medium items-center">
                <a href="index.php" class="hover:text-black transition-colors">Home</a>
                <a href="collection.php" class="hover:text-black transition-colors">Collection</a>
                <a href="about.php" class="hover:text-black transition-colors">About</a>
                <a href="contact.php" class="hover:text-black transition-colors">Contact</a>
            </nav>
            <div class="flex items-center space-x-2 sm:space-x-4">
                <div class="relative hidden sm:block">
                    <form id="nav-search-form">
                        <input id="nav-search" type="text" placeholder="Search" class="p-2 border rounded-lg text-xs sm:text-sm w-32 sm:w-48" />
                    </form>
                    <!-- Search Results Dropdown -->
                    <div id="search-results" class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 max-h-96 overflow-y-auto z-50 hidden">
                        <div id="search-results-content" class="p-2">
                            <!-- Search results will be populated here -->
                        </div>
                    </div>
                </div>
                <button class="text-gray-600 hover:text-black transition-colors text-black font-semibold p-1" onclick="window.location.href='<?php echo isset($_SESSION['user_id']) ? "account.php" : "login_register.php"; ?>'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </button>
                <button class="text-gray-600 hover:text-black transition-colors p-1" onclick="window.location.href='wishlist.php'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </button>
                <button class="text-gray-600 hover:text-black transition-colors p-1" onclick="window.location.href='cart.php'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.182 1.764.707 1.764H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <section class="flex-grow flex items-center justify-center px-4 py-4">
        <div class="w-full max-w-6xl">
            <!-- Success Message -->
            <?php if (isset($_SESSION['signup_success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
                    <?php echo $_SESSION['signup_success']; unset($_SESSION['signup_success']); ?>
                </div>
            <?php endif; ?>

            <!-- Forms Container -->
            <div class="flex flex-col lg:flex-row gap-8 justify-center items-stretch">
                <!-- Login Form -->
                <div id="login" class="bg-white p-8 md:p-10 lg:p-12 rounded-xl shadow-md w-full lg:w-1/2" <?= isActive('login', $activeForm) ? 'style="display: block;"' : 'style="display: none;"' ?>>
                    <h1 class="text-3xl md:text-4xl font-bold text-center mb-8">Login</h1>
                    <form action="login_register.php" method="post">
                        <?= getCSRFTokenField(); ?>
                        <?= showError($errors['login']); ?>
                        <div class="mb-6">
                            <input type="email" placeholder="Email" name="email" required class="w-full p-4 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                        </div>
                        <div class="mb-6">
                            <input type="password" placeholder="Password" name="password" required class="w-full p-4 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between text-base mb-6 space-y-2 sm:space-y-0">
                            <a href="#" class="text-gray-600 hover:underline text-center sm:text-left">Forgot password?</a>
                            <a href="#" class="text-gray-600 hover:underline text-center sm:text-right" onclick="showSignup()">Create Account</a>
                        </div>
                        <button type="submit" name="signin" class="w-full btn-primary py-4 text-lg">Sign in</button>
                    </form>
                </div>

                <!-- Signup Form -->
                <div id="signup" class="bg-white p-8 md:p-10 lg:p-12 rounded-xl shadow-md w-full lg:w-1/2" <?= isActive('signup', $activeForm) ? 'style="display: block;"' : 'style="display: none;"' ?>>
                    <h1 class="text-3xl md:text-4xl font-bold text-center mb-8">Sign up</h1>
                    <form action="login_register.php" method="post">
                        <?= getCSRFTokenField(); ?>
                        <?= showError($errors['signup']); ?>
                        <div class="mb-6">
                            <input type="text" placeholder="Full Name" name="name" required class="w-full p-4 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                        </div>
                        <div class="mb-6">
                            <input type="email" placeholder="Email" name="email" required class="w-full p-4 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                        </div>
                        <div class="mb-6">
                            <input type="password" placeholder="Password" name="password" required class="w-full p-4 text-lg border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                        </div>
                        <button type="submit" name="signup" class="w-full btn-primary py-4 text-lg mb-4">Sign up</button>
                    </form>
                    <div class="text-center">
                        <a href="#" class="text-gray-600 hover:underline text-base" onclick="showLogin()">Already have an account? Sign in</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer p-4 sm:p-6 md:p-8">
        <div class="container mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
            <div class="sm:col-span-2 md:col-span-1">
                <h3 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4 text-white">Velvet Vogue</h3>
                <p class="text-xs sm:text-sm leading-relaxed">
                    Velvet Vogue is your ultimate destination for high-quality, expressive fashion. We believe in style that tells a story, offering curated collections for the modern individual who values both trend and timelessness. Our mission is to empower you to look and feel your best, every day.
                </p>
            </div>
            <div>
                <h4 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4 text-white">Company</h4>
                <ul class="space-y-1 sm:space-y-2 text-xs sm:text-sm">
                    <li><a href="about.php" class="hover:underline">About Us</a></li>
                    <li><a href="#" class="hover:underline">Careers</a></li>
                    <li><a href="#" class="hover:underline">Store Locator</a></li>
                    <li><a href="#" class="hover:underline">Our Blog</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-base sm:text-lg font-semibold mb-3 sm:mb-4 text-white">Other</h4>
                <ul class="space-y-1 sm:space-y-2 text-xs sm:text-sm">
                    <li><a href="#" class="hover:underline">Returns & Exchanges</a></li>
                    <li><a href="#" class="hover:underline">Shipping & Delivery</a></li>
                    <li><a href="#" class="hover:underline">FAQ</a></li>
                    <li><a href="#" class="hover:underline">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="text-center mt-4 sm:mt-6 md:mt-8 text-xs sm:text-sm text-gray-500">
            &copy; 2025 Velvet Vogue. All rights reserved.
        </div>
    </footer>

<script src="assets/js/script.js"></script>
<script>
function showLogin() {
    document.getElementById('login').style.display = 'block';
    document.getElementById('signup').style.display = 'none';
}

function showSignup() {
    document.getElementById('login').style.display = 'none';
    document.getElementById('signup').style.display = 'block';
}

document.addEventListener('DOMContentLoaded', function() {
    const navSearch = document.getElementById('nav-search');
    const navForm = document.getElementById('nav-search-form');
    const searchResults = document.getElementById('search-results');
    const searchResultsContent = document.getElementById('search-results-content');
    
    if (navForm) navForm.addEventListener('submit', e => e.preventDefault());
    
    if (navSearch) {
        navSearch.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            
            if (query.length < 2) {
                searchResults.classList.add('hidden');
                return;
            }
            
            if (query.length >= 2) {
                window.location.href = `collection.php?search=${encodeURIComponent(query)}`;
            }
        });
        
        document.addEventListener('click', function(e) {
            if (!navSearch.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });
    }
});
</script>

</body>
</html>