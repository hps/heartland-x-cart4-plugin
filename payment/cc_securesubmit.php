<?php

$securesubmit_token = $_POST["securesubmit_token"];

if (!defined('XCART_START')) {
    header("Location: ../");
    die("Access denied");
}

x_load('crypt', 'http');

require_once($xcart_dir . "/payment/includes/Hps.php");

$hpsconfig = new HpsConfiguration();
$hpsconfig->secretApiKey = $module_params['param02'];
$hpsconfig->versionNumber = '1514';
$hpsconfig->developerId = '002914';

$chargeService = new HpsCreditService($hpsconfig);

$hpsaddress = new HpsAddress();
$hpsaddress->address = $userinfo['b_address'];
$hpsaddress->city = $userinfo['b_city'];
$hpsaddress->state = $userinfo['b_state'];
$hpsaddress->zip = preg_replace('/[^0-9]/', '', $userinfo['b_zipcode']);
$hpsaddress->country = $userinfo['b_country'];

$cardHolder = new HpsCardHolder();
$cardHolder->firstName = $bill_firstname;
$cardHolder->lastName = $bill_lastname;
$cardHolder->phone = preg_replace('/[^0-9]/', '', $userinfo['phone']);
$cardHolder->emailAddress = $userinfo['email'];
$cardHolder->address = $hpsaddress;

$hpstoken = new HpsTokenData();
$hpstoken->tokenValue = $securesubmit_token;

try {
    if ($module_params['param03'] == 'authorize') {
        $response = $chargeService->authorize(
            $cart['total_cost'],
            "usd",
            $hpstoken,
            $cardHolder,
            false,
            null
        );
    } else {
        $response = $chargeService->charge(
            $cart['total_cost'],
            "usd",
            $hpstoken,
            $cardHolder,
            false,
            null
        );
    }

    $bill_output['code'] = 1;
    $bill_output['billmes'] = " Transaction Code: " . $response->transactionId;
} catch (Exception $e) {
    $bill_output['code'] = 2;
    $bill_output['billmes'] = $e->getMessage();
}
