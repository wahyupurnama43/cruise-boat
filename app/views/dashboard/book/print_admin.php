<?php
$values = '';
$manifest = '';
$row = 1;
foreach ($data['seatDetail'] as $sd) {
    $passeger  = '';
    if ($sd['seatReturn'] == 0) {
        // $row++;
        // if ($row % 6 == 0) {
        //     $br = '<br>';
        // } else {
        //     $br = '';
        // }
        // $values != "" && $values .= ",";
        // $values .= $sd['seatNumber'] . $br;
        // var_dump($sd);
        // die;
        $seatReturnNew = Controller::model('M_Book')->getSeat($sd['bookID'], $sd['fullName'], 'return');

        if ($seatReturnNew) {
            $tambahan = ' (' . $seatReturnNew['seatNumber'] . ')';
        }

        $manifest .= substr($sd['fullName'], 0, 15) . ' (' . $sd['seatNumber'] . ')' . $tambahan . '<br>';
    }
    

    if ($data['tsDetail']['transportID'] != '0') {
        $transport =   '<tr>
                            <td>Transport</td>
                            <td class="alignright">
                                ' . $data['transportDetail']['transpotName'] . ' 
                            </td>
                        </tr>';
    }

    if ($data['tsDetail']['scheduleReturnID'] != '0' || ($data['tsDetail']['datereturn'] !== '0000-00-00' && $data['tsDetail']['datereturn'] !== NULL)) {
        $returnSeat = '';
        $rows = 1;
        foreach ($data['seatDetail'] as $sdr) {
            if ($sdr['seatReturn'] == 1) {
                $rows++;
                if ($rows % 6 == 0) {
                    $br = '<br>';
                } else {
                    $br = '';
                }
                $returnSeat != "" && $returnSeat .= ",";
                $returnSeat .= $sdr['seatNumber'] . $br;
            }
        }
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
    }
}

// <tr>
//                         <td>Seat (Return)</td>
//                         <td class="alignright" style="font-size:9px">' . $returnSeat . '</td>
//                     </tr>

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
            font-family: 'fontFake';
        }

        td,
        th,
        tr,
        table {
            border-top: 1px solid black;
            border-collapse: collapse;
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
            width: 180px;
            max-width: 180px;
            margin-left: -10px;
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
            border-top: #eee 1px solid;
        }

        .invoice .total td {
            border-top: 2px dashed #333;
            /* border-bottom: 2px dashed #333; */
            font-weight: 700;
        }

        .alignright {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="ticket">
        <p class="centered">
            <?= $data['tsDetail']['nameOrderer'] ?><br> <?= $data['payment']['orderNum'] ?>
            <br>
            <br>
            <!-- <?php (@GetImageSize(ASSETS . '/' . 'barcode/' . $data['tsDetail']['orderNum'] . '.png')) ? $cek_barcode = ASSETS . '/' . 'barcode/' . $data['tsDetail']['orderNum'] . '.png' :  $cek_barcode =  ASSETS . '/' . 'barcode/' . $data['tsDetail']['urlPayment'] . '.png' ?>

            <img class="qr" src="<?= $cek_barcode ?>" alt=""> -->
        </p>
        <table class="invoice">
            <tbody width="100%">
                <tr>
                    <td>From</td>
                    <td class="alignright"> <?= $data['tsDetail']['sFrom'] ?> </td>
                </tr>
                <tr>
                    <td>To</td>
                    <td class="alignright"><?= $data['tsDetail']['sTo'] ?></td>
                </tr>
                <tr>
                    <td>Departure (Go)</td>
                    <td class="alignright"><?= date('d/m/Y', strtotime($data['tsDetail']['depart'])) . ' ' . date('H:i A', strtotime($data['tsDetail']['sTime'])) ?></td>
                </tr>
                <tr>
                    <td>Boat (Go)</td>
                    <td class="alignright"><?= $data['tsDetail']['boatNameGo'] ?></td>
                </tr>
                <!-- <tr>
                    <td>Seat (Go)</td>
                    <td class="alignright" style="font-size:10px"><?= $values ?></td>
                </tr> -->
                <?= $return ?>
                <tr>
                    <td style="vertical-align: top;">Passenger</td>
                    <td class="alignright">
                        <?= $manifest ?>
                    </td>
                </tr>
                <?= $transport ?>
                <tr>
                    <td>Phone</td>
                    <td class="alignright">
                        <?= $data['tsDetail']['phone'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <br>
                    </td>
                </tr>
                <tr class="total">
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <p class="centered">Thanks for using <?= COMPANY ?></p>
    </div>
</body>

<script>
    window.print();
    window.onafterprint = window.close;
</script>

</html>