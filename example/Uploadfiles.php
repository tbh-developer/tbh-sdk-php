<?php
if(isset($_FILES)) {
if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }

        $count = count($_FILES['file']['name']);
        for ($i = 0; $i < $count; $i++) {
            if (0 < $_FILES['file']['error'][$i]) {
                echo 'Error: ' . $_FILES['file']['error'][$i] . '<br>';
            } else {
                move_uploaded_file($_FILES['file']['tmp_name'][$i], 'uploads/' . $_FILES['file']['name'][$i]);
                echo 'uploads/ ' . $_FILES['file']['name'][$i] . '<br/>';
            }
        }
}