<?php
session_start();
include_once('connection.php');
// تحديث حالة الاتصال عند تسجيل الدخول
$user_id = $_SESSION['id'];
$sql = "UPDATE tbl_user SET is_online =true WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

// تحديث حالة الاتصال عند تسجيل الخروج
$user_id = $_SESSION['id'];
$sql = "UPDATE tbl_user SET is_online =false WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
if (isset($_POST['login'])) {



    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM `tbl_user` WHERE `username`='$username' AND `password`='$password'";
    $result = mysqli_query($conn, $sql);

    if (empty($_POST['username']) && empty($_POST['password'])) {
        echo "<script>alert('Please Fill Username and Password');</script>";
        exit;
    } elseif (empty($_POST['password'])) {
        echo "<script>alert('Please Fill Password');</script>";
        exit;
    } elseif (empty($_POST['username'])) {
        echo "<script>alert('Please Fill Username);</script>";
        exit;
    } else {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
                 $id = $row['id'];
            $name = $row['name'];
            $username = $row['username'];
            $password = $row['password'];


            if ($username == $username && $password == $password) {
            	      $_SESSION['id'] = $id;
                $_SESSION['name'] = $name;
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                header('location:facebook/fFacebook2.php');
            }
        } else {
            echo "<script>alert('Invalid Username or Password');</script>";
            exit;
        }
    }
$sql = "
    SELECT
        u.id AS id,
        u.name,
        u.profile_image,
        m.message,
        m.sent_at
    FROM tbl_user u
    LEFT JOIN messages m ON u.id = m.sender_id
    GROUP BY u.id, m.id
    ORDER BY m.sent_at DESC
";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {
        $profile_image_path = '../uploads/' . $row["profile_image"];
        echo "<div class='message2'>";
        echo "<h4 class='name'>" . $row["name"]. "</h4>";
        echo "<img src='" . $profile_image_path . "' class='profile-image2'>";
        echo "<h6 class='message-text'>" . $row["message"]. "</h6>";
        echo "<small class='sent-at'>" . $row["sent_at"]. "</small>";
        echo "</div>";
    }
    
} else {
    echo "No messages found.";
}

}
