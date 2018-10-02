<?php
require_once('vendor/autoload.php');

$stripe = array(
  "secret_key"      => "sk_test_tTxop19TniFweC8uIVhfZhXV",
  "publishable_key" => "pk_test_cH7PZpOvg4z4OU5JFjaEJkC4"
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);

 ?>
