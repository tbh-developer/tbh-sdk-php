<?php
include_once './api.php';
$source_data = getSourceLang()->data;
$category_data = GetAllCategory()->data;
?>
<div><h2 class="text-center">Get Quote</h2></div>
<div class="clearfix"></div>
<br>
<div id="placeorder">
    <select class="form-control col-sm-offset-3 col-sm-3" style="width:50%" id="source_language_select" onchange="ChangeSource(this.value)" name="source_language_select">
        <option value="0">-- select source language --</option>
        <?php
        foreach ($source_data as $key => $value) {
            echo "<option value='$value->code'>$value->name</option>";
        }
        ?>
    </select>
    <select id="target_language_select" class="form-control col-sm-offset-3 col-sm-3" name="destination_language_id[]" multiple style="display: none;margin-bottom:10px;width:50%;margin-top: 10px;">

    </select>
    <ul id="myTab" class="nav nav-tabs col-sm-offset-3 col-sm-3" style="display:none;width: 50%;margin-top:10px;margin-bottom:10px;">
        <li class="active"><a data-toggle="tab" href="#tab1">Paste Text</a></li>
        <li><a data-toggle="tab" href="#tab2">Upload files</a></li>
        <!--<li><a data-toggle="tab" href="#tab3">Cloude source</a></li>-->
    </ul>
    <div class="tab-pane fade active in" id="tab1">
        <textarea id="transalation_text" class="form-control col-sm-offset-3 col-sm-3 tab-area"  name="transalation_text" placeholder="Enter your text here" style="display:none;width:50%;margin-bottom:10px;margin-top:10px;"></textarea>
    </div>
    <div class="tab-pane fade" id="tab2">
        <input type="file" name="translate_file_1[]" id="translate_file_1" onchange="upload_file()" class="tab-area col-sm-offset-3 col-sm-3" multiple="multiple" style="display:none;width:50%;margin-bottom:10px;">
    </div>
    <select id="category_list"  class="form-control col-sm-offset-3 col-sm-3 select-menu" name="category_type" style="display: none;width:50%;margin-bottom:10px;margin-top:10px;">
        <option value="0">-- Choose Category --</option>
        <?php
        foreach ($category_data as $key => $value) {
            echo "<option value='$value->id'>$value->name</option>";
        }
        ?>
    </select>
    <div id="proof" class="col-sm-offset-3" style="display:none;margin-top:10px;width:50%;">
        <label class="control-label" style="">Apply proofreading </label>
        <select id="proof_select" class="form-control" name="proof_id[]" multiple style="display: none;margin-bottom:10px;width:50%;margin-top: 10px;">

        </select>

    </div>

</div>

<br><br><br>
<a href="javasript:void(0)" class="btn btn-success col-sm-offset-3 col-sm-2" id="quote">Fetch Data</a>

<div class="col-sm-offset-3 col-lg-6 col-md-6 col-sm-12 col-xs-12">
    <h4>Response Body</h4>
    <br>
    <div class="loading">Loading...</div>
    <pre id="response_header">
        
    </pre>
</div>
<script type="text/javascript">
    function ChangeSource(lang_code) {
        handleLoading();
        $.ajax({
            url: './api.php',
            data: {lang_code: lang_code},
            type: 'GET',
            success: function (data, textStatus, jqXHR) {
                handleLoading();
                var response = JSON.parse(data);
                $("#target_language_select").css('display', 'block');
                $("#proof_select").css('display', 'block');

                var toAppend = '';
                $.each(response.data, function (i, o) {
                    toAppend += '<option value="' + o.destination_language_code + '">' + o.destination_language_name + '</option>';
                });
                $("#target_language_select").html(toAppend);
                $("#proof_select").html(toAppend);
                $('#category_list').css('display', 'block');
                $('#transalation_text').css('display', 'block');
                $('#myTab').css('display', 'block');
                $('#translate_file_1').css('display', 'block');
                $('#proof').css('display', 'block');

            }, error: function (jqXHR, textStatus, errorThrown) {

            }
        });
    }
    function upload_file() {
        handleLoading();
        var i = 1;
        var file_data = [];
        var form_data = new FormData();
        for (i = 0; i < ($("#translate_file_1")[0].files).length; i++) {
            file_data[i] = $("#translate_file_1")[0].files[i];
            form_data.append('file[]', file_data[i]);
        }
        $.ajax({
            url: './Uploadfiles.php', // point to server-side PHP script 
            dataType: 'text', // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'POST',
            success: function (php_script_response) {
                handleLoading();
                console.log('File uploaded successfully');
            }
        });
//
    }
    $('#quote').click(function () {
        handleLoading();
        var formData = new FormData();
        var source_lang = $("#source_language_select").val();
        var target_lang = $("#target_language_select").val();
        var source_text = $("#transalation_text").val();
        //var document_1 = $("#translate_file_1")[0].files[0];
        var category = $("#category_list").val();
        var proof = $('#proof_select').val();

        formData.append('source_language_code', source_lang);
        formData.append('destination_language_code', target_lang);
        formData.append('category_id', category);
        if (source_text != '') {
            formData.append('source_text', source_text);
        }
        if ($("#translate_file_1").val() != '') {
            var doc = 'document';
            for (i = 1; i < (($("#translate_file_1")[0].files).length) + 1; i++) {
                formData.append(doc + '_' + i, $("#translate_file_1")[0].files[i - 1]);
            }
        }
        formData.append('proof_reading', proof);
        $.ajax({
            url: './GetQuote.php',
            data: formData,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (data, textStatus, jqXHR) {
                handleLoading();
                $("#response_header").html(data);
            }, error: function (jqXHR, textStatus, errorThrown) {
                handleLoading();
            }
        });
    });
</script>
