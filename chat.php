<?php
require "db.php";
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION["user"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["logout"])) {
        session_destroy();
        header("Location: login.php");
        exit;
    }
    
    if (isset($_POST["message"])) {
        $msg = $conn->real_escape_string($_POST["message"]);
        $conn->query("INSERT INTO messages (username, message) VALUES ('$username', '$msg')");
        header("Location: chat.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - Simple Chat</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .header h2 {
            margin: 0;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .logout-btn {
            padding: 8px 20px;
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid white;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .messages {
            flex: 1;
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            overflow-y: auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .message {
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 8px;
            background: #f8f9fa;
            border-left: 3px solid #667eea;
        }
        .message-user {
            font-weight: 600;
            color: #667eea;
            margin-bottom: 5px;
        }
        .message-text {
            color: #333;
            margin-bottom: 5px;
        }
        .message-time {
            font-size: 12px;
            color: #999;
        }
        .message-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            gap: 10px;
        }
        .message-input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .message-input:focus {
            outline: none;
            border-color: #667eea;
        }
        .send-btn {
            padding: 12px 30px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .send-btn:hover {
            background: #5568d3;
        }
        .no-messages {
            text-align: center;
            color: #999;
            padding: 40px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>💬 Simple Chat</h2>
        <div class="user-info">
            <span>Logged in as: <b><?php echo htmlspecialchars($username); ?></b></span>
            <form method="POST" style="margin: 0;">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>
    
    <div class="container">
        <div class="messages">
            <?php
            $result = $conn->query("SELECT * FROM messages ORDER BY id DESC");
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='message'>";
                    echo "<div class='message-user'>" . htmlspecialchars($row['username']) . "</div>";
                    echo "<div class='message-text'>" . htmlspecialchars($row['message']) . "</div>";
                    echo "<div class='message-time'>" . htmlspecialchars($row['time_sent']) . "</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='no-messages'>No messages yet. Be the first to send one!</div>";
            }
            ?>
        </div>
        
        <form method="POST" class="message-form">
            <input type="text" name="message" class="message-input" placeholder="Type your message..." required autocomplete="off">
            <button type="submit" class="send-btn">Send</button>
        </form>
    </div>
</body>
</html>
