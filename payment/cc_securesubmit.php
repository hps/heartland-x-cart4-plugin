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
} catch (HpsException $e) { // order failed
    if ($e->getCode() == 27) { // order was fraud
        if ($module_params['param04'] == 'yes') { // customer enabled advanced fraud
            if ($module_params['param05'] == 'yes') {
                HpsSendEmail(
                    $module_params['param06'], // to
                    $module_params['param06'], // from
                    'Suspicious order allowed (' . $secure_oid . ')', // subject
                    'Hello,<br><br>Heartland has determined that you should review order ' . $secure_oid . ' for the amount of ' . $cart['total_cost'] . '.', // body
                    true
                );
            }

            $bill_output['code'] = 1;
            $bill_output['billmes'] = " Review. Transaction Code: " . $response->transactionId;
        } else { // advanced fraud is turned off
            $bill_output['code'] = 2;

            if ($module_params['param07'] != '') { // give them eitehr a custom error or some bad news.
                $hpsError = $module_params['param07'];
            } else {
                $hpsError = $e->getMessage();
            }

            $bill_output['billmes'] = sprintf($hpsError, $e->getMessage());
        }
    } else { // regular error
        $bill_output['code'] = 2;
        $bill_output['billmes'] = $e->getMessage();
    }
} catch (Exception $e) {
    $bill_output['code'] = 2;
    $bill_output['billmes'] = $e->getMessage();
}

function HpsSendEmail($to, $from, $subject, $body, $isHtml)
{
    $message = '<html><body>';
    $message .= $body;
    $message .= '</body></html>';

    $headers = "From: $from\r\n";
    $headers .= "Reply-To: $from\r\n";

    if ($isHtml) {
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=ISO-8859-1\r\n";
    }
    mail($to, $subject, $message, $headers);
}
