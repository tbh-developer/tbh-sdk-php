
<div><h2 class="text-center">Get All Orders</h2></div>
<div class="clearfix"></div>
<br>
<div id="getsource">
    <a href="javasript:void(0)" class="fetchbtn btn btn-success col-sm-offset-3 col-sm-2" id="GetOrders" >Fetch Data</a>
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
        $("#GetOrders").click(function () {
            handleLoading();
            $.ajax({
                url: './api.php',
                type: 'POST',
                data: {orders: ''},
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