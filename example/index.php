<?php
$base_url = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
include_once 'header.php';
include_once 'config.php';
?>
<style>
    .loading{
        text-align: center;
        position: absolute;
        left: 46%;
        top: 52%;
        display: none;
        color:#fff;
    }
    #response_header.active{
        background-color: rgba(0,0,0,0.6);
    }
</style>
<aside id="left-panel">
    <nav>
        <ul>
            <li><a href="<?= $base_url ?>?call=sourcelanguage">Get Source Language</a></li>
            <li><a href="<?= $base_url ?>?call=destinationlanguage">Get Target Language</a></li>
            <li><a href="<?= $base_url ?>?call=category">Get Category</a></li>
            <li><a href="<?= $base_url ?>?call=placeorder">Place Order</a></li>
            <li><a href="<?= $base_url ?>?call=orders">Get Orders</a></li>
            <li><a href="<?= $base_url ?>?call=orderdetail">Get Order Detail</a></li>
            <li><a href="<?= $base_url ?>?call=getquote">Get Quote</a></li>
        </ul>
    </nav>
</aside>
<div class="container" id="content">
    <?php
    if (isset($_GET['call'])) {

        if (file_exists('view/' . $_GET['call'] . ".php")) {
            include_once 'view/' . $_GET['call'] . ".php";
        } else {
            echo "Invalid selection";
            die();
        }
    } else {
        echo "Invalid selection";
        die();
    }
    ?>
    <!-- END MAIN CONTENT -->
</div>
<script type="text/javascript">
    function handleLoading() {
        $(".loading").toggle();
        $("#response_header").toggleClass("active");
    }
</script>