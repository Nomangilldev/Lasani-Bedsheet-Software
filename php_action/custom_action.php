<?php
require_once '../php_action/db_connect.php';
require_once '../includes/functions.php';
if (isset($_REQUEST['add_manually_user'])) {
	$data = [
		'customer_name' => @$_REQUEST['customer_name'],
		'customer_phone' => @$_REQUEST['customer_phone'],
		'customer_email' => @$_REQUEST['customer_email'],
		'customer_address' => @$_REQUEST['customer_address'],
		'customer_type' => @$_REQUEST['customer_type'],
		'customer_status' => @$_REQUEST['customer_status'],
		'customer_type' => @$_REQUEST['add_manually_user'],
	];
	if ($_REQUEST['customer_id'] == "") {

		if (insert_data($dbc, "customers", $data)) {


			$res = ['msg' => ucfirst($_REQUEST['add_manually_user']) . " Added Successfully", 'sts' => 'success'];
		} else {

			$res = ['msg' => mysqli_error($dbc), 'sts' => 'error'];
		}
	} else {
		if (update_data($dbc, "customers", $data, "customer_id", $_REQUEST['customer_id'])) {


			$res = ['msg' => ucfirst($_REQUEST['add_manually_user']) . " Updated Successfully", 'sts' => 'success'];
		} else {

			$res = ['msg' => mysqli_error($dbc), 'sts' => 'error'];
		}
	}
	echo json_encode($res);
}

if (isset($_REQUEST['new_voucher_date'])) {

	if ($_REQUEST['voucher_id'] == "") {
		if ($_REQUEST['voucher_group'] == "general_voucher") {
			$data = [
				'customer_id1' => @$_REQUEST['voucher_from_account'],
				'customer_id2' => @$_REQUEST['voucher_to_account'],
				'voucher_date' => @$_REQUEST['new_voucher_date'],
				'voucher_hint' => @$_REQUEST['voucher_hint'],
				'voucher_type' => @$_REQUEST['voucher_type'],
				'voucher_amount' => @$_REQUEST['voucher_debit'],
				'voucher_group' => @$_REQUEST['voucher_group'],
				'td_check_no' => @$_REQUEST['td_check_no'],
				'voucher_bank_name' => @$_REQUEST['voucher_bank_name'],
				'td_check_date' => @$_REQUEST['td_check_date'],
				'check_type' => @$_REQUEST['check_type'],
				'addby_user_id' => @$_SESSION['userId'],
			];
		} else {
			$data = [
				'customer_id1' => @$_REQUEST['voucher_from_account'],
				'customer_id2' => @$_REQUEST['voucher_to_account'],
				'voucher_date' => @$_REQUEST['new_voucher_date'],
				'voucher_hint' => @$_REQUEST['voucher_hint'],
				'voucher_type' => @$_REQUEST['voucher_type'],
				'voucher_amount' => @$_REQUEST['voucher_debit'],
				'voucher_group' => @$_REQUEST['voucher_group'],
				'addby_user_id' => @$_SESSION['userId'],
			];
		}
		if (insert_data($dbc, "vouchers", $data)) {
			$last_id = mysqli_insert_id($dbc);
			if ($_REQUEST['voucher_group'] == "expense_voucher") {
				$voucher_to_account = fetchRecord($dbc, "customers", "customer_id", $_REQUEST['voucher_to_account']);
				$budget = [
					'budget_amount' => @$_REQUEST['voucher_debit'],
					'budget_type' => "expense",
					'budget_date' => $_REQUEST['new_voucher_date'],
					'voucher_id' => $last_id,
					'voucher_type' => @$_REQUEST['voucher_type'],
					'budget_name' => @"expense added to " . @$voucher_to_account['customer_name'],
				];
				insert_data($dbc, "budget", $budget);
			} elseif ($_REQUEST['voucher_group'] == "general_voucher" and !empty($_REQUEST['td_check_no'])) {
				$data_checks = [
					'check_no' => $_REQUEST['td_check_no'],
					'check_bank_name' => $_REQUEST['voucher_bank_name'],
					'check_expiry_date' => $_REQUEST['td_check_date'],
					'check_type' => $_REQUEST['check_type'],
					'voucher_id' => $last_id,
					'check_status' => 0,
				];
				insert_data($dbc, "checks", $data_checks);
			}


			$debit = [
				'debit' => @$_REQUEST['voucher_debit'],
				'credit' => 0,
				'customer_id' => @$_REQUEST['voucher_from_account'],
				'transaction_from' => 'voucher',
				'transaction_type' => @$_REQUEST['voucher_type'],
				'transaction_remarks' => @$_REQUEST['voucher_hint'],
				'transaction_date' => @$_REQUEST['new_voucher_date'],
			];
			insert_data($dbc, "transactions", $debit);
			$transaction_id1 = mysqli_insert_id($dbc);
			$credit = [
				'credit' => @$_REQUEST['voucher_debit'],
				'debit' => 0,
				'customer_id' => @$_REQUEST['voucher_to_account'],
				'transaction_from' => 'voucher',
				'transaction_type' => @$_REQUEST['voucher_type'],
				'transaction_remarks' => @$_REQUEST['voucher_hint'],
				'transaction_date' => @$_REQUEST['new_voucher_date'],
			];

			insert_data($dbc, "transactions", $credit);
			$transaction_id2 = mysqli_insert_id($dbc);
			$newData = ['transaction_id1' => $transaction_id1, 'transaction_id2' => $transaction_id2];
			if (update_data($dbc, "vouchers", $newData, "voucher_id", $last_id)) {
				$res = ['msg' => "Voucher Added Successfully", 'sts' => 'success', 'voucher_id' => base64_encode($last_id)];
			} else {
				$res = ['msg' => mysqli_error($dbc), 'sts' => 'error'];
			}
		} else {

			$res = ['msg' => mysqli_error($dbc), 'sts' => 'error'];
		}
	} else {
		if ($_REQUEST['voucher_group'] == "general_voucher") {
			$data = [
				'customer_id1' => @$_REQUEST['voucher_from_account'],
				'customer_id2' => @$_REQUEST['voucher_to_account'],
				'voucher_date' => @$_REQUEST['new_voucher_date'],
				'voucher_hint' => @$_REQUEST['voucher_hint'],
				'voucher_type' => @$_REQUEST['voucher_type'],
				'voucher_amount' => @$_REQUEST['voucher_debit'],
				'voucher_group' => @$_REQUEST['voucher_group'],
				'editby_user_id' => @$_SESSION['userId'],
				'td_check_no' => @$_REQUEST['td_check_no'],
				'voucher_bank_name' => @$_REQUEST['voucher_bank_name'],
				'check_type' => @$_REQUEST['check_type'],
				'td_check_date' => @$_REQUEST['td_check_date'],
			];
		} else {
			$data = [
				'customer_id1' => @$_REQUEST['voucher_from_account'],
				'customer_id2' => @$_REQUEST['voucher_to_account'],
				'voucher_date' => @$_REQUEST['new_voucher_date'],
				'voucher_hint' => @$_REQUEST['voucher_hint'],
				'voucher_type' => @$_REQUEST['voucher_type'],
				'voucher_amount' => @$_REQUEST['voucher_debit'],
				'voucher_group' => @$_REQUEST['voucher_group'],
				'editby_user_id' => @$_SESSION['userId'],
			];
		}
		if (update_data($dbc, "vouchers", $data, "voucher_id", $_REQUEST['voucher_id'])) {
			$last_id = $_REQUEST['voucher_id'];

			$transactions = fetchRecord($dbc, "vouchers", "voucher_id", $_REQUEST['voucher_id']);


			if ($_REQUEST['voucher_group'] == "expense_voucher") {
				$voucher_to_account = fetchRecord($dbc, "customers", "customer_id", $_REQUEST['voucher_to_account']);
				$budget = [
					'budget_amount' => @$_REQUEST['voucher_debit'],
					'budget_type' => "expense",
					'budget_date' => $_REQUEST['new_voucher_date'],
					'voucher_id' => $last_id,
					'voucher_type' => @$_REQUEST['voucher_type'],
					'budget_name' => @"expense added to " . @$voucher_to_account['customer_name'],
				];

				update_data($dbc, "budget", $budget, "voucher_id", $_REQUEST['voucher_id']);
			} elseif ($_REQUEST['voucher_group'] == "general_voucher") {
				$data_checks = [
					'check_no' => $_REQUEST['td_check_no'],
					'check_bank_name' => $_REQUEST['voucher_bank_name'],
					'check_expiry_date' => $_REQUEST['td_check_date'],
					'check_type' => $_REQUEST['check_type'],
					'voucher_id' => $last_id,
				];
				update_data($dbc, "checks", $data_checks, "voucher_id", $_REQUEST['voucher_id']);
			}

			$debit = [
				'debit' => @$_REQUEST['voucher_debit'],
				'credit' => 0,
				'customer_id' => @$_REQUEST['voucher_from_account'],
				'transaction_from' => 'voucher',
				'transaction_type' => @$_REQUEST['voucher_type'],
				'transaction_remarks' => @$_REQUEST['voucher_hint'],
				'transaction_date' => @$_REQUEST['new_voucher_date'],
			];

			update_data($dbc, "transactions", $debit, "transaction_id", $transactions['transaction_id1']);

			$credit = [
				'credit' => @$_REQUEST['voucher_debit'],
				'debit' => 0,
				'customer_id' => @$_REQUEST['voucher_to_account'],
				'transaction_from' => 'voucher',
				'transaction_type' => @$_REQUEST['voucher_type'],
				'transaction_remarks' => @$_REQUEST['voucher_hint'],
				'transaction_date' => @$_REQUEST['new_voucher_date'],
			];

			update_data($dbc, "transactions", $credit, "transaction_id", $transactions['transaction_id2']);

			$res = ['msg' => "Voucher Updated Successfully", 'sts' => 'success', 'voucher_id' => base64_encode($last_id)];
		} else {

			$res = ['msg' => mysqli_error($dbc), 'sts' => 'error'];
		}
	}
	echo json_encode($res);
}
if (isset($_REQUEST['new_sin_voucher_date'])) {
	if (!empty($_REQUEST['voucher_debit'])) {
		$amount = $_REQUEST['voucher_debit'];
	} else {
		$amount = $_REQUEST['voucher_credit'];
	}
	if ($_REQUEST['voucher_id'] == "") {
		$data = [
			'customer_id1' => @$_REQUEST['voucher_from_account'],
			'voucher_date' => @$_REQUEST['new_sin_voucher_date'],
			'voucher_hint' => @$_REQUEST['voucher_hint'],
			'voucher_amount' => $amount,
			'voucher_group' => @$_REQUEST['voucher_group'],
			'addby_user_id' => @$_SESSION['userId'],
		];
		if (insert_data($dbc, "vouchers", $data)) {
			$last_id = mysqli_insert_id($dbc);

			if (!empty($_REQUEST['voucher_debit'])) {
				$debit = [
					'debit' => $amount,
					'credit' => 0,
					'customer_id' => @$_REQUEST['voucher_from_account'],
					'transaction_from' => 'voucher',
					'transaction_type' => "single_voucher",
					'transaction_remarks' => @$_REQUEST['voucher_hint'],
					'transaction_date' => @$_REQUEST['new_sin_voucher_date'],
				];
				insert_data($dbc, "transactions", $debit);
			} else {
				$credit = [
					'credit' => $amount,
					'debit' => 0,
					'customer_id' => @$_REQUEST['voucher_from_account'],
					'transaction_from' => 'voucher',
					'transaction_type' => "single_voucher",
					'transaction_remarks' => @$_REQUEST['voucher_hint'],
					'transaction_date' => @$_REQUEST['new_sin_voucher_date'],
				];
				insert_data($dbc, "transactions", $credit);
			}



			$transaction_id1 = mysqli_insert_id($dbc);

			$newData = ['transaction_id1' => $transaction_id1];
			if (update_data($dbc, "vouchers", $newData, "voucher_id", $last_id)) {
				$res = ['msg' => "Voucher Added Successfully", 'sts' => 'success', 'voucher_id' => base64_encode($last_id)];
			} else {
				$res = ['msg' => mysqli_error($dbc), 'sts' => 'error'];
			}
		} else {

			$res = ['msg' => mysqli_error($dbc), 'sts' => 'error'];
		}
	} else {
		$data = [
			'customer_id1' => @$_REQUEST['voucher_from_account'],
			'voucher_date' => @$_REQUEST['new_sin_voucher_date'],
			'voucher_hint' => @$_REQUEST['voucher_hint'],
			'voucher_amount' => $amount,
			'voucher_group' => @$_REQUEST['voucher_group'],
			'editby_user_id' => @$_SESSION['userId'],
		];

		if (update_data($dbc, "vouchers", $data, "voucher_id", $_REQUEST['voucher_id'])) {
			$last_id = $_REQUEST['voucher_id'];

			$transactions = fetchRecord($dbc, "vouchers", "voucher_id", $_REQUEST['voucher_id']);

			if (!empty($_REQUEST['voucher_debit'])) {
				$debit = [
					'debit' => @$_REQUEST['voucher_debit'],
					'credit' => 0,
					'customer_id' => @$_REQUEST['voucher_from_account'],
					'transaction_from' => 'voucher',
					'transaction_type' => "single_voucher",
					'transaction_remarks' => @$_REQUEST['voucher_hint'],
					'transaction_date' => @$_REQUEST['new_sin_voucher_date'],
				];
				update_data($dbc, "transactions", $debit, "transaction_id", $transactions['transaction_id1']);
			} else {
				$credit = [
					'credit' => @$_REQUEST['voucher_credit'],
					'debit' => 0,
					'customer_id' => @$_REQUEST['voucher_from_account'],
					'transaction_from' => 'voucher',
					'transaction_type' => "single_voucher",
					'transaction_remarks' => @$_REQUEST['voucher_hint'],
					'transaction_date' => @$_REQUEST['new_sin_voucher_date'],
				];
				update_data($dbc, "transactions", $credit, "transaction_id", $transactions['transaction_id1']);;
			}


			$res = ['msg' => "Voucher Updated Successfully", 'sts' => 'success', 'voucher_id' => base64_encode($last_id)];
		} else {

			$res = ['msg' => mysqli_error($dbc), 'sts' => 'error'];
		}
	}
	echo json_encode($res);
}
if (!empty($_REQUEST['action']) and $_REQUEST['action'] == "product_module") {
	$category_price = fetchRecord($dbc, "categories", "categories_id", $_REQUEST['category_id']);

	$total = (float) @$_REQUEST['product_mm'] * (float) @$_REQUEST['product_inch'] * (float) @$_REQUEST['product_meter'];

	$brand_id = $_REQUEST['brand_id'];
	if (empty($brand_id) && isset($_REQUEST['new_brand_name'])) {
		$newBrandName = $_REQUEST['new_brand_name'];
		$newBrandStatus = 1;

		$insertBrandQuery = "INSERT INTO brands (brand_name, brand_status) VALUES ('$newBrandName', $newBrandStatus)";
		if (mysqli_query($dbc, $insertBrandQuery)) {
			$brand_id = mysqli_insert_id($dbc);
		} else {
			echo json_encode([
				"msg" => "Failed to add new brand: " . mysqli_error($dbc),
				"sts" => "error"
			]);
			exit;
			
		}
	}

	$category_id = $_REQUEST['category_id'];
	if (empty($category_id) && isset($_REQUEST['new_category_name'])) {
		$newCategoryName = $_REQUEST['new_category_name'];
		$newCategoryStatus = 1;

		$insertCategoryQuery = "INSERT INTO categories (categories_name, categories_status) VALUES ('$newCategoryName', $newCategoryStatus)";
		if (mysqli_query($dbc, $insertCategoryQuery)) {
			$category_id = mysqli_insert_id($dbc);
		} else {
			echo json_encode([
				"msg" => "Failed to add new category: " . mysqli_error($dbc),
				"sts" => "error"
			]);
			exit;
		}
	}

	$data_array = [
		'product_name' => $_REQUEST['product_name'],
		'product_code' => @$_REQUEST['product_code'],
		'brand_id' => $brand_id,
		'category_id' => $category_id,
		'product_mm' => @$_REQUEST['product_mm'],
		'product_inch' => @$_REQUEST['product_inch'],
		'product_meter' => @$_REQUEST['product_meter'],
		'current_rate' => @$_REQUEST['current_rate'],
		'product_description' => @$_REQUEST['product_description'],
		't_days' => @$_REQUEST['t_days'],
		'f_days' => @$_REQUEST['f_days'],
		'alert_at' => @$_REQUEST['alert_at'],
		'quantity_instock' => @$_REQUEST['quantity_instock'],
		'availability' => @$_REQUEST['availability'],
		'actual_rate' => @$_REQUEST['actual_rate'],
		'purchase_rate' => @$_REQUEST['purchase_rate'],
		'status' => 1,
	];

	if ($_REQUEST['product_id'] == "") {
		if (insert_data($dbc, "product", $data_array)) {
			$last_id = mysqli_insert_id($dbc);

			if (@$_FILES['product_image']['tmp_name']) {
				upload_pic(@$_FILES['product_image'], '../img/uploads/');
				$product_image = $_SESSION['pic_name'];
				$data_image = [
					'product_image' => $product_image,
				];
				update_data($dbc, "product", $data_image, "product_id", $last_id);
			}

			$response = [
				"msg" => "Product Has Been Added",
				"sts" => "success",
				"id" => base64_encode($last_id),
				"link" => base64_encode('add_stock'),
			];
		} else {
			$response = [
				"msg" => mysqli_error($dbc),
				"sts" => "error"
			];
		}
	} else {
		// Update existing product
		if (update_data($dbc, "product", $data_array, "product_id", base64_decode($_REQUEST['product_id']))) {
			$last_id = $_REQUEST['product_id'];

			if ($_FILES['product_image']['tmp_name']) {
				upload_pic($_FILES['product_image'], '../img/uploads/');
				$product_image = $_SESSION['pic_name'];
				$data_image = [
					'product_image' => $product_image,
				];
				update_data($dbc, "product", $data_image, "product_id", $last_id);
			}

			$response = [
				"msg" => "Product Updated",
				"sts" => "success",
				"id" => base64_encode($last_id),
				"link" => base64_encode('add_stock'),
			];
		} else {
			$response = [
				"msg" => mysqli_error($dbc),
				"sts" => "error"
			];
		}
	}
	echo json_encode($response);
}


if (!empty($_REQUEST['action']) and $_REQUEST['action'] == "inventory_module") {

	$data_array = [
		'product_name' => $_REQUEST['product_name'],
		'product_code' => rand(),
		'brand_id' => 0,
		'category_id' => 0,
		'current_rate' => @$_REQUEST['current_rate'],
		'alert_at' => 5,
		'availability' => 1,
		'purchase_rate' => $_REQUEST['current_rate'],
		'status' => 1,
		'inventory' => 1,
	];
	if ($_REQUEST['product_id'] == "") {

		if (insert_data($dbc, "product", $data_array)) {
			$last_id = mysqli_insert_id($dbc);

			$response = [
				"msg" => "Inventory Product Has Been Added",
				"sts" => "success",
			];
		} else {
			$response = [
				"msg" => mysqli_error($dbc),
				"sts" => "error"
			];
		}
	} else {
		if (update_data($dbc, "product", $data_array, "product_id", base64_decode($_REQUEST['product_id']))) {
			$last_id = $_REQUEST['product_id'];



			$response = [
				"msg" => "Product Updated",
				"sts" => "success",
			];
		} else {
			$response = [
				"msg" => mysqli_error($dbc),
				"sts" => "error"
			];
		}
	}
	echo json_encode($response);
}
if (isset($_REQUEST['get_products_list'])) {

	if ($_REQUEST['type'] == "code") {
		$q = mysqli_query($dbc, "SELECT * FROM product WHERE product_code LIKE '%" . $_REQUEST['get_products_list'] . "%' AND status=1 ");
		if (mysqli_num_rows($q) > 0) {
			while ($r = mysqli_fetch_assoc($q)) {
				echo '<option value="' . $r['product_id'] . '">' . $r['product_name'] . '</option>';
			}
		} else {
			echo '<option value="">Not Found</option>';
		}
	}
	if ($_REQUEST['type'] == "product") {
		$q = mysqli_query($dbc, "SELECT * FROM product WHERE product_id='" . $_REQUEST['get_products_list'] . "' AND status=1 ");
		if (mysqli_num_rows($q) > 0) {
			$r = mysqli_fetch_assoc($q);
			echo $r['product_code'];
		}
	}
}
if (!empty($_REQUEST['getPrice'])) {
	if ($_REQUEST['type'] == "product") {
		$record = fetchRecord($dbc, "product", "product_id", $_REQUEST['getPrice']);
	} else {
		$record = fetchRecord($dbc, "product", "product_code", $_REQUEST['getPrice']);
	}
	$sale_price = @$record['current_rate'];
	$price = @$record['purchase_rate'];





	$response = [
		"sale_price" => isset($sale_price) ? $sale_price : 0,
		"price" => isset($price) ? $price : 0,
		"qty" => @(float) $record['quantity_instock'],
		"description" => $record['product_description'],
		"sts" => "success",
		"type" => @$_REQUEST['credit_sale_type'],
	];


	echo json_encode($response);
}

/*---------------------- cash sale-order   -------------------------------------------------------------------*/
if (isset($_REQUEST['sale_order_client_name']) && empty($_REQUEST['order_return'])) {
	$get_company = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM company ORDER BY id DESC LIMIT 1"));
	if (!empty($_REQUEST['product_ids'])) {
		# code...
		//print_r(json_encode($_REQUEST));
		$total_ammount = $total_grand = 0;

		$data = [
			'order_date' => $_REQUEST['order_date'],
			'client_name' => $_REQUEST['sale_order_client_name'],
			'bill_no' => @$_REQUEST['bill_no'],
			'client_contact' => $_REQUEST['client_contact'],
			'paid' => $_REQUEST['paid_ammount'],
			'payment_account' => @$_REQUEST['payment_account'],
			'payment_type' => 'cash_in_hand',
			'vehicle_no' => @$_REQUEST['vehicle_no'],
			'order_narration' => @$_REQUEST['order_narration'],
			'freight' => @$_REQUEST['freight'],
		];

		if ($_REQUEST['product_order_id'] == "") {

			if (insert_data($dbc, 'orders', $data)) {
				$last_id = mysqli_insert_id($dbc);
				$paidAmount = @(float) $_REQUEST['paid_ammount'];
				if ($paidAmount > 0) {
					$debit = [
						'credit' => @$_REQUEST['paid_ammount'],
						'debit' => 0,
						'customer_id' => @$_REQUEST['payment_account'],
						'transaction_from' => 'invoice',
						'transaction_type' => "cash_in_hand",
						'transaction_remarks' => "cash_sale by order id#" . $last_id,
						'transaction_date' => $_REQUEST['order_date'],
					];
					insert_data($dbc, 'transactions', $debit);
					$transaction_paid_id = mysqli_insert_id($dbc);
				}

				$x = 0;
				foreach ($_REQUEST['product_ids'] as $key => $value) {

					$total = $qty = 0;
					$product_quantites = (float) $_REQUEST['product_quantites'][$x];
					$product_rates = (float) $_REQUEST['product_rates'][$x];
					$total = (float) $product_quantites * $product_rates;
					$total_ammount += (float) $total;
					$order_items = [
						'product_id' => $_REQUEST['product_ids'][$x],
						'rate' => $product_rates,
						'total' => $total,
						'order_id' => $last_id,
						'quantity' => $product_quantites,
						// 'product_detail' => @$_REQUEST['product_detail'][$x],
						'order_item_status' => 1,
					];
					if ($get_company['stock_manage'] == 1) {
						$product_id = $_REQUEST['product_ids'][$x];
						$quantity_instock = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT quantity_instock FROM  product WHERE product_id='" . $product_id . "' "));
						@$qty = (float) $quantity_instock['quantity_instock'] - (float) $product_quantites;
						$quantity_update = mysqli_query($dbc, "UPDATE product SET  quantity_instock='$qty' WHERE product_id='" . $product_id . "' ");
					}
					insert_data($dbc, 'order_item', $order_items);

					$x++;
				} //end of foreach
				$total_grand = @(float) $_REQUEST['freight'] + $total_ammount - $total_ammount * ((float) $_REQUEST['ordered_discount'] / 100);

				$due_amount = (float) $total_grand - @(float) $_REQUEST['paid_ammount'];

				if ($due_amount > 0) {
					$payment_status = 0; //pending

				} else {
					$payment_status = 1; //completed

				}
				$newOrder = [
					'total_amount' => $total_ammount,
					'discount' => $_REQUEST['ordered_discount'],
					'grand_total' => $total_grand,
					'payment_status' => $payment_status,
					'due' => $due_amount,
					'order_status' => 1,
					'transaction_paid_id' => @$transaction_paid_id,
				];
				if (update_data($dbc, 'orders', $newOrder, 'order_id', $last_id)) {
					# code...
					//echo "<script>alert('company Updated....!')</script>";
					$msg = "Order Has been Added";
					$sts = 'success';
				} else {
					$msg = mysqli_error($dbc);
					$sts = "danger";
				}
			} else {
				$msg = mysqli_error($dbc);
				$sts = "danger";
			}
		} else {
			if (update_data($dbc, 'orders', $data, 'order_id', $_REQUEST['product_order_id'])) {
				$last_id = $_REQUEST['product_order_id'];
				if ($get_company['stock_manage'] == 1) {
					$proQ = get($dbc, "order_item WHERE order_id='" . $last_id . "' ");

					while ($proR = mysqli_fetch_assoc($proQ)) {
						$newqty = 0;
						$quantity_instock = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT quantity_instock FROM  product WHERE product_id='" . $proR['product_id'] . "' "));
						$newqty = (float) $quantity_instock['quantity_instock'] + (float) $proR['quantity'];
						$quantity_update = mysqli_query($dbc, "UPDATE product SET  quantity_instock='$newqty' WHERE product_id='" . $proR['product_id'] . "' ");
					}
				}
				deleteFromTable($dbc, "order_item", 'order_id', $_REQUEST['product_order_id']);

				$x = 0;
				foreach ($_REQUEST['product_ids'] as $key => $value) {
					$total = $qty = 0;
					$product_quantites = (float) $_REQUEST['product_quantites'][$x];
					$product_rates = (float) $_REQUEST['product_rates'][$x];
					$total = $product_quantites * $product_rates;
					$total_ammount += (float) $total;

					$order_items = [
						'product_id' => $_REQUEST['product_ids'][$x],
						'rate' => $product_rates,
						'total' => $total,
						'order_id' => $_REQUEST['product_order_id'],
						'quantity' => $product_quantites,
						// 'product_detail' => $_REQUEST['product_detail'][$x],
						'order_item_status' => 1,
					];
					if ($get_company['stock_manage'] == 1) {
						$product_id = $_REQUEST['product_ids'][$x];
						$quantity_instock = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT quantity_instock FROM  product WHERE product_id='" . $product_id . "' "));
						$qty = (float) $quantity_instock['quantity_instock'] - $product_quantites;
						$quantity_update = mysqli_query($dbc, "UPDATE product SET  quantity_instock='$qty' WHERE product_id='" . $product_id . "' ");
					}
					//update_data($dbc,'order_item', $order_items , 'order_id',$_REQUEST['product_order_id']);
					insert_data($dbc, 'order_item', $order_items);

					$x++;
				} //end of foreach
				$total_grand = @(float) $_REQUEST['freight'] + $total_ammount - $total_ammount * ((float) $_REQUEST['ordered_discount'] / 100);
				$due_amount = (float) $total_grand - @(float) $_REQUEST['paid_ammount'];
				if ($due_amount > 0) {
					$payment_status = 0; //pending

				} else {
					$payment_status = 1; //completed

				}
				$newOrder = [

					'total_amount' => $total_ammount,
					'discount' => $_REQUEST['ordered_discount'],
					'grand_total' => $total_grand,
					'payment_status' => $payment_status,
					'due' => $due_amount,
				];
				$paidAmount = @(float) $_REQUEST['paid_ammount'];
				if ($paidAmount > 0) {
					$credit1 = [
						'credit' => @$_REQUEST['paid_ammount'],
						'debit' => 0,
						'customer_id' => @$_REQUEST['payment_account'],

					];
					$transactions = fetchRecord($dbc, "orders", "order_id", $_REQUEST['product_order_id']);
					update_data($dbc, "transactions", $credit1, "transaction_id", $transactions['transaction_paid_id']);
				}
				if (update_data($dbc, 'orders', $newOrder, 'order_id', $_REQUEST['product_order_id'])) {
					# code...
					//echo "<script>alert('company Updated....!')</script>";
					$msg = "Data Has been Updated";
					$sts = 'success';
				} else {
					$msg = mysqli_error($dbc);
					$sts = "danger";
				}
			} else {
				$msg = mysqli_error($dbc);
				$sts = "danger";
			}
		}
	} else {
		$msg = "Please Add Any Product";
		$sts = 'error';
	}
	echo json_encode(['msg' => $msg, 'sts' => $sts, 'order_id' => @$last_id, 'type' => "order", 'subtype' => $_REQUEST['payment_type'], 'print_url' => $get_company['print_url']]);
}
/*---------------------- credit sale-order   -------------------------------------------------------------------*/
if (isset($_REQUEST['credit_order_client_name']) && empty($_REQUEST['order_return'])) {
	$get_company = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM company ORDER BY id DESC LIMIT 1"));
	if (!empty($_REQUEST['product_ids'])) {
		# code...
		$total_ammount = $total_grand = 0;

		$data = [
			'order_date' => $_REQUEST['order_date'],
			'client_name' => $_REQUEST['credit_order_client_name'],
			'bill_no' => $_REQUEST['bill_no'],
			'client_contact' => $_REQUEST['client_contact'],
			'paid' => $_REQUEST['paid_ammount'],
			'order_narration' => @$_REQUEST['order_narration'],
			'payment_account' => @$_REQUEST['payment_account'],
			'customer_account' => @$_REQUEST['customer_account'],
			'payment_type' => 'credit_sale',
			'credit_sale_type' => @$_REQUEST['credit_sale_type'],
			'vehicle_no' => @$_REQUEST['vehicle_no'],
			'freight' => @$_REQUEST['freight'],
		];
		//'payment_status'=>1,
		if ($_REQUEST['product_order_id'] == "") {

			if (insert_data($dbc, 'orders', $data)) {
				$last_id = mysqli_insert_id($dbc);
				$x = 0;
				foreach ($_REQUEST['product_ids'] as $key => $value) {
					$total = $qty = 0;
					$product_quantites = (float) $_REQUEST['product_quantites'][$x];
					$product_rates = (float) $_REQUEST['product_rates'][$x];
					$total = $product_quantites * $product_rates;
					$total_ammount += (float) $total;
					$order_items = [
						'product_id' => $_REQUEST['product_ids'][$x],
						'rate' => $product_rates,
						'total' => $total,
						'order_id' => $last_id,
						'quantity' => $product_quantites,
						'product_detail' => @$_REQUEST['product_detail'][$x],
						'order_item_status' => 1,
					];

					if ($get_company['stock_manage'] == 1) {
						$product_id = $_REQUEST['product_ids'][$x];
						$quantity_instock = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT quantity_instock FROM  product WHERE product_id='" . $product_id . "' "));
						$qty = (float) $quantity_instock['quantity_instock'] - $product_quantites;
						$quantity_update = mysqli_query($dbc, "UPDATE product SET  quantity_instock='$qty' WHERE product_id='" . $product_id . "' ");
					}
					insert_data($dbc, 'order_item', $order_items);

					$x++;
				} //end of foreach

				$total_grand = @(float) $_REQUEST['freight'] + $total_ammount - $total_ammount * ((float) $_REQUEST['ordered_discount'] / 100);
				$due_amount = (float) $total_grand - @(float) $_REQUEST['paid_ammount'];

				$credit = [
					'credit' => $due_amount,
					'debit' => 0,
					'customer_id' => @$_REQUEST['customer_account'],
					'transaction_from' => 'invoice',
					'transaction_type' => "credit_sale",
					'transaction_remarks' => "credit_sale by order id#" . $last_id,
					'transaction_date' => $_REQUEST['order_date'],
				];
				if ($due_amount > 0) {
					$payment_status = 0; //pending
					insert_data($dbc, 'transactions', $credit);
					$transaction_id = mysqli_insert_id($dbc);
				} else {
					$payment_status = 1; //completed
					$transaction_id = 0;
				}
				$paidAmount = @(float) $_REQUEST['paid_ammount'];
				if ($paidAmount > 0) {
					$credit1 = [
						'credit' => @$_REQUEST['paid_ammount'],
						'debit' => 0,
						'customer_id' => @$_REQUEST['payment_account'],
						'transaction_from' => 'invoice',
						'transaction_type' => "credit_sale",
						'transaction_remarks' => "credit_sale by order id#" . $last_id,
						'transaction_date' => $_REQUEST['order_date'],
					];
					insert_data($dbc, 'transactions', $credit1);
					$transaction_paid_id = mysqli_insert_id($dbc);
				}


				$newOrder = [
					'payment_status' => $payment_status,
					'total_amount' => $total_ammount,
					'discount' => $_REQUEST['ordered_discount'],
					'grand_total' => $total_grand,
					'due' => $due_amount,
					'order_status' => 1,
					'transaction_id' => @$transaction_id,
					'transaction_paid_id' => @$transaction_paid_id,
				];
				if (update_data($dbc, 'orders', $newOrder, 'order_id', $last_id)) {
					# code...
					//echo "<script>alert('company Updated....!')</script>";
					$msg = "Order Has been Added";
					$sts = 'success';
				} else {
					$msg = mysqli_error($dbc);
					$sts = "danger";
				}
			} else {
				$msg = mysqli_error($dbc);
				$sts = "danger";
			}
		} else {
			if (update_data($dbc, 'orders', $data, 'order_id', $_REQUEST['product_order_id'])) {
				$last_id = $_REQUEST['product_order_id'];
				if ($get_company['stock_manage'] == 1) {
					$proQ = get($dbc, "order_item WHERE order_id='" . $last_id . "' ");

					while ($proR = mysqli_fetch_assoc($proQ)) {
						$newqty = 0;
						$quantity_instock = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT quantity_instock FROM  product WHERE product_id='" . $proR['product_id'] . "' "));
						$newqty = (float) $quantity_instock['quantity_instock'] + (float) $proR['quantity'];
						$quantity_update = mysqli_query($dbc, "UPDATE product SET  quantity_instock='$newqty' WHERE product_id='" . $proR['product_id'] . "' ");
					}
				}
				deleteFromTable($dbc, "order_item", 'order_id', $_REQUEST['product_order_id']);

				$x = 0;
				foreach ($_REQUEST['product_ids'] as $key => $value) {
					$total = $qty = 0;
					$product_quantites = (float) $_REQUEST['product_quantites'][$x];
					$product_rates = (float) $_REQUEST['product_rates'][$x];
					$total = $product_quantites * $product_rates;
					$total_ammount += (float) $total;
					$order_items = [
						'product_id' => $_REQUEST['product_ids'][$x],
						'rate' => $product_rates,
						'total' => $total,
						'order_id' => $_REQUEST['product_order_id'],
						'quantity' => $product_quantites,
						'product_detail' => $_REQUEST['product_detail'][$x],
						'order_item_status' => 1,
					];
					if ($get_company['stock_manage'] == 1) {
						$product_id = $_REQUEST['product_ids'][$x];
						$quantity_instock = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT quantity_instock FROM  product WHERE product_id='" . $product_id . "' "));
						$qty = (float) $quantity_instock['quantity_instock'] - $product_quantites;
						$quantity_update = mysqli_query($dbc, "UPDATE product SET  quantity_instock='$qty' WHERE product_id='" . $product_id . "' ");
					}
					insert_data($dbc, 'order_item', $order_items);

					$x++;
				} //end of foreach
				$total_grand = @(float) $_REQUEST['freight'] + $total_ammount - $total_ammount * ((float) $_REQUEST['ordered_discount'] / 100);
				$due_amount = (float) $total_grand - @(float) $_REQUEST['paid_ammount'];

				$transactions = fetchRecord($dbc, "orders", "order_id", $_REQUEST['product_order_id']);
				@deleteFromTable($dbc, "transactions", 'transaction_id', $transactions['transaction_id']);
				@deleteFromTable($dbc, "transactions", 'transaction_id', $transactions['transaction_paid_id']);

				$credit = [
					'credit' => $due_amount,
					'debit' => 0,
					'customer_id' => @$_REQUEST['customer_account'],
					'transaction_from' => 'invoice',
					'transaction_type' => "credit_sale",
					'transaction_remarks' => "credit_sale by order id#" . $last_id,
					'transaction_date' => $_REQUEST['order_date'],
				];
				if ($due_amount > 0) {
					$payment_status = 0; //pending
					insert_data($dbc, 'transactions', $credit);
					$transaction_id = mysqli_insert_id($dbc);
				} else {
					$payment_status = 1; //completed
					$transaction_id = 0;
				}
				$paidAmount = @(float) $_REQUEST['paid_ammount'];
				if ($paidAmount > 0) {
					$credit1 = [
						'credit' => @$_REQUEST['paid_ammount'],
						'debit' => 0,
						'customer_id' => @$_REQUEST['payment_account'],
						'transaction_from' => 'invoice',
						'transaction_type' => "credit_sale",
						'transaction_remarks' => "credit_sale by order id#" . $last_id,
						'transaction_date' => $_REQUEST['order_date'],
					];
					insert_data($dbc, 'transactions', $credit1);
					$transaction_paid_id = mysqli_insert_id($dbc);
				}

				$newOrder = [
					'payment_status' => $payment_status,
					'total_amount' => $total_ammount,
					'discount' => $_REQUEST['ordered_discount'],
					'grand_total' => $total_grand,
					'due' => $due_amount,
					'transaction_id' => @$transaction_id,
					'transaction_paid_id' => @$transaction_paid_id,
				];


				if (update_data($dbc, 'orders', $newOrder, 'order_id', $_REQUEST['product_order_id'])) {
					# code...
					//echo "<script>alert('company Updated....!')</script>";
					$msg = "Data Has been Updated";
					$sts = 'success';
				} else {
					$msg = mysqli_error($dbc);
					$sts = "danger";
				}
			} else {
				$msg = mysqli_error($dbc);
				$sts = "danger";
			}
		}
	} else {
		$msg = "Please Add Any Product";
		$sts = 'error';
	}
	echo json_encode(['msg' => $msg, 'sts' => $sts, 'order_id' => @$last_id, 'type' => "order", 'subtype' => $_REQUEST['payment_type'], 'print_url' => $get_company['print_url']]);
}

if (isset($_REQUEST['getProductPills'])) {
	$q = mysqli_query($dbc, "SELECT * FROM product WHERE brand_id='" . $_REQUEST['getProductPills'] . "' ");
	if (mysqli_num_rows($q) > 0) {
		while ($r = mysqli_fetch_assoc($q)) {
			echo '<li class="nav-item text-capitalize"  ><button type="button" onclick="addProductOrder(' . $r["product_id"] . ',' . $r["quantity_instock"] . ',`plus`)" class="btn btn-primary  m-1 ">' . $r["product_name"] . '</button></li>';
		}
	} else {
		echo '<li class="nav-item text-capitalize ">No Product Has Been Added</li>';
	}
}
if (isset($_REQUEST['getCustomer_name'])) {
	$q = mysqli_query($dbc, "SELECT DISTINCT client_name FROM  orders WHERE client_contact='" . $_REQUEST['getCustomer_name'] . "' ");
	if (mysqli_num_rows($q) > 0) {
		$r = mysqli_fetch_assoc($q);
		echo $r['client_name'];
	} else {
		echo '';
	}
}
if (isset($_REQUEST['getProductDetails'])) {
	$product = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT  product.*,brands.* FROM product INNER JOIN brands ON product.brand_id=brands.brand_id   WHERE product.product_id='" . $_REQUEST['getProductDetails'] . "' AND product.status=1  "));
	echo json_encode($product);
}
if (isset($_REQUEST['getProductDetailsBycode'])) {
	$product = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT  product.*,brands.*,order_item.* FROM order_item INNER JOIN product ON product.product_id=order_item.product_id INNER JOIN brands ON product.brand_id=brands.brand_id   WHERE product.product_code='" . $_REQUEST['getProductDetailsBycode'] . "' AND product.status=1  "));
	echo json_encode($product);
}
// if (isset($_REQUEST['getProductDetailsBycode'])) {
// 	$product = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT  product.*,brands.*,order_item.* FROM order_item INNER JOIN product ON product.product_id=order_item.product_id INNER JOIN brands ON product.brand_id=brands.brand_id   WHERE product.product_code='" . base64_decode($_REQUEST['getProductDetailsBycode']) . "'"));
// 	echo json_encode($product);
// }
/*---------------------- cash purchase   -------------------------------------------------------------------*/
if (isset($_REQUEST['cash_purchase_supplier']) && empty($_REQUEST['purchase_return'])) {

	if (!empty($_REQUEST['product_ids'])) {
		# code...
		$total_ammount = $total_grand = 0;
		$get_company = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM company ORDER BY id DESC LIMIT 1"));

		$data = [
			'purchase_date' => $_REQUEST['purchase_date'],
			'bill_no' => $_REQUEST['bill_no'],
			'client_name' => @$_REQUEST['cash_purchase_supplier'],
			'client_contact' => @$_REQUEST['client_contact'],
			'purchase_narration' => @$_REQUEST['purchase_narration'],
			'payment_account' => @$_REQUEST['payment_account'],
			'customer_account' => @$_REQUEST['customer_account'],
			'paid' => $_REQUEST['paid_ammount'],
			'payment_status' => 1,
			'payment_type' => $_REQUEST['payment_type'],
		];

		if ($_REQUEST['product_purchase_id'] == "") {

			if (insert_data($dbc, 'purchase', $data)) {
				$last_id = mysqli_insert_id($dbc);

				$x = 0;
				foreach ($_REQUEST['product_ids'] as $key => $value) {
					$total = $qty = 0;
					$product_quantites = (float) $_REQUEST['product_quantites'][$x];
					$product_rates = (float) @$_REQUEST['product_rates'][$x];
					$product_salerates = (float) @$_REQUEST['product_salerates'][$x];
					$total = (float) $product_quantites * $product_rates;
					$total_ammount += (float) $total;

					$updateProduct = mysqli_query($dbc, "
        UPDATE product 
        SET  
            purchase_rate = '$product_rates' 
        WHERE product_id = '$value'
    ");
					$order_items = [
						'product_id' => $_REQUEST['product_ids'][$x],
						'rate' => $product_rates,
						'sale_rate' => $product_salerates,
						'total' => $total,
						'purchase_id' => $last_id,
						'product_detail' => @$_REQUEST['product_detail'][$x],
						'quantity' => $product_quantites,
						'purchase_item_status' => 1,
					];

					insert_data($dbc, 'purchase_item', $order_items);

					if ($get_company['stock_manage'] == 1) {
						$product_id = $_REQUEST['product_ids'][$x];
						$quantity_instock = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT quantity_instock FROM  product WHERE product_id='" . $product_id . "' "));
						$qty = (float) $quantity_instock['quantity_instock'] + $product_quantites;
						$quantity_update = mysqli_query($dbc, "UPDATE product SET  quantity_instock='$qty' WHERE product_id='" . $product_id . "' ");
					}
					// if (isset($_REQUEST['product_salerates'][$x])) {
					// 	$product_id = $_REQUEST['product_ids'][$x];
					// 	$quantity_instock = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT current_rate FROM  product WHERE product_id='" . $product_id . "' "));
					// 	$current_rate = $_REQUEST['product_salerates'][$x];
					// 	$quantity_update = mysqli_query($dbc, "UPDATE product SET  current_rate='$current_rate' WHERE product_id='" . $product_id . "' ");
					// }



					$x++;
				} //end of foreach
				$total_grand = $total_ammount - $total_ammount * ((float) $_REQUEST['ordered_discount'] / 100);

				$due_amount = (float) $total_grand - @(float) $_REQUEST['paid_ammount'];
				if ($_REQUEST['payment_type'] == "credit_purchase"):
					if ($due_amount > 0) {
						$debit = [
							'debit' => $due_amount,
							'credit' => 0,
							'customer_id' => @$_REQUEST['customer_account'],
							'transaction_from' => 'purchase',
							'transaction_type' => $_REQUEST['payment_type'],
							'transaction_remarks' => "purchased on  purchased id#" . $last_id,
							'transaction_date' => $_REQUEST['purchase_date'],
						];
						insert_data($dbc, 'transactions', $debit);
						$transaction_id = mysqli_insert_id($dbc);
					}
				endif;
				$paidAmount = @(float) $_REQUEST['paid_ammount'];
				if ($paidAmount > 0) {
					$credit = [
						'debit' => @$_REQUEST['paid_ammount'],
						'credit' => 0,
						'customer_id' => @$_REQUEST['payment_account'],
						'transaction_from' => 'purchase',
						'transaction_type' => $_REQUEST['payment_type'],
						'transaction_remarks' => "purchased by purchased id#" . $last_id,
						'transaction_date' => $_REQUEST['purchase_date'],
					];
					insert_data($dbc, 'transactions', $credit);
					$transaction_paid_id = mysqli_insert_id($dbc);
				}

				$newOrder = [

					'total_amount' => $total_ammount,
					'discount' => $_REQUEST['ordered_discount'],
					'grand_total' => $total_grand,
					'due' => $due_amount,
					'transaction_paid_id' => @$transaction_paid_id,
					'transaction_id' => @$transaction_id,
				];
				if (update_data($dbc, 'purchase', $newOrder, 'purchase_id', $last_id)) {
					# code...
					//echo "<script>alert('company Updated....!')</script>";
					$msg = "Purchase Has been Added";
					$sts = 'success';
				} else {
					$msg = mysqli_error($dbc);
					$sts = "danger";
				}
			} else {
				$msg = mysqli_error($dbc);
				$sts = "danger";
			}
		} else {
			if (update_data($dbc, 'purchase', $data, 'purchase_id', $_REQUEST['product_purchase_id'])) {
				$last_id = $_REQUEST['product_purchase_id'];


				if ($get_company['stock_manage'] == 1) {
					$proQ = get($dbc, "purchase_item WHERE purchase_id='" . $last_id . "' ");

					while ($proR = mysqli_fetch_assoc($proQ)) {
						$newqty = 0;
						$quantity_instock = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT quantity_instock FROM  product WHERE product_id='" . $proR['product_id'] . "' "));
						$newqty = (float) $quantity_instock['quantity_instock'] - (float) $proR['quantity'];
						$quantity_update = mysqli_query($dbc, "UPDATE product SET  quantity_instock='$newqty' WHERE product_id='" . $proR['product_id'] . "' ");
					}
				}
				deleteFromTable($dbc, "purchase_item", 'purchase_id', $_REQUEST['product_purchase_id']);
				$x = 0;
				foreach ($_REQUEST['product_ids'] as $key => $value) {


					$total = $qty = 0;
					$product_quantites = (float) $_REQUEST['product_quantites'][$x];
					$product_rates = (float) $_REQUEST['product_rates'][$x];
					// $product_salerates = (float)$_REQUEST['product_salerates'][$x];
					$total = $product_quantites * $product_rates;
					$total_ammount += (float) $total;
					$updateProduct = mysqli_query($dbc, "
        UPDATE product 
        SET  
            purchase_rate = '$product_rates' 
        WHERE product_id = '$value'
    ");
					$purchase_item = [
						'product_id' => $_REQUEST['product_ids'][$x],
						'rate' => $product_rates,
						// 'sale_rate' => $product_salerates,
						'total' => $total,
						'purchase_id' => $_REQUEST['product_purchase_id'],
						'product_detail' => @$_REQUEST['product_detail'][$x],
						'quantity' => $product_quantites,
						'purchase_item_status' => 1,
					];

					//update_data($dbc,'order_item', $order_items , 'purchase_id',$_REQUEST['product_purchase_id']);
					insert_data($dbc, 'purchase_item', $purchase_item);

					if ($get_company['stock_manage'] == 1) {
						$product_id = $_REQUEST['product_ids'][$x];
						$quantity_instock = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT quantity_instock FROM  product WHERE product_id='" . $product_id . "' "));
						$qty = (float) $quantity_instock['quantity_instock'] + $product_quantites;
						$quantity_update = mysqli_query($dbc, "UPDATE product SET  quantity_instock='$qty' WHERE product_id='" . $product_id . "' ");
					}

					$x++;
				} //end of foreach
				$total_grand = $total_ammount - $total_ammount * ((float) $_REQUEST['ordered_discount'] / 100);
				$due_amount = (float) $total_grand - @(float) $_REQUEST['paid_ammount'];


				$transactions = fetchRecord($dbc, "purchase", "purchase_id", $_REQUEST['product_purchase_id']);
				@deleteFromTable($dbc, "transactions", 'transaction_id', $transactions['transaction_id']);
				@deleteFromTable($dbc, "transactions", 'transaction_id', $transactions['transaction_paid_id']);


				if ($_REQUEST['payment_type'] == "credit_purchase"):
					if ($due_amount > 0) {
						$debit = [
							'debit' => $due_amount,
							'credit' => 0,
							'customer_id' => @$_REQUEST['customer_account'],
							'transaction_from' => 'purchase',
							'transaction_type' => $_REQUEST['payment_type'],
							'transaction_remarks' => "purchased on  purchased id#" . $last_id,
							'transaction_date' => $_REQUEST['purchase_date'],
						];
						insert_data($dbc, 'transactions', $debit);
						$transaction_id = mysqli_insert_id($dbc);
					}
				endif;
				$paidAmount = @(float) $_REQUEST['paid_ammount'];
				if ($paidAmount > 0) {
					$credit = [
						'debit' => @$_REQUEST['paid_ammount'],
						'credit' => 0,
						'customer_id' => @$_REQUEST['payment_account'],
						'transaction_from' => 'purchase',
						'transaction_type' => $_REQUEST['payment_type'],
						'transaction_remarks' => "purchased by purchased id#" . $last_id,
						'transaction_date' => $_REQUEST['purchase_date'],
					];
					insert_data($dbc, 'transactions', $credit);
					$transaction_paid_id = mysqli_insert_id($dbc);
				}

				$newOrder = [

					'total_amount' => $total_ammount,
					'discount' => $_REQUEST['ordered_discount'],
					'grand_total' => $total_grand,
					'due' => $due_amount,
					'transaction_paid_id' => @$transaction_paid_id,
					'transaction_id' => @$transaction_id,
				];

				if (update_data($dbc, 'purchase', $newOrder, 'purchase_id', $_REQUEST['product_purchase_id'])) {
					# code...
					//echo "<script>alert('company Updated....!')</script>";
					$msg = "Purchase Has been Updated";
					$sts = 'success';
				} else {
					$msg = mysqli_error($dbc);
					$sts = "danger";
				}
			} else {
				$msg = mysqli_error($dbc);
				$sts = "danger";
			}
		}
	} else {
		$msg = "Please Add Any Product";
		$sts = 'error';
	}
	echo json_encode(['msg' => $msg, 'sts' => $sts, 'order_id' => @$last_id, 'type' => "purchase", 'subtype' => $_REQUEST['payment_type'], 'print_url' => $get_company['print_url']]);
}
/*---------------------- credit Purchase-order  end -------------------------------------------------------------------*/
if (isset($_REQUEST['get_products_code'])) {
	$q = mysqli_query($dbc, "SELECT *  FROM product WHERE product_code='" . $_REQUEST['get_products_code'] . "' AND status=1 ");
	if (mysqli_num_rows($q) > 0) {
		$r = mysqli_fetch_assoc($q);
		$response = [
			"msg" => "This Product Code Already Assign to " . $r['product_name'],
			"sts" => "error",
		];
	} else {
		$response = [
			"msg" => "",
			"sts" => "success"
		];
	}
	echo json_encode($response);
}
if (isset($_REQUEST['getBalance'])) {
	$from_balance = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT SUM(credit-debit) AS from_balance FROM transactions WHERE customer_id='" . $_REQUEST['getBalance'] . "'"));
	$cust = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM customers WHERE customer_id = '" . $_REQUEST['getBalance'] . "'"));
	if (!empty($from_balance['from_balance'])) {
		$response1 = [
			'blnc' => round($from_balance['from_balance']),
			'custLimit' => round($cust['customer_limit']),
		];
	} else {
		$response1 = [
			'blnc' => '0',
			'custLimit' => round($cust['customer_limit']),
		];
	}
	echo json_encode($response1);
}
if (isset($_REQUEST['pending_bills_detils'])) {
	$pending_bills_detils = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM orders WHERE order_id='" . base64_decode($_REQUEST['pending_bills_detils']) . "'"));
	echo json_encode($pending_bills_detils);
}
if (isset($_REQUEST['add_expense_name'])) {
	$data_array = [
		'expense_name' => $_REQUEST['add_expense_name'],
		'expense_status' => $_REQUEST['expense_status'],
	];
	if ($_REQUEST['expense_id'] == '') {
		if (insert_data($dbc, "expenses", $data_array)) {
			# code...
			$response = [
				"msg" => "expense Added successfully",
				"sts" => "success"
			];
		} else {
			$response = [
				"msg" => mysqli_error($dbc),
				"sts" => "danger"
			];
		}
	} else {
		if (update_data($dbc, "expenses", $data_array, "expense_id", $_REQUEST['expense_id'])) {
			# code...
			$response = [
				"msg" => "expense Updated successfully",
				"sts" => "success"
			];
		} else {
			$response = [
				"msg" => mysqli_error($dbc),
				"sts" => "error"
			];
		}
	}
	echo json_encode($response);
}

if (isset($_REQUEST['setAmountPaid'])) {
	$newOrder = [
		'payment_status' => 1,
		'paid' => $_REQUEST['paid'],
		'due' => 0,
	];
	if (update_data($dbc, 'orders', $newOrder, 'order_id', $_REQUEST['setAmountPaid'])) {

		$response = [
			'msg' => "Amount Has been Paid",
			'sts' => 'success'
		];
	} else {
		$response = [
			'msg' => mysqli_error($dbc),
			'sts' => 'error'
		];
	}
	echo json_encode($response);
}
if (isset($_REQUEST['setCheckStatus'])) {
	$newStat = [
		'check_status' => $_REQUEST['status'],
	];
	if (update_data($dbc, 'checks', $newStat, 'check_id', $_REQUEST['setCheckStatus'])) {

		$response = [
			'msg' => "Action Has been Perform Successfully",
			'sts' => 'success'
		];
	} else {
		$response = [
			'msg' => mysqli_error($dbc),
			'sts' => 'error'
		];
	}
	echo json_encode($response);
}
if (isset($_REQUEST['bill_customer_name'])) {
	$paidAmount = (float) $_REQUEST['bill_paid_ammount'] + (float) $_REQUEST['bill_paid'];


	if ($paidAmount > 0) {
		$transactions = fetchRecord($dbc, "orders", "order_id", $_REQUEST['order_id']);
		$order_date = date('Y-m-d');
		if ($transactions['transaction_paid_id'] > 0) {
			$credit1 = [
				'credit' => @$paidAmount,
				'debit' => 0,
				'customer_id' => @$_REQUEST['bill_payment_account'],
			];

			update_data($dbc, "transactions", $credit1, "transaction_id", $transactions['transaction_paid_id']);
			$transaction_paid_id = $transactions['transaction_paid_id'];
		} else {
			$credit1 = [
				'credit' => @$paidAmount,
				'debit' => 0,
				'customer_id' => @$_REQUEST['bill_payment_account'],
				'transaction_from' => 'invoice',
				'transaction_type' => "cash_in_hand",
				'transaction_remarks' => "cash_sale by order id#" . $_REQUEST['order_id'],
				'transaction_date' => $order_date,
			];
			insert_data($dbc, 'transactions', $credit1);
			$transaction_paid_id = mysqli_insert_id($dbc);
		}
	}
	$due_amount = (float) $_REQUEST['bill_grand_total'] - $paidAmount;
	if ($due_amount > 0) {
		$payment_status = 0; //pending
	} else {
		$payment_status = 1; //completed
	}
	$newOrder = [
		'payment_status' => $payment_status,
		'paid' => $paidAmount,
		'due' => $due_amount,
		'transaction_paid_id' => $transaction_paid_id,
	];
	if (update_data($dbc, 'orders', $newOrder, 'order_id', $_REQUEST['order_id'])) {

		$response = [
			'msg' => "Amount Has been Paid",
			'sts' => 'success'
		];
	} else {
		$response = [
			'msg' => mysqli_error($dbc),
			'sts' => 'error'
		];
	}
	echo json_encode($response);
}
if (isset($_REQUEST['LimitCustomer'])) {
	$data = [
		'check_no' => $_REQUEST['td_check_no'],
		'check_bank_name' => $_REQUEST['voucher_bank_name'],
		'check_expiry_date' => $_REQUEST['td_check_date'],
		'voucher_id' => 0,
		'customer_id' => $_REQUEST['LimitCustomer'],
		'check_amount' => $_REQUEST['check_amount'],
		'check_location' => $_REQUEST['location_info'],
		'check_type' => $_REQUEST['check_type'],
	];
	$cust = $_REQUEST['LimitCustomer'];
	$limitNow = $_REQUEST['check_amount'];

	$check = mysqli_query($dbc, "SELECT * FROM checks WHERE customer_id = '$cust' AND voucher_id = 0 AND check_amount != 0");
	//echo "SELECT * FROM checks WHERE customer_id = '$cust' AND voucher_id = 0 AND check_amount != 0";

	if (mysqli_num_rows($check) > 0) {
		$qq = mysqli_fetch_assoc($check);
		//echo $qq['check_id'];
		if (update_data($dbc, 'checks', $data, 'check_id', $qq['check_id'])) {
			mysqli_query($dbc, "UPDATE customers SET customer_limit = '$limitNow' WHERE customer_id = '$cust'");

			$response = [
				'msg' => "Data Updated successfully",
				'sts' => 'success'
			];
		}
	} else {
		if (insert_data($dbc, 'checks', $data)) {
			mysqli_query($dbc, "UPDATE customers SET customer_limit = '$limitNow' WHERE customer_id = '$cust'");
			$response = [
				'msg' => "Amount Has been Paid",
				'sts' => 'success'
			];
		} else {
			$response = [
				'msg' => mysqli_error($dbc),
				'sts' => 'error'
			];
		}
	}

	echo json_encode($response);
}


if (isset($_REQUEST['LimitCustomerajax'])) {
	$cust = $_REQUEST['LimitCustomerajax'];
	$check = mysqli_query($dbc, "SELECT * FROM checks WHERE customer_id = '$cust' AND voucher_id = 0 AND check_amount != 0");
	//echo "SELECT * FROM checks WHERE customer_id = '$cust' AND voucher_id = 0 AND check_amount != 0";
	if (mysqli_num_rows($check) > 0) {
		$qq = mysqli_fetch_assoc($check);
		//print_r($qq);
		$response = [
			'check_no' => $qq['check_no'],
			'bank_name' => $qq['check_bank_name'],
			'check_date' => $qq['check_expiry_date'],
			'check_type' => $qq['check_type'],
			'check_amount' => $qq['check_amount'],
			'check_location' => $qq['check_location'],
			'sts' => 'success',

		];
	} else {
		$response = '';
	}


	echo json_encode($response);
}

if (isset($_REQUEST['getCustomerLimit'])) {
	$cust = $_REQUEST['getCustomerLimit'];
	$q = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT customer_limit as customer_limit FROM customers WHERE customer_id = '$cust'"));
	echo $q['customer_limit'];
}


if (isset($_REQUEST['credit_order_client_name']) && isset($_REQUEST['order_return'])) {
	$get_company = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM company ORDER BY id DESC LIMIT 1"));

	if (!empty($_REQUEST['product_ids'])) {
		$total_amount = $total_grand = 0;
		$paid = (float) @$_REQUEST['paid_ammount'];

		$data = [
			'order_date' => $_REQUEST['order_date'],
			'client_name' => $_REQUEST['credit_order_client_name'],
			'client_contact' => $_REQUEST['client_contact'],
			'paid' => $paid,
			'order_narration' => @$_REQUEST['order_narration'],
			'payment_account' => @$_REQUEST['payment_account'],
			'customer_account' => @$_REQUEST['customer_account'],
			'payment_type' => 'credit',
			'credit_sale_type' => @$_REQUEST['credit_sale_type'],
			'vehicle_no' => @$_REQUEST['vehicle_no'],
			'return_days' => @$_REQUEST['return_days'],
			'freight' => @$_REQUEST['freight'],
			'user_id' => @$_REQUEST['user_id'],
			'bill_no' => @$_REQUEST['bill_no'],
		];

		$isNew = empty($_REQUEST['product_order_id']);

		if ($isNew) {
			// NEW return
			if (insert_data($dbc, 'orders_return', $data)) {
				$last_id = mysqli_insert_id($dbc);
			} else {
				$msg = mysqli_error($dbc);
				$sts = "danger";
				echo json_encode(['msg' => $msg, 'sts' => $sts, 'type' => 'order_return', 'subtype' => 'credit', 'print_url' => $get_company['print_url']]);
				return;
			}
		} else {
			// UPDATE existing return
			$last_id = $_REQUEST['product_order_id'];
			if (!update_data($dbc, 'orders_return', $data, 'order_id', $last_id)) {
				$msg = mysqli_error($dbc);
				$sts = "danger";
				echo json_encode(['msg' => $msg, 'sts' => $sts, 'type' => 'order_return', 'subtype' => 'credit', 'print_url' => $get_company['print_url']]);
				return;
			}
		}

		// Handle file upload
		if (!empty($_FILES['order_file']['tmp_name'])) {
			$uploadDir = '../img/uploads/';
			$fileName = time() . '_' . basename($_FILES['order_file']['name']);
			$uploadPath = $uploadDir . $fileName;
			if (move_uploaded_file($_FILES['order_file']['tmp_name'], $uploadPath)) {
				update_data($dbc, "orders_return", ['order_file' => $fileName], "order_id", $last_id);
			}
		}

		// Reverse old stock for updates
		if (!$isNew && $get_company['stock_manage'] == 1) {
			$res = mysqli_query($dbc, "SELECT * FROM order_return_item WHERE order_id='$last_id'");
			while ($proR = mysqli_fetch_assoc($res)) {
				$product_id = $proR['product_id'];
				$stock = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT quantity_instock FROM product WHERE product_id='$product_id'"));
				$new_qty = (float) $stock['quantity_instock'] - (float) $proR['quantity'];
				mysqli_query($dbc, "UPDATE product SET quantity_instock='$new_qty' WHERE product_id='$product_id'");
			}
		}

		// Clear old items if updating
		if (!$isNew) {
			deleteFromTable($dbc, "order_return_item", 'order_id', $last_id);
		}

		// Insert new items and update stock
		$x = 0;
		foreach ($_REQUEST['product_ids'] as $key => $value) {
			$product_id = $_REQUEST['product_ids'][$x];
			$product_quantities = (float) $_REQUEST['product_quantites'][$x];
			$product_rates = (float) $_REQUEST['product_rates'][$x];
			$total = $product_quantities * $product_rates;
			$total_amount += $total;

			$order_items = [
				'product_id' => $product_id,
				'final_rate' => @$_REQUEST['product_final_rates'][$x],
				'rate' => $product_rates,
				'total' => $total,
				'order_id' => $last_id,
				'quantity' => $product_quantities,
				'product_detail' => $_REQUEST['product_detail'][$x],
				'order_item_status' => 1,
				'user_id' => @$_REQUEST['user_id'],
			];

			if ($get_company['stock_manage'] == 1) {
				$stock = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT quantity_instock FROM product WHERE product_id='$product_id'"));
				$new_qty = (float) $stock['quantity_instock'] + $product_quantities;
				mysqli_query($dbc, "UPDATE product SET quantity_instock='$new_qty' WHERE product_id='$product_id'");
			}

			insert_data($dbc, 'order_return_item', $order_items);
			$x++;
		}

		$total_grand = $total_amount - (float) $_REQUEST['ordered_discount'];
		$due_amount = $total_grand - $paid;
		$payment_status = ($due_amount > 0) ? 0 : 1;

		// Handle transactions
		$transaction_id = 0;
		$transaction_paid_id = 0;

		if (!$isNew) {
			$existing_order = fetchRecord($dbc, "orders_return", "order_id", $last_id);
			$transaction_id = $existing_order['transaction_id'] ?? 0;
			$transaction_paid_id = $existing_order['transaction_paid_id'] ?? 0;
		}

		// Update or insert due transaction
		if ($due_amount > 0) {
			$due_transaction = [
				'credit' => 0,
				'debit' => $due_amount,
				'customer_id' => @$_REQUEST['customer_account'],
				'transaction_from' => 'sale_return',
				'transaction_type' => 'credit_sale',
				'transaction_remarks' => "credit_sale_return by order id#$last_id",
				'transaction_date' => $_REQUEST['order_date'],
			];

			if ($transaction_id > 0) {
				update_data($dbc, 'transactions', $due_transaction, 'transaction_id', $transaction_id);
			} else {
				insert_data($dbc, 'transactions', $due_transaction);
				$transaction_id = mysqli_insert_id($dbc);
			}
		} else {
			// If no due amount, clear any existing due transaction
			if ($transaction_id > 0) {
				deleteFromTable($dbc, "transactions", 'transaction_id', $transaction_id);
				$transaction_id = 0;
			}
		}

		// Update or insert paid transaction
		if ($paid > 0) {
			$paid_transaction = [
				'credit' => 0,
				'debit' => $paid,
				'customer_id' => @$_REQUEST['payment_account'],
				'transaction_from' => 'sale_return',
				'transaction_type' => 'credit_sale',
				'transaction_remarks' => "credit_sale_return by order id#$last_id",
				'transaction_date' => $_REQUEST['order_date'],
			];

			if ($transaction_paid_id > 0) {
				update_data($dbc, 'transactions', $paid_transaction, 'transaction_id', $transaction_paid_id);
			} else {
				insert_data($dbc, 'transactions', $paid_transaction);
				$transaction_paid_id = mysqli_insert_id($dbc);
			}
		} else {
			// If no paid amount, clear any existing paid transaction
			if ($transaction_paid_id > 0) {
				deleteFromTable($dbc, "transactions", 'transaction_id', $transaction_paid_id);
				$transaction_paid_id = 0;
			}
		}

		// Update order with final details
		$final_update = [
			'payment_status' => $payment_status,
			'total_amount' => $total_amount,
			'discount' => $_REQUEST['ordered_discount'],
			'grand_total' => $total_grand,
			'due' => $due_amount,
			'transaction_id' => $transaction_id,
			'transaction_paid_id' => $transaction_paid_id,
			'order_status' => 1,
		];

		if (update_data($dbc, 'orders_return', $final_update, 'order_id', $last_id)) {
			$msg = $isNew ? "Order Return Has been Added" : "Return Order Updated Successfully";
			$sts = "success";
		} else {
			$msg = mysqli_error($dbc);
			$sts = "danger";
		}
	} else {
		$msg = "Please Add at least one product";
		$sts = "error";
	}

	echo json_encode([
		'msg' => $msg,
		'sts' => $sts,
		'order_id' => @$last_id,
		'type' => "order_return",
		'subtype' => $_REQUEST['payment_type'],
		'print_url' => $get_company['print_url']
	]);
}

if (isset($_REQUEST['sale_order_client_name']) && isset($_REQUEST['order_return'])) {
	$get_company = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM company ORDER BY id DESC LIMIT 1"));

	if (!empty($_REQUEST['product_ids'])) {
		$total_amount = $total_grand = 0;
		$paidAmount = (float) @$_REQUEST['paid_ammount'];

		$data = [
			'order_date' => $_REQUEST['order_date'],
			'client_name' => $_REQUEST['sale_order_client_name'],
			'client_contact' => $_REQUEST['client_contact'],
			'paid' => $paidAmount,
			'payment_account' => @$_REQUEST['payment_account'],
			'payment_type' => 'cash_in_hand',
			'vehicle_no' => @$_REQUEST['vehicle_no'],
			'order_narration' => @$_REQUEST['order_narration'],
			'freight' => @$_REQUEST['freight'],
			'user_id' => @$_REQUEST['user_id'],
			'bill_no' => @$_REQUEST['bill_no'],
		];

		$isNew = empty($_REQUEST['product_order_id']);

		if ($isNew) {
			if (insert_data($dbc, 'orders_return', $data)) {
				$last_id = mysqli_insert_id($dbc);

				// Upload file
				if (!empty($_FILES['order_file']['tmp_name'])) {
					$uploadDir = '../img/uploads/';
					$fileName = time() . '_' . basename($_FILES['order_file']['name']);
					if (move_uploaded_file($_FILES['order_file']['tmp_name'], $uploadDir . $fileName)) {
						update_data($dbc, "orders_return", ['order_file' => $fileName], "order_id", $last_id);
					}
				}

				// Transaction
				if ($paidAmount > 0) {
					$transaction = [
						'credit' => 0,
						'debit' => $paidAmount,
						'customer_id' => @$_REQUEST['payment_account'],
						'transaction_from' => 'sale_return',
						'transaction_type' => "cash_in_hand",
						'transaction_remarks' => "Cash_sale_return by order id#{$last_id}",
						'transaction_date' => $_REQUEST['order_date'],
					];
					insert_data($dbc, 'transactions', $transaction);
					$transaction_paid_id = mysqli_insert_id($dbc);
				}

				// Insert order return items
				foreach ($_REQUEST['product_ids'] as $i => $product_id) {
					$qty = (float) $_REQUEST['product_quantites'][$i];
					$rate = (float) $_REQUEST['product_rates'][$i];
					$total = $qty * $rate;
					$total_amount += $total;

					$item = [
						'product_id' => $product_id,
						'final_rate' => @$_REQUEST['product_final_rates'][$i],
						'rate' => $rate,
						'total' => $total,
						'order_id' => $last_id,
						'quantity' => $qty,
						'product_detail' => @$_REQUEST['product_detail'][$i],
						'order_item_status' => 1,
						'user_id' => @$_REQUEST['user_id'],
					];
					insert_data($dbc, 'order_return_item', $item);

					// Stock update (increment)
					if ($get_company['stock_manage'] == 1) {
						mysqli_query($dbc, "UPDATE product SET quantity_instock = quantity_instock + $qty WHERE product_id = '$product_id'");
					}
				}

				$total_grand = $total_amount - (float) $_REQUEST['ordered_discount'];
				$due_amount = $total_grand - $paidAmount;
				$payment_status = $due_amount > 0 ? 0 : 1;

				$orderUpdate = [
					'total_amount' => $total_amount,
					'discount' => $_REQUEST['ordered_discount'],
					'grand_total' => $total_grand,
					'payment_status' => $payment_status,
					'due' => $due_amount,
					'order_status' => 1,
					'transaction_paid_id' => @$transaction_paid_id,
				];
				update_data($dbc, 'orders_return', $orderUpdate, 'order_id', $last_id);

				$msg = "Order Return has been added.";
				$sts = "success";
			} else {
				$msg = mysqli_error($dbc);
				$sts = "danger";
			}
		} else {
			// Existing order update
			$last_id = $_REQUEST['product_order_id'];
			if (update_data($dbc, 'orders_return', $data, 'order_id', $last_id)) {

				// Upload file if exists
				if (!empty($_FILES['order_file']['tmp_name'])) {
					$uploadDir = '../img/uploads/';
					$fileName = time() . '_' . basename($_FILES['order_file']['name']);
					if (move_uploaded_file($_FILES['order_file']['tmp_name'], $uploadDir . $fileName)) {
						update_data($dbc, "orders_return", ['order_file' => $fileName], "order_id", $last_id);
					}
				}

				// Rollback previous stock (optional, since it's return — might not be needed)
				if ($get_company['stock_manage'] == 1) {
					$prevItems = get($dbc, "order_return_item WHERE order_id = '$last_id'");
					while ($row = mysqli_fetch_assoc($prevItems)) {
						mysqli_query($dbc, "UPDATE product SET quantity_instock = quantity_instock - {$row['quantity']} WHERE product_id = '{$row['product_id']}'");
					}
				}

				// Delete old items
				deleteFromTable($dbc, "order_return_item", "order_id", $last_id);

				// Insert new items
				foreach ($_REQUEST['product_ids'] as $i => $product_id) {
					$qty = (float) $_REQUEST['product_quantites'][$i];
					$rate = (float) $_REQUEST['product_rates'][$i];
					$total = $qty * $rate;
					$total_amount += $total;

					$item = [
						'product_id' => $product_id,
						'rate' => $rate,
						'total' => $total,
						'order_id' => $last_id,
						'quantity' => $qty,
						'product_detail' => @$_REQUEST['product_detail'][$i],
						'order_item_status' => 1,
						'user_id' => @$_REQUEST['user_id'],
					];
					insert_data($dbc, 'order_return_item', $item);

					if ($get_company['stock_manage'] == 1) {
						mysqli_query($dbc, "UPDATE product SET quantity_instock = quantity_instock + $qty WHERE product_id = '$product_id'");
					}
				}

				// Update transaction
				if ($paidAmount > 0) {
					$transactionUpdate = [
						'credit' => 0,
						'debit' => $paidAmount,
						'customer_id' => @$_REQUEST['payment_account'],
					];
					$order = fetchRecord($dbc, "orders_return", "order_id", $last_id);
					update_data($dbc, "transactions", $transactionUpdate, "transaction_id", $order['transaction_paid_id']);
				}

				$total_grand = $total_amount - $_REQUEST['ordered_discount'];
				$due_amount = $total_grand - $paidAmount;
				$payment_status = $due_amount > 0 ? 0 : 1;

				$orderUpdate = [
					'total_amount' => $total_amount,
					'discount' => $_REQUEST['ordered_discount'],
					'grand_total' => $total_grand,
					'payment_status' => $payment_status,
					'due' => $due_amount,
				];
				update_data($dbc, 'orders_return', $orderUpdate, 'order_id', $last_id);

				$msg = "Order Return has been updated.";
				$sts = "success";
			} else {
				$msg = mysqli_error($dbc);
				$sts = "danger";
			}
		}
	} else {
		$msg = "Please add at least one product.";
		$sts = "error";
	}

	echo json_encode([
		'msg' => $msg,
		'sts' => $sts,
		'order_id' => @$last_id,
		'type' => 'order_return',
		'subtype' => 'cash_in_hand',
		'print_url' => $get_company['print_url']
	]);
}

if (isset($_REQUEST['cash_purchase_supplier']) && isset($_REQUEST['purchase_return'])) {
	if (!empty($_REQUEST['product_ids'])) {
		$total_amount = $total_grand = 0;
		$get_company = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT * FROM company ORDER BY id DESC LIMIT 1"));

		$data = [
			'purchase_date' => $_REQUEST['purchase_date'],
			'client_name' => $_REQUEST['cash_purchase_supplier'],
			'client_contact' => $_REQUEST['client_contact'],
			'purchase_narration' => @$_REQUEST['purchase_narration'],
			'payment_account' => $_REQUEST['payment_account'],
			'customer_account' => $_REQUEST['customer_account'],
			'paid' => $_REQUEST['paid_ammount'],
			'payment_status' => 1,
			'payment_type' => $_REQUEST['payment_type'],
		];

		$isNew = empty($_REQUEST['product_purchase_id']);

		if ($isNew) {
			if (insert_data($dbc, 'purchase_return', $data)) {
				$last_id = mysqli_insert_id($dbc);
			} else {
				$msg = mysqli_error($dbc);
				$sts = "danger";
				return;
			}
		} else {
			$last_id = $_REQUEST['product_purchase_id'];
			if (!update_data($dbc, 'purchase_return', $data, 'purchase_id', $last_id)) {
				$msg = mysqli_error($dbc);
				$sts = "danger";
				return;
			}
		}

		// Handle file upload
		if (!empty($_FILES['purchase_file']['tmp_name'])) {
			$uploadDir = '../img/uploads/';
			$fileName = time() . '_' . basename($_FILES['purchase_file']['name']);
			$uploadPath = $uploadDir . $fileName;

			if (move_uploaded_file($_FILES['purchase_file']['tmp_name'], $uploadPath)) {
				update_data($dbc, "purchase_return", ['purchase_file' => $fileName], "purchase_id", $last_id);
			}
		}

		// Handle stock reversal (only if update)
		if (!$isNew && $get_company['stock_manage'] == 1) {
			$oldItems = get($dbc, "purchase_return_item WHERE purchase_id='$last_id'");
			while ($row = mysqli_fetch_assoc($oldItems)) {
				$product_id = $row['product_id'];
				$current_stock = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT quantity_instock FROM product WHERE product_id='$product_id'"));
				$new_stock = (float) $current_stock['quantity_instock'] + (float) $row['quantity'];
				mysqli_query($dbc, "UPDATE product SET quantity_instock='$new_stock' WHERE product_id='$product_id'");
			}
		}

		// Clear old items if updating
		if (!$isNew) {
			deleteFromTable($dbc, "purchase_return_item", 'purchase_id', $last_id);
		}

		// Insert new items and manage stock
		foreach ($_REQUEST['product_ids'] as $x => $product_id) {
			$qty = (float) $_REQUEST['product_quantites'][$x];
			$rate = (float) $_REQUEST['product_rates'][$x];
			$sale_rate = (float) $_REQUEST['product_salerates'][$x];
			$total = $qty * $rate;
			$total_amount += $total;

			$item = [
				'product_id' => $product_id,
				'rate' => $rate,
				'sale_rate' => $sale_rate,
				'total' => $total,
				'purchase_id' => $last_id,
				'product_detail' => $_REQUEST['product_detail'][$x] ?? '',
				'quantity' => $qty,
				'purchase_item_status' => 1,
				'user_id' => $_REQUEST['user_id'] ?? null,
			];
			insert_data($dbc, 'purchase_return_item', $item);

			if ($get_company['stock_manage'] == 1) {
				$stock = mysqli_fetch_assoc(mysqli_query($dbc, "SELECT quantity_instock FROM product WHERE product_id='$product_id'"));
				$new_stock = (float) $stock['quantity_instock'] - $qty;
				mysqli_query($dbc, "UPDATE product SET quantity_instock='$new_stock' WHERE product_id='$product_id'");
			}
		}

		// Discount, total, and due
		$discount = (float) $_REQUEST['ordered_discount'];
		$paid = (float) $_REQUEST['paid_ammount'];
		$total_grand = $total_amount - $discount;
		$due = $total_grand - $paid;

		$oldTxn = fetchRecord($dbc, "purchase_return", "purchase_id", $last_id);
		$transaction_id = $transaction_paid_id = null;

		// Transaction: Credit
		if ($_REQUEST['payment_type'] === 'credit_purchase' && $due > 0) {
			$creditTxn = [
				'credit' => $due,
				'transaction_date' => $_REQUEST['purchase_date'],
				'transaction_remarks' => "purchase_return by id#$last_id",
			];

			if (!empty($oldTxn['transaction_id'])) {
				update_data($dbc, 'transactions', $creditTxn, 'transaction_id', $oldTxn['transaction_id']);
				$transaction_id = $oldTxn['transaction_id'];
			} else {
				$creditTxn = array_merge($creditTxn, [
					'debit' => 0,
					'customer_id' => $_REQUEST['customer_account'],
					'transaction_from' => 'purchase_return',
					'transaction_type' => $_REQUEST['payment_type'],
				]);
				insert_data($dbc, 'transactions', $creditTxn);
				$transaction_id = mysqli_insert_id($dbc);
			}
		}

		// Transaction: Paid
		if ($paid > 0) {
			$paidTxn = [
				'credit' => $paid,
				'transaction_date' => $_REQUEST['purchase_date'],
				'transaction_remarks' => "purchase_return id#$last_id",
			];

			if (!empty($oldTxn['transaction_paid_id'])) {
				update_data($dbc, 'transactions', $paidTxn, 'transaction_id', $oldTxn['transaction_paid_id']);
				$transaction_paid_id = $oldTxn['transaction_paid_id'];
			} else {
				$paidTxn = array_merge($paidTxn, [
					'debit' => 0,
					'customer_id' => $_REQUEST['payment_account'],
					'transaction_from' => 'purchase_return',
					'transaction_type' => $_REQUEST['payment_type'],
				]);
				insert_data($dbc, 'transactions', $paidTxn);
				$transaction_paid_id = mysqli_insert_id($dbc);
			}
		}

		// Final update
		$final = [
			'total_amount' => $total_amount,
			'discount' => $discount,
			'grand_total' => $total_grand,
			'due' => $due,
			'transaction_paid_id' => $transaction_paid_id,
			'transaction_id' => $transaction_id,
		];

		if (update_data($dbc, 'purchase_return', $final, 'purchase_id', $last_id)) {
			$msg = $isNew ? "Purchase Return has been added." : "Purchase Return has been updated.";
			$sts = "success";
		} else {
			$msg = mysqli_error($dbc);
			$sts = "danger";
		}
	} else {
		$msg = "No products selected.";
		$sts = "danger";
	}

	echo json_encode([
		'msg' => $msg,
		'sts' => $sts,
		'order_id' => $last_id,
		'type' => "purchase_return",
		'subtype' => $_REQUEST['payment_type'],
		'print_url' => $get_company['print_url']
	]);
}
