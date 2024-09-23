<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
</head>

<style>
    * {
        margin: 0px;
        padding: 0px;
        font-family: Arial, Helvetica, sans-serif;
    }

    p {
        margin-bottom: 0px;
    }

    body {
        padding: 40px;
        font-size: 16px;
    }

    .page-break {
        page-break-before: always;
        /* or page-break-after: always; */
    }

    .mb-10 {
        margin-bottom: 10px;
    }

    .mt-10 {
        margin-top: 10px;
    }

    .text-center {
        text-align: center;
    }

    .content-heading {
        margin-bottom: 20px;
    }

    .content-title {
        font-weight: bold;
        font-size: 20px;
        text-decoration: underline;
    }

    .content-subtitle {
        font-size: 18px;
        margin-top: 10px;
    }

    .table {
        width: 100%;
        border-spacing: 0px;
    }

    .table tr th {
        background-color: #f1f1f1;
    }

    .table tr td,
    .table tr th {
        padding: 5px 10px;
    }

    .table-bordered tr td,
    .table-bordered tr th {
        border: 1px solid lightgrey;
    }

    .table-text-center tr td,
    .table-text-center tr th {
        text-align: center;
        font-size: 12px;
        word-wrap: break-word;
        word-break: break-all;
    }

    .case-details-wrappers {
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 10px;
    }

    .form-label {
        font-weight: bold;
    }

    .view_col {
        padding: 10px;
    }

    .w-25 {
        width: 25% !important;
    }
</style>

<body>