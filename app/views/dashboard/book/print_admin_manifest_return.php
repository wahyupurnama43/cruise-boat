<?php
$seatList = $data['seatDetail'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Receipt</title>

    <style>
        @font-face {
            font-family: fontFake;
            src: url('<?= ASSETS ?>/assets/fonts/fake_receipt.ttf');
        }

        * {
            font-size: 10px;
            font-family: 'Poppins';
            margin: 0;
            padding: 0;
            color: #333;
        }

        .qr {
            width: 150px !important;
            display: flex;
            justify-content: center;
            margin: 0 auto;
            margin-bottom: 20px;
        }

        td,
        th,
        tr,
        table {
            border-top: none;
            border-collapse: collapse;
            /* margin: auto; */
            width: 100%;
        }

        td.description,
        th.description {
            width: 75px;
            max-width: 75px;
        }

        td.quantity,
        th.quantity {
            width: 40px;
            max-width: 40px;
            word-break: break-all;
        }

        td.price,
        th.price {
            width: 40px;
            max-width: 40px;
            word-break: break-all;
        }

        .centered {
            text-align: center;
            align-content: center;
        }

        .ticket {
            width: 280px;
            max-width: 280px;
            margin: auto;
            /* margin-left: -10px; */
        }

        img {
            max-width: inherit;
            width: inherit;
        }

        @media print {

            .hidden-print,
            .hidden-print * {
                display: none !important;
            }
        }

        .invoice td {
            border-top: none;
        }

        .invoice .total td {
            /*border-top: 2px dashed #333;*/
            /* border-bottom: 2px dashed #333; */
            font-weight: 700;
        }

        .alignright {
            text-align: right;
        }

        .judul {
            text-align: center;
            background: #333;
        }

        .seat-notif {
            text-align: center;
            border: 1px solid #333;
            height: auto;
            padding: 10px 0;
            width: 60%;
            margin: 0 auto;
        }

        .seat-notif p {
            color: #333;
            font-size: 13px;
        }

        .seat-notif h1 {
            font-size: 30px;
            text-transform: uppercase;
            color: #333;
        }

        .judul h1 {
            color: #fff;
            font-weight: 500;
            padding: 10px 0;
            font-size: 20px;
        }

        .order-name {
            text-align: center;
            font-size: 18px;
            color: #333;
            margin: 0 !important;
        }

        .data {
            margin-bottom: 10px;
        }

        .data h6,
        .data h1 {
            margin: 0 !important;
            text-transform: uppercase;
        }

        .data h1 {
            font-size: 14px;
        }

        .data h6 {
            font-size: 9px;
        }

        .footer {
            margin-top: 20px;
        }

        .footer h1 {
            font-size: 12px;
            text-align: center;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    $values    = '';
    $transport = '';
    $return    = '';
    $row       = 1;

    $i = 0;

    if ($data['tsDetail']['scheduleReturnID'] != '0' || ($data['tsDetail']['datereturn'] !== '0000-00-00' && $data['tsDetail']['datereturn'] !== NULL)) {
        $count = $data['tsDetail']['adult'] + $data['tsDetail']['child'];
    } else {
        $count = 999;
    }

    foreach ($seatList as $sd) {
        // var_dump($sd);
        // die;
        if ($sd['passengerCategory'] !== 'infant' && $sd['id'] == $data['id_pass']) {
            $i++;
            if ($i <= $count) {
                $passeger  = '';
                $manifest = '';

                $manifest = $sd['fullName'];

                if ($data['tsDetail']['transportID'] != '0') {
                    $transport =   '<tr>
                                    <td>Transport</td>
                                    <td class="alignright">
                                        ' . $data['transportDetail']['transpotName'] . ' 
                                    </td>
                                </tr>';
                }

                if ($data['tsDetail']['scheduleReturnID'] != '0' || ($data['tsDetail']['datereturn'] !== '0000-00-00' && $data['tsDetail']['datereturn'] !== NULL)) {
                    $return  = '
                            <tr>
                                <td>Departure (Return)</td>
                                <td class="alignright">' . date('d/m/Y', strtotime($data['tsDetail']['datereturn'])) . ' ' . date('H:i A', strtotime($data['tsDetail']['timeReturn'])) . '</td>
                            </tr>
                            <tr>
                                <td>Boat (Return)</td>
                                <td class="alignright">' . $data['tsDetail']['boatNameReturn'] . '</td>
                            </tr>
                            

                            ';
                } ?>

                <div class="ticket">
                    <div class="centered">
                        <img src="<?= ASSETS ?>/assets/img/header.png" alt="" class="qr">
                    </div>
                    <div class="centered">
                        <img src="<?= ASSETS ?>/assets/img/header-2.png" alt="">
                    </div>
                    <p class="centered">
                    <div style="display:flex; justify-content:space-between; padding:5px 0;" class="data">
                        <h1>
                            <?= date('d/m/y') ?>
                        </h1>

                        <h1>
                            <?= date('H:i:s') ?>
                        </h1>
                    </div>
                    <h1 class="order-name">
                        <?= $data['payment']['orderNum'] ?>
                    </h1>
                    <?php //(file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/' . 'barcode/' . $data['tsDetail']['orderNum'] . '.png')) ? $cek_barcode = ASSETS . '/' . 'barcode/' . $data['tsDetail']['orderNum'] . '.png' :  $cek_barcode =  ASSETS . '/' . 'barcode/' . $data['tsDetail']['urlPayment'] . '.png' 
                    ?>
                    <div id="qrcode" class="qr"></div>
                    <!-- <img class="qr" src="<?= $cek_barcode ?>" alt="QRCODE"> -->
                    </p>

                    <div class="data">
                        <h6>PASSANGER NAME RETURN</h6>
                        <h1><?= $manifest ?></h1>
                    </div>

                    <div class="data">
                        <h6>ID / PASSPORT</h6>
                        <h1> <?= $sd['passengerID'] ?></h1>
                    </div>

                    <table>
                        <tr>
                            <td style="width: 50%;">
                                <div class="data">
                                    <h6>FROM</h6>
                                    <h1><?= $data['tsDetail']['sTo'] ?></h1>
                                </div>
                            </td>
                            <td align="left">
                                <div class="data">
                                    <h6>TO</h6>
                                    <h1><?= $data['tsDetail']['sFrom'] ?></h1>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td style="width: 50%;">
                                <div class="data">
                                    <h6>DEPARTURE DATE</h6>
                                    <h1><?= date('d/m/Y', strtotime($data['tsDetail']['datereturn'])) ?></h1>
                                </div>
                            </td>
                            <td align="left">
                                <div class="data">
                                    <h6>DEPARTURE TIME</h6>
                                    <h1><?= date('H:i A', strtotime($data['tsDetail']['timeReturn'])) ?></h1>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td style="width: 50%;">
                                <div class="data">
                                    <h6>VESSEL NAME</h6>
                                    <h1><?= $data['tsDetail']['boatNameGo'] ?></h1>
                                </div>
                            </td>
                            <td align="left" valign="top">
                                <div class="data">
                                    <h6>CLASS</h6>
                                    <h1><?= $sd['seatCategory']  ?></h1>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <div class="seat-notif">
                        <p>SEAT NO.</p>
                        <h1><?= $sd['seatNumber'] ?></h1>
                    </div>

                    <div class="data footer">
                        <h1>PLEASE BE AT THE BOARDING GATE 30 MINUTES BEFORE DEPATURE TIME</h1>
                    </div>
                </div>
    <?php
            }
        }
    } ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        window.print();
        window.onafterprint = window.close;
    </script>
    <script type="text/javascript">
        new QRCode(document.getElementById("qrcode"), "<?= $data['id_pass']  ?>");
    </script>
</body>



</html>