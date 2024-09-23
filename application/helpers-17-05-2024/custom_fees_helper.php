<?php

function money_round_off($amount)
{
    return ceil($amount);
}

function get_current_dollar_to_rupee()
{
    $req_url = 'https://api.exchangerate.host/convert?from=USD&to=INR';
    // $req_url = 'https://api.exchangerate-api.com/v4/latest/USD';
    $response_json = file_get_contents($req_url);
    if (false !== $response_json) {
        try {
            $response = json_decode($response_json, true);
            if ($response['success'] === true) {
                return $response['result'];
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
            // throw new Error('Service is not available. Please contact support team');
            // die;
        }
    }
}

function dollar_to_rupee($amount)
{
    $amount = $amount;
    // $current_dollar_in_rupee = get_current_dollar_to_rupee();
    $current_dollar_in_rupee = 83;
    if ($current_dollar_in_rupee) {
        return money_round_off($amount * $current_dollar_in_rupee);
    }
    throw new Error('DOLLAR TO RUPEE: Service is not available. Please contact support team');
    die;
}

function rupee_to_dollar($amount)
{
    $amount = $amount;
    // $current_dollar_in_rupee = get_current_dollar_to_rupee();
    $current_dollar_in_rupee = 83;
    if ($current_dollar_in_rupee) {
        return money_round_off($amount / $current_dollar_in_rupee);
    }
    throw new Error('RUPEE TO DOLLAR: Service is not available. Please contact support team');
    die;
}

function rupee_to_dollar_using_existing_dollar($amount, $dollar_price)
{
    return money_round_off($amount / $dollar_price);
}

function current_dollar_price()
{
    return round(get_current_dollar_to_rupee(), 2);
}

function current_dollar_price_text()
{
    $price = get_current_dollar_to_rupee();
    if ($price) {
        return '1$ = ' . round($price, 2) . ' Rs.';
    }
    return 'Not Found';
}


function INDMoneyFormat($amount)
{
    $fmt = new NumberFormatter($locale = 'en_IN', NumberFormatter::CURRENCY);
    return $fmt->format($amount);
}


function dollarMoneyFormat($amount)
{
    $fmt = new NumberFormatter($locale = 'en_US', NumberFormatter::CURRENCY);
    return $fmt->format($amount);
}

function calculateBalance($amount1 = 0, $amount2 = 0)
{
    $amount1 = ($amount1) ? $amount1 : 0;
    $amount2 = ($amount2) ? $amount2 : 0;

    if ($amount1 >= $amount2) {
        return $amount1 - $amount2;
    }
    return 0;
}

function calculateExcess($amount1 = 0, $amount2 = 0)
{
    $amount1 = ($amount1) ? $amount1 : 0;
    $amount2 = ($amount2) ? $amount2 : 0;

    if ($amount1 < $amount2) {
        return $amount1 - $amount2;
    }
    return 0;
}

function symbol_checker($type_of_arbitration, $amount)
{
    if ($type_of_arbitration == 'DOMESTIC') {
        return INDMoneyFormat($amount);
    }
    if ($type_of_arbitration == 'INTERNATIONAL') {
        return dollarMoneyFormat($amount);
    }
    return $amount;
}

function dollar_converstion_if_international($type_of_arbitration, $amount, $current_dollar_price = '')
{
    if ($type_of_arbitration == 'INTERNATIONAL') {
        return dollarMoneyFormat(rupee_to_dollar_using_existing_dollar($amount, $current_dollar_price));
    }
    return '';
}
