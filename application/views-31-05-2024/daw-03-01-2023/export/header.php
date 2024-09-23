<!DOCTYPE html>
<html>

<head>
    <title><?= $page_title ?></title>
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<style>
    body {
        font-size: 14px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        color: #222;
    }

    @page {
        margin: 50px 30px;
    }

    p {
        margin: 0;
    }

    @media all {

        .table {
            font-size: 13px;
            width: 100%;
        }

        .table tr td,
        .table tr th {
            padding: 6px 10px;
        }

        .table-bordered tr td,
        .table-bordered tr th {
            border: 1px solid grey;
            padding: 6px 10px;
        }

        .data_col b {
            display: block;
            margin-bottom: 6px;
        }

        .text-center {
            text-align: center;
        }


        /* Single Farmer Report Styling End */

        /*Common Styling for all printed pages*/
        .vfr_body_content,
        .fc_body_content {
            page-break-after: always;
            overflow: hidden;
        }

        /* Farmer Card Styling Start */
        .fc_card_body {
            padding: 0px 20px;
        }

        .fieldset {
            padding: 30px 10px;
        }

        .legend {
            font-size: 14px;
            font-weight: bold;
            background-color: black;
            padding: 5px 15px;
            color: white;
            border-radius: 5px;
        }

        .mb-10 {
            margin-bottom: 20px;
        }

        .person_info_tbl td {
            width: 33%;
            vertical-align: top;
        }

        .reg_number {
            /* text-decoration: underline; */
            /* text-align: center; */
            font-size: 20px;
            margin-top: 0px;
        }
    }
</style>

<body>