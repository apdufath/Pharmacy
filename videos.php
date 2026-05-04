<?php include 'header.php'; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_video'])) {
    $title = $_POST['title'];
    $url = $_POST['url'];
    
    // Extract youtube ID if it's a youtube link
    $embed_url = $url;
    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $match)) {
        $embed_url = "https://www.youtube.com/embed/" . $match[1];
    }

    $stmt = $conn->prepare("INSERT INTO videos (title, url) VALUES (?, ?)");
    $stmt->execute([$title, $embed_url]);
     
    header("Location: videos.php");
    exit();
} 
?>

<div class="page-header">
    <h1>Educational Videos</h1> 
</div>

<div class="flex-row">
    <div class="flex-col glass" style="padding: 2rem; flex: 1;">
        <h3 style="margin-bottom: 1.5rem; color: var(--primary-color);">Add Video Link</h3>
        <form method="POST">
            <div class="form-group">
                <label>Video Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label>YouTube Link or Video URL</label>
                <input type="url" name="url" class="form-control" required placeholder="https://youtube.com/watch?v=...">
            </div>
            <button type="submit" name="add_video" class="btn"><i class="fas fa-video"></i> Add Video</button>
        </form>
    </div>

    <div class="flex-col" style="flex: 2; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        <?php
        $stmt = $conn->query("SELECT * FROM videos ORDER BY id DESC");
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='glass' style='padding: 15px; text-align: center;'>
                    <h4 style='margin-bottom: 15px; color: var(--primary-color);'>{$row['title']}</h4>
                    <iframe width='100%' height='200' src='{$row['url']}' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen style='border-radius: 8px;'></iframe>
                  </div>";
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>
