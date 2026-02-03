<?php
session_start();
$pageTitle = 'My Subscriptions';
include './init.php';
$do = isset($_GET['do']) ? $_GET['do'] : 'cart';
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
  $cartItems = $_SESSION['cart'];
} else {
  $cartItems = array();
}
if ($do == 'cart') {
?>
  <div class="cart">
    <div class="container">
      <h1><i class="fa-solid fa-cart-shopping"></i> Selected Subscriptions</h1>
      <?php
      if (isset($_SESSION['message'])) : ?>
        <div id="message">
          <?php echo $_SESSION['message']; ?>
        </div>
      <?php unset($_SESSION['message']);
      endif;
      ?>
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr class="text-bg-light">
              <th>Subscription Plan</th>
              <th>Billing</th>
              <th>Price/mo</th>
              <th>Total</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($cartItems)) : ?>
              <?php foreach ($cartItems as $item) : ?>
                <tr>
                  <td><strong><?php echo $item['product_name']; ?></strong></td>
                  <td><span class="badge bg-secondary">Monthly</span></td>
                  <td>$<?php echo $item['product_price']; ?></td>
                  <td><strong>$<?php echo $item['quantity'] * $item['product_price']; ?></strong></td>
                  <td>
                    <a href="cart.php?do=remove-product&id=<?php echo $item['id']; ?>" class="btn btn-outline-danger btn-sm"><i class="fa-solid fa-trash"></i>&nbsp;Remove</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else : ?>
              <tr>
                <td colspan="5" class="text-center py-4">
                  <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
                  <p>No subscriptions selected yet.</p>
                  <a class="btn btn-primary" href="./index.php"><i class="fa-solid fa-plus"></i> Browse Subscriptions</a>
                </td>
              </tr>
            <?php endif ?>
          </tbody>
          <tfoot>
            <?php
            $subtotal = 0;
            if (!empty($cartItems)) {
              foreach ($cartItems as $item) {
                $subtotal += $item['quantity'] * $item['product_price'];
              }
            }
            ?>
            <tr class="table-dark">
              <td colspan="3">
                <strong>Monthly Total:</strong>
              </td>
              <td>
                <strong>
                  $<?php echo $subtotal; ?>
                </strong>
              </td>
              <td>
                <a href="cart.php?do=checkout" class="btn btn-success"><i class="fa-solid fa-credit-card"></i>&nbsp;Subscribe Now</a>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
<?php
} elseif ($do == 'checkout') {
?>
  <div class="checkout">
    <div class="container">
      <h1><i class="fa-solid fa-credit-card"></i> Complete Your Subscription</h1>
      <form method="post" action="cart.php?do=process-payment" autocomplete="off" class="py-3">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="card mb-3">
              <div class="card-header bg-primary text-white">
                <i class="fa-solid fa-user"></i> Subscriber Details
              </div>
              <div class="card-body">
                <?php
                if (isset($_SESSION['message'])) : ?>
                  <div id="message">
                    <?php echo $_SESSION['message']; ?>
                  </div>
                <?php unset($_SESSION['message']);
                endif;
                ?>
                <div class="form-group mb-3">
                  <label for="name_customer">Full Name *</label>
                  <input type="text" name="name_customer" id="name_customer" class="form-control" required="required" placeholder="John Doe">
                </div>
                <div class="form-group mb-3">
                  <label for="phone_customer">Phone *</label>
                  <input type="tel" name="phone_customer" id="phone_customer" class="form-control" required="required" placeholder="+1 234 567 8900">
                </div>
                <div class="form-group mb-3">
                  <label for="email_customer">Email Address *</label>
                  <input type="email" name="email_customer" id="email_customer" class="form-control" required="required" placeholder="john@example.com">
                </div>
              </div>
            </div>
            
            <!-- Payment Card Section -->
            <div class="card">
              <div class="card-header bg-success text-white">
                <i class="fa-solid fa-lock"></i> Payment Details
              </div>
              <div class="card-body">
                <div class="alert alert-info">
                  <i class="fa-solid fa-info-circle"></i> <strong>Demo Mode:</strong> Use any test card details below
                </div>
                <div class="form-group mb-3">
                  <label for="card_number">Card Number *</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-credit-card"></i></span>
                    <input type="text" name="card_number" id="card_number" class="form-control" required="required" placeholder="4242 4242 4242 4242" maxlength="19" pattern="[\d\s]{13,19}">
                  </div>
                  <small class="text-muted">Test card: 4242 4242 4242 4242</small>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group mb-3">
                      <label for="card_expiry">Expiry Date *</label>
                      <input type="text" name="card_expiry" id="card_expiry" class="form-control" required="required" placeholder="MM/YY" maxlength="5">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group mb-3">
                      <label for="card_cvv">CVV *</label>
                      <div class="input-group">
                        <input type="password" name="card_cvv" id="card_cvv" class="form-control" required="required" placeholder="123" maxlength="4">
                        <span class="input-group-text"><i class="fa-solid fa-shield-halved"></i></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group mb-3">
                  <label for="card_name">Name on Card *</label>
                  <input type="text" name="card_name" id="card_name" class="form-control" required="required" placeholder="JOHN DOE" style="text-transform: uppercase;">
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="card">
              <div class="card-header bg-dark text-white">
                <i class="fa-solid fa-receipt"></i> Order Summary
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table">
                    <thead class="table-light">
                      <tr>
                        <th>Subscription</th>
                        <th>Price/mo</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($cartItems as $item) : ?>
                        <tr>
                          <td><strong><?php echo $item['product_name']; ?></strong></td>
                          <td>$<?php echo $item['product_price']; ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                      <?php
                      $subtotal = 0;
                      if (!empty($cartItems)) {
                        foreach ($cartItems as $item) {
                          $subtotal += $item['quantity'] * $item['product_price'];
                        }
                      }
                      ?>
                      <tr class="table-dark">
                        <td><strong>Monthly Total:</strong></td>
                        <td><strong>$<?php echo number_format($subtotal, 2); ?></strong></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
                
                <div class="d-grid gap-2 mt-3">
                  <button type="submit" name="process_payment" class="btn btn-success btn-lg">
                    <i class="fa-solid fa-lock"></i>&nbsp;Pay $<?php echo number_format($subtotal, 2); ?> &amp; Subscribe
                  </button>
                </div>
                
                <div class="text-center mt-3">
                  <small class="text-muted">
                    <i class="fa-solid fa-shield-check"></i> Secure payment · Cancel anytime
                  </small>
                </div>
                
                <div class="mt-3 text-center">
                  <img src="https://img.shields.io/badge/Visa-blue?style=flat&logo=visa" alt="Visa">
                  <img src="https://img.shields.io/badge/Mastercard-red?style=flat&logo=mastercard" alt="Mastercard">
                  <img src="https://img.shields.io/badge/Amex-blue?style=flat&logo=americanexpress" alt="Amex">
                  <img src="https://img.shields.io/badge/PayPal-blue?style=flat&logo=paypal" alt="PayPal">
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
<?php
} elseif ($do == 'process-payment') {
  // Dummy Payment Processing
  if (isset($_POST['process_payment'])) {
    $name_customer = $_POST['name_customer'];
    $phone_customer = $_POST['phone_customer'];
    $email_customer = $_POST['email_customer'];
    $card_number = $_POST['card_number'];
    $card_expiry = $_POST['card_expiry'];
    $card_cvv = $_POST['card_cvv'];
    $card_name = $_POST['card_name'];
    
    // Store payment info in session for confirmation page
    $_SESSION['payment_info'] = [
      'name' => $name_customer,
      'phone' => $phone_customer,
      'email' => $email_customer,
      'card_last4' => substr(str_replace(' ', '', $card_number), -4),
      'card_name' => $card_name
    ];
    
    // Simulate payment processing delay
    sleep(1);
    
    // Simulate payment validation (accept all cards starting with 4 or test cards)
    $card_clean = str_replace(' ', '', $card_number);
    $payment_success = true;
    
    // Simulate some card failures for testing
    if ($card_clean === '4000000000000002') {
      $payment_success = false;
      $_SESSION['payment_error'] = 'Card declined. Please try another card.';
    } elseif (strlen($card_clean) < 13) {
      $payment_success = false;
      $_SESSION['payment_error'] = 'Invalid card number.';
    }
    
    if ($payment_success) {
      // Process the subscription
      $orders_number = generateOrderNumber($con);
      $note_customer = 'Paid via card ending in ' . substr($card_clean, -4);
      
      // Create customer
      $customers = $con->prepare("INSERT INTO `customers`(`name_customer`, `email_customer`, `phone_customer`) VALUES (?, ?, ?)");
      $customers->execute([$name_customer, $email_customer, $phone_customer]);
      $customer_id = $con->lastInsertId();
      $_SESSION['customer_id'] = $customer_id;
      
      // Create subscriptions
      $renewal_date = date('Y-m-d', strtotime('+1 month'));
      foreach ($_SESSION['cart'] as $cartItems => $item) {
        $product_name = $item['product_name'];
        $product_quantity = $item['quantity'];
        $product_price = $item['product_price'];
        $subtotal = $product_quantity * $product_price;
        $orders = $con->prepare("INSERT INTO `orders`(`orders_number`, `customer_id`, `product_name`, `product_quantity`, `product_price`, `subtotal`, `note_customer`, `renewal_date`, `order_status`, `subscription_status`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'completed', 'active')");
        $orders->execute([$orders_number, $customer_id, $product_name, $product_quantity, $product_price, $subtotal, $note_customer, $renewal_date]);
      }
      
      // Calculate total for confirmation
      $_SESSION['payment_total'] = 0;
      foreach ($_SESSION['cart'] as $item) {
        $_SESSION['payment_total'] += $item['quantity'] * $item['product_price'];
      }
      $_SESSION['orders_number'] = $orders_number;
      $_SESSION['renewal_date'] = $renewal_date;
      
      unset($_SESSION['cart']);
      header('Location: cart.php?do=payment-success');
      exit();
    } else {
      header('Location: cart.php?do=payment-failed');
      exit();
    }
  } else {
    header('location: cart.php');
    exit();
  }
} elseif ($do == 'payment-success') {
?>
  <div class="payment-success">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card border-success mt-5">
            <div class="card-header bg-success text-white text-center">
              <i class="fa-solid fa-circle-check fa-3x mb-2"></i>
              <h2>Payment Successful!</h2>
            </div>
            <div class="card-body text-center">
              <div class="alert alert-success">
                <h4>Thank you for subscribing, <?php echo $_SESSION['payment_info']['name'] ?? 'Customer'; ?>!</h4>
                <p>Your subscriptions are now active.</p>
              </div>
              
              <div class="row text-start mb-4">
                <div class="col-md-6">
                  <h5><i class="fa-solid fa-receipt"></i> Order Details</h5>
                  <ul class="list-unstyled">
                    <li><strong>Order ID:</strong> <?php echo $_SESSION['orders_number'] ?? 'N/A'; ?></li>
                    <li><strong>Amount Paid:</strong> $<?php echo number_format($_SESSION['payment_total'] ?? 0, 2); ?></li>
                    <li><strong>Payment Method:</strong> Card ending in <?php echo $_SESSION['payment_info']['card_last4'] ?? '****'; ?></li>
                  </ul>
                </div>
                <div class="col-md-6">
                  <h5><i class="fa-solid fa-calendar"></i> Subscription Info</h5>
                  <ul class="list-unstyled">
                    <li><strong>Status:</strong> <span class="badge bg-success">Active</span></li>
                    <li><strong>Next Billing:</strong> <?php echo date('F j, Y', strtotime($_SESSION['renewal_date'] ?? '+1 month')); ?></li>
                    <li><strong>Email:</strong> <?php echo $_SESSION['payment_info']['email'] ?? 'N/A'; ?></li>
                  </ul>
                </div>
              </div>
              
              <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                <a href="index.php" class="btn btn-primary btn-lg"><i class="fa-solid fa-home"></i> Back to Home</a>
                <a href="tracking.php" class="btn btn-outline-success btn-lg"><i class="fa-solid fa-list-check"></i> View My Subscriptions</a>
              </div>
            </div>
            <div class="card-footer text-center text-muted">
              <small>A confirmation email has been sent to <?php echo $_SESSION['payment_info']['email'] ?? 'your email'; ?></small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php
  // Clear payment session data
  unset($_SESSION['payment_info']);
  unset($_SESSION['payment_total']);
  unset($_SESSION['orders_number']);
  unset($_SESSION['renewal_date']);
} elseif ($do == 'payment-failed') {
?>
  <div class="payment-failed">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card border-danger mt-5">
            <div class="card-header bg-danger text-white text-center">
              <i class="fa-solid fa-circle-xmark fa-3x mb-2"></i>
              <h2>Payment Failed</h2>
            </div>
            <div class="card-body text-center">
              <div class="alert alert-danger">
                <p><?php echo $_SESSION['payment_error'] ?? 'Your payment could not be processed.'; ?></p>
              </div>
              <p>Please check your card details and try again.</p>
              <div class="d-grid gap-2">
                <a href="cart.php?do=checkout" class="btn btn-primary"><i class="fa-solid fa-rotate-left"></i> Try Again</a>
                <a href="cart.php" class="btn btn-outline-secondary"><i class="fa-solid fa-cart-shopping"></i> Back to Cart</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php
  unset($_SESSION['payment_error']);
} elseif ($do == 'place-order') {
  if (isset($_POST['place_order'])) {
    $name_customer = $_POST['name_customer'];
    $phone_customer = $_POST['phone_customer'];
    $email_customer = $_POST['email_customer'];
    $note_customer = $_POST['note_customer'];
    $orders_number = generateOrderNumber($con);
    if (empty($name_customer) || empty($phone_customer) || empty($email_customer)) {
      show_message('Please fill in all required fields.', 'danger');
      header('location: ' . $_SERVER['HTTP_REFERER']);
      exit();
    } else {
      $customers = $con->prepare("INSERT INTO `customers`(`name_customer`, `email_customer`, `phone_customer`) VALUES (?, ?, ?)");
      $customers->execute([$name_customer, $email_customer, $phone_customer]);
      $customer_id = $con->lastInsertId();
      $_SESSION['customer_id'] = $customer_id;
      foreach ($_SESSION['cart'] as $cartItems => $item) {
        $product_name = $item['product_name'];
        $product_quantity = $item['quantity'];
        $product_price = $item['product_price'];
        $subtotal = $product_quantity * $product_price;
        $orders = $con->prepare("INSERT INTO `orders`(`orders_number`, `customer_id`, `product_name`, `product_quantity`, `product_price`, `subtotal`, `note_customer`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $orders->execute([$orders_number, $customer_id, $product_name, $product_quantity, $product_price, $subtotal, $note_customer]);
      }
    }
    unset($_SESSION['cart']);
    header('Location: cart.php?do=order-received');
    exit();
  } else {
    header('location: cart.php');
    exit();
  }
} elseif ($do == 'order-received') {
?>
  <div class="order-received">
    <div class="container">
      <h1>Thank you. Your order has been received.</h1>
      <?php if (isset($_SESSION['customer_id'])) {
        $stmt = $con->prepare("SELECT * FROM `customers` WHERE `id` = ?");
        $stmt->execute([$_SESSION['customer_id']]);
        $customer = $stmt->fetch();
        $order = $con->prepare("SELECT * FROM `orders` WHERE `customer_id` = ? ORDER BY `id` DESC");
        $order->execute([$customer['id']]);
        $orderCount = $order->rowCount();
        if ($orderCount > 0) {
      ?>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Order number</th>
                  <th>Date</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = $order->fetch()) {
                  $orders_number = $row['orders_number'];
                  $order_date = date("F j, Y", strtotime($row['order_date']));
                  $total_price = $row['subtotal'];
                ?>
                  <tr>
                    <td>
                      <?php echo $orders_number ?>
                    </td>
                    <td>
                      <?php echo $order_date ?>
                    </td>
                    <td>
                      <?php echo number_format($total_price, 2) . '&nbsp;' . $row['currency']; ?>
                    </td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
          </div>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead class="text-bg-light">
                <tr>
                  <th>Order details</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $order->execute([$customer['id']]);
                while ($row = $order->fetch()) {
                ?>
                  <tr>
                    <td>
                      <?php echo $row['product_name'] . ' x ' . $row['product_quantity'] ?>
                    </td>
                    <td>
                      <?php echo number_format($row['subtotal'], 2) . '&nbsp;' . $row['currency']; ?>
                    </td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
          </div>
        <?php
        } else {
        ?>
          <div class="container">
            <div class="alert alert-warning text-center mt-5" role="alert">
              No orders found for this customer.
            </div>
          </div>
      <?php
        }
      } else {
        header('location: cart.php');
        exit();
      }
      ?>
    </div>
  </div>
<?php
} elseif ($do == 'add-cart') {
  if (isset($_POST['add_to_cart'])) {
    $id = $_POST['id'];
    $quantity = $_POST['quantity'];
    $Details = $con->prepare("SELECT * FROM `products` WHERE `id` = ? LIMIT 1");
    $Details->execute([$id]);
    $cart = $Details->fetch(PDO::FETCH_ASSOC);
    $product_id = $cart['id'];
    $product_name = $cart['name_product'];
    $product_price = $cart['price_product'];
    $cart_item = array(
      'id' => $product_id,
      'product_name' => $product_name,
      'quantity' => $quantity,
      'product_price' => $product_price
    );
    $_SESSION['cart'][] = $cart_item;
    show_message('Add ' . $product_name . ' to cart successfully.', 'success');
    header('location: ' . $_SERVER['HTTP_REFERER']);
    exit();
  } else {
    header('location: index.php');
    exit();
  }
} elseif ($do == 'remove-product') {
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $name = $con->prepare("SELECT * FROM `products` WHERE `id` = ? LIMIT 1");
    $name->execute([$id]);
    $cart = $name->fetch(PDO::FETCH_ASSOC);
    $products = $cart['name_product'];
    foreach ($_SESSION['cart'] as $key => $item) {
      if ($item['id'] == $id) {
        unset($_SESSION['cart'][$key]);
        break;
      }
    }
    show_message('Remove ' . $products . ' from cart successfully.', 'success');
    header('location: ' . $_SERVER['HTTP_REFERER']);
    exit();
  } else {
    header('location: index.php');
    exit();
  }
} else {
}
include $tpl . 'footer.php';
