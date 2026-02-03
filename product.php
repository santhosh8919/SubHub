<?php
session_start();
$pageTitle = 'Subscription Plan';
include './init.php';
include 'Parsedown.php';
$Parsedown = new Parsedown();
$id = isset($_GET['id']) ? $_GET['id'] : '';
$DetailsProducts = $con->prepare("SELECT * FROM `products` WHERE `id` = ?");
$DetailsProducts->execute(array($id));
$product = $DetailsProducts->fetch(PDO::FETCH_ASSOC);
if (!$product) {
?>
  <div class="container">
    <div class="alert alert-warning text-center mt-5" role="alert">
      Product not found
    </div>
  </div>
<?php
  header('Refresh: 6; url=index.php');
} else {
  $test = $product['description_product'];
  $description = $Parsedown->text($test);
?>
  <div class="product-list">
    <div class="container">
      <a class="btn btn-light my-2" href="./index.php"><i class="fa fa-backward" aria-hidden="true"></i>&nbsp;Back to Plans</a>
      <div class="row g-3">
        <div class="col-md-4 text-center">
          <img class="img-dProduct p-4 bg-light rounded" src="<?php echo $dirs . $product['img_product']; ?>" alt="<?php echo $product['name_product']; ?>" style="max-height: 200px; object-fit: contain;" />
        </div>
        <div class="col-md-8">
          <?php
          if (isset($_SESSION['message'])) : ?>
            <div id="message">
              <?php echo $_SESSION['message']; ?>
            </div>
          <?php unset($_SESSION['message']);
          endif;
          ?>
          <span class="badge bg-primary mb-2"><?php echo $product['category'] ?? 'Subscription'; ?></span>
          <h6 class="text-muted"><?php echo $product['company_name'] ?? ''; ?></h6>
          <h1><?php echo $product['name_product']; ?></h1>
          <h2 class="text-success">$<?php echo $product['price_product']; ?> <small class="text-muted">/ <?php echo $product['billing_cycle'] ?? 'month'; ?></small></h2>
          <p class="fw-bold">
            <?php if ($product['stock_product'] > 0) : ?>
              <span class="text-success"><i class="fa-solid fa-check-circle"></i> Available for subscription</span>
            <?php else : ?>
              <span class="text-danger"><i class="fa-solid fa-times-circle"></i> Currently unavailable</span>
            <?php endif; ?>
          </p>
          <div class="desc_product border-top border-bottom border-dark py-3 my-3">
            <h5>Plan Features:</h5>
            <?php echo $description; ?>
          </div>
          <form action="./cart.php?do=add-cart" method="post">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>" />
            <input type="hidden" name="quantity" value="1" />
            <button class="btn btn-primary btn-lg" type="submit" name="add_to_cart" <?php if ($product['stock_product'] < 1) : ?>disabled<?php endif; ?>>
              <i class="fa-solid fa-plus"></i>&nbsp;Subscribe Now
            </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php
}
include $tpl . 'footer.php'; ?>