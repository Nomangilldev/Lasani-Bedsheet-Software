<!DOCTYPE html>
<html lang="en">
<?php include_once 'includes/head.php'; ?>
<style type="text/css">
	thead tr th {
		font-size: 19px !important;
		font-weight: bolder !important;
		color: #000 !important;
	}

	tr td {
		font-size: 18px !important;
		font-weight: bolder !important;
		color: #000 !important;
	}
</style>

<body class="horizontal light  ">
	<div class="wrapper">
		<?php include_once 'includes/header.php'; ?>
		<main role="main" class="main-content">
			<div class="container-fluid">
				<div class="card">
					<div class="card-header card-bg" align="center">

						<div class="row">
							<div class="col-12 mx-auto h4">
								<b class="text-center card-text">Product Purchase Report</b>


							</div>
						</div>

					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-sm-8">
								<form method="post">
									<select class="form-control searchableSelect" name="productName" id="productName">
										<option value="">~~ SELECT PRODUCT ~~</option>
										<?php
										$productSql = "SELECT * FROM product ORDER BY product_name ASC";
										$productData = $connect->query($productSql);

										while ($row = $productData->fetch_array()) {
											$product_id = $row['product_id'];
											$fetchProduct = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM product WHERE product_id='$product_id'"));
											$fetchCategory = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM categories WHERE categories_id='{$fetchProduct['category_id']}'"));
											$brand = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM brands WHERE brand_id='{$fetchProduct['brand_id']}'"));

											$category_show = ucwords($fetchCategory['categories_name']);
											$brand1 = ucwords($brand['brand_name']);
											$product_name = ucwords($row['product_name']);

											echo "<option value='{$row['product_id']}'>{$product_name} ({$category_show}) [{$brand1}]</option>";
										}
										?>
									</select>
							</div>
							<div class="col-sm-4">
								<button type="submit" name="show_deatils" class="btn btn-danger">Show Details</button>
								</form>
							</div>
						</div> <!-- end of row -->
					</div>
				</div> <!-- .card -->

				<?php if (isset($_POST['show_deatils'])):
					$product_id = $_POST['productName'];
				?>
					<div class="card">
						<div class="card-body">

							<table class="table table-bordered table-hover myTable text-center">
								<thead class="thead-light">
									<tr>
										<th>Purchase No#</th>
										<th>Date</th>
										<th>Supplier</th>
										<th>Product Name</th>
										<th>Quantity</th>
										<th>Rate</th>
										<th>Total Amount</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if (!empty($_REQUEST['productName'])) {
										$product_id = $_POST['productName'];
										$q = mysqli_query($dbc, "SELECT * FROM purchase_item WHERE product_id = '$product_id' ORDER BY purchase_item_id DESC");
									} else {
										$q = mysqli_query($dbc, "SELECT * FROM purchase_item ORDER BY purchase_item_id DESC");
									}

									while ($r = mysqli_fetch_assoc($q)):
										$purchase__fetch_id = $r['purchase_id'];
										$q2 = mysqli_query($dbc, "SELECT * FROM purchase WHERE purchase_id = '$purchase__fetch_id'");
										while ($r2 = mysqli_fetch_assoc($q2)):

											$fetchCustomer = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM customers WHERE customer_id = '{$r2['customer_account']}'"));
											$fetchProductName = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM product WHERE product_id = '{$r['product_id']}'"));
									?>
											<tr>
												<td><?= $r2['purchase_id'] ?></td>
												<td><?= $r2['purchase_date'] ?></td>
												<td><?= ucwords(@$r2['client_name']) ?></td>
												<td><?= ucwords($fetchProductName['product_name']) ?></td>
												<td><?= $r['quantity'] ?></td>
												<td><?= number_format($r['rate'], 2) ?></td>
												<td><?= number_format($r['total'], 2) ?></td>
											</tr>
									<?php
										endwhile;
									endwhile;
									?>
								</tbody>
							</table>


						</div>
					</div>
					<!-- .card -->
				<?php endif ?>

			</div> <!-- .container-fluid -->

		</main> <!-- main -->
	</div> <!-- .wrapper -->

</body>

</html>
<?php include_once 'includes/foot.php'; ?>