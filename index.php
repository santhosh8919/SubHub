<?php
session_start();
$pageTitle = 'Subscriptions';
include './init.php';
$stmt = $con->prepare("SELECT `id`, `name_product`, `company_name`, `category`, `description_product`, `price_product`, `billing_cycle`, `currency`, `img_product`, `stock_product`, `created_at` FROM `products` ORDER BY `products`.`created_at` DESC");
$stmt->execute();
$ListProducts = $stmt->fetchAll();
?>
<div class="product-list my-3">
  <div class="container">
    <h1><?php echo $lang['Last Products'] ?></h1>
    <p class="text-muted">Subscribe to your favorite services - all in one place</p>
    <div class="row g-3">
      <?php foreach ($ListProducts as $product) : ?>
        <div class="col-md-3">
          <div class="card h-100">
            <div class="card-header bg-light">
              <span class="badge bg-primary"><?php echo $product['category'] ?></span>
              <span class="badge bg-secondary float-end"><?php echo ucfirst($product['billing_cycle']) ?></span>
            </div>
            <img class="card-img-top p-3" src="<?php echo $dirs . $product['img_product'] ?>" alt="<?php echo $product['name_product'] ?>" style="height: 120px; object-fit: contain;">
            <div class="card-body">
              <h6 class="text-muted"><?php echo $product['company_name'] ?></h6>
              <a href="product.php?id=<?php echo $product['id'] ?>">
                <h5 class="card-title"><?php echo $product['name_product'] ?></h5>
              </a>
              <p class="card-text fw-bold text-success">$<?php echo $product['price_product'] ?>/<?php echo $product['billing_cycle'] == 'monthly' ? 'mo' : ($product['billing_cycle'] == 'yearly' ? 'yr' : 'once') ?></p>
            </div>
            <div class="card-footer">
              <a href="product.php?id=<?php echo $product['id'] ?>" class="btn btn-outline-primary btn-sm w-100">View Plan</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php include $tpl . 'footer.php';
