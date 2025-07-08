<?php
require_once 'config/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Westway Express - Global Logistics Solutions</title>
    <link href="assets/css/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
 
    <style>
        /* Custom Cargo Colors */
        :root {
            --cargo-blue: #1e40af;
            --cargo-orange: #f97316;
            --cargo-red: #dc2626;
            --cargo-dark: #0f172a;
        }

        .hero-slider {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            transition: all 1.5s ease-in-out;
        }
        
        /* Enhanced World Map Background */
        .world-map-bg {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1000 500'%3E%3Cg fill='%23ffffff' opacity='0.08'%3E%3Cpath d='M158 206c-1 3-2 7-2 11 0 4 1 8 2 11l5 9c2 3 5 5 8 6l7 2c3 0 6-1 8-3l6-5c2-2 3-5 3-8v-8c0-3-1-6-3-8l-6-5c-2-2-5-3-8-3l-7 2c-3 1-6 3-8 6l-5 9z'/%3E%3Cpath d='M300 150c-2 4-3 8-3 12s1 8 3 12l6 10c3 3 6 5 9 6l8 2c3 0 7-1 9-3l7-6c2-2 3-5 3-9v-8c0-4-1-7-3-9l-7-6c-2-2-6-3-9-3l-8 2c-3 1-6 3-9 6l-6 10z'/%3E%3Cpath d='M450 180c-1 3-2 6-2 10s1 7 2 10l4 8c2 2 4 4 7 5l6 1c2 0 5 0 7-2l5-4c1-2 2-4 2-7v-6c0-3-1-5-2-7l-5-4c-2-2-5-2-7-2l-6 1c-3 1-5 3-7 5l-4 8z'/%3E%3Cpath d='M600 200c-2 5-3 9-3 14s1 9 3 14l7 11c3 4 7 6 11 7l9 2c4 0 8-1 11-4l8-7c2-3 3-6 3-10v-10c0-4-1-7-3-10l-8-7c-3-3-7-4-11-4l-9 2c-4 1-8 3-11 7l-7 11z'/%3E%3Cpath d='M750 160c-1 4-2 8-2 12s1 8 2 12l5 9c2 3 5 5 8 6l7 2c3 0 6-1 8-3l6-5c2-2 3-5 3-8v-8c0-3-1-6-3-8l-6-5c-2-2-5-3-8-3l-7 2c-3 1-6 3-8 6l-5 9z'/%3E%3Cpath d='M100 350c-3 2-5 5-6 8l-2 7c0 3 1 6 3 8l5 6c2 2 5 3 8 3h8c3 0 6-1 8-3l5-6c2-2 3-5 3-8l-2-7c-1-3-3-6-6-8l-9-5c-3-1-6-1-9 0l-6 5z'/%3E%3Cpath d='M250 320c-2 3-3 7-3 11s1 8 3 11l6 9c2 3 5 5 8 6l7 2c3 0 6-1 8-3l6-5c2-2 3-5 3-8v-8c0-3-1-6-3-8l-6-5c-2-2-5-3-8-3l-7 2c-3 1-6 3-8 6l-6 9z'/%3E%3Cpath d='M400 380c-1 2-2 5-2 8s1 6 2 8l3 6c1 2 3 3 5 4l5 1c2 0 4 0 5-1l4-3c1-1 2-3 2-5v-5c0-2-1-4-2-5l-4-3c-1-1-3-1-5-1l-5 1c-2 1-4 2-5 4l-3 6z'/%3E%3Cpath d='M550 340c-2 4-3 8-3 12s1 8 3 12l6 10c3 3 6 5 9 6l8 2c3 0 7-1 9-3l7-6c2-2 3-5 3-9v-8c0-4-1-7-3-9l-7-6c-2-2-6-3-9-3l-8 2c-3 1-6 3-9 6l-6 10z'/%3E%3Cpath d='M700 300c-1 3-2 6-2 10s1 7 2 10l4 8c2 2 4 4 7 5l6 1c2 0 5 0 7-2l5-4c1-2 2-4 2-7v-6c0-3-1-5-2-7l-5-4c-2-2-5-2-7-2l-6 1c-3 1-5 3-7 5l-4 8z'/%3E%3Cpath d='M850 280c-2 5-3 9-3 14s1 9 3 14l7 11c3 4 7 6 11 7l9 2c4 0 8-1 11-4l8-7c2-3 3-6 3-10v-10c0-4-1-7-3-10l-8-7c-3-3-7-4-11-4l-9 2c-4 1-8 3-11 7l-7 11z'/%3E%3C/g%3E%3C/svg%3E");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #1e40af, #f97316);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .counter {
            font-variant-numeric: tabular-nums;
        }
        
        .team-card {
            transition: all 0.3s ease;
        }
        
        .team-card:hover {
            transform: translateY(-10px);
        }
        
        .service-card {
            transition: all 0.4s ease;
        }
        
        .service-card:hover {
            transform: translateY(-15px) scale(1.02);
        }
        
        .scroll-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .scroll-to-top.visible {
            opacity: 1;
            visibility: visible;
        }
        
        .language-dropdown {
            position: relative;
        }
        
        .language-dropdown:hover .dropdown-content {
            display: block;
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }
        
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        /* Custom Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes slideUp {
            0% { transform: translateY(100px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        @keyframes scaleIn {
            0% { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        .animate-slide-up {
            animation: slideUp 0.5s ease-out;
        }
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        .animate-scale-in {
            animation: scaleIn 0.6s ease-out;
        }
        .animate-bounce-slow {
            animation: bounce 3s infinite;
        }
        .animate-pulse-slow {
            animation: pulse 4s infinite;
        }

        /* Cargo Color Classes */
        .bg-cargo-blue { background-color: var(--cargo-blue); }
        .bg-cargo-orange { background-color: var(--cargo-orange); }
        .bg-cargo-red { background-color: var(--cargo-red); }
        .bg-cargo-dark { background-color: var(--cargo-dark); }
        .text-cargo-blue { color: var(--cargo-blue); }
        .text-cargo-orange { color: var(--cargo-orange); }
        .text-cargo-red { color: var(--cargo-red); }
        .text-cargo-dark { color: var(--cargo-dark); }
        .border-cargo-blue { border-color: var(--cargo-blue); }
        .border-cargo-orange { border-color: var(--cargo-orange); }
        .border-cargo-red { border-color: var(--cargo-red); }
        .border-cargo-dark { border-color: var(--cargo-dark); }

        /* From/To Gradients */
        .from-cargo-blue {
            --tw-gradient-from: var(--cargo-blue) var(--tw-gradient-from-position);
            --tw-gradient-to: rgb(30 64 175 / 0) var(--tw-gradient-to-position);
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
        }
        .from-cargo-orange {
            --tw-gradient-from: var(--cargo-orange) var(--tw-gradient-from-position);
            --tw-gradient-to: rgb(249 115 22 / 0) var(--tw-gradient-to-position);
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
        }
        .from-cargo-dark {
            --tw-gradient-from: var(--cargo-dark) var(--tw-gradient-from-position);
            --tw-gradient-to: rgb(15 23 42 / 0) var(--tw-gradient-to-position);
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
        }
        .to-cargo-blue { --tw-gradient-to: var(--cargo-blue) var(--tw-gradient-to-position); }
        .to-cargo-orange { --tw-gradient-to: var(--cargo-orange) var(--tw-gradient-to-position); }
        .to-cargo-dark { --tw-gradient-to: var(--cargo-dark) var(--tw-gradient-to-position); }

        /* Hide Google Translate top banner and widget */
        .goog-te-banner-frame.skiptranslate, .goog-te-gadget-icon, #goog-gt-tt, .goog-te-balloon-frame {
            display: none !important;
        }
        body { top: 0 !important; }
    </style>
</head>
<body class="bg-gray-50 overflow-x-hidden">
    <!-- 1. Make the Google Translate widget accessible but hidden -->
    <div id="google_translate_element" style="position: absolute; left: -9999px; top: -9999px;"></div>
    
    <!-- Enhanced Mobile-First Navigation -->
    <nav class="bg-gradient-to-r from-cargo-blue via-blue-800 to-cargo-dark text-white shadow-2xl sticky top-0 z-50 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-3 animate-fade-in">
                    <div class="bg-gradient-to-r from-cargo-orange to-red-500 p-2 md:p-3 rounded-xl shadow-lg animate-pulse-slow">
                        <i class="fas fa-shipping-fast text-white text-xl md:text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl md:text-2xl font-bold bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
                            Westway Express
                        </h1>
                        <p class="text-xs md:text-sm text-blue-200 hidden md:block">Global Logistics Solutions</p>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="#home" class="hover:text-cargo-orange transition-all duration-300 font-medium">Home</a>
                    <a href="#services" class="hover:text-cargo-orange transition-all duration-300 font-medium">Services</a>
                    <a href="#why-choose-us" class="hover:text-cargo-orange transition-all duration-300 font-medium">Why Us</a>
                    <a href="#projects" class="hover:text-cargo-orange transition-all duration-300 font-medium">Projects</a>
                    <a href="#team" class="hover:text-cargo-orange transition-all duration-300 font-medium">Team</a>
                    <a href="#contact" class="hover:text-cargo-orange transition-all duration-300 font-medium">Contact</a>
                    <a href="track.php" class="text-white hover:text-blue-200 transition-colors font-medium">Track Shipment</a>
                </div>

                <!-- Language Selector & Admin -->
                <div class="flex items-center space-x-2 md:space-x-4">
                    <!-- Language Dropdown -->
                    <div class="language-dropdown relative inline-block">
                      <button class="px-4 py-2 bg-cargo-blue text-white rounded">Language</button>
                      <div class="dropdown-content absolute hidden bg-white text-black mt-2 rounded shadow-lg z-50">
                        <a href="#" onclick="translatePage('en');return false;">English</a>
                        <a href="#" onclick="translatePage('es');return false;">Español</a>
                        <a href="#" onclick="translatePage('fr');return false;">Français</a>
                        <a href="#" onclick="translatePage('de');return false;">Deutsch</a>
                        <a href="#" onclick="translatePage('zh-CN');return false;">中文</a>
                        <a href="#" onclick="translatePage('ar');return false;">العربية</a>
                        <a href="#" onclick="translatePage('hi');return false;">हिन्दी</a>
                        <a href="#" onclick="translatePage('pt');return false;">Português</a>
                      </div>
                    </div>

                    <?php if (isAdmin()): ?>
                        <a href="admin/dashboard.php" class="bg-gradient-to-r from-cargo-orange to-red-500 hover:from-orange-600 hover:to-red-600 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 shadow-lg transform hover:scale-105">
                            <i class="fas fa-tachometer-alt mr-1 md:mr-2"></i>
                            <span class="hidden sm:inline">Admin Panel</span>
                        </a>
                    <?php endif; ?>
                    
                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="lg:hidden hidden pb-4 animate-slide-up">
                <div class="flex flex-col space-y-2">
                    <a href="#home" class="block px-4 py-3 hover:bg-blue-700 rounded-lg transition-colors">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="#services" class="block px-4 py-3 hover:bg-blue-700 rounded-lg transition-colors">
                        <i class="fas fa-cogs mr-2"></i>Services
                    </a>
                    <a href="#why-choose-us" class="block px-4 py-3 hover:bg-blue-700 rounded-lg transition-colors">
                        <i class="fas fa-star mr-2"></i>Why Us
                    </a>
                    <a href="#projects" class="block px-4 py-3 hover:bg-blue-700 rounded-lg transition-colors">
                        <i class="fas fa-project-diagram mr-2"></i>Projects
                    </a>
                    <a href="#team" class="block px-4 py-3 hover:bg-blue-700 rounded-lg transition-colors">
                        <i class="fas fa-users mr-2"></i>Team
                    </a>
                    <a href="#contact" class="block px-4 py-3 hover:bg-blue-700 rounded-lg transition-colors">
                        <i class="fas fa-envelope mr-2"></i>Contact
                    </a>
                    <a href="track.php" class="block px-4 py-3 hover:bg-blue-700 rounded-lg transition-colors">
                        <i class="fas fa-search mr-2"></i>Track Package
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Image Slider -->
    <section id="home" class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Slider -->
        <div id="hero-slider" class="absolute inset-0 hero-slider">
            <!-- Slide backgrounds will be set via JavaScript -->
        </div>
        
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-black/70"></div>
        
        <!-- Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <div id="hero-content" class="animate-fade-in">
                <!-- Content will be updated via JavaScript -->
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <div class="w-6 h-10 border-2 border-white rounded-full flex justify-center">
                <div class="w-1 h-3 bg-white rounded-full mt-2 animate-pulse"></div>
            </div>
        </div>
    </section>

    <!-- Enhanced Tracking Section -->
    <section class="py-16 md:py-24 bg-gradient-to-br from-blue-50 via-white to-orange-50 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 world-map-bg"></div>
        
        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 md:mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-5xl font-bold mb-6">
                    <span class="gradient-text">Track Your Shipment</span>
                </h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">
                    Get real-time updates on your package with our advanced tracking system. 
                    Enter your tracking ID below for instant results.
                </p>
            </div>
            
            <div class="glass-effect rounded-3xl p-8 md:p-12 shadow-2xl" data-aos="zoom-in" data-aos-delay="200">
                <form action="track.php" method="POST" class="flex flex-col md:flex-row gap-6">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-xl"></i>
                        </div>
                        <input type="text" name="tracking_id" 
                               placeholder="Enter Tracking ID (e.g., AWB-795362231)" 
                               class="w-full pl-16 pr-6 py-5 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-cargo-blue/20 focus:border-cargo-blue text-lg transition-all duration-300 bg-white/80 backdrop-blur-sm">
                    </div>
                    <button type="submit" class="bg-gradient-to-r from-cargo-blue to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-10 py-5 rounded-2xl font-bold text-lg shadow-xl transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-search mr-3"></i>Track Now
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Enhanced Services Section -->
    <section id="services" class="py-16 md:py-24 bg-gradient-to-br from-gray-900 via-cargo-dark to-gray-900 text-white relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-10 w-32 h-32 bg-cargo-orange/10 rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-20 right-10 w-48 h-48 bg-cargo-blue/10 rounded-full blur-3xl animate-float" style="animation-delay: -3s;"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 md:mb-20" data-aos="fade-up">
                <h2 class="text-3xl md:text-5xl font-bold mb-6">
                    Our <span class="text-cargo-orange">Premium</span> Services
                </h2>
                <p class="text-lg md:text-xl text-gray-300 max-w-3xl mx-auto">
                    Comprehensive logistics solutions tailored to meet your global shipping needs with unmatched reliability, cutting-edge technology, and 24/7 customer support across all transportation modes.
                </p>
            </div>
            
            <!-- Replace your Premium Services grid container and service-card classes with the following for best visibility and function -->

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10 bg-white/80 backdrop-blur-xl rounded-3xl p-6 md:p-12 shadow-2xl">


    <!-- Ocean Freight -->
    <div class="service-card bg-white rounded-3xl p-8 border border-gray-200 shadow-2xl text-gray-900 hover:shadow-3xl hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
        <div class="text-center mb-6">
            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-r from-blue-500 to-cyan-400 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-ship text-3xl text-white"></i>
            </div>
            <h3 class="text-2xl font-bold mb-4">Ocean Freight</h3>
        </div>
        <div class="space-y-4 mb-8">
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Cost-effective for large shipments & bulk cargo</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Global port network with 500+ destinations</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Eco-friendly & carbon-neutral options</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Real-time container tracking & IoT monitoring</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>FCL & LCL consolidation services</span>
            </div>
        </div>
        <div class="bg-blue-500/10 rounded-xl p-4 mb-6">
            <p class="text-sm text-blue-700">
                <strong>Industry Leadership:</strong> Our strategic partnerships with 50+ major shipping lines and 25+ years of maritime expertise ensure your cargo reaches its destination safely with 99.2% on-time delivery rate.
            </p>
        </div>
        <div class="text-center">
            <div class="text-3xl font-bold text-cargo-orange mb-2">From $25.99</div>
            <p class="text-sm text-gray-500">per cubic meter</p>
        </div>
    </div>

    <!-- Air Freight -->
    <div class="service-card bg-white rounded-3xl p-8 border border-orange-200 shadow-2xl text-gray-900 hover:shadow-3xl hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
        <div class="text-center mb-6">
            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-r from-orange-500 to-red-400 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-plane text-3xl text-white"></i>
            </div>
            <h3 class="text-2xl font-bold mb-4">Air Freight</h3>
        </div>
        <div class="space-y-4 mb-8">
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Express delivery within 24-72 hours globally</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Priority handling for time-critical cargo</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Temperature-controlled & pharmaceutical grade</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Real-time flight tracking & GPS monitoring</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Dangerous goods & oversized cargo handling</span>
            </div>
        </div>
        <div class="bg-orange-500/10 rounded-xl p-4 mb-6">
            <p class="text-sm text-orange-700">
                <strong>Premium Network:</strong> Direct relationships with 100+ airlines and our own dedicated cargo aircraft fleet ensure your time-sensitive shipments arrive exactly when needed with 99.8% reliability.
            </p>
        </div>
        <div class="text-center">
            <div class="text-3xl font-bold text-cargo-orange mb-2">From $45.99</div>
            <p class="text-sm text-gray-500">per kilogram</p>
        </div>
    </div>

    <!-- Road Transport -->
    <div class="service-card bg-white rounded-3xl p-8 border border-green-200 shadow-2xl text-gray-900 hover:shadow-3xl hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
        <div class="text-center mb-6">
            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-r from-green-500 to-emerald-400 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-truck text-3xl text-white"></i>
            </div>
            <h3 class="text-2xl font-bold mb-4">Road Transport</h3>
        </div>
        <div class="space-y-4 mb-8">
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Door-to-door delivery with last-mile service</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Flexible scheduling & same-day options</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Advanced GPS tracking & route optimization</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Specialized vehicles for all cargo types</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Cross-border & customs clearance included</span>
            </div>
        </div>
        <div class="bg-green-500/10 rounded-xl p-4 mb-6">
            <p class="text-sm text-green-700">
                <strong>Modern Fleet:</strong> Our 500+ vehicle fleet with AI-powered route optimization and experienced drivers provide reliable, cost-effective ground transportation with real-time visibility across all major routes.
            </p>
        </div>
        <div class="text-center">
            <div class="text-3xl font-bold text-cargo-orange mb-2">From $15.99</div>
            <p class="text-sm text-gray-500">per mile</p>
        </div>
    </div>

    <!-- Express Delivery -->
    <div class="service-card bg-white rounded-3xl p-8 border border-purple-200 shadow-2xl text-gray-900 hover:shadow-3xl hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="400">
        <div class="text-center mb-6">
            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-r from-purple-500 to-indigo-400 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-bolt text-3xl text-white"></i>
            </div>
            <h3 class="text-2xl font-bold mb-4">Express Delivery</h3>
        </div>
        <div class="space-y-4 mb-8">
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Same-day, next-day & 2-hour delivery options</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Priority handling with dedicated couriers</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Digital signature & photo confirmation</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Comprehensive insurance coverage included</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>White-glove & high-value item handling</span>
            </div>
        </div>
        <div class="bg-purple-500/10 rounded-xl p-4 mb-6">
            <p class="text-sm text-purple-700">
                <strong>Premium Service:</strong> When time is critical, our express network delivers with 99.5% on-time performance, dedicated customer support, and money-back guarantee for late deliveries.
            </p>
        </div>
        <div class="text-center">
            <div class="text-3xl font-bold text-cargo-orange mb-2">From $89.99</div>
            <p class="text-sm text-gray-500">per package</p>
        </div>
    </div>

    <!-- Warehousing & Distribution -->
    <div class="service-card bg-white rounded-3xl p-8 border border-yellow-200 shadow-2xl text-gray-900 hover:shadow-3xl hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="500">
        <div class="text-center mb-6">
            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-r from-yellow-500 to-orange-400 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-warehouse text-3xl text-white"></i>
            </div>
            <h3 class="text-2xl font-bold mb-4">Warehousing & Distribution</h3>
        </div>
        <div class="space-y-4 mb-8">
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Climate-controlled & automated storage</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Advanced WMS & inventory management</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>24/7 security with biometric access control</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Pick, pack, kitting & value-added services</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>E-commerce fulfillment & returns processing</span>
            </div>
        </div>
        <div class="bg-yellow-500/10 rounded-xl p-4 mb-6">
            <p class="text-sm text-yellow-700">
                <strong>Smart Facilities:</strong> State-of-the-art facilities with robotics automation, AI-powered inventory optimization, and strategic locations across 50+ cities for optimal distribution efficiency.
            </p>
        </div>
        <div class="text-center">
            <div class="text-3xl font-bold text-cargo-orange mb-2">From $2.99</div>
            <p class="text-sm text-gray-500">per sq ft/month</p>
        </div>
    </div>

    <!-- Customs & Trade Compliance -->
    <div class="service-card bg-white rounded-3xl p-8 border border-teal-200 shadow-2xl text-gray-900 hover:shadow-3xl hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="600">
        <div class="text-center mb-6">
            <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-r from-teal-500 to-cyan-400 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-file-contract text-3xl text-white"></i>
            </div>
            <h3 class="text-2xl font-bold mb-4">Customs & Trade Compliance</h3>
        </div>
        <div class="space-y-4 mb-8">
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Expert documentation & regulatory compliance</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Duty optimization & tax planning strategies</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>AEO certification & trusted trader programs</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Fast-track clearance & pre-clearance services</span>
            </div>
            <div class="flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-400"></i>
                <span>Trade consulting & supply chain optimization</span>
            </div>
        </div>
        <div class="bg-teal-500/10 rounded-xl p-4 mb-6">
            <p class="text-sm text-teal-700">
                <strong>Regulatory Expertise:</strong> Licensed customs brokers with deep regulatory knowledge across 200+ countries ensure smooth, compliant border crossings with 95% first-time clearance rate.
            </p>
        </div>
        <div class="text-center">
            <div class="text-3xl font-bold text-cargo-orange mb-2">From $199</div>
            <p class="text-sm text-gray-500">per shipment</p>
        </div>
    </div>
</div>
            <!-- Service Features Banner -->
            <div class="mt-16 bg-gradient-to-r from-cargo-blue/20 to-cargo-orange/20 backdrop-blur-lg rounded-3xl p-8" data-aos="fade-up" data-aos-delay="700">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-bold mb-4">Why Choose Our Premium Services?</h3>
                    <p class="text-gray-300 max-w-3xl mx-auto">
                        Experience the difference with our industry-leading logistics solutions backed by cutting-edge technology and unmatched customer service.
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-shield-check text-2xl text-white"></i>
                        </div>
                        <h4 class="font-semibold mb-2">99.8% Reliability</h4>
                        <p class="text-sm text-gray-300">Industry-leading on-time delivery performance</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-satellite text-2xl text-white"></i>
                        </div>
                        <h4 class="font-semibold mb-2">Real-Time Tracking</h4>
                        <p class="text-sm text-gray-300">Advanced IoT sensors and GPS monitoring</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-headset text-2xl text-white"></i>
                        </div>
                        <h4 class="font-semibold mb-2">24/7 Support</h4>
                        <p class="text-sm text-gray-300">Dedicated customer service in 15+ languages</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-orange-500 to-red-500 rounded-2xl flex items-center justify-center">
                            <i class="fas fa-award text-2xl text-white"></i>
                        </div>
                        <h4 class="font-semibold mb-2">ISO Certified</h4>
                        <p class="text-sm text-gray-300">Quality management and security standards</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section id="why-choose-us" class="py-16 md:py-24 bg-gradient-to-br from-white via-blue-50 to-orange-50 relative overflow-hidden">
        <div class="absolute inset-0 world-map-bg"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 md:mb-20" data-aos="fade-up">
                <h2 class="text-3xl md:text-5xl font-bold mb-6">
                    Why Choose <span class="gradient-text">Westway Express</span>?
                </h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">
                    With over two decades of excellence in global logistics, we deliver more than just packages – we deliver peace of mind.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 md:gap-16 items-center">
                <!-- Image Side -->
                <div class="relative" data-aos="fade-right">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                        <img src="images/hp-1-map-1.jpg" alt="Global Logistics Network" 
                             class="w-full h-96 md:h-[500px] object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        <div class="absolute bottom-6 left-6 text-white">
                            <h3 class="text-2xl font-bold mb-2">Global Network</h3>
                            <p class="text-lg">Connecting 200+ countries worldwide</p>
                        </div>
                    </div>
                    
                    <!-- Floating Stats -->
                    <div class="absolute -top-6 -right-6 bg-white rounded-2xl p-6 shadow-xl animate-float">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-cargo-blue">99.8%</div>
                            <div class="text-sm text-gray-600">On-Time Delivery</div>
                        </div>
                    </div>
                    
                    <div class="absolute -bottom-6 -left-6 bg-cargo-orange text-white rounded-2xl p-6 shadow-xl animate-float" style="animation-delay: -2s;">
                        <div class="text-center">
                            <div class="text-3xl font-bold">24/7</div>
                            <div class="text-sm">Customer Support</div>
                        </div>
                    </div>
                </div>

                <!-- Content Side -->
                <div class="space-y-8" data-aos="fade-left">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-r from-cargo-blue to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-globe text-2xl text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Global Reach</h3>
                            <p class="text-gray-600">Our extensive network spans across 200+ countries with strategic partnerships ensuring seamless international shipping.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-r from-cargo-orange to-red-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-shield-alt text-2xl text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Advanced Security</h3>
                            <p class="text-gray-600">State-of-the-art tracking technology and comprehensive insurance coverage protect your valuable shipments.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-leaf text-2xl text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Eco-Friendly Solutions</h3>
                            <p class="text-gray-600">Committed to sustainable logistics with carbon-neutral shipping options and green transportation methods.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-clock text-2xl text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Time-Critical Delivery</h3>
                            <p class="text-gray-600">Specialized express services for urgent shipments with guaranteed delivery times and priority handling.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-headset text-2xl text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Expert Support</h3>
                            <p class="text-gray-600">Dedicated customer service team available 24/7 with multilingual support and logistics expertise.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Counter Section -->
    <section class="py-16 md:py-24 bg-gradient-to-r from-cargo-dark via-gray-900 to-cargo-dark text-white relative overflow-hidden">
        <!-- Background Animation -->
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-cargo-blue/10 to-cargo-orange/10 animate-pulse"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-5xl font-bold mb-6">
                    Our <span class="text-cargo-orange">Achievements</span>
                </h2>
                <p class="text-lg md:text-xl text-gray-300 max-w-3xl mx-auto">
                    Numbers that speak for our commitment to excellence and customer satisfaction
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12">
                <div class="text-center" data-aos="zoom-in" data-aos-delay="100">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-cargo-blue to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-box text-2xl text-white"></i>
                    </div>
                    <div class="counter text-4xl md:text-5xl font-bold text-cargo-orange mb-2" data-target="2500000">0</div>
                    <div class="text-lg text-gray-300">Packages Shipped</div>
                </div>

                <div class="text-center" data-aos="zoom-in" data-aos-delay="200">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-2xl text-white"></i>
                    </div>
                    <div class="counter text-4xl md:text-5xl font-bold text-cargo-orange mb-2" data-target="150">0</div>
                    <div class="text-lg text-gray-300">Expert Staff</div>
                </div>

                <div class="text-center" data-aos="zoom-in" data-aos-delay="300">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-trophy text-2xl text-white"></i>
                    </div>
                    <div class="counter text-4xl md:text-5xl font-bold text-cargo-orange mb-2" data-target="25">0</div>
                    <div class="text-lg text-gray-300">Industry Awards</div>
                </div>

                <div class="text-center" data-aos="zoom-in" data-aos-delay="400">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-handshake text-2xl text-white"></i>
                    </div>
                    <div class="counter text-4xl md:text-5xl font-bold text-cargo-orange mb-2" data-target="500">0</div>
                    <div class="text-lg text-gray-300">Global Partners</div>
                </div>
            </div>

            <!-- Additional Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12 mt-16">
                <div class="text-center" data-aos="zoom-in" data-aos-delay="500">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-red-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-globe-americas text-2xl text-white"></i>
                    </div>
                    <div class="counter text-4xl md:text-5xl font-bold text-cargo-orange mb-2" data-target="200">0</div>
                    <div class="text-lg text-gray-300">Countries Served</div>
                </div>

                <div class="text-center" data-aos="zoom-in" data-aos-delay="600">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-clock text-2xl text-white"></i>
                    </div>
                    <div class="counter text-4xl md:text-5xl font-bold text-cargo-orange mb-2" data-target="22">0</div>
                    <div class="text-lg text-gray-300">Years Experience</div>
                </div>

                <div class="text-center" data-aos="zoom-in" data-aos-delay="700">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-star text-2xl text-white"></i>
                    </div>
                    <div class="counter text-4xl md:text-5xl font-bold text-cargo-orange mb-2" data-target="98">0</div>
                    <div class="text-lg text-gray-300">Customer Satisfaction %</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Projects Section -->
    <section id="projects" class="py-16 md:py-24 bg-gradient-to-br from-gray-50 via-white to-blue-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-5xl font-bold mb-6">
                    Recent <span class="gradient-text">Projects</span>
                </h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">
                    Showcasing our latest successful logistics operations and global shipping achievements
                </p>
            </div>

            <!-- Projects Slider -->
            <div class="relative" data-aos="fade-up" data-aos-delay="200">
                <div id="projects-slider" class="overflow-hidden rounded-3xl shadow-2xl">
                    <div class="flex transition-transform duration-500 ease-in-out" id="projects-track">
                        <!-- Project 1 -->
                        <div class="w-full flex-shrink-0 relative">
                            <img src="images/warehouse.jpg" alt="Global Electronics Shipment" 
                                 class="w-full h-96 md:h-[500px] object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-8 left-8 text-white max-w-2xl">
                                <h3 class="text-2xl md:text-3xl font-bold mb-4">Global Electronics Distribution</h3>
                                <p class="text-lg mb-6">Successfully coordinated the shipment of 50,000 electronic devices across 15 countries within 72 hours, utilizing our air freight network and customs clearance expertise.</p>
                                <div class="flex flex-wrap gap-4">
                                    <span class="bg-cargo-blue/80 px-4 py-2 rounded-full text-sm font-medium">Air Freight</span>
                                    <span class="bg-cargo-orange/80 px-4 py-2 rounded-full text-sm font-medium">Express Delivery</span>
                                    <span class="bg-green-500/80 px-4 py-2 rounded-full text-sm font-medium">Customs Clearance</span>
                                </div>
                            </div>
                        </div>

                        <!-- Project 2 -->
                        <div class="w-full flex-shrink-0 relative">
                            <img src="images/ocean freight.jpg" alt="Ocean Freight Container Ship" 
                                 class="w-full h-96 md:h-[500px] object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-8 left-8 text-white max-w-2xl">
                                <h3 class="text-2xl md:text-3xl font-bold mb-4">Automotive Parts Ocean Freight</h3>
                                <p class="text-lg mb-6">Managed the transportation of 200 containers of automotive components from Germany to Asia, ensuring just-in-time delivery for manufacturing schedules.</p>
                                <div class="flex flex-wrap gap-4">
                                    <span class="bg-blue-500/80 px-4 py-2 rounded-full text-sm font-medium">Ocean Freight</span>
                                    <span class="bg-purple-500/80 px-4 py-2 rounded-full text-sm font-medium">Container Shipping</span>
                                    <span class="bg-yellow-500/80 px-4 py-2 rounded-full text-sm font-medium">Supply Chain</span>
                                </div>
                            </div>
                        </div>

                        <!-- Project 3 -->
                        <div class="w-full flex-shrink-0 relative">
                            <img src="images/pexels-tima-miroshnichenko-6169858.jpg" alt="Medical Supplies Emergency Delivery" 
                                 class="w-full h-96 md:h-[500px] object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-8 left-8 text-white max-w-2xl">
                                <h3 class="text-2xl md:text-3xl font-bold mb-4">Emergency Medical Supplies</h3>
                                <p class="text-lg mb-6">Coordinated urgent delivery of critical medical equipment to disaster-affected regions, working with international relief organizations for rapid deployment.</p>
                                <div class="flex flex-wrap gap-4">
                                    <span class="bg-red-500/80 px-4 py-2 rounded-full text-sm font-medium">Emergency Delivery</span>
                                    <span class="bg-cargo-blue/80 px-4 py-2 rounded-full text-sm font-medium">Air Freight</span>
                                    <span class="bg-green-500/80 px-4 py-2 rounded-full text-sm font-medium">Humanitarian Aid</span>
                                </div>
                            </div>
                        </div>

                        <!-- Project 4 -->
                        <div class="w-full flex-shrink-0 relative">
                            <img src="images/pexels-ethan-nguyen-63327081-9749472.jpg" alt="E-commerce Fulfillment Center" 
                                 class="w-full h-96 md:h-[500px] object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-8 left-8 text-white max-w-2xl">
                                <h3 class="text-2xl md:text-3xl font-bold mb-4">E-commerce Fulfillment Network</h3>
                                <p class="text-lg mb-6">Established a comprehensive fulfillment network for a major e-commerce platform, handling 1 million packages monthly across North America and Europe.</p>
                                <div class="flex flex-wrap gap-4">
                                    <span class="bg-cargo-orange/80 px-4 py-2 rounded-full text-sm font-medium">Warehousing</span>
                                    <span class="bg-purple-500/80 px-4 py-2 rounded-full text-sm font-medium">Last Mile</span>
                                    <span class="bg-teal-500/80 px-4 py-2 rounded-full text-sm font-medium">E-commerce</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <button id="projects-prev" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 w-12 h-12 rounded-full shadow-lg transition-all duration-300 hover:scale-110">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button id="projects-next" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 w-12 h-12 rounded-full shadow-lg transition-all duration-300 hover:scale-110">
                    <i class="fas fa-chevron-right"></i>
                </button>

                <!-- Dots Indicator -->
                <div class="flex justify-center mt-8 space-x-2">
                    <button class="w-3 h-3 rounded-full bg-cargo-blue transition-all duration-300" data-slide="0"></button>
                    <button class="w-3 h-3 rounded-full bg-gray-300 hover:bg-gray-400 transition-all duration-300" data-slide="1"></button>
                    <button class="w-3 h-3 rounded-full bg-gray-300 hover:bg-gray-400 transition-all duration-300" data-slide="2"></button>
                    <button class="w-3 h-3 rounded-full bg-gray-300 hover:bg-gray-400 transition-all duration-300" data-slide="3"></button>
                </div>
            </div>
        </div>
    </section>

    <!-- Fleet & Assets Section -->
    <section class="py-16 md:py-24 bg-gradient-to-br from-cargo-dark via-gray-900 to-cargo-dark text-white relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-20 left-20 w-64 h-64 bg-cargo-blue/5 rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-20 right-20 w-80 h-80 bg-cargo-orange/5 rounded-full blur-3xl animate-float" style="animation-delay: -4s;"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-5xl font-bold mb-6">
                    Our <span class="text-cargo-orange">Fleet</span> & Assets
                </h2>
                <p class="text-lg md:text-xl text-gray-300 max-w-3xl mx-auto">
                    State-of-the-art transportation assets and cutting-edge technology powering global logistics
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10">
                <!-- Cargo Ships -->
                <div class="group relative bg-gradient-to-br from-blue-900/30 to-blue-800/20 backdrop-blur-lg rounded-3xl p-8 border border-blue-500/20 shadow-2xl hover:shadow-3xl transition-all duration-500 hover:scale-105" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-blue-500 to-cyan-400 rounded-3xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-ship text-4xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Cargo Ships</h3>
                    </div>
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Fleet Size:</span>
                            <span class="font-bold text-cargo-orange">25 Vessels</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Capacity:</span>
                            <span class="font-bold text-white">Up to 20,000 TEU</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Routes:</span>
                            <span class="font-bold text-white">Global Coverage</span>
                        </div>
                    </div>
                    <div class="bg-blue-500/20 rounded-xl p-4">
                        <p class="text-sm text-blue-200">
                            Modern container ships equipped with advanced navigation systems and eco-friendly engines for efficient ocean freight operations.
                        </p>
                    </div>
                </div>

                <!-- Cargo Aircraft -->
                <div class="group relative bg-gradient-to-br from-orange-900/30 to-red-800/20 backdrop-blur-lg rounded-3xl p-8 border border-orange-500/20 shadow-2xl hover:shadow-3xl transition-all duration-500 hover:scale-105" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-orange-500 to-red-400 rounded-3xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-plane text-4xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Cargo Aircraft</h3>
                    </div>
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Fleet Size:</span>
                            <span class="font-bold text-cargo-orange">12 Aircraft</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Capacity:</span>
                            <span class="font-bold text-white">Up to 150 Tons</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Range:</span>
                            <span class="font-bold text-white">Intercontinental</span>
                        </div>
                    </div>
                    <div class="bg-orange-500/20 rounded-xl p-4">
                        <p class="text-sm text-orange-200">
                            Dedicated cargo aircraft including Boeing 747-8F and Airbus A330-200F for time-critical air freight services worldwide.
                        </p>
                    </div>
                </div>

                <!-- Heavy Trucks -->
                <div class="group relative bg-gradient-to-br from-green-900/30 to-emerald-800/20 backdrop-blur-lg rounded-3xl p-8 border border-green-500/20 shadow-2xl hover:shadow-3xl transition-all duration-500 hover:scale-105" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-green-500 to-emerald-400 rounded-3xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-truck text-4xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Heavy Trucks</h3>
                    </div>
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Fleet Size:</span>
                            <span class="font-bold text-cargo-orange">500+ Trucks</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Capacity:</span>
                            <span class="font-bold text-white">Up to 40 Tons</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Coverage:</span>
                            <span class="font-bold text-white">Continental</span>
                        </div>
                    </div>
                    <div class="bg-green-500/20 rounded-xl p-4">
                        <p class="text-sm text-green-200">
                            Modern fleet of heavy-duty trucks with GPS tracking, temperature control, and specialized trailers for various cargo types.
                        </p>
                    </div>
                </div>

                <!-- Warehouses -->
                <div class="group relative bg-gradient-to-br from-purple-900/30 to-indigo-800/20 backdrop-blur-lg rounded-3xl p-8 border border-purple-500/20 shadow-2xl hover:shadow-3xl transition-all duration-500 hover:scale-105" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-purple-500 to-indigo-400 rounded-3xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-warehouse text-4xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Warehouses</h3>
                    </div>
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Facilities:</span>
                            <span class="font-bold text-cargo-orange">50+ Centers</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Total Space:</span>
                            <span class="font-bold text-white">2M+ Sq Ft</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Technology:</span>
                            <span class="font-bold text-white">Automated</span>
                        </div>
                    </div>
                    <div class="bg-purple-500/20 rounded-xl p-4">
                        <p class="text-sm text-purple-200">
                            Climate-controlled warehouses with automated sorting systems, RFID tracking, and 24/7 security monitoring.
                        </p>
                    </div>
                </div>

                <!-- Rail Transport -->
                <div class="group relative bg-gradient-to-br from-yellow-900/30 to-orange-800/20 backdrop-blur-lg rounded-3xl p-8 border border-yellow-500/20 shadow-2xl hover:shadow-3xl transition-all duration-500 hover:scale-105" data-aos="fade-up" data-aos-delay="500">
                    <div class="text-center mb-6">
                        <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-yellow-500 to-orange-400 rounded-3xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-train text-4xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Rail Transport</h3>
                    </div>
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Rail Cars:</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Capacity:</span>
                            <span class="font-bold text-white">Up to 125 Tons</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">Networks:</span>
                            <span class="font-bold text-white">Major Routes</span>
                        </div>
                    </div>

                <!-- Technology Systems -->
                <div class="group relative bg-gradient-to-br from-teal-900/30 to-cyan-800/20 backdrop-blur-lg rounded-3xl p-8 border border-teal-500/20 shadow-2xl hover:shadow-3xl transition-all duration-500 hover:scale-105" data-aos="fade-up" data-aos-delay="600">
                                       <div class="text-center mb-6">
                        <div class="w-24
                            <i class="fas fa-satellite text-4xl text-white"></i>
                        <h3 class="text-2xl font-bold mb-4">Tech Systems</h3>
                    </div>
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">GPS Tracking:</span>
                            <span class="font-bold text-cargo-orange">Real-time</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">AI Systems:</span>
                            <span class="font-bold text-white">Advanced</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-300">IoT Sensors:</span>
                            <span class="font-bold text-white">10,000+</span>
                        </div>
                    </div>
                    <div class="bg-teal-500/20 rounded-xl p-4 mb-6">
                        <p class="text-sm text-teal-200">
                            Cutting-edge technology including AI-powered route optimization, IoT sensors, and blockchain tracking.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Members Section -->
    <section id="team" class="py-16 md:py-24 bg-gradient-to-br from-white via-blue-50 to-orange-50 relative overflow-hidden">
        <div class="absolute inset-0 world-map-bg"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-5xl font-bold mb-6">
                    Meet Our <span class="gradient-text">Expert Team</span>
                </h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">
                    Dedicated professionals with decades of combined experience in global logistics and supply chain management
                </p>
            </div>

            <!-- Team Slider -->
            <div class="relative" data-aos="fade-up" data-aos-delay="200">
                <div id="team-slider" class="overflow-hidden">
                    <div class="flex transition-transform duration-500 ease-in-out" id="team-track">
                        <!-- Team Member 1 -->
                        <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-4">
                            <div class="team-card bg-white rounded-3xl shadow-xl overflow-hidden">
                                <div class="relative">
                                    <img src="images/CEO 2.jpg" alt="Sarah Johnson - CEO" 
                                         class="w-full h-80 object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                    <div class="absolute bottom-6 left-6 text-white">
                                        <h3 class="text-2xl font-bold">Sarah Johnson</h3>
                                        <p class="text-lg text-blue-200">Chief Executive Officer</p>
                                    </div>
                                </div>
                                <div class="p-8">
                                    <p class="text-gray-600 mb-6">
                                        With 20+ years in logistics, Sarah leads our global operations with a vision for sustainable and innovative shipping solutions.
                                    </p>
                                    <div class="flex space-x-4">
                                        <a href="#" class="w-10 h-10 bg-cargo-blue text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                                            <i class="fab fa-linkedin text-sm"></i>
                                        </a>
                                        <a href="#" class="w-10 h-10 bg-gray-600 text-white rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors">
                                            <i class="fas fa-envelope text-sm"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Team Member 2 -->
                        <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-4">
                            <div class="team-card bg-white rounded-3xl shadow-xl overflow-hidden">
                                <div class="relative">
                                    <img src="images/CEO 1.jpg" alt="Michael Chen - CTO" 
                                         class="w-full h-80 object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                    <div class="absolute bottom-6 left-6 text-white">
                                        <h3 class="text-2xl font-bold">Michael Chen</h3>
                                        <p class="text-lg text-orange-200">Chief Technology Officer</p>
                                    </div>
                                </div>
                                <div class="p-8">
                                    <p class="text-gray-600 mb-6">
                                        Michael drives our digital transformation with cutting-edge tracking systems and AI-powered logistics optimization.
                                    </p>
                                    <div class="flex space-x-4">
                                        <a href="#" class="w-10 h-10 bg-cargo-blue text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                                            <i class="fab fa-linkedin text-sm"></i>
                                        </a>
                                        <a href="#" class="w-10 h-10 bg-gray-600 text-white rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors">
                                            <i class="fas fa-envelope text-sm"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Team Member 3 -->
                        <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-4">
                            <div class="team-card bg-white rounded-3xl shadow-xl overflow-hidden">
                                <div class="relative">
                                    <img src="images/shutterstock_249164311.jpg" alt="Emma Rodriguez - COO" 
                                         class="w-full h-80 object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                    <div class="absolute bottom-6 left-6 text-white">
                                        <h3 class="text-2xl font-bold">Emma Rodriguez</h3>
                                        <p class="text-lg text-green-200">Chief Operations Officer</p>
                                    </div>
                                </div>
                                <div class="p-8">
                                    <p class="text-gray-600 mb-6">
                                        Emma oversees our global operations network, ensuring seamless coordination across all transportation modes.
                                    </p>
                                    <div class="flex space-x-4">
                                        <a href="#" class="w-10 h-10 bg-cargo-blue text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                                            <i class="fab fa-linkedin text-sm"></i>
                                        </a>
                                        <a href="#" class="w-10 h-10 bg-gray-600 text-white rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors">
                                            <i class="fas fa-envelope text-sm"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Team Member 4 -->
                        <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-4">
                            <div class="team-card bg-white rounded-3xl shadow-xl overflow-hidden">
                                <div class="relative">
                                    <img src="images/hp-testimonial-3.jpg" alt="David Thompson - Head of Sales" 
                                         class="w-full h-80 object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                    <div class="absolute bottom-6 left-6 text-white">
                                        <h3 class="text-2xl font-bold">David Thompson</h3>
                                        <p class="text-lg text-purple-200">Head of Sales</p>
                                    </div>
                                </div>
                                <div class="p-8">
                                    <p class="text-gray-600 mb-6">
                                        David leads our global sales team, building strategic partnerships and delivering customized logistics solutions.
                                    </p>
                                    <div class="flex space-x-4">
                                        <a href="#" class="w-10 h-10 bg-cargo-blue text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                                            <i class="fab fa-linkedin text-sm"></i>
                                        </a>
                                        <a href="#" class="w-10 h-10 bg-gray-600 text-white rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors">
                                            <i class="fas fa-envelope text-sm"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Team Member 5 -->
                        <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-4">
                            <div class="team-card bg-white rounded-3xl shadow-xl overflow-hidden">
                                <div class="relative">
                                    <img src="images/woman.jpg" alt="Lisa Park - Head of Customer Service" 
                                         class="w-full h-80 object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                    <div class="absolute bottom-6 left-6 text-white">
                                        <h3 class="text-2xl font-bold">Lisa Park</h3>
                                        <p class="text-lg text-teal-200">Head of Customer Service</p>
                                    </div>
                                </div>
                                <div class="p-8">
                                    <p class="text-gray-600 mb-6">
                                        Lisa ensures exceptional customer experience with our 24/7 support team and multilingual service capabilities.
                                    </p>
                                    <div class="flex space-x-4">
                                        <a href="#" class="w-10 h-10 bg-cargo-blue text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                                            <i class="fab fa-linkedin text-sm"></i>
                                        </a>
                                        <a href="#" class="w-10 h-10 bg-gray-600 text-white rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors">
                                            <i class="fas fa-envelope text-sm"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Team Member 6 -->
                        <div class="w-full md:w-1/2 lg:w-1/3 flex-shrink-0 px-4">
                            <div class="team-card bg-white rounded-3xl shadow-xl overflow-hidden">
                                <div class="relative">
                                    <img src="images/taff.jpg" alt="James Wilson - Head of Security" 
                                         class="w-full h-80 object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                    <div class="absolute bottom-6 left-6 text-white">
                                        <h3 class="text-2xl font-bold">James Wilson</h3>
                                        <p class="text-lg text-red-200">Head of Security</p>
                                    </div>
                                </div>
                                <div class="p-8">
                                    <p class="text-gray-600 mb-6">
                                        James oversees cargo security protocols and risk management across our global transportation network.
                                    </p>
                                    <div class="flex space-x-4">
                                        <a href="#" class="w-10 h-10 bg-cargo-blue text-white rounded-full flex items-center justify-center hover:bg-blue-700 transition-colors">
                                            <i class="fab fa-linkedin text-sm"></i>
                                        </a>
                                        <a href="#" class="w-10 h-10 bg-gray-600 text-white rounded-full flex items-center justify-center hover:bg-gray-700 transition-colors">
                                            <i class="fas fa-envelope text-sm"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Navigation -->
                <div class="flex justify-center mt-12 space-x-4">
                    <button id="team-prev" class="bg-cargo-blue hover:bg-blue-700 text-white w-12 h-12 rounded-full shadow-lg transition-all duration-300 hover:scale-110">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button id="team-next" class="bg-cargo-blue hover:bg-blue-700 text-white w-12 h-12 rounded-full shadow-lg transition-all duration-300 hover:scale-110">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Footer -->
    <footer id="contact" class="bg-gradient-to-br from-cargo-dark via-gray-900 to-black text-white py-16 md:py-20 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-full h-full world-map-bg opacity-5"></div>
            <div class="absolute top-20 right-20 w-64 h-64 bg-cargo-orange/5 rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-20 left-20 w-80 h-80 bg-cargo-blue/5 rounded-full blur-3xl animate-float" style="animation-delay: -3s;"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Main Footer Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 md:gap-12 mb-12">
                <!-- Company Info -->
                <div class="lg:col-span-1" data-aos="fade-up">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="bg-gradient-to-r from-cargo-orange to-red-500 p-3 rounded-xl shadow-lg">
                            <i class="fas fa-shipping-fast text-white text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold bg-gradient-to-r from-blue-900 to-blue-600 bg-clip-text text-transparent">
                                Westway Express
                            </h3>
                            <p class="text-sm text-gray-400">Global Logistics Solutions</p>
                        </div>
                    </div>
                    <p class="text-gray-300 mb-6 leading-relaxed">
                        Your trusted partner for comprehensive logistics solutions with over 20 years of experience in global shipping and supply chain management.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-12 h-12 bg-gradient-to-r from-blue-800 to-blue-700 rounded-xl flex items-center justify-center hover:scale-110 transition-transform duration-300">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-gradient-to-r from-blue-800 to-blue-500 rounded-xl flex items-center justify-center hover:scale-110 transition-transform duration-300">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-gradient-to-r from-blue-800 to-blue-800 rounded-xl flex items-center justify-center hover:scale-110 transition-transform duration-300">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-gradient-to-r from-red-600 to-red-700 rounded-xl flex items-center justify-center hover:scale-110 transition-transform duration-300">
                            <i class="fab fa-youtube text-xl"></i>
                        </a>
                    </div>
                </div>

                <!-- Services -->
                <div data-aos="fade-up" data-aos-delay="100">
                    <h4 class="text-xl font-bold mb-6 text-cargo-orange">Our Services</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-300 flex items-center">
                            <i class="fas fa-ship mr-2 text-cargo-orange"></i>Ocean Freight
                        </a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-300 flex items-center">
                            <i class="fas fa-plane mr-2 text-cargo-orange"></i>Air Freight
                        </a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-300 flex items-center">
                            <i class="fas fa-truck mr-2 text-cargo-orange"></i>Road Transport
                        </a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-300 flex items-center">
                            <i class="fas fa-warehouse mr-2 text-cargo-orange"></i>Warehousing
                        </a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors duration-300 flex items-center">
                            <i class="fas fa-file-contract mr-2 text-cargo-orange"></i>Customs Clearance
                        </a></li>
                    </ul>
                </div>

                <!-- Quick Links -->
                <div data-aos="fade-up" data-aos-delay="200">
                    <h4 class="text-xl font-bold mb-6 text-cargo-orange">Quick Links</h4>
                    <ul class="space-y-3">
                        <li><a href="#home" class="text-gray-300 hover:text-white transition-colors duration-300 flex items-center">
                            <i class="fas fa-home mr-2 text-cargo-orange"></i>Home
                        </a></li>
                        <li><a href="#services" class="text-gray-300 hover:text-white transition-colors duration-300 flex items-center">
                            <i class="fas fa-cogs mr-2 text-cargo-orange"></i>Services
                        </a></li>
                        <li><a href="#why-choose-us" class="text-gray-300 hover:text-white transition-colors duration-300 flex items-center">
                            <i class="fas fa-star mr-2 text-cargo-orange"></i>Why Choose Us
                        </a></li>
                        <li><a href="#projects" class="text-gray-300 hover:text-white transition-colors duration-300 flex items-center">
                            <i class="fas fa-project-diagram mr-2 text-cargo-orange"></i>Projects
                        </a></li>
                        <li><a href="#team" class="text-gray-300 hover:text-white transition-colors duration-300 flex items-center">
                            <i class="fas fa-users mr-2 text-cargo-orange"></i>Our Team
                        </a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div data-aos="fade-up" data-aos-delay="300">
                    <h4 class="text-xl font-bold mb-6 text-cargo-orange">Contact Information</h4>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-map-marker-alt text-cargo-orange mt-1"></i>
                            <div>
                                <p class="text-gray-300">123 Business Avenue</p>
                                <p class="text-gray-300">Global Trade Center</p>
                                <p class="text-gray-300">New York, NY 10001</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-phone text-cargo-orange"></i>
                            <a href="tel:+15551234567" class="text-gray-300 hover:text-white transition-colors">
                                +1 (555) 123-4567
                            </a>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-cargo-orange"></i>
                            <a href="mailto:info@westwayexpress.com" class="text-gray-300 hover:text-white transition-colors">
                                info@westwayexpress.com
                            </a>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-clock text-cargo-orange"></i>
                            <div>
                                <p class="text-gray-300">24/7 Customer Support</p>
                                <p class="text-sm text-gray-400">Always here to help</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Photo Gallery -->
            <div class="mb-12" data-aos="fade-up" data-aos-delay="400">
                <h4 class="text-xl font-bold mb-6 text-cargo-orange text-center">Our Operations Gallery</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <div class="relative group overflow-hidden rounded-xl">
                        <img src="images/ocean freight.jpg" alt="Cargo Ship" 
                             class="w-full h-24 object-cover transition-transform duration-300 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <i class="fas fa-search-plus text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="relative group overflow-hidden rounded-xl">
                        <img src="images/air freight.jpg" alt="Air Freight" 
                             class="w-full h-24 object-cover transition-transform duration-300 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <i class="fas fa-search-plus text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="relative group overflow-hidden rounded-xl">
                        <img src="images/warehouse.jpg" alt="Warehouse" 
                             class="w-full h-24 object-cover transition-transform duration-300 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <i class="fas fa-search-plus text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="relative group overflow-hidden rounded-xl">
                        <img src="images/land transport.jpg" alt="Truck Fleet" 
                             class="w-full h-24 object-cover transition-transform duration-300 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <i class="fas fa-search-plus text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="relative group overflow-hidden rounded-xl">
                        <img src="images/ocean freight.jpg" alt="Container Port" 
                             class="w-full h-24 object-cover transition-transform duration-300 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <i class="fas fa-search-plus text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="relative group overflow-hidden rounded-xl">
                        <img src="images/pexels-gabby-k-7411970.jpg" alt="Global Network" 
                             class="w-full h-24 object-cover transition-transform duration-300 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <i class="fas fa-search-plus text-white text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Newsletter Signup -->
            <div class="bg-gradient-to-r from-cargo-blue/20 to-cargo-orange/20 backdrop-blur-lg rounded-3xl p-8 mb-12" data-aos="fade-up" data-aos-delay="500">
                <div class="text-center mb-6">
                    <h4 class="text-2xl font-bold mb-2">Stay Updated</h4>
                    <p class="text-gray-300">Subscribe to our newsletter for the latest logistics insights and updates</p>
                </div>
                <form class="flex flex-col md:flex-row gap-4 max-w-md mx-auto">
                    <input type="email" placeholder="Enter your email address" 
                           class="flex-1 px-6 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-cargo-orange">
                    <button type="submit" class="bg-gradient-to-r from-cargo-orange to-red-500 hover:from-orange-600 hover:to-red-600 text-white px-8 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                        Subscribe
                    </button>
                </form>
            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-gray-700 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-gray-900 text-center md:text-left mb-4 md:mb-0">
                        <p>&copy; 2024 Westway Express. All rights reserved.</p>
                        <p class="text-sm mt-1">Secured by Norton | ISO 9001:2015 Certified</p>
                    </div>
                    <div class="flex flex-wrap justify-center md:justify-end space-x-6 text-sm">
                        <a href="#" class="text-gray-800 hover:text-white transition-colors">Privacy Policy</a>
                        <a href="#" class="text-gray-800 hover:text-white transition-colors">Terms of Service</a>
                        <a href="#" class="text-gray-800 hover:text-white transition-colors">Cookie Policy</a>
                        <a href="#" class="text-gray-800 hover:text-white transition-colors">Sitemap</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scroll-to-top" class="scroll-to-top bg-gradient-to-r from-cargo-orange to-red-500 hover:from-orange-600 hover:to-red-600 text-white w-14 h-14 rounded-full shadow-2xl transition-all duration-300 transform hover:scale-110">
        <i class="fas fa-arrow-up text-xl"></i>
    </button>

    <!-- Scripts -->
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        // Hero Slider
        const heroSlides = [
            {
                background: 'images/illia-cherednychenko-178496-unsplash.jpg',
                title: 'Global Logistics Excellence',
                subtitle: 'Connecting the world through reliable shipping solutions',
                cta: 'Track Your Shipment'
            },
            {
                background: 'images/goh-rhy-yan-412929-unsplash.jpg',
                title: 'Fast & Secure Delivery',
                subtitle: 'Your cargo, our priority - delivered with precision',
                cta: 'Track Your Package'
            },
            {
                background: 'images/rhys-moult-328651-unsplash-1.jpg',
                title: 'Worldwide Network',
                subtitle: 'Seamless logistics across 200+ countries',
                cta: 'Track Shipment Now'
            }
        ];

        let currentSlide = 0;
        const heroSlider = document.getElementById('hero-slider');
        const heroContent = document.getElementById('hero-content');

        function updateHeroSlide() {
            const slide = heroSlides[currentSlide];
            
            // Update background
            heroSlider.style.backgroundImage = `url('${slide.background}')`;
            
            // Update content
            heroContent.innerHTML = `
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 animate-slide-up">
                    ${slide.title}
                </h1>
                <p class="text-xl md:text-2xl lg:text-3xl mb-12 text-gray-200 max-w-4xl mx-auto animate-fade-in" style="animation-delay: 0.3s;">
                    ${slide.subtitle}
                </p>
                <a href="track.php" class="inline-block bg-gradient-to-r from-cargo-orange to-red-500 hover:from-orange-600 hover:to-red-600 text-white px-12 py-5 rounded-2xl text-xl font-bold shadow-2xl transform hover:scale-105 transition-all duration-300 animate-scale-in" style="animation-delay: 0.6s;">
                    <i class="fas fa-search mr-3"></i>${slide.cta}
                </a>
            `;
            
            currentSlide = (currentSlide + 1) % heroSlides.length;
        }

        // Initialize hero slider
        updateHeroSlide();
        setInterval(updateHeroSlide, 5000);

        // Counter Animation
        function animateCounters() {
            const counters = document.querySelectorAll('.counter');
            
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target'));
                const increment = target / 100;
                let current = 0;
                
                const updateCounter = () => {
                    if (current < target) {
                        current += increment;
                        counter.textContent = Math.floor(current).toLocaleString();
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target.toLocaleString();
                    }
                };
                
                updateCounter();
            });
        }

        // Trigger counter animation when section is visible
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    counterObserver.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const statsSection = document.querySelector('.counter').closest('section');
        if (statsSection) {
            counterObserver.observe(statsSection);
        }

        // Projects Slider
        let currentProjectSlide = 0;
        const projectsTrack = document.getElementById('projects-track');
        const projectSlides = document.querySelectorAll('#projects-track > div');
        const totalProjectSlides = projectSlides.length;

        function updateProjectSlide() {
            const translateX = -currentProjectSlide * 100;
            projectsTrack.style.transform = `translateX(${translateX}%)`;
            
            // Update dots
            document.querySelectorAll('[data-slide]').forEach((dot, index) => {
                if (index === currentProjectSlide) {
                    dot.classList.remove('bg-gray-300');
                    dot.classList.add('bg-cargo-blue');
                } else {
                    dot.classList.remove('bg-cargo-blue');
                    dot.classList.add('bg-gray-300');
                }
            });
        }

        document.getElementById('projects-prev').addEventListener('click', () => {
            currentProjectSlide = (currentProjectSlide - 1 + totalProjectSlides) % totalProjectSlides;
            updateProjectSlide();
        });

        document.getElementById('projects-next').addEventListener('click', () => {
            currentProjectSlide = (currentProjectSlide + 1) % totalProjectSlides;
            updateProjectSlide();
        });

        // Dot navigation
        document.querySelectorAll('[data-slide]').forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentProjectSlide = index;
                updateProjectSlide();
            });
        });

        // Auto-advance projects slider
        setInterval(() => {
            currentProjectSlide = (currentProjectSlide + 1) % totalProjectSlides;
            updateProjectSlide();
        }, 6000);

        // Team Slider
        let currentTeamSlide = 0;
        const teamTrack = document.getElementById('team-track');
        const teamSlides = document.querySelectorAll('#team-track > div');
        const totalTeamSlides = teamSlides.length;
        const slidesToShow = window.innerWidth >= 1024 ? 3 : window.innerWidth >= 768 ? 2 : 1;

        function updateTeamSlide() {
            const slideWidth = 100 / slidesToShow;
            const translateX = -currentTeamSlide * slideWidth;
            teamTrack.style.transform = `translateX(${translateX}%)`;
        }

        document.getElementById('team-prev').addEventListener('click', () => {
            currentTeamSlide = Math.max(0, currentTeamSlide - 1);
            updateTeamSlide();
        });

        document.getElementById('team-next').addEventListener('click', () => {
            const maxSlide = totalTeamSlides - slidesToShow;
            currentTeamSlide = Math.min(maxSlide, currentTeamSlide + 1);
            updateTeamSlide();
        });

        // Auto-advance team slider
        setInterval(() => {
            const maxSlide = totalTeamSlides - slidesToShow;
            if (currentTeamSlide >= maxSlide) {
                currentTeamSlide = 0;
            } else {
                currentTeamSlide++;
            }
            updateTeamSlide();
        }, 4000);

        // Scroll to top functionality
        const scrollToTopBtn = document.getElementById('scroll-to-top');

        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('visible');
            } else {
                scrollToTopBtn.classList.remove('visible');
            }
        });

        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    
                    // Close mobile menu if open
                    const mobileMenu = document.getElementById('mobile-menu');
                    mobileMenu.classList.add('hidden');
                }
            });
        });

        // Google Translate Integration
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,es,fr,de,zh-CN,ar,hi,pt',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }

        // Dynamically load Google Translate script if not already loaded
        (function() {
          var gt = document.createElement('script');
          gt.type = 'text/javascript';
          gt.async = true;
          gt.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
          var s = document.getElementsByTagName('script')[0];
          s.parentNode.insertBefore(gt, s);
        })();

        // Language code mapping for Google Translate cookie
        const langMap = {
          'en': 'en',
          'es': 'es',
          'fr': 'fr',
          'de': 'de',
          'zh-CN': 'zh-CN',
          'ar': 'ar',
          'hi': 'hi',
          'pt': 'pt'
        };

        function translatePage(lang) {
          if (!langMap[lang]) return;
          var gtCombo = document.querySelector('select.goog-te-combo');
          if (gtCombo) {
            gtCombo.value = langMap[lang];
            gtCombo.dispatchEvent(new Event('change'));
          } else {
            setTimeout(function() { translatePage(lang); }, 500);
          }
        }
    </script>

    <!-- GSAP Animations -->
    <script>
        gsap.registerPlugin(ScrollTrigger);

        // Service cards animation
        gsap.from('.service-card', {
            duration: 0.8,
            y: 100,
            opacity: 0,
            stagger: 0.2,
            scrollTrigger: {
                trigger: '#services',
                start: 'top 80%',
                end: 'bottom 20%',
                toggleActions: 'play none none reverse'
            }
        });

        // Team cards animation
        gsap.from('.team-card', {
            duration: 0.8,
            scale: 0.8,
            opacity: 0,
            stagger: 0.3,
            scrollTrigger: {
                trigger: '#team',
                start: 'top 80%',
                end: 'bottom 20%',
                toggleActions: 'play none none reverse'
            }
        });

        // Responsive handling
        window.addEventListener('resize', () => {
            const newSlidesToShow = window.innerWidth >= 1024 ? 3 : window.innerWidth >= 768 ? 2 : 1;
            if (newSlidesToShow !== slidesToShow) {
                location.reload(); // Simple solution for responsive changes
            }
        });
    </script>
</body>
</html>
