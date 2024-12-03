<?php
include_once('connection.php');

if(isset($_POST['register']))
{
    $name=$_POST['name'];
    $username=$_POST['username'];
    $pass=md5($_POST['password']);
    $profile_image=$_FILES['profile_image']['name'];
    $tmp_name=$_FILES['profile_image']['tmp_name'];
    $image_size=$_FILES['profile_image']['size'];
    $image_type=$_FILES['profile_image']['type'];

    // Check if username already exists
    $sql = "SELECT * FROM tbl_user WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Username already exists, show error message
        echo "Username already exists. Please choose a different username.";
    } else {
        // Validate image file
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        $image_extension = strtolower(pathinfo($profile_image, PATHINFO_EXTENSION));
        if(in_array($image_extension, $allowed_types) && $image_size <= 2097152) {
            $new_image_name = uniqid() . '.' . $image_extension;
            $upload_path = 'uploads/' . $new_image_name;
            if(move_uploaded_file($tmp_name, $upload_path)) {
                $sql   ="INSERT INTO tbl_user(name, username, password, profile_image) VALUES ('$name', '$username', '$pass', '$new_image_name')";
                $result=mysqli_query($conn,$sql);
                if($result){ 
                    header('location:index.php');
                    echo"<script>alert('New User Register Success');</script>";   
                }else{
                    die(mysqli_error($conn)) ;
                }
            } else {
                echo "Error uploading image.";
            }
        } else {
            echo "Invalid image file or file size exceeds 2MB.";
        }
    }
} 

$conn->close();
?>