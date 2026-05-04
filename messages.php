<?php
/**
 * Messaging system – view conversations, send new messages.
 */

session_start();
if(!isset($_SESSION['user_id'])) header("Location: login.php");
include 'DBConn.php';
$uid = $_SESSION['user_id'];

if(isset($_POST['send'])){
    $to = intval($_POST['receiver']);
    $msgText = $woodDb->real_escape_string($_POST['msg']);
    $woodDb->query("INSERT INTO tblMessage (sender_id, receiver_id, message_text) VALUES ($uid, $to, '$msgText')");
    header("Location: messages.php");
    exit;
}

$conversations = $woodDb->query("SELECT m.*, u.name as other_name FROM tblMessage m JOIN tblUser u ON (m.receiver_id = u.user_id OR m.sender_id = u.user_id) WHERE (m.sender_id=$uid OR m.receiver_id=$uid) AND u.user_id != $uid GROUP BY m.message_id ORDER BY m.sent_at DESC");
?>
<!DOCTYPE html>
<html>
<head><title>Messages - Pastimes Wood</title><link rel="stylesheet" href="style.css"></head>
<body>
<div class="wood-wrapper">
    <h2>Your Messages</h2>
    <?php while($msg = $conversations->fetch_assoc()): ?>
    <div class="message-bubble" style="padding:12px 18px; border-radius:24px; margin:10px 0; max-width:65%; <?php echo ($msg['sender_id'] == $uid) ? 'background:#d4b896; color:#3b2a1f; margin-left:auto;' : 'background:#f5e6d3;'; ?>">
        <strong><?php echo htmlspecialchars($msg['other_name']); ?>:</strong> <?php echo htmlspecialchars($msg['message_text']); ?>
        <small style="display:block; font-size:11px;"><?php echo $msg['sent_at']; ?></small>
    </div>
    <?php endwhile; ?>
    <hr>
    <h3>Send New Message</h3>
    <form method="post">
        <input type="number" name="receiver" placeholder="Recipient User ID" required>
        <textarea name="msg" rows="3" placeholder="Your message" required></textarea>
        <button type="submit" name="send" class="nude-btn">Send</button>
    </form>
    <p><a href="dashboard.php">Back</a></p>
</div>
</body>
</html>