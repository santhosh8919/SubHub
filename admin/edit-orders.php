<?php
session_start();
$pageTitle = 'Active Plans';
include './init.php';
if (isset($_SESSION['username'])) {
  $do = isset($_GET['do']) ? $_GET['do'] : 'dashboard';
  $ListOrders = $con->prepare("SELECT `orders`.`id`, `orders`.`orders_number`, `orders`.`customer_id`, `orders`.`product_name`, `orders`.`product_quantity`, `orders`.`product_price`, `orders`.`subtotal`, `orders`.`note_customer`, `orders`.`order_date`, `orders`.`renewal_date`, `orders`.`order_status`, `orders`.`subscription_status`, `customers`.`name_customer`, `customers`.`email_customer`, `customers`.`phone_customer` FROM `orders` INNER JOIN `customers` ON `orders`.`customer_id` = `customers`.`id` ORDER BY `orders`.`order_date` DESC");
  $ListOrders->execute();
  $Orders = $ListOrders->fetchAll(PDO::FETCH_ASSOC);
  if ($do == 'dashboard') {
?>
    <div class="orders">
      <div class="container">
        <h1><i class="fa-solid fa-credit-card"></i> Active Subscriptions&nbsp;<a class="btn btn-outline-primary" href="./edit-orders.php?do=add-new">New Subscription</a></h1>
        <div class="table-responsive">
          <?php if (isset($_SESSION['message'])) : ?>
            <div id="message">
              <?php echo $_SESSION['message']; ?>
            </div>
          <?php unset($_SESSION['message']);
          endif; ?>
          <table class="table table-bordered">
            <thead>
              <tr class="table-dark">
                <th>Subscription ID</th>
                <th>Subscriber</th>
                <th>Plan</th>
                <th>Amount</th>
                <th>Start Date</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($Orders as $order) : ?>
                <tr>
                  <td><code><?php echo $order['orders_number'] ?></code></td>
                  <td><?php echo $order['name_customer'] ?></td>
                  <td><strong><?php echo $order['product_name']; ?></strong></td>
                  <td>$<?php echo $order['subtotal'] ?>/mo</td>
                  <td><?php echo date('M j, Y', strtotime($order['order_date'])) ?></td>
                  <td>
                    <?php 
                    $status = $order['subscription_status'] ?? $order['order_status'];
                    $statusClass = match($status) {
                      'active' => 'bg-success',
                      'paused' => 'bg-warning',
                      'cancelled', 'expired' => 'bg-danger',
                      'pending' => 'bg-info',
                      default => 'bg-secondary'
                    };
                    ?>
                    <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($status) ?></span>
                  </td>
                  <td>
                    <form action="./edit-orders.php?do=action" method="post">
                      <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                      <div class="btn-group btn-group-sm">
                        <button type="submit" class="btn btn-success" name="btn_edit" title="Edit"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button type="submit" class="btn btn-info" name="btn_view" title="View"><i class="fa-solid fa-eye"></i></button>
                        <button type="submit" class="btn btn-danger" name="btn_delete" title="Cancel"><i class="fa-solid fa-trash"></i></button>
                      </div>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php
  } elseif ($do == 'add-new') {
  ?>
    <div class="order-new">
      <div class="container">
        <a class="btn btn-light my-2" href="./edit-orders.php"><i class="fa fa-backward" aria-hidden="true"></i>&nbsp;Back</a>
        <div class="col-md-6 mx-auto">
          <h1>New Order</h1>
          <form method="POST" action="./edit-orders.php?do=orders-true" enctype="multipart/form-data">
            <?php if (isset($_SESSION['message'])) : ?>
              <div id="message">
                <?php echo $_SESSION['message']; ?>
              </div>
            <?php unset($_SESSION['message']);
            endif; ?>
            <div class="form-group mb-3">
              <?php
              $ListProducts = $con->prepare("SELECT `id`, `name_product`, `description_product`, `price_product`, `currency`, `img_product`, `stock_product`, `created_at` FROM `products` WHERE `created_at`");
              $ListProducts->execute();
              $products = $ListProducts->fetchAll(PDO::FETCH_ASSOC);
              foreach ($products as $product) {
                $id = $product['id'];
                $pack = $product['name_product'];
                $price = $product['price_product'];
              ?>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="selected_plans[]" value="<?php echo $id; ?>" id="plan_<?php echo $id; ?>">
                  <label class="form-check-label" for="plan_<?php echo $id; ?>"><?php echo $pack; ?> - $<?php echo $price; ?></label>
                </div>
              <?php
              }
              ?>
            </div>
            <div class="form-group mb-3">
              <span class="label">Quantity</span>
              <input class="form-control" type="number" value="1" name="product_quantity" min="1">
            </div>
            <div class="form-group mb-3">
              <select class="form-select text-capitalize" name="customer_id">
                <option selected>Select customer</option>
                <?php
                $isCustomer = $con->prepare("SELECT * FROM `customers`");
                $isCustomer->execute();
                $customers = $isCustomer->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($customers)) {
                  foreach ($customers as $customer) {
                    echo '<option value="' . $customer['id'] . '">' . $customer['name_customer'] . '</option>';
                  }
                } else {
                  echo '<option value="...">...</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group mb-3">
              <select class="form-select text-capitalize" name="order_status">
                <option selected>pending payment</option>
                <?php
                $isStatus = $con->prepare("SELECT * FROM `status`");
                $isStatus->execute();
                $Status = $isStatus->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($Status)) {
                  foreach ($Status as $status) {
                    echo '<option value="' . $status['name'] . '">' . $status['name'] . '</option>';
                  }
                } else {
                  echo '<option value="...">...</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group mb-3">
              <span class="label">Note</span>
              <textarea name="note_customer" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary mb-3" name="order_true">
              <i class="fa-solid fa-floppy-disk"></i>&nbsp;Save
            </button>
          </form>
        </div>
      </div>
    </div>
    <?php
  } elseif ($do == 'action') {
    if (isset($_POST['btn_edit'])) {
      $id = $_POST['id'];
      $edit = ordersInfoView($con, $id);
    ?>
      <div class="edit-order">
        <div class="container">
          <a class="btn btn-light my-2" href="./edit-orders.php"><i class="fa fa-backward" aria-hidden="true"></i>&nbsp;Back</a>
          <div class="col-md-6 mx-auto">
            <h1>Edit Subscription: <?php echo $edit['orders_number']; ?></h1>
            <p class="text-muted">Subscriber: <?php echo $edit['name_customer']; ?></p>
            <form method="POST" action="edit-orders.php?do=orders-update" enctype="multipart/form-data">
              <input type="hidden" name="id" value="<?php echo $edit['id']; ?>">
              <input type="hidden" name="orders_number" value="<?php echo $edit['orders_number']; ?>">
              
              <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                  <i class="fa-solid fa-info-circle"></i> Subscription Status
                </div>
                <div class="card-body">
                  <div class="form-group mb-3">
                    <label class="form-label">Order Status</label>
                    <select class="form-select text-capitalize" name="order_status">
                      <option value="<?php echo $edit['order_status']; ?>" selected>
                        <?php echo ucfirst($edit['order_status']); ?> (Current)
                      </option>
                      <?php
                      $isStatus = $con->prepare("SELECT * FROM `status`");
                      $isStatus->execute();
                      $Status = $isStatus->fetchAll(PDO::FETCH_ASSOC);
                      if (!empty($Status)) {
                        foreach ($Status as $status) {
                          if ($status['name'] !== $edit['order_status']) {
                            echo '<option value="' . $status['name'] . '">' . ucfirst($status['name']) . '</option>';
                          }
                        }
                      }
                      ?>
                    </select>
                    <small class="text-muted">Changing this will also update the user-facing subscription status</small>
                  </div>
                  
                  <div class="alert alert-info">
                    <strong>Status Mapping:</strong><br>
                    <small>
                      • Completed/Processing → <span class="badge bg-success">Active</span><br>
                      • Pending → <span class="badge bg-warning">Paused</span><br>
                      • Cancelled/Failed → <span class="badge bg-danger">Cancelled</span>
                    </small>
                  </div>
                </div>
              </div>
              
              <div class="form-group mb-3">
                <label class="form-label">Note</label>
                <textarea name="note_customer" class="form-control" rows="3" placeholder="Add notes about this subscription..."><?php echo $edit['note_customer']; ?></textarea>
              </div>
              <button type="submit" class="btn btn-primary mb-3" name="order_update">
                <i class="fa-solid fa-save"></i> Update Subscription
              </button>
            </form>
          </div>
        </div>
      </div>
    <?php
    } elseif (isset($_POST['btn_view'])) {
      $id = $_POST['id'];
      $view = ordersInfoView($con, $id);
    ?>
      <div class="view-order">
        <div class="container">
          <a class="btn btn-light my-2" href="./edit-orders.php"><i class="fa fa-backward" aria-hidden="true"></i>&nbsp;Back</a>
          <div class="col-md-8 mx-auto">
            <h1>Order <?php echo $view['orders_number'] ?> - <strong class="p-1 text-capitalize rounded <?php
                                                                                                        $status = $view['order_status'];
                                                                                                        if ($status == 'pending') :
                                                                                                          echo 'text-bg-warning'; // Pending
                                                                                                        elseif ($status == 'cancelled') :
                                                                                                          echo 'text-bg-danger'; // Cancelled
                                                                                                        elseif ($status == 'processing') :
                                                                                                          echo 'text-bg-primary'; // Processing
                                                                                                        elseif ($status == 'pending payment') :
                                                                                                          echo 'text-bg-info'; // Pending Payment
                                                                                                        elseif ($status == 'completed') :
                                                                                                          echo 'text-bg-success'; // Completed
                                                                                                        elseif ($status == 'failed') :
                                                                                                          echo 'text-bg-danger'; // Failed
                                                                                                        endif;
                                                                                                        ?>">
                <?php echo $view['order_status'] ?>
              </strong>
            </h1>
            <div class="view-content">
              <h2>Billing details</h2>
              <ul>
                <li><strong>Full Name :</strong>
                  <?php echo $view['name_customer']; ?>
                </li>
                <li><strong>Email :</strong>
                  <?php echo $view['email_customer']; ?>
                </li>
                <li><strong>Phone :</strong>
                  <?php echo $view['phone_customer']; ?>
                </li>
                <li><strong>Note :</strong>
                  <?php echo $view['note_customer']; ?>
                </li>
              </ul>
              <div class="table-responsive">
                <table class="table table-bordered">
                  <tbody>
                    <tr class="text-bg-dark">
                      <td>Product</td>
                      <td>Quantity</td>
                      <td>Total</td>
                      <td>Action</td>
                    </tr>
                    <tr>
                      <td>
                        <?php echo $view['product_name']; ?>
                      </td>
                      <td>
                        <?php echo $view['product_quantity']; ?>
                      </td>
                      <td>
                        <?php echo $view['subtotal']; ?>
                      </td>
                      <td>
                        <form method="POST" action="edit-orders.php?do=receipt-orders">
                          <input type="hidden" name="id" value="<?php echo $view['id'] ?>">
                          <button type="submit" class="btn btn-light" name="order_receipt">
                            <i class="fa-solid fa-file-invoice"></i>&nbsp;Receipt
                          </button>
                        </form>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
<?php
    } elseif (isset($_POST['btn_delete'])) {
      $id = $_POST['id'];
      $stmt = $con->prepare("DELETE FROM orders WHERE `orders`.`id` = ?");
      $stmt->execute([$id]);
      show_message('Order deleted successfully', 'success');
      header('location: ' . $_SERVER['HTTP_REFERER']);
      exit();
    } else {
      header('location: edit-orders.php');
      exit();
    }
  } elseif ($do == 'orders-update') {
    if (isset($_POST['order_update'])) {
      $id = $_POST['id'];
      $orders_number = $_POST['orders_number'];
      $order_status = $_POST['order_status'];
      $note_customer = $_POST['note_customer'];
      
      // Map order_status to subscription_status
      $subscription_status = match(strtolower($order_status)) {
        'completed', 'processing' => 'active',
        'pending', 'pending payment' => 'paused',
        'cancelled', 'failed' => 'cancelled',
        default => 'active'
      };
      
      $stmt = $con->prepare("UPDATE `orders` SET `note_customer` = ?, `order_status` = ?, `subscription_status` = ? WHERE `id` = ?");
      $stmt->execute([$note_customer, $order_status, $subscription_status, $id]);
      show_message('Order ' . $orders_number . ' updated successfully', 'success');
      header('location: ' . $_SERVER['HTTP_REFERER']);
      exit();
    } else {
      header('location: edit-orders.php');
      exit();
    }
  } elseif ($do == 'orders-true') {
    if (isset($_POST['order_true'])) {
      $customer_id = $_POST['customer_id'];
      $selected_plans = $_POST['selected_plans'];
      $product_quantity = $_POST['product_quantity'];
      $note_customer = $_POST['note_customer'];
      $orders_number = generateOrderNumber($con);
      $order_status = $_POST['order_status'];
      $order_date = date('Y-m-d H:i:s');
      foreach ($selected_plans as $product_id) {
        $product = getProductById($product_id);
        $product_name = $product['name_product'];
        $product_price = $product['price_product'];
        $subtotal = $product_price * $product_quantity;
        $stmt = $con->prepare("INSERT INTO orders (orders_number, customer_id, product_name, product_quantity, product_price, subtotal, note_customer, order_date, order_status)
          VALUES (:orders_number, :customer_id, :product_name, :product_quantity, :product_price, :subtotal, :note_customer, :order_date, :order_status)");
        $stmt->execute([
          'orders_number' => $orders_number,
          'customer_id' => $customer_id,
          'product_name' => $product_name,
          'product_quantity' => $product_quantity,
          'product_price' => $product_price,
          'subtotal' => $subtotal,
          'note_customer' => $note_customer,
          'order_date' => $order_date,
          'order_status' => $order_status
        ]);
      }
      show_message('Order ' . $orders_number . ' have been added successfully.', 'success');
      header('location: ' . $_SERVER['HTTP_REFERER']);
    } else {
      header('location: edit-orders.php');
      exit();
    }
  } elseif ($do == 'receipt-orders') {
    if (isset($_POST['order_receipt'])) {
      $id = $_POST['id'];
      $stmt = $con->prepare("SELECT `orders`.*, `customers`.`name_customer`, `customers`.`email_customer`, `customers`.`phone_customer` FROM `orders` INNER JOIN `customers` ON `orders`.`customer_id` = `customers`.`id` WHERE `orders`.`id` = ? LIMIT 1");
      $stmt->execute([$id]);
      $order = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($order) {
?>
        <div class="receipt-orders">
          <div class="container">
            <a class="btn btn-light my-2 no-print" href="./edit-orders.php"><i class="fa fa-backward" aria-hidden="true"></i>&nbsp;Back</a>
            <div class="col-md-8 mx-auto border p-4" id="receipt">
              <div class="text-center mb-4">
                <h1>Order Receipt</h1>
                <p class="text-muted">Order #<?php echo $order['orders_number']; ?></p>
              </div>
              <hr>
              <div class="row mb-4">
                <div class="col-md-6">
                  <h5>Customer Details</h5>
                  <p><strong>Name:</strong> <?php echo $order['name_customer']; ?></p>
                  <p><strong>Email:</strong> <?php echo $order['email_customer']; ?></p>
                  <p><strong>Phone:</strong> <?php echo $order['phone_customer']; ?></p>
                </div>
                <div class="col-md-6 text-end">
                  <h5>Order Info</h5>
                  <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($order['order_date'])); ?></p>
                  <p><strong>Status:</strong> <span class="badge 
                    <?php
                    $status = $order['order_status'];
                    if ($status == 'pending') echo 'bg-warning';
                    elseif ($status == 'cancelled' || $status == 'failed') echo 'bg-danger';
                    elseif ($status == 'processing') echo 'bg-primary';
                    elseif ($status == 'pending payment') echo 'bg-info';
                    elseif ($status == 'completed') echo 'bg-success';
                    ?>">
                    <?php echo ucfirst($order['order_status']); ?>
                  </span></p>
                </div>
              </div>
              <hr>
              <h5>Order Details</h5>
              <table class="table table-bordered">
                <thead class="table-light">
                  <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><?php echo $order['product_name']; ?></td>
                    <td><?php echo $order['product_quantity']; ?></td>
                    <td><?php echo $order['product_price']; ?></td>
                    <td><?php echo $order['subtotal']; ?></td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr class="table-dark">
                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                    <td><strong><?php echo $order['subtotal']; ?></strong></td>
                  </tr>
                </tfoot>
              </table>
              <?php if (!empty($order['note_customer'])) : ?>
                <div class="mt-3">
                  <h5>Notes</h5>
                  <p><?php echo $order['note_customer']; ?></p>
                </div>
              <?php endif; ?>
              <hr>
              <div class="text-center mt-4">
                <p class="text-muted">Thank you for your order!</p>
                <button class="btn btn-primary no-print" onclick="window.print()"><i class="fa-solid fa-print"></i>&nbsp;Print Receipt</button>
              </div>
            </div>
          </div>
        </div>
        <style>
          @media print {
            .no-print { display: none !important; }
            .navbar, .sidebar, footer { display: none !important; }
            #receipt { border: none !important; }
          }
        </style>
<?php
      } else {
?>
        <div class="container">
          <div class="alert alert-warning text-center mt-5" role="alert">
            Order not found
          </div>
        </div>
<?php
        header('Refresh: 3; url=./edit-orders.php');
      }
    } else {
      header('location: edit-orders.php');
      exit();
    }
  } else {
    header('location: dashboard.php');
  }
} else {
  header('location: index.php');
  exit();
}
include $tpl . 'footer.php';
