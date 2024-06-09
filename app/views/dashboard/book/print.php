<?php

$values = '';
$manifest = '';
foreach ($data['seatDetail'] as $sd) {
    if ($sd['seatReturn'] == 0) {
        $values != "" && $values .= ",";
        $values .= $sd['seatNumber'];
        $manifest .= $sd['fullName'] . '<br>';
    }

    if ($data['tsDetail']['transportID'] != '0') {
        $transport =   '<tr>
                            <td>Transport</td>
                            <td class="alignright">
                                <strong> ' . $transportDetail['transpotName'] . ' </strong>
                            </td>
                        </tr>';
    }

    if ($data['tsDetail']['scheduleReturnID'] != '0' || $data['tsDetail']['datereturn'] !== '0000-00-00') {
        $returnSeat = '';
        foreach ($seatDetail as $sdr) {
            if ($sdr['seatReturn'] == 1) {
                $returnSeat != "" && $returnSeat .= ",";
                $returnSeat .= $sdr['seatNumber'];
            }
        }
        $return  = '
                    <tr>
                        <td>Departure (Return)</td>
                        <td class="alignright"><strong>' . date('d/m/Y', strtotime($data['tsDetail']['datereturn'])) . ' ' . date('H:i A', strtotime($data['tsDetail']['timeReturn'])) . '</strong></td>
                    </tr>
                    <tr>
                        <td>Boat (Return)</td>
                        <td class="alignright"><strong>' . $data['tsDetail']['boatNameReturn'] . '</strong></td>
                    </tr>
                    <tr>
                        <td>Seat (Return)</td>
                        <td class="alignright"><strong>' . $returnSeat . '</strong></td>
                    </tr>';
    }
}


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Billing e.g. invoices and receipts</title>

    <style>
        .notif {
            font-size: 14px;
            color: #999;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
            width: 100% !important;
            height: 100%;
            line-height: 1.6;
            font-family: "Poppins";
        }

        table td {
            vertical-align: top;
        }

        body {
            background-color: #f6f6f6;
        }

        .body-wrap {
            background-color: #f6f6f6;
            width: 100%;
        }

        .container {
            display: block !important;
            max-width: 600px !important;
            margin: 0 auto !important;
            /* makes it centered */
            clear: both !important;
        }

        .content {
            max-width: 600px;
            margin: 0 auto;
            display: block;
            padding: 20px;
        }

        .main {
            background: #fff;
            border: 1px solid #e9e9e9;
            border-radius: 3px;
        }

        .content-wrap {
            padding: 20px;
        }

        .content-block {
            padding: 0 0 20px;
        }

        .header {
            width: 100%;
            margin-bottom: 20px;
        }

        .footer {
            width: 100%;
            clear: both;
            color: #999;
            padding: 20px;
        }

        .footer a {
            color: #999;
        }

        .footer p,
        .footer a,
        .footer unsubscribe,
        .footer td {
            font-size: 12px;
        }

        h1,
        h2,
        h3 {
            font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
            color: #000;
            margin: 40px 0 0;
            line-height: 1.2;
            font-weight: 400;
        }

        h1 {
            font-size: 32px;
            font-weight: 500;
        }

        h2 {
            font-size: 24px;
        }

        h3 {
            font-size: 18px;
        }

        h4 {
            font-size: 14px;
            font-weight: 600;
        }

        p,
        ul,
        ol {
            margin-bottom: 10px;
            font-weight: normal;
        }

        p li,
        ul li,
        ol li {
            margin-left: 5px;
            list-style-position: inside;
        }

        a {
            color: #1ab394;
            text-decoration: underline;
        }

        .btn-primary {
            text-decoration: none;
            color: #FFF;
            background-color: #1ab394;
            border: solid #1ab394;
            border-width: 5px 10px;
            line-height: 2;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            display: inline-block;
            border-radius: 5px;
            text-transform: capitalize;
        }

        .last {
            margin-bottom: 0;
        }

        .first {
            margin-top: 0;
        }

        .aligncenter {
            text-align: center;
        }

        .alignright {
            text-align: right;
        }

        .alignleft {
            text-align: left;
        }

        .clear {
            clear: both;
        }

        .alert {
            font-size: 16px;
            color: #fff;
            font-weight: 500;
            padding: 20px;
            text-align: center;
            border-radius: 3px 3px 0 0;
        }

        .alert a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
        }

        .alert.alert-warning {
            background: #f8ac59;
        }

        .alert.alert-bad {
            background: #ed5565;
        }

        .alert.alert-good {
            background: #1ab394;
        }

        .invoice {
            margin: 40px auto;
            text-align: left;
            width: 80%;
        }

        .invoice td {
            padding: 5px 0;
        }

        .invoice .invoice-items {
            width: 100%;
        }

        .invoice .invoice-items td {
            border-top: #eee 1px solid;
        }

        .invoice .invoice-items .total td {
            border-top: 2px dashed #333;
            border-bottom: 2px dashed #333;
            font-weight: 700;
        }

        @media only screen and (max-width: 640px) {

            h1,
            h2,
            h3,
            h4 {
                font-weight: 600 !important;
                margin: 20px 0 5px !important;
            }

            h1 {
                font-size: 22px !important;
            }

            h2 {
                font-size: 18px !important;
            }

            h3 {
                font-size: 16px !important;
            }

            .container {
                width: 100% !important;
            }

            .content,
            .content-wrap {
                padding: 10px !important;
            }

            .invoice {
                width: 100% !important;
            }
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <table class="body-wrap">
        <tr>
            <td></td>
            <td class="container" width="600">
                <div class="content">
                    <table class="main" width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="content-wrap aligncenter">
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td class="content-block">
                                            <h2>Thanks for using Cruise Booking</h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block">
                                            <table class="invoice">
                                                <tr class="text-center">
                                                    <td> <?= $data['tsDetail']['nameOrderer'] ?><br> <?= $data['payment']['orderNum'] ?>
                                                    </td>
                                                </tr>
                                                <tr class="text-center">
                                                    <td>
                                                        <img class="qr" src="<?= ASSETS ?>/barcode/<?= $data['tsDetail']['orderNum'] ?>.png" alt="">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <table class="invoice-items" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td>From</td>
                                                                <td class="alignright"> <strong><?= $data['tsDetail']['sFrom'] ?></strong> </td>
                                                            </tr>
                                                            <tr>
                                                                <td>To</td>
                                                                <td class="alignright"><strong><?= $data['tsDetail']['sTo'] ?></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Departure (Go)</td>
                                                                <td class="alignright"><strong><?= date('d/m/Y', strtotime($data['tsDetail']['depart'])) . ' ' . date('H:i A', strtotime($data['tsDetail']['sTime'])) ?></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Boat (Go)</td>
                                                                <td class="alignright"><strong><?= $data['tsDetail']['boatNameGo'] ?></strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Seat (Go)</td>
                                                                <td class="alignright"><strong><?= $values ?></strong></td>
                                                            </tr>
                                                            <?= $return ?>
                                                            <tr>
                                                                <td>Passenger</td>
                                                                <td class="alignright">
                                                                    <strong>
                                                                        <?= $manifest ?>
                                                                    </strong>
                                                                </td>
                                                            </tr>
                                                            <?= $transport ?>
                                                            <tr>
                                                                <td>Phone</td>
                                                                <td class="alignright">
                                                                    <strong> <?= $data['tsDetail']['phone'] ?> </strong>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <br>
                                                                </td>
                                                            </tr>
                                                            <tr class="total">
                                                                <td class="">Total</td>
                                                                <td class="alignright">RP <?= number_format($payment['nominal']) ?></td>
                                                            </tr>
                                                        </table>
                                                        <p class="text-center notif">*Please show the officer when you reach the port*</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <script>
        window.print();
    </script>
</body>

</html>