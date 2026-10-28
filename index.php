<?php
require_once 'functions.php';

// Search query
$search = trim($_GET['q'] ?? '');
$where = " WHERE status='published' ";
if ($search !== '') {
    $s = $conn->real_escape_string($search);
    $where .= " AND (title LIKE '%$s%' OR make LIKE '%$s%' OR model LIKE '%$s%') ";
}

// Fetch listings
$result = $conn->query("SELECT * FROM listings $where ORDER BY created_at DESC LIMIT 100");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CB Rental</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4e73df;
            --primary-dark: #2e59d9;
            --secondary: #f8f9fa;
            --dark: #343a40;
            --light: #fff;
            --gray: #6c757d;
            --border-radius: 8px;
            --shadow: 0 4px 12px rgba(0,0,0,0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--dark);
            background-color: var(--secondary);
        }
        
        a {
            text-decoration: none;
            color: inherit;
        }
        
        ul {
            list-style: none;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Header & Navigation */
        header {
            background-color: var(--light);
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
        }
        
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
        }
        
        .logo span {
            color: var(--dark);
        }
        
        .nav-links {
            display: flex;
            gap: 25px;
        }
        
        .nav-links a {
            font-weight: 500;
            transition: var(--transition);
            position: relative;
        }
        
        .nav-links a:hover, .nav-links a.active {
            color: var(--primary);
        }
        
        .nav-links a.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--primary);
        }
        
        .auth-buttons {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            padding: 8px 20px;
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: var(--transition);
            display: inline-block;
            cursor: pointer;
        }
        
        .btn-outline {
            border: 1px solid var(--primary);
            color: var(--primary);
        }
        
        .btn-outline:hover {
            background-color: var(--primary);
            color: var(--light);
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: var(--light);
            border: none;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
        }
        
        .mobile-toggle {
            display: none;
            font-size: 24px;
            cursor: pointer;
        }
        
        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            color: var(--light);
            padding: 100px 0;
            text-align: center;
        }
        
        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            font-weight: 700;
        }
        
        .hero p {
            font-size: 18px;
            max-width: 700px;
            margin: 0 auto 30px;
        }
        
        /* Search Form */
        .search-section {
            margin-top: -50px;
            position: relative;
            z-index: 100;
        }
        
        .search-card {
            background-color: var(--light);
            border-radius: var(--border-radius);
            padding: 30px;
            box-shadow: var(--shadow);
        }
        
        .search-card h2 {
            margin-bottom: 20px;
            color: var(--primary);
        }
        
        .search-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-family: 'Poppins', sans-serif;
        }
        
        .date-time-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        
        /* Features Section */
        .section {
            padding: 80px 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title span {
            color: var(--primary);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .section-title h2 {
            font-size: 36px;
            font-weight: 700;
            margin-top: 10px;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        
        .feature-card {
            background-color: var(--light);
            padding: 30px;
            border-radius: var(--border-radius);
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            background-color: rgba(78, 115, 223, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: var(--primary);
            font-size: 24px;
        }
        
        .feature-card h3 {
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        /* About Section */
        .about {
            background-color: var(--light);
        }
        
        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }
        
        .about-img {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }
        
        .about-img img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .about-text h2 {
            font-size: 36px;
            margin-bottom: 20px;
            font-weight: 700;
        }
        
        .about-text p {
            margin-bottom: 20px;
            color: var(--gray);
        }
        
        /* Services Section */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        
        .service-card {
            background-color: var(--light);
            padding: 30px;
            border-radius: var(--border-radius);
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }
        
        .service-card:hover {
            background-color: var(--primary);
            color: var(--light);
        }
        
        .service-card:hover .service-icon {
            background-color: rgba(255,255,255,0.2);
            color: var(--light);
        }
        
        .service-icon {
            width: 70px;
            height: 70px;
            background-color: rgba(78, 115, 223, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: var(--primary);
            font-size: 24px;
        }
        
        .service-card h3 {
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        /* CTA Section */
        .cta {
            background: linear-gradient(rgba(78, 115, 223, 0.9), rgba(78, 115, 223, 0.9)), url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80');
            background-size: cover;
            background-position: center;
            color: var(--light);
            text-align: center;
            padding: 80px 0;
        }
        
        .cta h2 {
            font-size: 36px;
            margin-bottom: 20px;
            font-weight: 700;
        }
        
        .cta p {
            max-width: 700px;
            margin: 0 auto 30px;
        }
        
        /* Testimonials */
        .testimonials {
            background-color: var(--secondary);
        }
        
        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .testimonial-card {
            background-color: var(--light);
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
        }
        
        .testimonial-text {
            margin-bottom: 20px;
            font-style: italic;
            color: var(--gray);
        }
        
        .testimonial-author {
            display: flex;
            align-items: center;
        }
        
        .author-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 15px;
        }
        
        .author-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .author-info h4 {
            font-weight: 600;
        }
        
        .author-info p {
            font-size: 14px;
            color: var(--gray);
        }
        
        /* Stats Section */
        .stats {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1486496572940-2bb2341fdbdf?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            color: var(--light);
            text-align: center;
            padding: 80px 0;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
        }
        
        .stat-card {
            padding: 30px;
        }
        
        .stat-number {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--primary);
        }
        
        .stat-text {
            font-size: 18px;
        }
        
        /* Listings Section */
        .listings {
            background-color: var(--light);
        }
        
        .listing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }
        
        .listing-card {
            background-color: var(--light);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }
        
        .listing-card:hover {
            transform: translateY(-5px);
        }
        
        .listing-img {
            height: 200px;
            overflow: hidden;
        }
        
        .listing-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }
        
        .listing-card:hover .listing-img img {
            transform: scale(1.05);
        }
        
        .listing-content {
            padding: 20px;
        }
        
        .listing-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .listing-price {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .listing-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 14px;
            color: var(--gray);
        }
        
        .listing-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .listing-btn {
            background-color: var(--primary);
            color: var(--light);
            padding: 8px 15px;
            border-radius: var(--border-radius);
            font-size: 14px;
            transition: var(--transition);
        }
        
        .listing-btn:hover {
            background-color: var(--primary-dark);
        }
        
        /* Footer */
        footer {
            background-color: var(--dark);
            color: var(--light);
            padding: 80px 0 20px;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            margin-bottom: 50px;
        }
        
        .footer-col h3 {
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .footer-col ul li {
            margin-bottom: 10px;
        }
        
        .footer-col ul li a {
            color: #adb5bd;
            transition: var(--transition);
        }
        
        .footer-col ul li a:hover {
            color: var(--light);
        }
        
        .footer-col p {
            color: #adb5bd;
            margin-bottom: 20px;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
        }
        
        .social-links a {
            width: 40px;
            height: 40px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }
        
        .social-links a:hover {
            background-color: var(--primary);
        }
        
        .contact-info li {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            color: #adb5bd;
        }
        
        .contact-info li i {
            margin-right: 10px;
            color: var(--primary);
        }
        
        .copyright {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: #adb5bd;
            font-size: 14px;
        }
        
        /* Responsive Styles */
        @media (max-width: 992px) {
            .nav-links, .auth-buttons {
                display: none;
            }
            
            .mobile-toggle {
                display: block;
            }
            
            .hero h1 {
                font-size: 36px;
            }
            
            .about-content {
                grid-template-columns: 1fr;
            }
            
            .about-img {
                order: -1;
            }
        }
        
        @media (max-width: 768px) {
            .hero {
                padding: 80px 0;
            }
            
            .section {
                padding: 60px 0;
            }
            
            .search-form {
                grid-template-columns: 1fr;
            }
            
            .date-time-group {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 576px) {
            .hero h1 {
                font-size: 28px;
            }
            
            .hero p {
                font-size: 16px;
            }
            
            .section-title h2 {
                font-size: 28px;
            }
            
            .search-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav class="navbar">
                <a href="index.html" class="logo">CB<span>Rental</span></a>
                
                <ul class="nav-links">
                    <li><a href="index.html" class="active">Home</a></li>
                    <li><a href="about.html">About</a></li>
                    <li><a href="services.html">Services</a></li>
                    <li><a href="pricing.html">Pricing</a></li>
                    <li><a href="car.php">Cars</a></li>
                    <li><a href="contact.html">Contact</a></li>
                     <a href="customer/register.php">Customer signup</a> |
    <a href="customer/login.php">Customer login</a> |
    <a href="admin/login.php">Admin</a>
                </ul>
                
                <div class="auth-buttons">
                    <a href="dealer/login.php" class="btn btn-outline">Dealer Login</a>
                    <a href="dealer/register.php" class="btn btn-primary">Dealer Register</a>
                </div>
                
                <div class="mobile-toggle">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>



    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Fast & Easy Way To Rent A Car</h1>
            <p>Find the perfect car for your needs at competitive prices. With a wide selection of vehicles and flexible rental options, we make car rental simple and convenient.</p>
            <a href="car.html" class="btn btn-primary">Browse Cars</a>
        </div>
    </section>

 

   <section class="search-section">
    <div class="container">
        <div class="search-card">
            <h2>Find Your Car</h2>
            <form class="search-form" method="get" action="">
                <div class="form-group">
                    <label for="q">Search</label>
                    <input type="text" id="q" name="q" class="form-control"
                           placeholder="Search by title, make, model"
                           value="<?php echo e($search); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>
</section>

    <!-- About Section -->
    <section class="section about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <span class="subheading">About us</span>
                    <h2>Welcome to CB Rental</h2>
                    <p>We are a premier car rental service dedicated to providing high-quality vehicles and exceptional customer service. With years of experience in the industry, we understand what travelers need for a comfortable and reliable journey.</p>
                    <p>Our fleet includes a wide variety of vehicles to suit every need and budget, from compact cars for city driving to spacious SUVs for family trips. We pride ourselves on maintaining our vehicles to the highest standards to ensure your safety and satisfaction.</p>
                    <a href="about.html" class="btn btn-primary">Learn More</a>
                </div>
                
                <div class="about-img">
                    <img src="https://images.unsplash.com/photo-1552519507-da3b142c6e3d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="About CB Rental">
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section">
        <div class="container">
            <div class="section-title">
                <span>What We Offer</span>
                <h2>Our Services</h2>
            </div>
            
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-car-side"></i>
                    </div>
                    <h3>Wedding Ceremony</h3>
                    <p>Make your special day even more memorable with our luxury wedding cars.</p>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-taxi"></i>
                    </div>
                    <h3>City Transfer</h3>
                    <p>Comfortable and reliable transportation for your city travel needs.</p>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-plane"></i>
                    </div>
                    <h3>Airport Transfer</h3>
                    <p>Timely airport pickups and drop-offs with professional drivers.</p>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3>Whole City Tour</h3>
                    <p>Explore the city at your own pace with our flexible rental options.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Listings Section -->
    <section class="section listings">
        <div class="container">
            <div class="section-title">
                <span>Available Cars</span>
                <h2>Featured Listings</h2>
            </div>
            
            <div class="listing-grid">
                <!-- Car listings will be dynamically loaded here -->
                <?php
                // PHP code to fetch and display car listings
                require_once 'functions.php';
                
                // Search query
                $search = trim($_GET['q'] ?? '');
                $where = " WHERE status='published' ";
                if ($search !== '') {
                    $s = $conn->real_escape_string($search);
                    $where .= " AND (title LIKE '%$s%' OR make LIKE '%$s%' OR model LIKE '%$s%') ";
                }
                
                // Fetch listings
                $result = $conn->query("SELECT * FROM listings $where ORDER BY created_at DESC LIMIT 6");
                
                while ($row = $result->fetch_assoc()):
                    $imgs = $row['images'] ? json_decode($row['images'], true) : [];
                    $thumb = $imgs[0] ?? '';
                    
                    if (!empty($thumb)) {
                        // Build path relative to project root for browser
                        $thumbUrl = str_replace('\\','/',$thumb);
                        
                        // Optional: check file exists on server
                        if (!file_exists(__DIR__ . '/' . $thumb)) {
                            $thumbUrl = 'assets/images/no_image.png';
                        }
                    } else {
                        $thumbUrl = 'assets/images/no_image.png';
                    }
                ?>
                <div class="listing-card">
                    <div class="listing-img">
                        <img src="<?php echo e($thumbUrl); ?>" alt="<?php echo e($row['title']); ?>">
                    </div>
                    <div class="listing-content">
                        <h3 class="listing-title"><?php echo e($row['title']); ?></h3>
                        <div class="listing-price">AED <?php echo number_format($row['price']); ?></div>
                        <div class="listing-details">
                            <span><i class="fas fa-calendar-alt"></i> <?php echo e($row['year']); ?></span>
                            <span><i class="fas fa-tachometer-alt"></i> <?php echo e($row['mileage']); ?> km</span>
                        </div>
                        <div class="listing-meta">
                            <span><?php echo e($row['make'].' '.$row['model']); ?></span>
                            <a href="listings/view.php?id=<?php echo $row['id']; ?>" class="listing-btn">View Details</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            
            <div style="text-align: center; margin-top: 40px;">
                <a href="car.html" class="btn btn-primary">View All Cars</a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="container">
            <h2>Do You Want To Earn With Us?</h2>
            <p>Join our network of drivers and start earning with CB Rental. We offer competitive rates, flexible schedules, and a supportive community.</p>
            <a href="driver-register.html" class="btn btn-primary">Become A Driver</a>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="section testimonials">
        <div class="container">
            <div class="section-title">
                <span>Testimonials</span>
                <h2>Happy Customers</h2>
            </div>
            
            <div class="testimonial-grid">
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "Renting from CB Rental was a seamless experience. The car was in excellent condition, and the pickup/drop-off process was incredibly smooth. Will definitely use again!"
                    </div>
                    <div class="testimonial-author">
                        <div class="author-img">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Customer">
                        </div>
                        <div class="author-info">
                            <h4>Robert Johnson</h4>
                            <p>Business Traveler</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "I needed a car for a weekend trip with my family. CB Rental had the perfect SUV at a great price. The vehicle was clean, comfortable, and reliable. Highly recommended!"
                    </div>
                    <div class="testimonial-author">
                        <div class="author-img">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Customer">
                        </div>
                        <div class="author-info">
                            <h4>Sarah Williams</h4>
                            <p>Family Vacationer</p>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial-card">
                    <div class="testimonial-text">
                        "As a frequent business traveler, I've used many rental services. CB Rental stands out for their professionalism, vehicle quality, and customer service. My go-to rental company now."
                    </div>
                    <div class="testimonial-author">
                        <div class="author-img">
                            <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Customer">
                        </div>
                        <div class="author-info">
                            <h4>Michael Chen</h4>
                            <p>Corporate Client</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">5+</div>
                    <div class="stat-text">Years Experience</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number">100+</div>
                    <div class="stat-text">Total Cars</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number">500+</div>
                    <div class="stat-text">Happy Customers</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-number">10+</div>
                    <div class="stat-text">Total Branches</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-col">
                    <h3>CB Rental</h3>
                    <p>Your trusted partner for car rental services. We offer a wide selection of vehicles at competitive prices with exceptional customer service.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="footer-col">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><a href="about.html">About Us</a></li>
                        <li><a href="services.html">Services</a></li>
                        <li><a href="pricing.html">Pricing</a></li>
                        <li><a href="car.html">Cars</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3>Support</h3>
                    <ul>
                        <li><a href="faq.html">FAQ</a></li>
                        <li><a href="privacy.html">Privacy Policy</a></li>
                        <li><a href="terms.html">Terms & Conditions</a></li>
                        <li><a href="contact.html">Contact Us</a></li>
                        <li><a href="support.html">Customer Support</a></li>
                    </ul>
                </div>
                
                <div class="footer-col">
                    <h3>Contact Info</h3>
                    <ul class="contact-info">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>123 Business Avenue, Downtown, City 10001</span>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <span>+1 (555) 123-4567</span>
                        </li>
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>info@cbrental.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; 2023 CB Rental. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-toggle').addEventListener('click', function() {
            const navLinks = document.querySelector('.nav-links');
            const authButtons = document.querySelector('.auth-buttons');
            
            navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
            authButtons.style.display = authButtons.style.display === 'flex' ? 'none' : 'flex';
            
            if (navLinks.style.display === 'flex') {
                navLinks.style.flexDirection = 'column';
                authButtons.style.flexDirection = 'column';
                navLinks.style.gap = '15px';
                authButtons.style.gap = '15px';
                navLinks.style.marginTop = '20px';
                authButtons.style.marginTop = '20px';
            }
        });
        
        // Set minimum dates for pickup and dropoff
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('pickup-date').min = today;
        document.getElementById('dropoff-date').min = today;
        
        // Update dropoff date min when pickup date changes
        document.getElementById('pickup-date').addEventListener('change', function() {
            document.getElementById('dropoff-date').min = this.value;
        });
    </script>
</body>
</html>