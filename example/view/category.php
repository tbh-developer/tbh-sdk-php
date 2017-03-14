
<div><h2 class="text-center">Get Category</h2></div>
<div class="clearfix"></div>
<br>
<div id="getsource">
    <a href="javasript:void(0)" class="fetchbtn btn btn-success col-sm-offset-3 col-sm-2" id="GetCategory" >Fetch Data</a>
<!--    <input type="submit" value="Fetch Data" name="fetch" class="fetchbtn btn btn-success col-sm-offset-3 col-sm-2" id="GetSourceLang">-->
</div>
<!--</form>-->



<div class="col-sm-offset-3 col-lg-6 col-md-6 col-sm-12 col-xs-12">
    <h4>Response Body</h4>
    <div class="loading">Loading...</div>
    <pre id="response_header">
     
    </pre>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#GetCategory").click(function () {
            handleLoading();
            $.ajax({
                url: './api.php',
                type: 'POST',
                data: {category: ''},
                success: function (data, textStatus, jqXHR) {
                    handleLoading();
                    $("#response_header").html(data);
                }, error: function (jqXHR, textStatus, errorThrown) {
                    handleLoading();
                    $("#response_header").html("Error whole request" + textStatus);
                }
            });
        });

    });

</script>