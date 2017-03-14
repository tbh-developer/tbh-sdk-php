<?php

include_once 'config.php';
require_once '../tbh-sdk/TbhClient.php';

$apiclient = new TbhClient(CLIENT_ID, CLIENT_SECRET, CLIENT_PUBLIC, ACCESS_TOKEN);

if (isset($_POST['lang_code'])) {
    $lang_code = $_POST['lang_code'];
    $target_data = $apiclient->GetDestinationLanguage($lang_code);
    echo json_encode($target_data, JSON_PRETTY_PRINT);
}

if (isset($_GET['lang_code'])) {
    $lang_code = $_GET['lang_code'];
    $source_data = $apiclient->GetDestinationLanguage($lang_code);
    echo json_encode($source_data);
}

if (isset($_POST['sourcelang'])) {
    $source_data = $apiclient->GetSourceLanguage();
    echo json_encode($source_data, JSON_PRETTY_PRINT);
}

if (isset($_POST['orders'])) {
    $order_data = $apiclient->GetAllOrders();
    echo json_encode($order_data, JSON_PRETTY_PRINT);
}

if (isset($_POST['category'])) {
    $category_data = $apiclient->GetCategory();
    echo json_encode($category_data, JSON_PRETTY_PRINT);
}

if (isset($_POST['order_no'])) {
    $orderno = $_POST['order_no'];
    $order_data = $apiclient->GetOrderDetail($orderno);
    echo json_encode($order_data, JSON_PRETTY_PRINT);
}

function getSourceLang() {
    $apiclient = new TbhClient(CLIENT_ID, CLIENT_SECRET, CLIENT_PUBLIC, ACCESS_TOKEN);
    $source_data = $apiclient->GetSourceLanguage();
    return $source_data;
}

function getDestination($lang_code) {
    $apiclient = new TbhClient(CLIENT_ID, CLIENT_SECRET, CLIENT_PUBLIC, ACCESS_TOKEN);
    $target_data = $apiclient->GetDestinationLanguage($lang_code);
    return $target_data;
}

function GetAllCategory() {
    $apiclient = new TbhClient(CLIENT_ID, CLIENT_SECRET, CLIENT_PUBLIC, ACCESS_TOKEN);
    $category_data = $apiclient->GetCategory();
    return $category_data;
}

function GetAllOrders() {
    $apiclient = new TbhClient(CLIENT_ID, CLIENT_SECRET, CLIENT_PUBLIC, ACCESS_TOKEN);
    $order_data = $apiclient->GetAllOrders();
    return $order_data;
}

function GetOrderDetails($orderno) {
    $apiclient = new TbhClient(CLIENT_ID, CLIENT_SECRET, CLIENT_PUBLIC, ACCESS_TOKEN);
    $order_data = $apiclient->GetOrderDetail($orderno);
    return $order_data;
}

function PlaceOrder($data, $filesarr) {
    $apiclient = new TbhClient(CLIENT_ID, CLIENT_SECRET, CLIENT_PUBLIC, ACCESS_TOKEN);
    $place_order = $apiclient->PlaceOrder($data, $filesarr);
    return $place_order;
}

function GetQuote($data, $filesarr) {
    $apiclient = new TbhClient(CLIENT_ID, CLIENT_SECRET, CLIENT_PUBLIC, ACCESS_TOKEN);
    $quote_data = $apiclient->GetQuote($data, $filesarr);
    return $quote_data;
}
