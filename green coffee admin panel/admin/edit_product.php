<?php
include '../components/connection.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit;
}

// Update product
if (isset($_POST['update'])) {
    $post_id = $_GET['id'];

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);

    $content = $_POST['content'];
    $content = filter_var($content, FILTER_SANITIZE_STRING);

    if (isset($_POST['status'])) {
        $status = $_POST['status'];
        $status = filter_var($status, FILTER_SANITIZE_STRING);
    } else {
        $status = ""; // ডিফল্ট মান
    }

    $old_image = $_POST['old_image'];
    $new_image = $_FILES['image']['name'];
    $new_image_tmp = $_FILES['image']['tmp_name'];
    $image_folder = '../image/' . $new_image;

    if (!empty($new_image)) {
        // পুরনো ছবিটি মুছে ফেলা
        if (!empty($old_image) && file_exists('../image/' . $old_image)) {
            unlink('../image/' . $old_image);
        }

        // নতুন ছবি আপলোড করা
        move_uploaded_file($new_image_tmp, $image_folder);

        // ডাটাবেসে ছবি আপডেট
        $update_product = $conn->prepare("UPDATE `products` SET name = ?, price = ?, product_detail = ?, status = ?, image = ? WHERE id = ?");
        $update_product->execute([$name, $price, $content, $status, $new_image, $post_id]);
    } else {
        // ছবি ছাড়া ডাটাবেস আপডেট
        $update_product = $conn->prepare("UPDATE `products` SET name = ?, price = ?, product_detail = ?, status = ? WHERE id = ?");
        $update_product->execute([$name, $price, $content, $status, $post_id]);
    }

    if ($update_product->rowCount() > 0) {
        $success_msg[] = 'Product updated successfully';
    } else {
        $error_msg[] = 'Failed to update the product.';
    }
}



// Delete product
if (isset($_POST['delete'])) {
    $p_id = $_POST['product_id'];
    $p_id = filter_var($p_id, FILTER_SANITIZE_STRING);

    // Select product image
    $delete_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
    $delete_image->execute([$p_id]);

    $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);

    if ($fetch_delete_image['image'] != '') {
        $image_path = '../image/' . $fetch_delete_image['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    // Delete product
    $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
    $delete_product->execute([$p_id]);

    header('location:view_product.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="admin_style.css?v=<?php echo time(); ?>">
  <title>Green Coffee Admin Panel - Edit Products</title>
</head>
<body>

  <?php include '../components/admin_header.php'; ?>

  <div class="main">
    <div class="banner">
      <h1>Edit Products</h1>
    </div>

    <div class="title2">
      <a href="dashboard.php">Dashboard</a><span> / Edit Products</span>
    </div>

    <section class="read-post">
      <h1 class="heading">Edit Product</h1>

        <?php
        $post_id = $_GET['id'];

        $select_product = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
        $select_product->execute([$post_id]);

        if ($select_product->rowCount() > 0) {
            while ($fetch_product = $select_product->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="form-container">
          <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="old_image" value="<?= $fetch_product['image']; ?>">
            <input type="hidden" name="product_id" value="<?= $fetch_product['id']; ?>">

            <div class="input-field">
              <label for="">Update Status</label>
              <select name="status" id="">
                <option selected disabled value="<?= $fetch_product['status']; ?>">
                  <?= $fetch_product['status']; ?>
                </option>
                <option value="active">Active</option>
                <option value="deactive">Deactive</option>
              </select>
            </div>

            <div class="input-field">
              <label for="">Product Name</label>
              <input type="text" name="name" value="<?= $fetch_product['name']; ?>">
            </div>

            <div class="input-field">
              <label for="">Product Price</label>
              <input type="text" name="price" value="<?= $fetch_product['price']; ?>">
            </div>

            <div class="input-field">
              <label for="">Product Description</label>
              <textarea name="content"><?= $fetch_product['product_detail']; ?></textarea>
            </div>

            <div class="input-field">
              <label for="">Product Image</label>
              <input type="file" name="image" accept="image/*">
              <img src="../image/<?= $fetch_product['image']; ?>" alt="">
            </div>

            <div class="flex-btn">
              <button type="submit" name="update" class="btn">Update Product</button>
              <a href="view_product.php" class="btn">Go Back</a>
              <button type="submit" name="delete" class="btn">Delete Product</button>
            </div>

          </form>
        </div>
        <?php
            }
        } else {
            echo '<div class="empty">
                    <p>No product found! <br> 
                      <a href="add_products.php" style="margin-top:1.5rem;" class="btn">Add Product</a>
                    </p>
                  </div>';
        }
        ?>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script type="text/javascript" src="script.js"></script>
    <?php include '../components/alert.php'; ?>
  </div>
</body>
</html>
