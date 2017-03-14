<div><h2 class="text-center">Get Order Details</h2></div>
<div class="clearfix"></div>
<br>
<div id="getorderdetail">

    <label class="control-label col-sm-offset-3 col-sm-2">Enter Order No :</label>
    <input type="text" id="order_no" class="col-sm-3">
    <br><br><br>
    <a href="javasript:void(0)" class="btn btn-success col-sm-offset-3 col-sm-2" id="GetOrderDetail">Fetch Data</a>
</div>

<div class="col-sm-offset-3 col-lg-6 col-md-6 col-sm-12 col-xs-12">
    <h4>Response Body</h4>
    <br>
    <div class="loading">Loading...</div>
    <pre id="response_header">
        
    </pre>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#GetOrderDetail").click(function () {
            handleLoading();
            var order_no = $('#order_no').val();
            $.ajax({
                url: './api.php',
                data: {order_no: order_no},
                type: 'POST',
                success: function (data, textStatus, jqXHR) {
                    $("#response_header").html(data);
                    handleLoading();
                }, error: function (jqXHR, textStatus, errorThrown) {
                    handleLoading();
                }
            });
        });

    });

</script>

