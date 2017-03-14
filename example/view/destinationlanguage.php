<?php
include_once './api.php';
$source_data = getSourceLang();
$source_data = $source_data->data;
?>
<div><h2 class="text-center">Get Destination Language</h2></div>
<div class="clearfix"></div>
<br>
<div id="gettarget">
    <select class="form-control col-sm-offset-3 col-sm-3" style="width:50%" id="source_language_select" name="source_language_select" >
        <option value="0">-- select source language --</option>
        <?php
        foreach ($source_data as $key => $value) {
            echo "<option value='$value->code'>$value->name</option>";
        }
        ?>
    </select>
    <br><br>
    <a href="javasript:void(0)" class="fetchbtn btn btn-success col-sm-offset-3 col-sm-2" id="GetDestination" >Fetch Data</a>

    <div class="col-sm-offset-3 col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <h4>Response Body</h4>
        <br>
        <div class="loading">Loading...</div>
        <pre id="response_header">
            
        </pre>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            handleLoading();
            $("#GetDestination").click(function () {
                var lang_code = $('#source_language_select').val();
                $.ajax({
                    url: './api.php',
                    data: {lang_code: lang_code},
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

