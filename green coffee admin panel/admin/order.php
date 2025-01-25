<?php
include '../components/connection.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
    exit;
}

// Delete order
if (isset($_POST['delete_order'])) {
    $delete_id = $_POST['order_id'];
    $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

    $verify_delete = $conn->prepare("SELECT * FROM `orders` WHERE id = ?");
    $verify_delete->execute([$delete_id]);

    if ($verify_delete->rowCount() > 0) {
        $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
        $delete_order->execute([$delete_id]);
        echo "<script>
                alert('Order deleted successfully!');
              </script>";
    } else {
        echo "<script>
                alert('Order already deleted or does not exist.');
              </script>";
    }
}

// Update order
if (isset($_POST['update_order'])) {
    $order_id = $_POST['order_id'];
    $order_id = filter_var($order_id, FILTER_SANITIZE_STRING);

    $update_payment = $_POST['update_payment'];
    $update_payment = filter_var($update_payment, FILTER_SANITIZE_STRING);

    $update_pay = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
    $update_pay->execute([$update_payment, $order_id]);

    echo "<script>
            alert('Order updated successfully!');
          </script>";
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" type="text/css" href="admin_style.css?v=<?php echo time(); ?>">
  <title>Green Coffee Admin Panel - Order Placed Page</title>
  <style>
    .order-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }
    .box {
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 10px;
        width: 45%;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }
    .box .status {
        font-weight: bold;
        margin-bottom: 10px;
    }
    .box .status.paid {
        color: green;
    }
    .box .status.unpaid {
        color: red;
    }
  </style>
</head>
<body>
    <?php include '../components/admin_header.php'; ?>
    <div class="main">
        <div class="banner">
            <h1>Order Placed</h1>
        </div>
        
        <div class="title2">
            <a href="dashboard.php">Dashboard</a><span> / Order Placed</span>
        </div>

        <h1 class="heading">Total Order Placed</h1> 
        <section class="order-container">
            <?php
                $select_orders = $conn->prepare("SELECT * FROM `orders`");
                $select_orders->execute();

                if ($select_orders->rowCount() > 0) {
                    while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                        $status_class = ($fetch_orders['payment_status'] == 'Paid') ? 'paid' : 'unpaid';
            ?>
                <div class="box">
                    <div class="status <?= $status_class; ?>">
                        Status: <?= htmlspecialchars($fetch_orders['payment_status']); ?>
                    </div>
                    <div class="detail">
                        <p>User Name: <span><?= htmlspecialchars($fetch_orders['name']); ?></span></p>
                        <p>User ID: <span><?= htmlspecialchars($fetch_orders['id']); ?></span></p>
                        <p>Placed On: <span><?= htmlspecialchars($fetch_orders['date'] ?? 'Not specified'); ?></span></p>
                        <p>User Number: <span><?= htmlspecialchars($fetch_orders['number']); ?></span></p>
                        <p>User Email: <span><?= htmlspecialchars($fetch_orders['email']); ?></span></p>
                        <p>Total Price: <span><?= htmlspecialchars($fetch_orders['price']); ?>$</span></p>
                        <p>Method: <span><?= htmlspecialchars($fetch_orders['method']); ?></span></p>
                        <p>Address: <span><?= htmlspecialchars($fetch_orders['address']); ?></span></p>
                    </div>
                    <form action="" method="post">
                        <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                        <select name="update_payment" id="">
                            <option value="" disabled selected><?= htmlspecialchars($fetch_orders['payment_status']); ?></option>
                            <option value="pending">Pending</option>
                            <option value="complete">Complete</option>
                        </select>
                        <div class="flex-btn">
                            <button type="submit" name="update_order" class="btn">Update</button>
                            <button type="submit" name="delete_order" class="btn">Delete</button>
                        </div>
                    </form>
                </div>
            <?php
                    }
                } else {
                    echo '<div class="empty"><p>No orders placed yet.</p></div>';
                }
            ?>
        </section>
    </div>
    <!--sweetalert cdn linl -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    
    <!-- custom js link -->
    <script type="text/javascript" src="script.js"></script>

    <!-- alert -->
     <?php include '../components/alert.php'; ?>
</body>
</html>
