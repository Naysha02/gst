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
    <title>Car Listings - Featured Vehicles</title>
    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Section Title Styles */
        .section-title {
            text-align: center;
            margin-bottom: 50px;
            padding-top: 40px;
        }
        
        .section-title span {
            color: #e74c3c;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
        }
        
        .section-title h2 {
            font-size: 36px;
            margin-top: 10px;
            color: #2c3e50;
            font-weight: 700;
        }
        
        /* Listing Grid Styles */
        .listing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }
        
        .listing-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .listing-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .listing-image {
            height: 220px;
            width: 100%;
            overflow: hidden;
            position: relative;
        }
        
        .listing-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .listing-card:hover .listing-image img {
            transform: scale(1.05);
        }
        
        .listing-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #e74c3c;
            color: white;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .listing-content {
            padding: 20px;
        }
        
        .listing-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .listing-price {
            font-size: 22px;
            font-weight: 700;
            color: #e74c3c;
            margin-bottom: 15px;
        }
        
        .listing-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        
        .detail-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .detail-value {
            font-weight: 700;
            font-size: 16px;
            color: #2c3e50;
        }
        
        .detail-label {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 5px;
        }
        
        .listing-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        
        .btn:hover {
            background: #2980b9;
        }
        
        .listing-date {
            font-size: 12px;
            color: #95a5a6;
        }
        
        /* Search Bar Styles */
        .search-container {
            max-width: 600px;
            margin: 0 auto 40px;
        }
        
        .search-form {
            display: flex;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-radius: 50px;
            overflow: hidden;
        }
        
        .search-input {
            flex: 1;
            padding: 15px 25px;
            border: none;
            font-size: 16px;
            outline: none;
        }
        
        .search-button {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 0 30px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        
        .search-button:hover {
            background: #c0392b;
        }
        
        /* No Results Message */
        .no-results {
            text-align: center;
            padding: 40px;
            grid-column: 1 / -1;
        }
        
        .no-results h3 {
            color: #7f8c8d;
            margin-bottom: 15px;
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .listing-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
            
            .section-title h2 {
                font-size: 28px;
            }
        }
        
        @media (max-width: 480px) {
            .listing-grid {
                grid-template-columns: 1fr;
            }
            
            .search-form {
                flex-direction: column;
                border-radius: 10px;
            }
            
            .search-input {
                border-radius: 10px 10px 0 0;
            }
            
            .search-button {
                padding: 15px;
                border-radius: 0 0 10px 10px;
            }
        }
    </style>
</head>
<body>
   
          <!-- Listings Section -->
    <section class="section listings">
        <div class="container">
            <div class="section-title">
                <span>Available Cars</span>
                <h2>Featured Listings</h2>
            </div>

            <!-- Search Bar -->
            <div class="search-container">
                <form class="search-form" method="GET" action="">
                    <input type="text" name="q" class="search-input" placeholder="Search by make, model, or title..." value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    <button type="submit" class="search-button">Search</button>
                </form>
            </div>

            <div class="listing-grid">
                <?php
                // Fetch 6 listings for homepage
                $result = $conn->query("SELECT * FROM listings $where ORDER BY created_at DESC LIMIT 6");

                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                        $imgs = $row['images'] ? json_decode($row['images'], true) : [];
                        $thumb = $imgs[0] ?? '';

                        if (!empty($thumb)) {
                            $thumbUrl = str_replace('\\','/',$thumb);
                            if (!file_exists(__DIR__ . '/' . $thumb)) {
                                $thumbUrl = 'assets/images/no_image.png';
                            }
                        } else {
                            $thumbUrl = 'assets/images/no_image.png';
                        }
                ?>
                <div class="listing-card">
                    <div class="listing-image">
                        <img src="<?php echo htmlspecialchars($thumbUrl); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        <div class="listing-badge">Featured</div>
                    </div>
                    <div class="listing-content">
                        <h3 class="listing-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                        <div class="listing-price">AED <?php echo number_format($row['price']); ?></div>
                        <div class="listing-details">
                            <span><i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars($row['year']); ?></span>
                            <span><i class="fas fa-tachometer-alt"></i> <?php echo htmlspecialchars($row['mileage']); ?> km</span>
                        </div>
                        <div class="listing-meta">
                            <span><?php echo htmlspecialchars($row['make'].' '.$row['model']); ?></span>
                            <a href="listings/view.php?id=<?php echo $row['id']; ?>" class="btn">View Details</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-results">
                        <h3>No cars found matching your search criteria.</h3>
                        <p>Please try different search terms or <a href="?">browse all listings</a>.</p>
                    </div>
                <?php endif; ?>
            </div>

           
    </section>

                   
            
</body>
</html>