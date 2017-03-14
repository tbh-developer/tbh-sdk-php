<?php

include_once 'api.php';
$quote = GetQuote($_POST, $_FILES);
echo json_encode($quote, JSON_PRETTY_PRINT);
