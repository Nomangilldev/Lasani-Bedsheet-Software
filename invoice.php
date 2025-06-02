<!DOCTYPE html>
<?php
include("./includes/head.php");

for ($i = 0; $i < 2; $i++) :
    $totalQTY = 0;
    if ($i > 0) {
        $margin = "margin-top:-270px !important";
        $copy = "Company Copy";
    } else {
        $margin = "";
        $copy = "Customer Copy";
    }

    if ($_REQUEST['type'] == "purchase") {
        $nameSHow = 'Supplier';
        $order = fetchRecord($dbc, "purchase", "purchase_id", $_REQUEST['id']);
        $comment = $order['purchase_narration'];
        $table_row = "390px";
        $getDate = $order['purchase_date'];
        if ($order['payment_type'] == "credit_purchase") {

            $order_type = "credit purchase";
        } else {
            $order_type = "cash purchase";
        }
        $order_item = mysqli_query($dbc, "SELECT purchase_item.*,product.* FROM purchase_item INNER JOIN product ON purchase_item.product_id=product.product_id WHERE purchase_item.purchase_id='" . $_REQUEST['id'] . "'");
    } else {
        $nameSHow = 'Customer';
        $order = fetchRecord($dbc, "orders", "order_id", $_REQUEST['id']);
        $getDate = $order['order_date'];
        $comment = $order['order_narration'];
        $order_item = mysqli_query($dbc, "SELECT order_item.*,product.* FROM order_item INNER JOIN product ON order_item.product_id=product.product_id WHERE order_item.order_id='" . $_REQUEST['id'] . "'");
        if ($order['payment_type'] == "credit_sale") {
            $table_row = "300px";
            if ($order['payment_type'] == "none") {
                $order_type = "credit sale";
            } else {
                $order_type = $order['credit_sale_type'] . " (Credit)";
            }
        } else {
            $order_type = "cash sale";
            $table_row = "350px";
        }
    }
?>

    <style>
        header,
        main,
        footer {
            font-family: "Poppins", sans-serif !important;
        }

        table {
            width: 100%;
        }

        table thead {
            background-color: black;
            border: 1px solid gray;
        }

        table thead tr th {
            padding: 10px 5px !important;
            color: #fff;
        }

        table tr td {
            padding: 10px 5px !important;
            border: 1px solid gray;
        }

        table .tfoot-payment-td {
            border: none !important;
        }
    </style>

    <header>
        <div class="row pt-5 m-0 px-2">
            <div class="col-4">
                <img src="./img/logo/<?= $get_company['logo'] ?>" width="150" height="150" alt="">
            </div>
            <div class="col-8">
                <div class="d-flex  flex-column align-items-end">
                    <h1 class="text-uppercase"><?= $get_company['name'] ?></h1>
                    <h3><?= $get_company['company_phone'] ?> - <?= $get_company['personal_phone'] ?></h3>
                    <h5><?= $get_company['email'] ?></h5>
                </div>
            </div>
        </div>
    </header>

    <hr class="px-3">

    <main>
        <div class="row pt-3 px-2 m-0">
            <div class="col-4 d-flex  flex-column">
                <h4>INVOICE TO:</h4>
                <h2><?= ucwords(str_replace('-', ' ', $order['client_name'])) ?></h2>
                <div></div>
                <p class="m-0 pb-1">P : <?= $order['client_contact'] ?></p>
                <p class="m-0 pb-1">Date : <?php echo $date = date('D d-M-Y h:i A', strtotime($order['timestamp'] . " +10 hours")); ?></p>
            </div>
            <div class="col-8 d-flex  flex-column align-items-end">
                <h5>Bill No: <?= $order['bill_no'] ?></h5>
                <h2>Total: PKR <?= $order['grand_total'] ?></h2>
            </div>

            <!-- Table -->

            <div class="col-12 pt-3">
                <table>
                    <thead>
                        <tr>
                            <th>Products</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $c = 0;
                        while ($r = mysqli_fetch_assoc($order_item)) {
                            $c++;

                        ?>
                            <tr>
                                <td><?= strtoupper($r['product_name']) ?>
                                    <?php
                                    if ($r['product_detail']) {
                                    ?>
                                        (<?= $r['product_detail'] ?>)
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td class="text-center"><?= $r['quantity'] ?></td>
                                <td class="text-center"><?= $r['rate'] ?></td>
                                <td class="text-center"><?= $r['rate'] * $r['quantity'] ?></td>
                            </tr>

                        <?php
                            $totalQTY += $r['quantity'];
                        } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="tfoot-payment-td pt-3">
                                <h2>Payment Type</h2>
                                <p class="m-0 pb-1"><?= ucwords(str_replace('_', ' ', $order['payment_type'])) ?></p>
                            </td>
                            <td colspan="2">
                                <div class="row text-center">
                                    <div class="col-6 p-0">
                                        <strong>Subtotal :</strong>
                                    </div>
                                    <div class="col-6 p-0">
                                        <strong><?= $order['total_amount'] ?></strong>
                                    </div>
                                    <div class="col-6 p-0">
                                        <strong>Discount :</strong>
                                    </div>
                                    <div class="col-6 p-0">
                                        <strong><?= $order['discount'] ?></strong>
                                    </div>
                                    <div class="col-6 p-0">
                                        <strong>Freight :</strong>
                                    </div>
                                    <div class="col-6 p-0">
                                        <strong><?= empty($order['freight']) ? "0" : $order['freight'] ?></strong>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </main>

    <footer>
        <div class="row m-0 px-3 pt-5 text-center">
            <div class="col-12">
                <h1>Thank You For Your <?= ucwords($_REQUEST['type']) ?>!</h1>
            </div>
        </div>
    </footer>

<?php
endfor;
include("./includes/foot.php");
?>

<script type="text/javascript">
    window.print();
</script>