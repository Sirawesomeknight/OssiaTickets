<?php
require_once('vendor/autoload.php');

$stripe = array(
  "secret_key"      => "sk_live_oSM9ajNagB67EEuZ0wvoBodj",
  "publishable_key" => "pk_live_uernK9Jly8uIssvCzYdbS1Ly"
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);

 ?>
