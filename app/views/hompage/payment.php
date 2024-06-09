<?php
$userDetail = Controller::model('M_Home')->getTransaksiDetail($data['doku']['bookID']);
$seatDetail = Controller::model('M_Home')->getSeatDetail($data['doku']['bookID']);

$totalGo = 0;
$totalGo_child = 0;
$totalReturn = 0;
$totalReturn_child = 0;
foreach ($seatDetail as $sD) {

    if ($sD['passengerCategory'] == 'adult') {
        $split = str_split($sD['seatNumber']);
        if ($sD['region'] == 'international') {
            // pergi 
            if ($sD['passengerCategory'] !== 'infant') {
                if ($sD['seatReturn'] == 0) {
                    $totalGo += 1;
                    if ($sD['seatCategory'] == 'VIP') {
                        $harga[] = number_format($userDetail['priceInternationalVIPGo'], 0, ",", ".");
                    } else {
                        $harga[] = number_format($userDetail['priceInternationalGo'], 0, ",", ".");
                    }
                } else {
                    // pulang
                    $totalReturn += 1;
                    if ($sD['seatCategory'] == 'VIP') {
                        $hargaReturn[] = number_format($userDetail['priceInternationalVIPReturn'], 0, ",", ".");
                    } else {
                        $hargaReturn[] = number_format($userDetail['priceInternationalReturn'], 0, ",", ".");
                    }
                }
            }
        } else {
            // pergi
            if ($sD['passengerCategory'] !== 'infant') {
                if ($sD['seatReturn'] == 0) {
                    $totalGo += 1;
                    if ($sD['seatCategory'] == 'VIP') {
                        $harga[] = number_format($userDetail['priceDomesticVIPGo'], 0, ",", ".");
                    } else {
                        $harga[] = number_format($userDetail['priceDomesticGo'], 0, ",", ".");
                    }
                } else {
                    // pulang
                    $totalReturn += 1;
                    if ($sD['seatCategory'] == 'VIP') {
                        $hargaReturn[] = number_format($userDetail['priceDomesticVIPReturn'], 0, ",", ".");
                    } else {
                        $hargaReturn[] = number_format($userDetail['priceDomesticReturn'], 0, ",", ".");
                    }
                }
            }
        }
    } else {
        $split = str_split($sD['seatNumber']);
        if ($sD['region'] == 'international') {
            // pergi 
            if ($sD['passengerCategory'] !== 'infant') {
                if ($sD['seatReturn'] == 0) {
                    $totalGo_child += 1;
                    if ($sD['seatCategory'] == 'VIP') {
                        $harga_child[] = number_format($userDetail['child_priceInternationalVIPGo'], 0, ",", ".");
                    } else {
                        $harga_child[] = number_format($userDetail['child_priceInternationalGo'], 0, ",", ".");
                    }
                } else {
                    // pulang
                    $totalReturn_child += 1;
                    if ($sD['seatCategory'] == 'VIP') {
                        $hargaReturn_child[] = number_format($userDetail['child_priceInternationalVIPReturn'], 0, ",", ".");
                    } else {
                        $hargaReturn_child[] = number_format($userDetail['child_priceInternationalReturn'], 0, ",", ".");
                    }
                }
            }
        } else {
            // pergi
            if ($sD['passengerCategory'] !== 'infant') {
                if ($sD['seatReturn'] == 0) {
                    $totalGo_child += 1;
                    if ($sD['seatCategory'] == 'VIP') {
                        $harga_child[] = number_format($userDetail['child_priceDomesticVIPGo'], 0, ",", ".");
                    } else {
                        $harga_child[] = number_format($userDetail['child_priceDomesticGo'], 0, ",", ".");
                    }
                } else {
                    // pulang
                    $totalReturn_child += 1;
                    if ($sD['seatCategory'] == 'VIP') {
                        $hargaReturn_child[] = number_format($userDetail['child_priceDomesticVIPReturn'], 0, ",", ".");
                    } else {
                        $hargaReturn_child[] = number_format($userDetail['child_priceDomesticReturn'], 0, ",", ".");
                    }
                }
            }
        }
    }
}

// menggabungkan array yang sama
if (is_array($harga)) {
    $hargaCom       = array_unique($harga);
} else {
    $hargaCom       = array($harga);
}

if (is_array($harga_child)) {
    $hargaCom_child       = array_unique($harga_child);
} else {
    $hargaCom_child       = array($harga_child);
}

if (is_array($hargaReturn)) {
    $hargaReturnCom = array_unique($hargaReturn);
} else {
    $hargaReturnCom       = array($hargaReturn);
}

if (is_array($hargaReturn_child)) {
    $hargaReturnCom_child = array_unique($hargaReturn_child);
} else {
    $hargaReturnCom_child       = array($hargaReturn_child);
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>INVOICE</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <script src="https://kit.fontawesome.com/e216f95262.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>

    <style>
        .gambar {
            margin: 0 auto;
            display: flex;
            justify-content: center;
            margin-bottom: 60px;
        }

        body {
            font-family: 'Poppins';
        }

        .label {
            padding: 10px;
            font-size: 18px;
            color: #111;
        }

        .copy-text {
            margin: 0 auto;
            width: 90%;
            position: relative;
            padding: 10px;
        }


        .copy-text input.text {
            padding: 10px;
            font-size: 18px;
            color: #555;
            border: none;
            outline: none;
            background: none;
        }

        .copy-text button {
            padding: 10px;
            background: #5784f5;
            color: #fff;
            font-size: 18px;
            border: none;
            outline: none;
            border-radius: 10px;
            cursor: pointer;
        }

        .copy-text button:active {
            background: #809ce2;
        }

        .copy-text-2 button:before {
            content: "Copied";
            position: absolute;
            top: 90px;
            right: 145px;
            background: #5c81dc;
            padding: 8px 10px;
            border-radius: 20px;
            font-size: 15px;
            display: none;
        }

        /* .copy-text-2 button:after {
            content: "";
            position: absolute;
            top: -20px;
            right: 25px;
            width: 10px;
            height: 10px;
            background: #5c81dc;
            transform: rotate(45deg);
            display: none;
        } */

        .copy-text-2.active button:before,
        .copy-text-2.active button:after {
            display: block;
        }

        footer {
            position: fixed;
            height: 50px;
            width: 100%;
            left: 0;
            bottom: 0;
            background-color: #5784f5;
            color: white;
            text-align: center;
        }

        footer p {
            margin: revert;
            padding: revert;
        }

        .mt-10 {
            margin-top: 14rem;
        }


        @media (max-width: 990px) {
            .d-desktop {
                display: none !important;
            }

            .d-mobile {
                display: block !important;
            }

            table.table.d-mobile {
                font-size: 13px;
                width: 100%;
            }

            .translate-middle {
                transform: none !important;
            }

            .start-50 {
                left: 0 !important;
            }

            .top-50 {
                top: 0 !important;
            }

            .card {
                width: 100% !important;
            }

            .copy-text {
                width: 100%;
            }

            .position-absolute {
                position: relative !important;
            }

            .copy-text-2 button:before {
                top: -65px;
                right: -25px;
            }

            .mmt-4 {
                margin: 3rem auto;
            }

            .mmt-5 {
                margin: 0;
            }

            .copy-text input.text {
                width: 207px !important;
            }
        }

        .card {
            width: 80%;
            margin: 2rem auto !important;
        }

        .d-desktop {
            text-align: left;
            display: inline-table;
        }

        .d-mobile {
            text-align: left;
            display: none;
        }

        .btn-primary {
            color: #fff !important;
            background: #5784f5 !important;
            border-color: #5784f5 !important;
        }

        .btn {
            display: inline-block;
            font-weight: 400;
            line-height: 1.5;
            color: #5784f5;
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: .375rem .75rem;
            font-size: 1rem;
            border-radius: .25rem;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .badge {
            color: #5e5e5e;
            font-size: 15px;
            font-weight: 600;
            padding: 6px 15px;
            text-shadow: none;
        }

        .badge-danger {
            background-color: #d9534f !important;
            color: #FFF !important;
        }

        .badge-success {
            background-color: #5cb85c !important;
            color: #FFF !important;
        }

        .badge-warning {
            background-color: #ffd500 !important;
        }

        .badge-info {
            background-color: #5bc0de !important;
        }

        .mmt-5 {
            margin: 4rem 0;
        }
    </style>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</head>

<body>

    <?php if ($_SESSION['session_login_grade'] == 'staff' || $_SESSION['session_login_grade'] == 'administrator') : ?>
        <a href="<?= BASEURL ?>dashboard/scan/<?= $userDetail['orderNum'] ?>" target="_blank" id="print"></a>
    <?php endif; ?>


    <div class="container mmt-4">
        <div class="card mmt-5  d-flex justify-content-center align-content-center shadow " style=" border:none;">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="invoice" role="tabpanel" aria-labelledby="home-tab">
                    <div class="card-body text-center">
                        <div class="copy-text">
                            <?php if ($userDetail['type'] !== 'On The Spot' && $userDetail['type'] !== 'Paylater' && $userDetail['type'] !== 'Cash'  && $userDetail['type'] !== 'ACC/BON'  && $userDetail['type'] !== 'Edc' && $userDetail['type'] !== 'Qris') : ?>
                                <div class="copy-text-2 position-relative">
                                    <h2><?= $userDetail['orderNum'] ?></h2>
                                    <?php if ($userDetail['type'] == 'Transfer QRIS') : ?>
                                        <p>Please Scan QR Code Below</p>
                                        <input type="hidden" value="<?= $userDetail['urlPayment'] ?>" id="qrCode">
                                        <div id="qr"></div>
                                        <script>
                                            $('#qr').qrcode($('#qrCode').val())
                                        </script>
                                    <?php else : ?>
                                        <p>Please transfer to the following account</p>
                                        <input type="text" class="text" value="<?= $userDetail['urlPayment'] ?>" readonly />
                                        <button><i class="fa fa-clone"></i></button>
                                        <p>Expired in <?= date('d M Y H:i A', strtotime($userDetail['dateExpired'])) ?> </p>
                                        <script>
                                            let copyText = document.querySelector(".copy-text-2");
                                            copyText.querySelector("button").addEventListener("click", function() {
                                                let input = copyText.querySelector("input.text");
                                                input.select();
                                                document.execCommand("copy");
                                                window.getSelection().removeAllRanges();
                                                Swal.fire('Copied');
                                            });
                                        </script>
                                    <?php endif; ?>
                                    <div class="badge badge-warning">Waiting</div>
                                </div>
                            <?php else : ?>
                                <div class="copy-text-2 position-relative">
                                    <h2><?= $userDetail['orderNum'] ?></h2>
                                    <p>Transaction paid at <?= date('d M Y H:i A') ?></p>
                                    <?php if ($userDetail['type'] !== 'Qris') : ?>
                                        <p>
                                            <?php
                                            if ($userDetail['status'] == "0") echo '<div class="badge badge-warning">Waiting</div>';
                                            elseif ($userDetail['status'] == "1") echo '<div class="badge badge-success">Success</div>';
                                            elseif ($userDetail['status'] == "4") echo '<div class="badge badge-danger">Expired</div>';
                                            ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <p style="margin-top:20px">Please check your email inbox or spam inbox</p>
                            <table class="table d-desktop">
                                <tr>
                                    <td>
                                        From
                                    </td>
                                    <td>
                                        <strong id="Dfrom"><?= $userDetail['sFrom'] ?></strong>
                                    </td>
                                    <td>
                                        To
                                    </td>
                                    <td>
                                        <strong id="Dto"> <?= $userDetail['sTo'] ?> </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Name Orderer
                                    </td>
                                    <td>
                                        <strong id="Dorder"><?= $userDetail['nameOrderer'] ?></strong>
                                    </td>
                                    <td>
                                        Phone
                                    </td>
                                    <td>
                                        <strong id="Dphone"> <?= $userDetail['phone'] ?></strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Email
                                    </td>
                                    <td>
                                        <strong id="Demail"> <?= $userDetail['emailBook']   ?></strong>
                                    </td>

                                    <td>
                                        Date Go ~ Date Return
                                    </td>
                                    <td>
                                        <strong id="Djadwal">
                                            <?= date('d M Y', strtotime($userDetail['depart'])) ?>
                                            (<?= date('H:i A', strtotime($userDetail['sTime'])) ?>)
                                            <?= ($userDetail['datereturn'] !== '0000-00-00' && $userDetail['datereturn'] !== NULL) ? '<br>' . date('d M Y', strtotime($userDetail['datereturn'])) . '(' . date('H:i A', strtotime($userDetail['timeReturn'])) . ')' : '' ?>
                                        </strong>
                                    </td>

                                </tr>

                                <tr>
                                    <td>
                                        Boat (Go)
                                    </td>
                                    <td>
                                        <strong id="Dboat"><?= $userDetail['boatNameGo'] ?></strong>
                                    </td>
                                    <td>
                                        Boat (Return)
                                    </td>
                                    <td>
                                        <strong id="DboatReturn"><?= ($userDetail['boatNameReturn'] !== NULL) ? $userDetail['boatNameReturn'] : '-' ?></strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Seat (Go)
                                    </td>
                                    <td>
                                        <strong id="Dseat">
                                            <?php foreach ($seatDetail as $seat) : ?>
                                                <?php if ($seat['seatReturn'] == '0') :  ?>
                                                    <?= $seat['seatNumber'] ?>,
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </strong>
                                    </td>
                                    <td>
                                        Seat (Return)
                                    </td>
                                    <td>
                                        <strong id="DseatReturn">
                                            <?php foreach ($seatDetail as $seat) : ?>
                                                <?php if ($seat['seatReturn'] == '1') :  ?>
                                                    <?= $seat['seatNumber'] ?>,
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Passenger
                                    </td>
                                    <td>
                                        <strong>
                                            <ol id="Dpassager">
                                                <?php foreach ($seatDetail as $seat) : ?>
                                                    <?php if ($seat['seatReturn'] == '0') :  ?>
                                                        <li><?= $seat['fullName'] ?></li>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </ol>
                                        </strong>
                                    </td>

                                    <td>
                                        Price Passenger
                                    </td>
                                    <td>
                                        <strong id="DpricePasseger">
                                            <?= $totalGo ?> x (<?php $values = '';
                                                                foreach ($hargaCom as $p) {
                                                                    $values != "" && $values .= ", ";
                                                                    $values .= $p;
                                                                }
                                                                echo $values ?>) (Depart)
                                            <br>
                                            <?php if ($totalGo_child > 0) : ?>
                                                <?= $totalGo_child ?> x (<?php $values = '';
                                                                            foreach ($hargaCom_child as $p) {
                                                                                $values != "" && $values .= ", ";
                                                                                $values .= $p;
                                                                            }
                                                                            echo $values ?>) (Depart)
                                            <?php endif; ?>
                                            <br>
                                            <?php if ($hargaReturn !== NULL) :  ?>
                                                <?= $totalReturn ?> x (<?php $values = '';
                                                                        foreach ($hargaReturnCom as $p) {
                                                                            $values != "" && $values .= ", ";
                                                                            $values .= $p;
                                                                        }
                                                                        echo $values ?>)(Return)
                                                <br>
                                                <?php if ($totalReturn_child > 0) : ?>
                                                    <?= $totalReturn_child ?> x (<?php $values = '';
                                                                                    foreach ($hargaReturn_child as $p) {
                                                                                        $values != "" && $values .= ", ";
                                                                                        $values .= $p;
                                                                                    }
                                                                                    echo $values ?>) (Return)
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </strong>
                                        <!-- <strong id="DpricePasseger">
                                            <?= $totalGo ?> x (<?php $values = '';
                                                                foreach ($hargaCom as $p) {
                                                                    $values != "" && $values .= ", ";
                                                                    $values .= $p;
                                                                }
                                                                echo $values ?>) (Depart)
                                            <br>
                                            <?php if ($hargaReturn !== NULL) :  ?>
                                                <?= $totalReturn ?> x (<?php $values = '';
                                                                        foreach ($hargaReturnCom as $p) {
                                                                            $values != "" && $values .= ", ";
                                                                            $values .= $p;
                                                                        }
                                                                        echo $values ?>) (Return)
                                            <?php endif; ?>
                                        </strong> -->
                                    </td>

                                </tr>

                                <tr>
                                    <td>
                                        Transport
                                    </td>
                                    <td>
                                        <strong id="Dtransport">
                                            <?= ($userDetail['transpotName'] !== NULL) ?  $userDetail['transpotName']  : '-' ?>
                                        </strong>
                                    </td>

                                    <td>
                                        Price Transport
                                    </td>
                                    <td>
                                        <strong id="DpriceTransport">
                                            <?php $totalPasser =  ($totalGo); ?>
                                            <?= ($userDetail['price'] !== NULL) ? $totalPasser . ' x Rp' . number_format($userDetail['price'])  : '-' ?>
                                        </strong>
                                    </td>

                                </tr>

                                <tr>
                                    <td>Category Passanger</td>
                                    <td><strong><?= strtoupper($userDetail['region']) ?></strong></td>

                                    <td>Status Passanger</td>
                                    <td><strong><?php if (($userDetail['status']) == '0') {
                                                    echo 'Waiting Payment';
                                                } else if (($userDetail['status']) == '1') {
                                                    echo 'Waiting Schedule';
                                                } else if (($userDetail['status']) == '2') {
                                                    echo 'On Depart';
                                                } else if (($userDetail['status']) == '3') {
                                                    echo 'Finished';
                                                } else if (($userDetail['status']) == '4') {
                                                    echo 'Expired';
                                                } else if (($userDetail['status']) == '5') {
                                                    echo 'On Return';
                                                } else {
                                                    echo '-';
                                                } ?></strong></td>
                                </tr>

                                <tr>
                                    <td>Payment Type</td>
                                    <td><strong><?= $userDetail['type'] ?></strong></td>
                                    <td>
                                        Total
                                    </td>
                                    <td>
                                        <?php $total =  $userDetail['nominal'] ?>
                                        <strong id="totalBayar">Rp <?= number_format($total) ?> </strong>
                                    </td>
                                </tr>

                            </table>

                            <table class="table d-mobile">
                                <tr>
                                    <td width="60%">
                                        From
                                    </td>
                                    <td width="60%">
                                        <strong id="Dfrom"><?= $userDetail['sFrom'] ?></strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        To
                                    </td>
                                    <td>
                                        <strong id="Dto"> <?= $userDetail['sTo'] ?> </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Name Orderer
                                    </td>
                                    <td>
                                        <strong id="Dorder"><?= $userDetail['nameOrderer'] ?></strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Phone
                                    </td>
                                    <td>
                                        <strong id="Dphone"> <?= $userDetail['phone'] ?></strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Email
                                    </td>
                                    <td>
                                        <strong id="Demail"> <?= $userDetail['emailBook']  ?></strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Date Go
                                    </td>
                                    <td>
                                        <strong id="Djadwal">
                                            <?= date('d M Y', strtotime($userDetail['depart'])) ?>
                                            (<?= date('H:i A', strtotime($userDetail['sTime'])) ?>)

                                        </strong>
                                    </td>
                                </tr>


                                <tr>
                                    <td>
                                        Boat (Go)
                                    </td>
                                    <td>
                                        <strong id="Dboat"><?= $userDetail['boatNameGo'] ?></strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Seat (Go)
                                    </td>
                                    <td>
                                        <strong id="Dseat">
                                            <?php foreach ($seatDetail as $seat) : ?>
                                                <?php if ($seat['seatReturn'] == '0') :  ?>
                                                    <?= $seat['seatNumber'] ?>,
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Date Return
                                    </td>
                                    <td>
                                        <strong id="Djadwal">
                                            <?= ($userDetail['datereturn'] !== '0000-00-00' && $userDetail['datereturn'] !== NULL) ? '<br>' . date('d M Y', strtotime($userDetail['datereturn'])) . '(' . date('H:i A', strtotime($userDetail['timeReturn'])) . ')' : '-' ?>
                                        </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Boat (Return)
                                    </td>
                                    <td>
                                        <strong id="DboatReturn"><?= ($userDetail['boatNameReturn'] !== NULL) ? $userDetail['boatNameReturn'] : '-' ?></strong>
                                    </td>
                                </tr>


                                <tr>
                                    <td>
                                        Seat (Return)
                                    </td>
                                    <td>
                                        <strong id="DseatReturn">
                                            <?php
                                            $cek = 0;
                                            foreach ($seatDetail as $seat) : ?>
                                                <?php
                                                if ($seat['seatReturn'] == '1') :
                                                    $cek++
                                                ?>
                                                    <?= $seat['seatNumber'] ?>,
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            <?php if ($cek == 0) :  ?>
                                                <?= '-' ?>
                                            <?php endif; ?>

                                        </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        Passager
                                    </td>
                                    <td>
                                        <strong>
                                            <ol id="Dpassager">
                                                <?php foreach ($seatDetail as $seat) : ?>
                                                    <?php if ($seat['seatReturn'] == '0') :  ?>
                                                        <li><?= $seat['fullName'] ?></li>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </ol>
                                        </strong>
                                    </td>
                                </tr>


                                <tr>
                                    <td>
                                        Price Passeger
                                    </td>
                                    <td>
                                        <strong id="DpricePasseger">
                                            <?= $totalGo ?> x (<?php $values = '';
                                                                foreach ($hargaCom as $p) {
                                                                    $values != "" && $values .= ", ";
                                                                    $values .= $p;
                                                                }
                                                                echo $values ?>) (Depart)
                                            <br>
                                            <?php if ($totalGo_child > 0) : ?>
                                                <?= $totalGo_child ?> x (<?php $values = '';
                                                                            foreach ($hargaCom_child as $p) {
                                                                                $values != "" && $values .= ", ";
                                                                                $values .= $p;
                                                                            }
                                                                            echo $values ?>) (Depart)
                                            <?php endif; ?>
                                            <br>
                                            <?php if ($hargaReturn !== NULL) :  ?>
                                                <?= $totalReturn ?> x (<?php $values = '';
                                                                        foreach ($hargaReturnCom as $p) {
                                                                            $values != "" && $values .= ", ";
                                                                            $values .= $p;
                                                                        }
                                                                        echo $values ?>)(Return)
                                                <br>
                                                <?php if ($totalReturn_child > 0) : ?>
                                                    <?= $totalReturn_child ?> x (<?php $values = '';
                                                                                    foreach ($hargaReturn_child as $p) {
                                                                                        $values != "" && $values .= ", ";
                                                                                        $values .= $p;
                                                                                    }
                                                                                    echo $values ?>) (Return)
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </strong>
                                        <!-- <strong id="DpricePasseger">
                                            <?= $totalGo ?> x (<?php $values = '';
                                                                foreach ($hargaCom as $p) {
                                                                    $values != "" && $values .= ", ";
                                                                    $values .= $p;
                                                                }
                                                                echo $values ?>) (Depart)
                                            <br>
                                            <?php if ($hargaReturn !== NULL) :  ?>
                                                <?= $totalReturn ?> x (<?php $values = '';
                                                                        foreach ($hargaReturnCom as $p) {
                                                                            $values != "" && $values .= ", ";
                                                                            $values .= $p;
                                                                        }
                                                                        echo $values ?>)(Return)
                                            <?php endif; ?>
                                        </strong> -->
                                    </td>

                                </tr>

                                <tr>
                                    <td>
                                        Transport
                                    </td>
                                    <td>
                                        <strong id="Dtransport">
                                            <?= ($userDetail['transpotName'] !== NULL) ?  $userDetail['transpotName']  : '-' ?>
                                        </strong>
                                    </td>
                                </tr>


                                <tr>
                                    <td>
                                        Price Transport
                                    </td>
                                    <td>
                                        <strong id="DpriceTransport">
                                            <?php $totalPasser =  ($totalGo); ?>
                                            <?= ($userDetail['price'] !== NULL) ? $totalPasser . ' x Rp' . number_format($userDetail['price'])  : '-' ?>
                                        </strong>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Category Passanger</td>
                                    <td><strong><?= strtoupper($userDetail['region']) ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Status Passanger</td>
                                    <td><strong><?php if (($userDetail['status']) == '0') {
                                                    echo 'Waiting Payment';
                                                } else if (($userDetail['status']) == '1') {
                                                    echo 'Waiting Schedule';
                                                } else if (($userDetail['status']) == '2') {
                                                    echo 'On Depart';
                                                } else if (($userDetail['status']) == '3') {
                                                    echo 'Finished';
                                                } else if (($userDetail['status']) == '4') {
                                                    echo 'Expired';
                                                } else if (($userDetail['status']) == '5') {
                                                    echo 'On Return';
                                                } else {
                                                    echo '-';
                                                } ?></strong></td>
                                </tr>

                                <tr>
                                    <td>Payment Type</td>
                                    <td><strong><?= $userDetail['type'] ?></td>
                                </tr>
                                <tr>
                                    <td>
                                        Total
                                    </td>
                                    <td>
                                        <?php $total =  $userDetail['nominal'] ?>
                                        <strong id="totalBayar">Rp <?= number_format($total) ?> </strong>
                                    </td>
                                </tr>


                            </table>

                            <a href="<?= BASEURL ?>" class="btn btn-primary">Close</a>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="check" role="tabpanel" aria-labelledby="profile-tab">...</div>
                </div>
            </div>

        </div>
    </div>
    <?php if ($_SESSION['session_login_grade'] == 'staff' || $_SESSION['session_login_grade'] == 'administrator') : ?>
        <script>
            $(document).ready(function() {
                document.querySelector('#print').click();
            })
        </script>
    <?php endif; ?>

</body>

</html>