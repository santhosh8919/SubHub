<?php
session_start();
$pageTitle = 'My Subscriptions';
include './init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['your_order'])) {
  $orders_number = trim($_POST['orders_number']);
  $stmt = $con->prepare("SELECT `orders`.`id`, `orders`.`orders_number`, `orders`.`customer_id`, `orders`.`product_name`, `orders`.`product_quantity`, `orders`.`product_price`, `orders`.`currency`, `orders`.`subtotal`, `orders`.`note_customer`, `orders`.`order_date`, `orders`.`renewal_date`, `orders`.`order_status`, `orders`.`subscription_status`, `customers`.`name_customer`, `customers`.`email_customer`, `customers`.`phone_customer` FROM `orders` INNER JOIN `customers` ON `orders`.`customer_id` = `customers`.`id` WHERE `orders`.`orders_number` = ?");
  $stmt->execute([$orders_number]);
  $Orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<div class="track-order">
  <div class="container">
    <?php if (isset($Orders)) : ?>
      <a class="btn btn-light my-2" href="./tracking.php"><i class="fa fa-backward" aria-hidden="true"></i>&nbsp;Back</a>
      <?php if (count($Orders) > 0) : 
        $customer = $Orders[0];
        $totalMonthly = 0;
        foreach ($Orders as $o) { $totalMonthly += $o['subtotal']; }
      ?>
        <div class="row">
          <div class="col-md-8">
            <div class="card mb-4">
              <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fa-solid fa-credit-card"></i> Subscription Details</h4>
              </div>
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <div>
                    <h5 class="text-muted mb-1">Order ID</h5>
                    <h3><code><?php echo $customer['orders_number']; ?></code></h3>
                  </div>
                  <div class="text-end">
                    <?php 
                    $subStatus = $customer['subscription_status'] ?? 'active';
                    $statusClass = match($subStatus) {
                      'active' => 'bg-success',
                      'paused' => 'bg-warning',
                      'cancelled', 'expired' => 'bg-danger',
                      default => 'bg-secondary'
                    };
                    ?>
                    <span class="badge <?php echo $statusClass; ?> fs-5"><?php echo ucfirst($subStatus); ?></span>
                  </div>
                </div>
                
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead class="table-dark">
                      <tr>
                        <th>Subscription Plan</th>
                        <th>Price</th>
                        <th>Billing</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($Orders as $order) : ?>
                        <tr>
                          <td>
                            <strong><?php echo $order['product_name']; ?></strong>
                          </td>
                          <td>
                            <span class="text-success fw-bold">$<?php echo number_format($order['subtotal'], 2); ?></span>/mo
                          </td>
                          <td>
                            <small class="text-muted">
                              Started: <?php echo date('M j, Y', strtotime($order['order_date'])); ?><br>
                              <?php if ($order['renewal_date']) : ?>
                                Next: <?php echo date('M j, Y', strtotime($order['renewal_date'])); ?>
                              <?php endif; ?>
                            </small>
                          </td>
                          <td>
                            <?php 
                            $status = $order['subscription_status'] ?? $order['order_status'];
                            $sClass = match($status) {
                              'active', 'completed' => 'bg-success',
                              'paused', 'pending' => 'bg-warning',
                              'cancelled', 'expired', 'failed' => 'bg-danger',
                              default => 'bg-secondary'
                            };
                            ?>
                            <span class="badge <?php echo $sClass; ?>"><?php echo ucfirst($status); ?></span>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-light">
                      <tr>
                        <td><strong>Total Monthly</strong></td>
                        <td colspan="3"><strong class="text-success fs-5">$<?php echo number_format($totalMonthly, 2); ?>/mo</strong></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-4">
            <div class="card mb-4">
              <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fa-solid fa-user"></i> Subscriber Info</h5>
              </div>
              <div class="card-body">
                <p><i class="fa-solid fa-user text-primary"></i> <strong><?php echo $customer['name_customer']; ?></strong></p>
                <p><i class="fa-solid fa-envelope text-primary"></i> <?php echo $customer['email_customer']; ?></p>
                <p><i class="fa-solid fa-phone text-primary"></i> <?php echo $customer['phone_customer']; ?></p>
              </div>
            </div>
            
            <div class="card">
              <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fa-solid fa-circle-info"></i> Quick Actions</h5>
              </div>
              <div class="card-body d-grid gap-2">
                <button class="btn btn-outline-warning" disabled><i class="fa-solid fa-pause"></i> Pause Subscription</button>
                <button class="btn btn-outline-danger" disabled><i class="fa-solid fa-xmark"></i> Cancel Subscription</button>
                <a href="index.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add More Plans</a>
              </div>
            </div>
          </div>
        </div>
      <?php else : ?>
        <div class="row justify-content-center">
          <div class="col-md-6">
            <div class="alert alert-warning text-center mt-5" role="alert">
              <i class="fa-solid fa-triangle-exclamation fa-2x mb-3"></i>
              <h4>Subscription Not Found</h4>
              <p>No subscriptions found with Order ID: <strong><?php echo htmlspecialchars($orders_number); ?></strong></p>
              <a href="tracking.php" class="btn btn-primary mt-2">Try Again</a>
            </div>
          </div>
        </div>
      <?php endif; ?>
    <?php else : ?>
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card mt-5">
            <div class="card-header bg-primary text-white text-center">
              <h3 class="mb-0"><i class="fa-solid fa-magnifying-glass"></i> Track Your Subscriptions</h3>
            </div>
            <div class="card-body">
              <p class="text-center text-muted">Enter your Order ID to view your active subscriptions</p>
              <form method="post" role="search" autocomplete="off" class="py-3">
                <div class="form-group mb-3">
                  <label for="orders_number" class="form-label">Order ID / Subscription Number</label>
                  <div class="input-group input-group-lg">
                    <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
                    <input class="form-control" name="orders_number" id="orders_number" placeholder="e.g. ORD-123456" aria-label="Order ID" required="required" />
                  </div>
                  <small class="text-muted">You received this ID after completing your subscription</small>
                </div>
                <div class="d-grid">
                  <button class="btn btn-primary btn-lg" type="submit" name="your_order">
                    <i class="fa-solid fa-search"></i> Track Subscription
                  </button>
                </div>
              </form>
            </div>
            <div class="card-footer text-center text-muted">
              <small>Can't find your Order ID? Check your email confirmation.</small>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php
include $tpl . 'footer.php'; ?>