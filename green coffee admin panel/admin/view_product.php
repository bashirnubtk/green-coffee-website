<?php
include '../components/connection.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit;
}

/* delete product */
if (isset($_POST['delete'])) {
    if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
        $p_id = $_POST['product_id'];
        $p_id = filter_var($p_id, FILTER_SANITIZE_STRING);

        $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
        $delete_product->execute([$p_id]);

        $success_msg[] = 'Product deleted successfully';
    } else {
        $error_msg[] = 'Product ID is missing!';
    }
}
?>


  

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Boxicons CDN link -->
  <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="admin_style.css?v=<?php echo time(); ?>">
  <title>Green Coffee Admin Panel - All Products</title>
</head>
<body>

  <?php include '../components/admin_header.php'; ?>

  <div class="main">
    <div class="banner">
      <h1>All Products</h1>
    </div>

    <div class="title2">
      <a href="dashboard.php">Dashboard</a><span> / All Products</span>
    </div>

    <section class="show-post">
      <h1 class="heading">All Products</h1>

      <div class="box-container">

        <?php
          $select_products = $conn->prepare("SELECT * FROM `products`");
          $select_products->execute();

          if ($select_products->rowCount() > 0) {
              while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
        ?>
        
      <form action="" method="post" class="box">
      <input type="hidden" name="product_id" value="<?= $fetch_products['id']; ?>">
      <?php if ($fetch_products['image'] != '') { ?>
        <img src="../image/<?= $fetch_products['image']; ?>" class="image">
      <?php } ?>

    <div class="status" style="color: <?php if ($fetch_products['status'] ==
     'active') {echo 'green';} else {echo 'red';} ?>;">

        <?= $fetch_products['status']; ?>
    </div>

    <div class="price">$<?= $fetch_products['price']; ?>/-</div>
    <div class="title"><?= $fetch_products['name']; ?></div>
    <div class="fixed-btn"></div>

    <div class="flex-btn">
    <a href="edit_product.php?id=<?= $fetch_products['id']; ?>" class="btn">Edit</a>
    <button type="submit" name="delete" class="btn" onclick="return confirm('Delete this product?');">Delete</button>
    <a href="read_product.php?post_id=<?= $fetch_products['id']; ?>" class="btn">View</a>
    </div>


</form>
        <?php
              }
          } else {
              echo '
                <div class="empty">
                  <p>No product added yet! <br>. <a href="add_products.php"
                  style="margin: top 1.5em;" class="btn">add product</a></p>
                </div>
              ';
          }
        ?>

      </div>
    </section>

    <!-- SweetAlert CDN link -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    
    <!-- Custom JS link -->
    <script type="text/javascript" src="script.js"></script>

    <!-- Alert -->
    <?php include '../components/alert.php'; ?>
  </div>
</body>
</html>
