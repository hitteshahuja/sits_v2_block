<?php
define('AJAX_SCRIPT',true);
require_once('../../config.php');
global $CFG;
//This page would recieve request from JS,and return a JSON response.
require_once($CFG->dirroot . '/local/sits2/lib/sits_client_request2.class.php');
$params = json_decode($_POST['params']);
$objSITSClientRequest = new sits_client_request2($params);
$response = $objSITSClientRequest->json_respond();
echo json_encode($response);
