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