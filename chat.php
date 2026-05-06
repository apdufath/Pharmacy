<?php include 'header.php'; ?>
<?php 
$user = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; // Use logged in user or fallback to Guest
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_msg'])) {
    $msg = $_POST['message']; 
    $stmt = $conn->prepare("INSERT INTO messages (sender, message) VALUES (?, ?)");
    $stmt->execute([$user, $msg]);
    header("Location: chat.php");
    exit();  
}
?>  
<style>    
.chat-container {  
    height: 400px; 
    overflow-y: auto;
    padding: 20px;
    background: rgba(0,0,0,0.3);  
    border-radius: 10px; 
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    gap: 15px;
} 
.chat-bubble {
    padding: 10px 15px;
    border-radius: 20px;
    max-width: 70%;
    background: rgba(255,255,255,0.1);
}
.chat-bubble.me {
    align-self: flex-end;
    background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
}
.chat-bubble.other {
    align-self: flex-start;
}
.chat-time {
    font-size: 0.7rem;
    color: rgba(255,255,255,0.5);
    margin-top: 5px;
    text-align: right;
}
</style>

<div class="page-header">
    <h1>Doctor & Staff Chat</h1>
</div>

<div class="glass" style="padding: 2rem; max-width: 800px; margin: 0 auto;">
    <div class="chat-container">
        <?php
        $stmt = $conn->query("SELECT * FROM messages ORDER BY id ASC");
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $isMe = ($row['sender'] == $user) ? 'me' : 'other';
            echo "<div class='chat-bubble {$isMe}'>
                    <strong style='font-size:0.8rem; display:block; margin-bottom:5px; color:" . ($isMe=='me'?'#fff':'var(--primary-color)') . "'>{$row['sender']}</strong>
                    {$row['message']}
                    <div class='chat-time'>{$row['timestamp']}</div>
                  </div>";
        }
        ?>
    </div>
    <form method="POST" style="display: flex; gap: 10px;">
        <input type="text" name="message" class="form-control" placeholder="Type a message..." required style="flex: 1;">
        <button type="submit" name="send_msg" class="btn"><i class="fas fa-paper-plane"></i> Send</button>
    </form>
</div>

<script>
// Auto scroll to bottom
var chatContainer = document.querySelector('.chat-container');
chatContainer.scrollTop = chatContainer.scrollHeight;
</script>

<?php include 'footer.php'; ?>
