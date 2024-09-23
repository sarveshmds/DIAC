<?php

function formatDate($date)
{
    if ($date) {
        return date('Y-m-d', strtotime($date));
    }
    return null;
}

function formatDateTime($date)
{
    if ($date) {
        return date('Y-m-d H:i:s', strtotime($date));
    }
    return null;
}


function formatDatepickerDate($date)
{
    if ($date) {
        return date('d-m-Y', strtotime($date));
    }
    return '';
}

function formatReadableDate($date)
{
    if ($date) {
        return date('d-M, Y', strtotime($date));
    }
    return '';
}


function formatReadableDateTime($datetime)
{
    if ($datetime) {
        return date('d-M, Y - h:i A', strtotime($datetime));
    }
    return '';
}

function formatReadableNumberDate($date)
{
    return date('d-m-Y', strtotime($date));
}


function currentDateTimeStamp()
{
    date_default_timezone_set('Asia/Kolkata');
    return date('Y-m-d H:i:s');
}


function currentDate()
{
    date_default_timezone_set('Asia/Kolkata');
    return date('Y-m-d');
}
