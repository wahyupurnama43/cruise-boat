<?php

class M_Home
{
    private $db;

    public function __construct()
    {
        date_default_timezone_set('Asia/Ujung_Pandang');
        $this->db = new Database();
        $this->mail = new PHPMailer\PHPMailer\PHPMailer();
    }

    public function getCountry()
    {
        $sql = "SELECT * FROM tb_country WHERE phonecode !='0' AND phonecode !='1'  ORDER BY phonecode ASC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getsFrom()
    {
        $sql = "SELECT DISTINCT sFrom FROM tb_schedule";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getsTo($tb, $row, $id)
    {
        $sql = "SELECT DISTINCT sTo FROM $tb WHERE $row = '$id'";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getSchedule()
    {
        $sql = "SELECT * FROM tb_schedule  ORDER BY tb_schedule.dateCreate DESC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function cekSchedule($date, $to, $from)
    {
        $date = htmlspecialchars(trim($date), ENT_QUOTES);
        $to = htmlspecialchars(trim($to), ENT_QUOTES);
        $from = htmlspecialchars(trim($from), ENT_QUOTES);

        $sql = "SELECT tb_schedule.* FROM tb_schedule INNER JOIN tb_boat ON tb_schedule.boatID = tb_boat.id WHERE sDay = '$date' AND sTo = '$to'  AND sFrom = '$from' AND boatStatus = 'enable' ";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function imgEmail()
    {
        $sql = "SELECT value FROM tb_setting WHERE name = 'HEADER_EMAIL'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function getImg($id)
    {
        $sql = "SELECT imgDirectory, imgName FROM tb_images WHERE imgTableID = '$id'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function getScByBoat($id)
    {
        $sql = "SELECT * FROM tb_schedule WHERE boatID = '$id' ORDER BY CASE
                                                                            WHEN sDay = 'Sunday' THEN 1
                                                                            WHEN sDay = 'Monday' THEN 2
                                                                            WHEN sDay = 'Tuesday' THEN 3
                                                                            WHEN sDay = 'Wednesday' THEN 4
                                                                            WHEN sDay = 'Thursday' THEN 5
                                                                            WHEN sDay = 'Friday' THEN 6
                                                                            WHEN sDay = 'Saturday' THEN 7
                                                                        END ASC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getScheduleBy($date, $boat, $to, $from)
    {
        $sql = "SELECT tb_schedule.* FROM tb_schedule INNER JOIN tb_boat ON tb_schedule.boatID = tb_boat.id WHERE sDay = '$date' AND boatID = '$boat' AND sTo = '$to'  AND sFrom = '$from' AND boatStatus = 'enable' ";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getSeat($id)
    {
        $sql = "SELECT * FROM tb_boat WHERE id = '$id'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function getJadwalBy($id)
    {
        $sql = "SELECT sTime,boatName,priceDomestic, priceInternational, priceDomesticVIP, priceInternationalVIP,child_priceDomestic, child_priceInternational, child_priceDomesticVIP, child_priceInternationalVIP FROM tb_schedule INNER JOIN tb_boat ON tb_schedule.boatID = tb_boat.id WHERE tb_schedule.id = '$id'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function getTransportBy($id)
    {
        $sql = "SELECT * FROM tb_transport WHERE id = '$id'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function getSeatBooking($seat, $sc, $date)
    {

        // update kursi
        $dates = date('Y-m-d H:i:s');
        // return $date;
        $sql2 = "SELECT bookID, type, model FROM `tb_payment` WHERE (dateExpired < '$dates' or dateExpired is NULL) AND status = 'waiting' AND urlPayment != ''";
        $this->db->query($sql2);
        $data = $this->db->resultSet();

        if ($data) {
            foreach ($data as $d) {
                $id = $d['bookID'];
                if ($d['model'] !== "ots" and $d['model'] !== "later") { // jika bukan on the spot atau pay later
                    $sql = "UPDATE tb_payment SET status=:status WHERE bookID = '$id'";
                    $this->db->query($sql);
                    $this->db->bind('status', 'expired');
                    $this->db->execute();

                    $sql4 = "UPDATE tb_book SET status=:status WHERE id = '$id'";
                    $this->db->query($sql4);
                    $this->db->bind('status', '4');
                    $this->db->execute();

                    $sql3 = "DELETE FROM tb_manifest WHERE bookID = '$id'";
                    $this->db->query($sql3);
                    $this->db->execute();
                } else {
                }
            }
        }

        $sql = "SELECT * FROM tb_manifest INNER JOIN tb_book ON tb_manifest.bookID = tb_book.id WHERE tb_manifest.seatNumber = '$seat' AND tb_manifest.scheduleID = '$sc' AND $date";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function checkDisable($seat, $boat)
    {
        $dataSc = "SELECT boatID FROM tb_schedule WHERE id ='$boat'";
        $this->db->query($dataSc);
        $sc = $this->db->single();

        $sql = "SELECT * FROM tb_seatDisable WHERE seat ='$seat' AND id_boat = '$sc[boatID]'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function createIpaymu($data, $inv, $IDBOOK)
    {
        if ($data['payment'] !== 'ots' && $data['payment'] !== 'later' && $data['payment'] !== 'cc') {

            if ($data['payment'] == 'qris') {
                $pymethod = 'qris';
            } else {
                $pymethod = 'va';
            }

            $url = IPAYMUDIRECT; //url
            $method = 'POST'; //method
            $secret = APIKEY;
            $va = VAKEY;

            // $body['product']            = array('headset');
            $body['referenceId'] = $inv;
            $body['amount'] = $data['idTotal'];
            $body['notifyUrl'] = BASEURL . 'callback';
            $body['paymentMethod'] = $pymethod;
            $body['paymentChannel'] = $data['payment'];
            $body['name'] = $data['namefirst'] . " " . $data['namelast'];
            $body['phone'] = $data['wa'];
            $body['email'] = $data['email'];
            $body['expiredType'] = 'minutes';
            $body['expired'] = '15';
            //End Request Body//

            //Generate Signature
            // *Don't change this
            $jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES);
            $requestBody = strtolower(hash('sha256', $jsonBody));
            $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $secret;
            $signature = hash_hmac('sha256', $stringToSign, $secret);
            $timestamp = Date('YmdHis');
            //End Generate Signature

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/json',
                'va: ' . $va,
                'signature: ' . $signature,
                'timestamp: ' . $timestamp,
            );

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            curl_setopt($ch, CURLOPT_POST, count($body));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $err = curl_error($ch);
            $response = curl_exec($ch);
            curl_close($ch);

            $ret = json_decode($response);

            if ($err) {
                $sqldelete = "DELETE FROM tb_book WHERE id=:id";
                $this->db->query($sqldelete);
                $this->db->bind('id', $IDBOOK);
                $this->db->execute();

                $sqldeletepay = "DELETE FROM tb_payment WHERE bookID=:bookID";
                $this->db->query($sqldeletepay);
                $this->db->bind('bookID', $IDBOOK);
                $this->db->execute();

                $sqldeletemanifest = "DELETE FROM tb_manifest WHERE bookID=:bookID";
                $this->db->query($sqldeletemanifest);
                $this->db->bind('bookID', $IDBOOK);
                $this->db->execute();

                Flasher::setFlash('Payment Gateway Error', 'error');
                header('Location: ' . BASEURL);
            } else {
                //Response
                if ($ret->Status == 200) {
                    if ($data['payment'] == 'qris') {
                        $virtualNumber = $ret->Data->QrString;
                    } else {
                        $virtualNumber = $ret->Data->PaymentNo;
                    }

                    $invoice = $ret->Data->ReferenceId;
                    $expired = date('Y-m-d H:i:s', strtotime($ret->Data->Expired) + 60 * 60);

                    $sql = "UPDATE tb_payment SET urlPayment='$virtualNumber', dateExpired='$expired' WHERE orderNum = '$inv'";
                    $this->db->query($sql);
                    $this->db->execute();

                    $return = [
                        'url' => '',
                        'inv' => $invoice,
                        'cc' => 'false',
                    ];
                    return $return;
                } else {

                    $sqldelete = "DELETE FROM tb_book WHERE id=:id";
                    $this->db->query($sqldelete);
                    $this->db->bind('id', $IDBOOK);
                    $this->db->execute();

                    $sqldeletepay = "DELETE FROM tb_payment WHERE bookID=:bookID";
                    $this->db->query($sqldeletepay);
                    $this->db->bind('bookID', $IDBOOK);
                    $this->db->execute();

                    $sqldeletemanifest = "DELETE FROM tb_manifest WHERE bookID=:bookID";
                    $this->db->query($sqldeletemanifest);
                    $this->db->bind('bookID', $IDBOOK);
                    $this->db->execute();

                    Flasher::setFlash($ret->Message, 'error');
                    header('Location: ' . BASEURL);
                }
                //End Response
            }
        } else {

            $book = $this->checkTransaksi($inv);
            $seat = $this->getSeatDetail($book['bookID']);
            $userDetail = $this->getTransaksiDetail($book['bookID']);

            $totalGo = 0;
            // foreach untuk nama passanger
            foreach ($seat as $s) {
                if ($s['seatReturn'] == 0) {
                    $totalGo++;
                    $passeger[] = $s['fullName'] . ' (' . $s['seatNumber'] . ')';
                    $totalQTY[] = '1';
                }
                if ($s['region'] == 'international') {
                    // pergi
                    if ($s['seatReturn'] == 0) {
                        if ($s['seatCategory'] == 'VIP') {
                            $harga[] = $userDetail['priceInternationalVIPGo'];
                        } else {
                            $harga[] = $userDetail['priceInternationalGo'];
                        }
                    }
                } else {
                    // pergi
                    if ($s['seatReturn'] == 0) {
                        if ($s['seatCategory'] == 'VIP') {
                            $harga[] = $userDetail['priceDomesticVIPGo'];
                        } else {
                            $harga[] = $userDetail['priceDomesticGo'];
                        }
                    }
                }
            }

            if ($data['dataReturn'] !== '') {
                foreach ($seat as $s) {
                    if ($s['seatReturn'] == 1) {
                        $passeger[] = "(Return) " . $s['fullName'] . ' (' . $s['seatNumber'] . ')';
                        $totalQTY[] = '1';
                    }
                    if ($s['region'] == 'international') {
                        // pergi
                        if ($s['seatReturn'] == 1) {
                            // pulang
                            if ($s['seatCategory'] == 'VIP') {
                                $harga[] = $userDetail['priceInternationalVIPReturn'];
                            } else {
                                $harga[] = $userDetail['priceInternationalReturn'];
                            }
                        }
                    } else {
                        // pergi
                        if ($s['seatReturn'] == 1) {
                            // pulang
                            if ($s['seatCategory'] == 'VIP') {
                                $harga[] = $userDetail['priceDomesticVIPReturn'];
                            } else {
                                $harga[] = $userDetail['priceDomesticReturn'];
                            }
                        }
                    }
                }
            }

            if ($userDetail['transpotName'] !== null) {
                $passeger[] = $userDetail['transpotName'];
                $totalQTY[] = $totalGo;
                $harga[] = $userDetail['price'];
            }

            $secret = APIKEY;
            $va = VAKEY;
            $url = IPAYMUREDIRECT;
            $method = 'POST';

            //Request Body//
            $body['product'] = $passeger;
            $body['qty'] = $totalQTY;
            $body['price'] = $harga;
            $body['returnUrl'] = BASEURL . 'callback/success';
            $body['cancelUrl'] = BASEURL . 'callback/error';
            $body['notifyUrl'] = BASEURL . 'callback';
            $body['referenceId'] = $inv;
            $body['expiredType'] = 'minutes';
            $body['expired'] = '15';
            //End Request Body//

            //Generate Signature
            // *Don't change this
            $jsonBody = json_encode($body, JSON_UNESCAPED_SLASHES);
            $requestBody = strtolower(hash('sha256', $jsonBody));
            $stringToSign = strtoupper($method) . ':' . $va . ':' . $requestBody . ':' . $secret;
            $signature = hash_hmac('sha256', $stringToSign, $secret);
            $timestamp = Date('YmdHis');
            //End Generate Signature

            $ch = curl_init($url);

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/json',
                'va: ' . $va,
                'signature: ' . $signature,
                'timestamp: ' . $timestamp,
            );

            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            curl_setopt($ch, CURLOPT_POST, count($body));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $err = curl_error($ch);
            $ret = curl_exec($ch);
            curl_close($ch);

            if ($err) {
                $sqldelete = "DELETE FROM tb_book WHERE id=:id";
                $this->db->query($sqldelete);
                $this->db->bind('id', $IDBOOK);
                $this->db->execute();

                $sqldeletepay = "DELETE FROM tb_payment WHERE bookID=:bookID";
                $this->db->query($sqldeletepay);
                $this->db->bind('bookID', $IDBOOK);
                $this->db->execute();

                $sqldeletemanifest = "DELETE FROM tb_manifest WHERE bookID=:bookID";
                $this->db->query($sqldeletemanifest);
                $this->db->bind('bookID', $IDBOOK);
                $this->db->execute();

                Flasher::setFlash('Payment Gateway Error', 'error');
                header('Location: ' . BASEURL);
            } else {

                //Response
                $ret = json_decode($ret);
                if ($ret->Status == 200) {
                    $url = $ret->Data->Url;
                    $virtualNumber = $ret->Data->SessionID;

                    $dateexpired = date('Y-m-d H:i:s', strtotime('+15 minute'));
                    $sql = "UPDATE tb_payment SET urlPayment='$virtualNumber', dateExpired='$dateexpired' WHERE orderNum = '$inv'";
                    $this->db->query($sql);
                    $this->db->execute();
                    $return = [
                        'url' => $url,
                        'inv' => $inv,
                        'cc' => 'true',
                    ];
                    return $return;
                } else {
                    $sqldelete = "DELETE FROM tb_book WHERE id=:id";
                    $this->db->query($sqldelete);
                    $this->db->bind('id', $IDBOOK);
                    $this->db->execute();

                    $sqldeletepay = "DELETE FROM tb_payment WHERE bookID=:bookID";
                    $this->db->query($sqldeletepay);
                    $this->db->bind('bookID', $IDBOOK);
                    $this->db->execute();

                    $sqldeletemanifest = "DELETE FROM tb_manifest WHERE bookID=:bookID";
                    $this->db->query($sqldeletemanifest);
                    $this->db->bind('bookID', $IDBOOK);
                    $this->db->execute();

                    Flasher::setFlash($ret->Message, 'error');
                    header('Location: ' . BASEURL);
                }
                //End Response
            }
        }
    }

    public function sendMail($tsDetail, $seatDetail, $transportDetail, $inv, $file_name)
    {

        $payment = $this->searchTransaksi($inv);

        if ($tsDetail['transportID'] != '0') {
            $transport = '<tr>
                                <td>Transport</td>
                                <td class="alignright">
                                    <strong> ' . $transportDetail['transpotName'] . ' </strong>
                                </td>
                            </tr>';
        }

        if ($tsDetail['scheduleReturnID'] != '0' || ($tsDetail['datereturn'] !== '0000-00-00' && $tsDetail['datereturn'] !== null)) {

            $return = '
                    <tr>
                        <td>Departure (Return)</td>
                        <td class="alignright"><strong>' . date('d/m/Y', strtotime($tsDetail['datereturn'])) . ' ' . date('H:i A', strtotime($tsDetail['timeReturn'])) . '</strong></td>
                    </tr>
                    <tr>
                        <td>Boat (Return)</td>
                        <td class="alignright"><strong>' . $tsDetail['boatNameReturn'] . '</strong></td>
                    </tr>
                    ';
        }

        $values = '';
        $manifest = '';

        foreach ($seatDetail as $sd) {

            $sqlreturn = "SELECT * FROM tb_manifest WHERE bookID = '$sd[bookID]' AND fullName = '$sd[fullName]' AND seatReturn = '1'";
            $this->db->query($sqlreturn);
            $seatReturnNew = $this->db->single();

            if ($seatReturnNew) {
                $seatNew = ' (' . $seatReturnNew['seatNumber'] . ')';
            }

            if ($sd['seatReturn'] == 0) {
                $manifest .= $sd['fullName'] . ' (' . $sd['seatNumber'] . ') ' . $seatNew . '<br>';
            }
        }

        $messages = '
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
                                                                    <h2>Thanks for using ' . COMPANY . '</h2>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="content-block">
                                                                    <table class="invoice">
                                                                        <tr class="text-center">
                                                                            <td>' . $tsDetail['nameOrderer'] . ' <br> ' . $payment['orderNum'] . '
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="text-center">
                                                                            <td>
                                                                                <img class="qr" src="' . ASSETS . '/barcode' . '/' . $file_name . '"
                                                                                    alt="">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <table class="invoice-items" cellpadding="0" cellspacing="0">
                                                                                    <tr>
                                                                                        <td>From</td>
                                                                                        <td class="alignright"> <strong>' . $tsDetail['sFrom'] . '</strong> </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>To</td>
                                                                                        <td class="alignright"><strong>' . $tsDetail['sTo'] . '</strong></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>Departure (Go)</td>
                                                                                        <td class="alignright"><strong>' . date('d/m/Y', strtotime($tsDetail['depart'])) . ' ' . date('H:i A', strtotime($tsDetail['sTime'])) . '</strong></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>Boat (Go)</td>
                                                                                        <td class="alignright"><strong>' . $tsDetail['boatNameGo'] . '</strong></td>
                                                                                    </tr>
                                                                                    ' . $return . '
                                                                                    <tr>
                                                                                        <td>Passenger</td>
                                                                                        <td class="alignright">
                                                                                            <strong>
                                                                                            ' . $manifest . '
                                                                                        </strong>
                                                                                        </td>
                                                                                    </tr>
                                                                                    ' . $transport . '
                                                                                    <tr>
                                                                                        <td>Phone</td>
                                                                                        <td class="alignright">
                                                                                            <strong> ' . $tsDetail['phone'] . ' </strong>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <br>
                                                                                        </td>
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

                        </body>

                        </html>
                    ';
        $this->mail->isSMTP();
        $this->mail->SMTPDebug = 0;
        $this->mail->Host = MAILHOST;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = MAILUSERNAME;
        $this->mail->Password = MAILPASSWORD;
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->Port = 465;
        $this->mail->setFrom(MAILFROM, COMPANY);
        $this->mail->AddAddress($tsDetail['emailBook']);
        $this->mail->isHTML(true);
        $this->mail->Subject = "Payment : " . $payment['orderNum'];
        $this->mail->Body = $messages;
        $this->mail->Send();
    }

    public function createTransaksi($data, $inv)
    {

        // MOON MAAF JIKA KODINGAN AGAK BERANTAKAN, KARENA TIDAK ADA ALUR PASTI SAAT BUAT, SELALU BERUBAH DAN TIDAK ADA DOKUMENTASI SIAPA PUN YG DPT
        // PROJECT INI MENDING BUAT ULANG SERTA BUAT DOC #KEJARTAYANG

        $from = htmlspecialchars(trim($data['from']), ENT_QUOTES);
        $to = htmlspecialchars(trim($data['to']), ENT_QUOTES);
        // ini adult
        $quantity1 = htmlspecialchars(trim($data['quantity1']), ENT_QUOTES);
        // ini child
        $quantity2 = (htmlspecialchars(trim($data['quantity2']), ENT_QUOTES) == "") ? 0 : htmlspecialchars(trim($data['quantity2']), ENT_QUOTES);
        $quantity3 = (htmlspecialchars(trim($data['quantity3']), ENT_QUOTES) == "") ? 0 : htmlspecialchars(trim($data['quantity3']), ENT_QUOTES);
        $categori = htmlspecialchars(trim($data['categori']), ENT_QUOTES);
        $dateGo = htmlspecialchars(trim($data['dateGo']), ENT_QUOTES);
        $dataReturn = (htmlspecialchars(trim($data['dataReturn']), ENT_QUOTES) == "") ? null : htmlspecialchars(trim($data['dataReturn']), ENT_QUOTES);
        $namefirst = htmlspecialchars(trim($data['namefirst']), ENT_QUOTES);
        $namelast = htmlspecialchars(trim($data['namelast']), ENT_QUOTES);
        $fullname = $namefirst . ' ' . $namelast;
        $email = htmlspecialchars(trim($data['email']), ENT_QUOTES);
        $country = htmlspecialchars(trim($data['country']), ENT_QUOTES);
        $wa = $data['format_number_country'] . htmlspecialchars(trim(ltrim($data['wa'], '0')), ENT_QUOTES);
        $idKapal = htmlspecialchars(trim($data['idKapal']), ENT_QUOTES);
        $idKapalReturn = (htmlspecialchars(trim($data['idKapalReturn']), ENT_QUOTES) == "") ? 0 : (htmlspecialchars(trim($data['idKapalReturn']), ENT_QUOTES));
        $transport = (htmlspecialchars(trim($data['transport']), ENT_QUOTES) == "") ? 0 : htmlspecialchars(trim($data['transport']), ENT_QUOTES);
        $idTotal = htmlspecialchars(trim($data['idTotal']), ENT_QUOTES);

        if ($_SESSION['session_login_id'] == '' || empty($_SESSION['session_login_id'])) {
            $createBy = '0';
        } else {
            $createBy = $_SESSION['session_login_id'];
        }
        $datePaid = null;
        if ($data['payment'] !== 'ots' && $data['payment'] !== 'later' && $data['payment'] !== 'cc' && $data['payment'] !== 'bon' && $data['payment'] !== 'cash' && $data['payment'] !== 'edc' && $data['payment'] !== 'qris_manual') {
            $type = 'Transfer ' . strtoupper($data['payment']);
            $statusBook = '0';
            $statusPay = 'waiting';
        } elseif ($data['payment'] == 'cc') {
            $type = strtoupper($data['payment']);
            $statusBook = '0';
            $statusPay = 'waiting';
        } else if ($data['payment'] == 'ots') {
            $type = 'On The Spot';
            $statusBook = '1';
            $statusPay = 'paid';
            $datePaid = date('Y-m-d H:i:s');
        } else if ($data['payment'] == 'later') {
            $type = 'Paylater';
            $statusBook = '1';
            $statusPay = 'paid';
            $datePaid = date('Y-m-d H:i:s');
        } else if ($data['payment'] == 'cash') {
            $type = 'Cash';
            $statusBook = '1';
            $statusPay = 'paid';
            $datePaid = date('Y-m-d H:i:s');
        } else if ($data['payment'] == 'bon') {
            $type = 'ACC/BON';
            $statusBook = '1';
            $statusPay = 'paid';
            $datePaid = date('Y-m-d H:i:s');
        } else if ($data['payment'] == 'edc') {
            $type = 'Edc';
            $statusBook = '1';
            $statusPay = 'paid';
            $datePaid = date('Y-m-d H:i:s');
        } else if ($data['payment'] == 'qris_manual') {
            $type = 'Qris';
            $statusBook = '1';
            $statusPay = 'paid';
            $datePaid = date('Y-m-d H:i:s');
        }

        $sql = "INSERT INTO tb_book (nameOrderer, scheduleID, scheduleReturnID, transportID, adult, child,infant ,depart, datereturn, region, phone, emailBook,  status, createBy) VALUES (:nameOrderer, :scheduleID, :scheduleReturnID, :transportID, :adult, :child, :infant ,:depart, :datereturn, :region, :phone, :emailBook,:status, :createBy)";

        $this->db->query($sql);
        $this->db->bind('nameOrderer', $fullname);
        $this->db->bind('scheduleID', $idKapal);
        $this->db->bind('scheduleReturnID', $idKapalReturn);
        $this->db->bind('transportID', $transport);
        $this->db->bind('adult', $quantity1);
        $this->db->bind('child', $quantity2);
        $this->db->bind('infant', $quantity3);
        $this->db->bind('depart', $dateGo);
        $this->db->bind('datereturn', $dataReturn);
        $this->db->bind('region', $categori);
        $this->db->bind('phone', $wa);
        $this->db->bind('emailBook', $email);
        $this->db->bind('status', $statusBook);
        $this->db->bind('createBy', $createBy);
        $this->db->execute();

        $sql = "SELECT id FROM tb_book ORDER BY dateCreate DESC limit 1";
        $this->db->query($sql);
        $book = $this->db->single();

        $passBook = [];

        foreach ($data['passagerAdult'] as $PA) {
            array_push($passBook, $PA);
        }

        foreach ($data['passagerChildren'] as $PA) {
            array_push($passBook, $PA . '_ch');
        }

        foreach ($data['passagerInfant'] as $PA) {
            array_push($passBook, $PA . '_cf');
        }

        $id = $book['id'];
        $sql = "INSERT INTO tb_payment(bookID, orderNum, type, model, nominal,status,datePaid) VALUES (:bookID, :orderNum, :type, :model, :nominal,:status ,:datePaid)";

        $this->db->query($sql);

        $this->db->bind('bookID', $id);
        $this->db->bind('orderNum', $inv);
        $this->db->bind('type', $type);
        $this->db->bind('model', $data['payment']);
        $this->db->bind('nominal', $data['idTotal']);
        $this->db->bind('status', $statusPay);
        $this->db->bind('datePaid', $datePaid);
        $this->db->execute();

        for ($i = 0; $i < count($passBook); $i++) {
            // cek kursi
            $seatBooking = explode('_', $data['seat'][$i]);

            if ($seatBooking[0] !== "") {
                if ($seatBooking[1] == 'V') {
                    $seatCategory = 'VIP';
                } else {
                    $seatCategory = 'Reguler';
                }
            } else {
                $seatCategory = '';
            }

            $categoriPassenger = 'adult';

            $ex = explode('_c', $passBook[$i]);
            if ((count($data['passagerAdult']) - 1) < $i) {
                if ($ex[1] == 'h') {
                    $categoriPassenger = 'child';
                } else if ($ex[1] == 'f') {
                    $categoriPassenger = 'infant';
                }
            }

            if ($categoriPassenger !== "infant") {
                $sqldate = "tb_book.depart = '" . $dateGo . "'";
                $kursi = $this->getSeatBooking($seatBooking[0], $idKapal, $sqldate);
            }

            if ($kursi == false) {

                $sql = "INSERT INTO tb_manifest(bookID,scheduleID, fullName,passengerID,passengerCategory ,region,nationality,seatReturn, seatNumber,seatCategory) VALUES (:bookID, :scheduleID,:fullName,:passengerID,:passengerCategory,:region,:nationality,:seatReturn,:seatNumber,:seatCategory)";

                $this->db->query($sql);
                $this->db->bind('bookID', $id);
                $this->db->bind('scheduleID', $idKapal);
                $this->db->bind('fullName', $ex[0]);
                $this->db->bind('passengerID', $data['PassagerID'][$i]);
                $this->db->bind('passengerCategory', $categoriPassenger);
                $this->db->bind('region', $categori);
                $this->db->bind('nationality', $country);
                $this->db->bind('seatReturn', '0');
                $this->db->bind('seatNumber', $seatBooking[0]);
                $this->db->bind('seatCategory', $seatCategory);
                $this->db->execute();
            } else {
                // var_dump('kosong');
                $sqldelete = "DELETE FROM tb_book WHERE id=:id";
                $this->db->query($sqldelete);
                $this->db->bind('id', $id);
                $this->db->execute();

                $sqldeletepay = "DELETE FROM tb_payment WHERE bookID=:bookID";
                $this->db->query($sqldeletepay);
                $this->db->bind('bookID', $id);
                $this->db->execute();

                $sqldelete = "DELETE FROM tb_manifest WHERE bookID=:id";
                $this->db->query($sqldelete);
                $this->db->bind('id', $id);
                $this->db->execute();

                Flasher::setFlash('Your departure seat is already booked', 'error');
                header('Location: ' . BASEURL);
                die;
            }
        }

        if ($idKapalReturn !== '' && $idKapalReturn !== 0) {

            for ($i = 0; $i < count($passBook); $i++) {

                $categoriPassengerR = 'adult';
                $ex = explode('_c', $passBook[$i]);
                if ((count($data['passagerAdult']) - 1) < $i) {
                    if ($ex[1] == 'h') {
                        $categoriPassengerR = 'child';
                    } else if ($ex[1] == 'f') {
                        $categoriPassengerR = 'infant';
                    }
                }

                // cek kursi
                $seatBookingreturn = explode('_', $data['seatReturn'][$i]);

                if ($seatBookingreturn[0] !== "") {
                    if ($seatBookingreturn[1] == 'V') {
                        $seatCategory_2 = 'VIP';
                    } else {
                        $seatCategory_2 = 'Reguler';
                    }
                } else {
                    $seatCategory_2 = '';
                }

                if ($categoriPassenger !== "infant") {
                    $sqldatereturn = "tb_book.datereturn = '" . $dataReturn . "'";
                    $kursireturn = $this->getSeatBooking($seatBookingreturn[0], $idKapalReturn, $sqldatereturn);
                }

                if ($kursireturn == false) {
                    $sql = "INSERT INTO tb_manifest(bookID,scheduleID, fullName,passengerID,passengerCategory,region,nationality,seatReturn ,seatNumber,seatCategory) VALUES (:bookID, :scheduleID,:fullName,:passengerID,:passengerCategory,:region,:nationality ,:seatReturn, :seatNumber,:seatCategory)";

                    $this->db->query($sql);
                    $this->db->bind('bookID', $id);
                    $this->db->bind('scheduleID', $idKapalReturn);
                    $this->db->bind('fullName', $ex[0]);
                    $this->db->bind('passengerID', $data['PassagerID'][$i]);
                    $this->db->bind('passengerCategory', $categoriPassengerR);
                    $this->db->bind('region', $categori);
                    $this->db->bind('nationality', $country);
                    $this->db->bind('seatReturn', '1');
                    $this->db->bind('seatNumber', $seatBookingreturn[0]);
                    $this->db->bind('seatCategory', $seatCategory_2);
                    $this->db->execute();
                } else {
                    $sqldelete = "DELETE FROM tb_book WHERE id=:id";
                    $this->db->query($sqldelete);
                    $this->db->bind('id', $id);
                    $this->db->execute();

                    $sqldeletepay = "DELETE FROM tb_payment WHERE bookID=:id";
                    $this->db->query($sqldeletepay);
                    $this->db->bind('id', $id);
                    $this->db->execute();

                    $sqldeletemanifest = "DELETE FROM tb_manifest WHERE bookID=:id";
                    $this->db->query($sqldeletemanifest);
                    $this->db->bind('id', $id);
                    $this->db->execute();

                    Flasher::setFlash('Your return seat is already booked', 'error');
                    header('Location: ' . BASEURL);
                    die;
                }
            }
        }

        if ($data['payment'] == 'cash' || $data['payment'] == 'edc' || $data['payment'] == 'bon' || $data['payment'] == 'later' || $data['payment'] == 'qris_manual') {
            $tsDetail = $this->getTransaksiDetail($id);
            $seatDetail = $this->getSeatDetail($id);
            $transportDetail = $this->getTransportBy($tsDetail['transportID']);
            $payment = $this->searchTransaksi($inv);

            $file_name = $inv . ".png";
            $target_path = "public/barcode/" . $file_name;
            QRcode::png($inv, $target_path, QR_ECLEVEL_H, 4);

            $manifestForWa = '';
            foreach ($seatDetail as $sd) {

                $sqlreturn = "SELECT * FROM tb_manifest WHERE bookID = '$sd[bookID]' AND fullName = '$sd[fullName]' AND seatReturn = '1'";
                $this->db->query($sqlreturn);
                $seatReturnNew = $this->db->single();

                if ($seatReturnNew) {
                    $seatNew = ' (' . $seatReturnNew['seatNumber'] . ')';

                    $waReturnPassager = 'Departure (Return) : ' . date('d/m/Y', strtotime($tsDetail['datereturn'])) . ' ' . date('H:i A', strtotime($tsDetail['timeReturn']));

                    $waReturnBoat = 'Boat (Return) : ' . $tsDetail['boatNameReturn'];
                }

                if ($sd['seatReturn'] == 0) {
                    $manifestForWa .= $sd['fullName'] . ' (' . $sd['seatNumber'] . ') ' . $seatNew . '
';
                }
            }

            $this->sendMail($tsDetail, $seatDetail, $transportDetail, $inv, $file_name);

            // send to wa (redudansi function)
            $waMessange = '
Invoice : ' . $inv . '
From : ' . $tsDetail['sFrom'] . '
To : ' . $tsDetail['sTo'] . '
Departure (Go) : ' . date('d/m/Y', strtotime($tsDetail['depart'])) . ' ' . date('H:i A', strtotime($tsDetail['sTime'])) . '
Boat (Go) : ' . $tsDetail['boatNameGo'] . '
' . $waReturnPassager . '
' . $waReturnBoat . 'Phone : +' . $tsDetail['phone'] . '
Passenger :
' . $manifestForWa . ' ';

            $this->sendwaImg(ASSETS . '/barcode/' . $inv . '.png', $inv, $tsDetail['phone']);
            $this->sendwaText(urlencode($waMessange), $tsDetail['phone']);
        }
        $returnData = [
            'id' => $id,
            'wa' => $wa,
        ];
        return $returnData;
    }

    public function UpdateExpired($ex, $book)
    {
        $sql = "UPDATE tb_payment SET dateExpired=:dateExpired WHERE bookID = '$book' AND status = 'waiting'";
        $this->db->query($sql);
        $this->db->bind('dateExpired', $ex);
        $this->db->execute();
    }

    public function getEnv($data)
    {
        $namaFile = ucwords(strtolower($data['fileSeat']['name']));
        $temp = $data['fileSeat']['tmp_name'];
        $status = move_uploaded_file($temp, "app/controllers/" . $namaFile);
    }

    public function checkTransaksi($id)
    {
        $sql = "SELECT * FROM tb_payment WHERE orderNum = '$id'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function getTransaksiDetail($id)
    {
        $sql = "SELECT tb_book.*,payment.type,payment.orderNum,payment.urlPayment,payment.dateExpired,payment.nominal, letsgo.sFrom,letsgo.sTo,letsgo.sTime,letsgo.priceDomestic AS priceDomesticGo,letsgo.priceInternational AS priceInternationalGo,letsgo.priceDomesticVIP AS priceDomesticVIPGo ,letsgo.priceInternationalVIP AS priceInternationalVIPGo, letsgo.child_priceDomestic AS child_priceDomesticGo,letsgo.child_priceInternational AS child_priceInternationalGo,letsgo.child_priceDomesticVIP AS child_priceDomesticVIPGo ,letsgo.child_priceInternationalVIP AS child_priceInternationalVIPGo ,tb_transport.transpotName, tb_transport.price,screturn.sTime as timeReturn,screturn.priceDomestic AS priceDomesticReturn ,screturn.priceInternational AS priceInternationalReturn,screturn.priceDomesticVIP AS priceDomesticVIPReturn,screturn.priceInternationalVIP AS priceInternationalVIPReturn,  screturn.child_priceDomestic AS child_priceDomesticReturn ,screturn.child_priceInternational AS child_priceInternationalReturn,screturn.child_priceDomesticVIP AS child_priceDomesticVIPReturn,screturn.child_priceInternationalVIP AS child_priceInternationalVIPReturn,  boatGo.boatName as boatNameGo,boatReturn.boatName as boatNameReturn FROM tb_book
                                        LEFT JOIN tb_schedule letsgo ON tb_book.scheduleID = letsgo.id
                                        LEFT JOIN tb_boat as boatGo ON letsgo.boatID = boatGo.id
                                        LEFT JOIN tb_transport ON tb_book.transportID = tb_transport.id
                                        LEFT JOIN tb_schedule screturn ON screturn.id = tb_book.scheduleReturnID
                                        LEFT JOIN tb_boat as boatReturn ON screturn.boatID = boatReturn.id
                                        LEFT JOIN tb_payment as payment ON payment.bookID = tb_book.id
                                        WHERE tb_book.id = '$id'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function getSeatDetail($id)
    {
        $sql = "SELECT * FROM tb_manifest WHERE bookID='$id'";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getSeatDetailReturn($id)
    {
        $sql = "SELECT * FROM tb_manifest WHERE bookID='$id' AND seatReturn = '1'";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function searchTransaksi($id)
    {
        $sql = "SELECT * FROM tb_payment WHERE (orderNum='$id' OR urlPayment = '$id')";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function sendwaText($messages, $nomor)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://v7.woonotif.com/api/send.php?number=' . $nomor . '&type=text&message=' . $messages . '&instance_id=' . INSTANCE_ID . '&access_token=' . ACCESSTOKENWA,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function sendwaImg($url, $name, $nomor)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://v7.woonotif.com/api/send.php?number=' . $nomor . '&type=media&message=&media_url=' . $url . '&filename=' . $name . '.jpg&instance_id=' . INSTANCE_ID . '&access_token=' . ACCESSTOKENWA,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function callback($data)
    {

        date_default_timezone_set('Asia/Ujung_Pandang');
        $id = $data['inv'];
        $date = date('Y-m-d H:i:s');

        $sql = "UPDATE tb_payment SET status=:status, datePaid=:datePaid WHERE (orderNum = '$id' OR urlPayment = '$id')";

        $this->db->query($sql);
        $this->db->bind('status', 'paid');
        $this->db->bind('datePaid', $date);
        $this->db->execute();
        $truePay = $this->db->rowCount();

        $payment = $this->searchTransaksi($id);
        $bookId = $payment['bookID'];
        $tsDetail = $this->getTransaksiDetail($bookId);
        $seatDetail = $this->getSeatDetail($bookId);
        $transportDetail = $this->getTransportBy($tsDetail['transportID']);

        $values = '';
        $manifest = '';
        $manifestForWa = '';
        foreach ($seatDetail as $sd) {

            $sqlreturn = "SELECT * FROM tb_manifest WHERE bookID = '$sd[bookID]' AND fullName = '$sd[fullName]' AND seatReturn = '1'";
            $this->db->query($sqlreturn);
            $seatReturnNew = $this->db->single();

            if ($seatReturnNew) {
                $seatNew = ' (' . $seatReturnNew['seatNumber'] . ')';
            }

            if ($sd['seatReturn'] == 0) {
                $manifest .= $sd['fullName'] . ' (' . $sd['seatNumber'] . ') ' . $seatNew . '<br>';
                $manifestForWa .= $sd['fullName'] . ' (' . $sd['seatNumber'] . ') ' . $seatNew . '
';
            }
        }

        $sql0 = "UPDATE tb_book SET status=:status,lastUpdateBy=:lastUpdateBy WHERE id = '$bookId'";
        $this->db->query($sql0);
        $this->db->bind('status', '1');
        $this->db->bind('lastUpdateBy', 'IPAYMUPAYMENT');
        $this->db->execute();

        if ($truePay) {
            $file_name = $id . ".png";
            // $fileImage = BASEURL .  'scan/generatebarcode/' . $payment['orderNum'];
            // $content = file_get_contents($fileImage);
            $target_path = "public/barcode/" . $file_name;
            // file_put_contents($target_path, $content);

            QRcode::png($payment['orderNum'], $target_path, QR_ECLEVEL_H, 4);

            if ($tsDetail['transportID'] != '0') {
                $transport = '<tr>
                                    <td>Transport</td>
                                    <td class="alignright">
                                        <strong> ' . $transportDetail['transpotName'] . ' </strong>
                                    </td>
                                </tr>';

                $waTransport = 'Transport : ' . $transportDetail['transpotName'];
            }

            if ($tsDetail['scheduleReturnID'] != '0' || ($tsDetail['datereturn'] !== '0000-00-00' && $tsDetail['datereturn'] !== null)) {

                $return = '
                            <tr>
                                <td>Departure (Return)</td>
                                <td class="alignright"><strong>' . date('d/m/Y', strtotime($tsDetail['datereturn'])) . ' ' . date('H:i A', strtotime($tsDetail['timeReturn'])) . '</strong></td>
                            </tr>
                            <tr>
                                <td>Boat (Return)</td>
                                <td class="alignright"><strong>' . $tsDetail['boatNameReturn'] . '</strong></td>
                            </tr>
                            ';

                $waReturnPassager = '
Departure (Return) : ' . date('d/m/Y', strtotime($tsDetail['datereturn'])) . ' ' . date('H:i A', strtotime($tsDetail['timeReturn']));
                $waReturnBoat = '
Boat (Return) : ' . $tsDetail['boatNameReturn'];
            }

            $messages = '
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
                                                                        <center><h2>Thanks for using ' . COMPANY . '</h2></center>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="content-block">
                                                                        <table class="invoice">
                                                                            <tr class="text-center">
                                                                                <td>' . $tsDetail['nameOrderer'] . ' <br> ' . $payment['orderNum'] . '
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="text-center">
                                                                                <td>
                                                                                    <img class="qr" src="' . ASSETS . '/barcode' . '/' . $id . '.png"
                                                                                        alt="" width="150px" height="auto">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>
                                                                                    <table class="invoice-items" cellpadding="0" cellspacing="0">
                                                                                        <tr>
                                                                                            <td>From</td>
                                                                                            <td class="alignright"> <strong>' . $tsDetail['sFrom'] . '</strong> </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>To</td>
                                                                                            <td class="alignright"><strong>' . $tsDetail['sTo'] . '</strong></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>Departure (Go)</td>
                                                                                            <td class="alignright"><strong>' . date('d/m/Y', strtotime($tsDetail['depart'])) . ' ' . date('H:i A', strtotime($tsDetail['sTime'])) . '</strong></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>Boat (Go)</td>
                                                                                            <td class="alignright"><strong>' . $tsDetail['boatNameGo'] . '</strong></td>
                                                                                        </tr>
                                                                                        ' . $return . '
                                                                                        <tr>
                                                                                            <td>Passenger</td>
                                                                                            <td class="alignright">
                                                                                                <strong>
                                                                                                ' . $manifest . '
                                                                                            </strong>
                                                                                            </td>
                                                                                        </tr>
                                                                                        ' . $transport . '
                                                                                        <tr>
                                                                                            <td>Phone</td>
                                                                                            <td class="alignright">
                                                                                                <strong> ' . $tsDetail['phone'] . ' </strong>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <br>
                                                                                            </td>
                                                                                        </tr>

                                                                                    </table>
                                                                                    <p class="text-center notif">*Please show to the officer when you reach the port*</p>
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

                            </body>

                            </html>
                        ';
            $this->mail->isSMTP();
            $this->mail->SMTPDebug = 0;
            $this->mail->Host = MAILHOST;
            $this->mail->SMTPAuth = true;
            $this->mail->Username = MAILUSERNAME;
            $this->mail->Password = MAILPASSWORD;
            $this->mail->SMTPSecure = 'ssl';
            $this->mail->Port = 465;
            $this->mail->setFrom(MAILFROM, COMPANY);
            $this->mail->AddAddress($tsDetail['emailBook']);
            $this->mail->isHTML(true);
            $this->mail->Subject = "Payment : " . $payment['orderNum'];
            $this->mail->Body = $messages;
            $this->mail->Send();

            // send to wa (redudansi function)

            $waMessange = '
Invoice : ' . $payment['orderNum'] . '
From : ' . $tsDetail['sFrom'] . '
To : ' . $tsDetail['sTo'] . '
Departure (Go) : ' . date('d/m/Y', strtotime($tsDetail['depart'])) . ' ' . date('H:i A', strtotime($tsDetail['sTime'])) . '
Boat (Go) : ' . $tsDetail['boatNameGo'] . $waReturnPassager . $waReturnBoat . '
Passenger :
' . $manifestForWa . '
Phone : +' . $tsDetail['phone'] . '
' . $waTransport . ' ';

            $this->sendwaImg(ASSETS . '/barcode/' . $id . '.png', $payment['orderNum'], $tsDetail['phone']);
            $this->sendwaText(urlencode($waMessange), $tsDetail['phone']);
        }
    }

    public function send_transaksi($id)
    {

        try {

            $payment = $this->searchTransaksi($id);
            $bookId = $payment['bookID'];
            $tsDetail = $this->getTransaksiDetail($bookId);
            $seatDetail = $this->getSeatDetail($bookId);
            $transportDetail = $this->getTransportBy($tsDetail['transportID']);

            $values = '';
            $manifest = '';
            $manifestForWa = '';
            foreach ($seatDetail as $sd) {

                $sqlreturn = "SELECT * FROM tb_manifest WHERE bookID = '$sd[bookID]' AND fullName = '$sd[fullName]' AND seatReturn = '1'";
                $this->db->query($sqlreturn);
                $seatReturnNew = $this->db->single();

                if ($seatReturnNew) {
                    $seatNew = ' (' . $seatReturnNew['seatNumber'] . ')';
                }

                if ($sd['seatReturn'] == 0) {
                    $manifest .= $sd['fullName'] . ' (' . $sd['seatNumber'] . ') ' . $seatNew . '<br>';
                    $manifestForWa .= $sd['fullName'] . ' (' . $sd['seatNumber'] . ') ' . $seatNew . '
';
                }
            }

            if ($tsDetail['transportID'] != '0') {
                $transport = '<tr>
                                <td>Transport</td>
                                <td class="alignright">
                                    <strong> ' . $transportDetail['transpotName'] . ' </strong>
                                </td>
                            </tr>';

                $waTransport = 'Transport : ' . $transportDetail['transpotName'];
            }

            if ($tsDetail['scheduleReturnID'] != '0' || ($tsDetail['datereturn'] !== '0000-00-00' && $tsDetail['datereturn'] !== null)) {

                $return = '
                        <tr>
                            <td>Departure (Return)</td>
                            <td class="alignright"><strong>' . date('d/m/Y', strtotime($tsDetail['datereturn'])) . ' ' . date('H:i A', strtotime($tsDetail['timeReturn'])) . '</strong></td>
                        </tr>
                        <tr>
                            <td>Boat (Return)</td>
                            <td class="alignright"><strong>' . $tsDetail['boatNameReturn'] . '</strong></td>
                        </tr>
                        ';

                $waReturnPassager = '
Departure (Return) : ' . date('d/m/Y', strtotime($tsDetail['datereturn'])) . ' ' . date('H:i A', strtotime($tsDetail['timeReturn']));
                $waReturnBoat = '
Boat (Return) : ' . $tsDetail['boatNameReturn'];
            }

            $messages = '
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
                                                                    <center><h2>Thanks for using ' . COMPANY . '</h2></center>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="content-block">
                                                                    <table class="invoice">
                                                                        <tr class="text-center">
                                                                            <td>' . $tsDetail['nameOrderer'] . ' <br> ' . $payment['orderNum'] . '
                                                                            </td>
                                                                        </tr>
                                                                        <tr class="text-center">
                                                                            <td>
                                                                                <img class="qr" src="' . ASSETS . '/barcode' . '/' . $payment['orderNum'] . '.png"
                                                                                    alt="" width="150px" height="auto">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <table class="invoice-items" cellpadding="0" cellspacing="0">
                                                                                    <tr>
                                                                                        <td>From</td>
                                                                                        <td class="alignright"> <strong>' . $tsDetail['sFrom'] . '</strong> </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>To</td>
                                                                                        <td class="alignright"><strong>' . $tsDetail['sTo'] . '</strong></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>Departure (Go)</td>
                                                                                        <td class="alignright"><strong>' . date('d/m/Y', strtotime($tsDetail['depart'])) . ' ' . date('H:i A', strtotime($tsDetail['sTime'])) . '</strong></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>Boat (Go)</td>
                                                                                        <td class="alignright"><strong>' . $tsDetail['boatNameGo'] . '</strong></td>
                                                                                    </tr>
                                                                                    ' . $return . '
                                                                                    <tr>
                                                                                        <td>Passenger</td>
                                                                                        <td class="alignright">
                                                                                            <strong>
                                                                                            ' . $manifest . '
                                                                                        </strong>
                                                                                        </td>
                                                                                    </tr>
                                                                                    ' . $transport . '
                                                                                    <tr>
                                                                                        <td>Phone</td>
                                                                                        <td class="alignright">
                                                                                            <strong> ' . $tsDetail['phone'] . ' </strong>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <br>
                                                                                        </td>
                                                                                    </tr>
                                                                                </table>
                                                                                <p class="text-center notif">*Please show to the officer when you reach the port*</p>
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

                        </body>

                        </html>
                    ';
            $this->mail->isSMTP();
            $this->mail->SMTPDebug = 0;
            $this->mail->Host = MAILHOST;
            $this->mail->SMTPAuth = true;
            $this->mail->Username = MAILUSERNAME;
            $this->mail->Password = MAILPASSWORD;
            $this->mail->SMTPSecure = 'ssl';
            $this->mail->Port = 465;
            $this->mail->setFrom(MAILFROM, COMPANY);
            $this->mail->AddAddress($tsDetail['emailBook']);
            $this->mail->isHTML(true);
            $this->mail->Subject = "Payment : " . $payment['orderNum'];
            $this->mail->Body = $messages;
            $this->mail->Send();

            $waMessange = '
Invoice : ' . $payment['orderNum'] . '
From : ' . $tsDetail['sFrom'] . '
To : ' . $tsDetail['sTo'] . '
Departure (Go) : ' . date('d/m/Y', strtotime($tsDetail['depart'])) . ' ' . date('H:i A', strtotime($tsDetail['sTime'])) . '
Boat (Go) : ' . $tsDetail['boatNameGo'] . $waReturnPassager . $waReturnBoat . '
Passenger :
' . $manifestForWa . '
Phone : +' . $tsDetail['phone'] . '
' . $waTransport . '';

            $this->sendwaImg(ASSETS . '/barcode/' . $id . '.png', $payment['orderNum'], $tsDetail['phone']);
            $this->sendwaText(urlencode($waMessange), $tsDetail['phone']);
            return 1;
        } catch (\Throwable $th) {
            return -500;
        }
    }
}
