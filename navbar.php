<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?> 
<nav class="navbar glass"> 
    <div class="logo-container"> 
        <img src="assets/img/logo.png" alt="Pharmacy Logo" class="logo-img">  
        <h2 style="background: -webkit-linear-gradient(45deg, #00e5ff, #ff00cc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Neon Pharma</h2>
    </div>
    <ul class="nav-links">  
        <li><a href="index.php" class="<?php echo $currentPage == 'index.php' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="medicines.php" class="<?php echo $currentPage == 'medicines.php' || $currentPage == 'edit_medicine.php' ? 'active' : ''; ?>"><i class="fas fa-pills"></i> Medicines</a></li>
        <li><a href="sales.php" class="<?php echo $currentPage == 'sales.php' ? 'active' : ''; ?>"><i class="fas fa-shopping-cart"></i> Sales</a></li>
        <li><a href="warehouse.php" class="<?php echo $currentPage == 'warehouse.php' ? 'active' : ''; ?>"><i class="fas fa-warehouse"></i> Warehouse</a></li>
        <li><a href="chat.php" class="<?php echo $currentPage == 'chat.php' ? 'active' : ''; ?>"><i class="fas fa-comments"></i> Chat</a></li>
        <li><a href="videos.php" class="<?php echo $currentPage == 'videos.php' ? 'active' : ''; ?>"><i class="fas fa-video"></i> Videos</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="logout.php" style="color: #ff00cc;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        <?php else: ?>
            <li><a href="login.php" style="color: #00ff88;"><i class="fas fa-sign-in-alt"></i> Login</a></li> 
        <?php endif; ?>
    </ul>
</nav>

