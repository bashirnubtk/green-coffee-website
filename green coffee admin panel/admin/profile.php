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
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="admin_style.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include '../components/admin_header.php'; ?>

    <div class="main">
        <div class="banner">
            <h1>Profile</h1>
        </div>
        
        <div class="title2">
            <a href="dashboard.php">Dashboard</a><span> / Profile</span>
        </div>

        <section class="dashboard">
            <h1 class="heading">Your Profile</h1>

            <div class="box-container">
                <div class="box">
                    <div class="profile-img">
                        <img src="../image/<?= $fetch_profile['profile']; ?>" alt="Profile Picture">
                    </div>
                    <form method="POST" enctype="multipart/form-data">
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
                        <div class="flex-btn">
                            <button type="submit" name="update" class="btn">Update Profile</button>
                            <a href="dashboard.php" class="btn">Go Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <!-- SweetAlert CDN link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <!-- Custom JS link -->
    <script type="text/javascript" src="script.js"></script>
</body>
</html>