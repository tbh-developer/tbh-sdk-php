<?php

include_once 'api.php';
$place_data = PlaceOrder($_POST, $_FILES);
echo json_encode($place_data, JSON_PRETTY_PRINT);


