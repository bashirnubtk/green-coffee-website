<?php
// ডাটাবেস কানেকশন ইনক্লুড করুন
include '../components/connection.php'; // connection.php ফাইলটি components ফোল্ডারে থাকলে

session_start();

// লগইন চেক করুন
if (!isset($_SESSION['admin_id'])) {
    header('location:login.php'); // লগইন না থাকলে লগইন পেজে রিডাইরেক্ট
    exit;
}

$admin_id = $_SESSION['admin_id'];

// প্রোফাইল ডেটা ফেচ করুন
$select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
$select_profile->execute([$admin_id]);

if ($select_profile->rowCount() > 0) {
    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
} else {
    echo "No profile found!";
    exit;
}

// প্রোফাইল আপডেট লজিক
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $profile_pic = $_FILES['profile']['name'];
    $profile_pic_tmp = $_FILES['profile']['tmp_name'];

    if ($profile_pic != "") {
        $profile_pic_folder = "../image/" . $profile_pic;
        move_uploaded_file($profile_pic_tmp, $profile_pic_folder);
        $update_query = $conn->prepare("UPDATE `admin` SET name = ?, email = ?, profile = ? WHERE id = ?");
        $update_query->execute([$name, $email, $profile_pic, $admin_id]);
    } else {
        $update_query = $conn->prepare("UPDATE `admin` SET name = ?, email = ? WHERE id = ?");
        $update_query->execute([$name, $email, $admin_id]);
    }

    if ($update_query) {
        echo "<script>alert('Profile updated successfully');</script>";
    } else {
        echo "<script>alert('Profile update failed');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Admin Panel</title>
    <style>
        /* প্রোফাইল পেজের জন্য সিএসএস */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .main {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .banner {
            background-color: #87a243;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .profile-container {
            padding: 20px;
        }
        .profile-details {
            text-align: center;
        }
        .profile-img img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 3px solid #87a243;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .btn {
            background-color: #87a243;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #84c95a;
        }
        .go-back-btn {
            background-color: #555;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .go-back-btn:hover {
            background-color: #333;
        }
    </style>
</head>
<body>

    <?php include '../components/admin_header.php'; ?>
 <!-- হেডার ফাইল ইনক্লুড করুন -->

    <div class="main">
        <div class="banner">
            <h1>Profile</h1>
        </div>
        
        <div class="profile-container">
            <div class="profile-details">
                <h2>Your Profile</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="profile-img">
                        <img src="../image/<?= $fetch_profile['profile']; ?>" alt="Profile Picture">
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" value="<?= $fetch_profile['name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="<?= $fetch_profile['email']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="profile">Profile Picture</label>
                        <input type="file" name="profile" id="profile">
                    </div>
                    <button type="submit" name="update" class="btn">Update Profile</button>
                    <a href="dashboard.php" class="go-back-btn">Go Back</a> <!-- গো ব্যাক বাটন -->
                </form>
            </div>
        </div>
    </div>

</body>
</html>