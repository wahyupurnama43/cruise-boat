<?php

class Home extends Controller
{

    public function __construct()
    {
        $this->mail = new PHPMailer\PHPMailer\PHPMailer();
    }

    public function index()
    {

        $data['nm_code']     = $this->model('M_Home')->getCountry();
        $data['schedule']    = $this->model('M_Home')->getsFrom();
        $data['Allschedule'] = $this->model('M_Schedule')->getAllSC();
        $data['boat']        = $this->model('M_Boat')->getAllBoatEnable();
        $data['transport']   = $this->model('M_Transport')->getAllTR();
        if ($_SESSION['session_status'] == 'true' && $_SESSION['session_login_grade_real'] == 'agent') {
            $sessionId    = $_SESSION['session_login_token'];
            $data['user']       = $this->model('M_User')->getUserBy('tb_user', 'id', $sessionId);
            $data['list_agent'] = $this->model('M_User')->getUserBy('tb_mitra', 'id', $data['user']['mitraCompanyID']);
            $data['diskon']     = $this->model('M_User')->getUserBy('tb_grade', 'id', $data['user']['grade']);
        }
        $this->view('hompage/home', $data);
    }

    public function search_agent()
    {
        $dataGo  = $this->model('M_User')->search_agent($_POST['query']);
        $output  = '';
        if ($dataGo) {
            foreach ($dataGo as $row) {
                $output .= '<li class="list-group-item contsearch">
                <a href="javascript:void(0)" class="gsearch" style="color:#333;text-decoration:none;" data-id="' . $row["grade"] . '">' . $row["userName"] . '</a>
                </li>';
            }
            echo $output;
        }
    }

    public function sendwaText($number, $text)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://v7.woonotif.com/api/send.php?number=' . $number . '&type=text&message=' . $text . '&instance_id=' . INSTANCE_ID . '&access_token=' . ACCESSTOKENWA,
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

    public function create_book()
    {

        $inv = "B-" . date('ymd') . rand(1, 99999);
        if (!empty($_POST)) {
            $IDBOOK = $this->model('M_Home')->createTransaksi($_POST, $inv);

            if ($_POST['payment'] !== 'ots' && $_POST['payment'] !== 'later' && $_POST['payment'] !== 'bon' && $_POST['payment'] !== 'edc' && $_POST['payment'] !== 'cash' && $_POST['payment'] !== 'qris_manual') {
                // booking when virtual akun not ots and patlater
                $invoice = $this->model('M_Home')->createIpaymu($_POST, $inv, $IDBOOK['id']);

                if (!empty($invoice)) {
                    if ($invoice['cc'] == 'true') {
                        $invoiceNew = $invoice['inv'];
                        header('Location: ' . $invoice['url']);
                    } else {
                        $invoiceNew = $invoice['inv'];
                    }
                    $data['doku'] = $this->model('M_Home')->checkTransaksi($invoiceNew);
                } else {
                    $data['doku'] = '';
                }

                if ($data['doku']['type'] !== 'Transfer QRIS' && $data['doku']['type'] !== 'CC') {
                    $paymentInfo = 'Please make a payment to Bank <span class="UP">' . $_POST['payment'] . '</span> with Virtual Account number of <strong> ' . $data['doku']['urlPayment'] . '</strong>';
                } else if ($data['doku']['type'] == 'CC') {
                    $paymentInfo = 'Please make a payment to  <span class="UP">' . htmlspecialchars($_POST['payment']) . '</span>';
                } else {
                    $paymentInfo = 'Please the link below to get your payment QR code ';
                }

                $imgEmail = $this->model('M_Home')->imgEmail();

                if (!empty($invoice)) {
                    $messages = '<html xmlns="http://www.w3.org/1999/xhtml">
                            <head>
                                <meta name="viewport" content="width=device-width" />
                                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                                <title>Payment Invoice</title>

                                <style>
                                    * {
                                        margin: 0;
                                        padding: 0;
                                        font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                                        box-sizing: border-box;
                                        font-size: 14px;
                                    }

                                    img {
                                        max-width: 100%;
                                    }

                                    body {
                                        -webkit-font-smoothing: antialiased;
                                        -webkit-text-size-adjust: none;
                                        width: 100% !important;
                                        height: 100%;
                                        line-height: 1.6;
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
                                    .UP{
                                        text-transform: uppercase;
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
                                        text-align: center;
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
                                        border-top: 2px solid #333;
                                        border-bottom: 2px solid #333;
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
                                                        <td class="content-wrap">
                                                            <table cellpadding="0" cellspacing="0">
                                                                <tr>
                                                                    <td>
                                                                        <img class="img-responsive" src="' . ASSETS . '/home/images/' . $imgEmail['value'] . '" />
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="content-block">
                                                                        <h3>Thank you for using our services</h3>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="content-block">
                                                                        ' . $paymentInfo . '
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="content-block">
                                                                        Check the booking invoice on the following link
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="content-block aligncenter">
                                                                    <form>
                                                                    <a href="' . BASEURL . 'home/transaksi/' . $data['doku']['orderNum'] . '" class="btn-primary">Go to Link</a>
                                                                    </form>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                        <td></td>
                                    </tr>
                                </table>
                            </body>
                        </html>';

                    $this->mail->isSMTP();
                    $this->mail->SMTPDebug = 0;
                    $this->mail->Host       = MAILHOST;
                    $this->mail->SMTPAuth   = true;
                    $this->mail->Username   = MAILUSERNAME;
                    $this->mail->Password   = MAILPASSWORD;
                    $this->mail->SMTPSecure = 'ssl';
                    $this->mail->Port       = 465;
                    $this->mail->setFrom(MAILFROM, COMPANY);
                    $this->mail->AddAddress($_POST['email'], $_POST['namefirst'] . " " . $_POST['namelast']);
                    $this->mail->isHTML(true);
                    $this->mail->Subject    = "Payment : " .  $data['doku']['orderNum'];
                    $this->mail->Body       = $messages;
                    $this->mail->Send();
                }


                if ($data['doku']['type'] == 'CC' || $data['doku']['type'] == 'Transfer QRIS') {
                    $this->sendwaText($IDBOOK['wa'], 'Thank+you+for+using+our+services%0A%0ACheck+the+booking+invoice+on+the+following+link%0A' . BASEURL . 'home/transaksi/' . $data['doku']['orderNum']);
                } else if ($data['doku']['type'] !== 'CC' && $data['doku']['type'] !== 'Transfer QRIS') {
                    $this->sendwaText($IDBOOK['wa'], 'Thank+you+for+using+our+services%0A%0APlease+make+a+payment+to+Bank+' . htmlspecialchars($_POST['payment']) . '+with+Virtual+Account+number+of+%2A' . $data['doku']['urlPayment'] . '%2A%0A%0ACheck+the+booking+invoice+on+the+following+link%0A' . BASEURL . 'home/transaksi/' . $data['doku']['orderNum']);
                }
            } else {
                // payment when pay later and ots
                $data['doku'] = $this->model('M_Home')->checkTransaksi($inv);
            }
            $this->view('hompage/payment', $data);
        } else {
            header('Location: ' . BASEURL);
        }
    }

    public function getSeatAll()
    {
        $namaFile = ucwords(strtolower($_FILES['fileSeat']['name']));
        $temp     = $_FILES['fileSeat']['tmp_name'];
        $status   = move_uploaded_file($temp, "app/controllers/" . $namaFile);
    }

    public function getTo()
    {
        $id   = $_POST['sFrom'];
        $data = $this->model('M_Home')->getsTo('tb_schedule', 'sFrom', $id);
        foreach ($data as $d) {
            if ($d['sTo'] !== '') {
                echo "<option value='$d[sTo]'>" . $d['sTo'] . "</option>";
            } else {
                echo "<option selected>- Kapal Tidak Tersedia -</option>";
            }
        }
    }

    public function getSchedule()
    {
        $date = date('l', strtotime($_POST['dateGo']));
        $boat = $_POST['boat'];
        $to   = $_POST['to'];
        $from = $_POST['from'];
        $data = $this->model('M_Home')->getScheduleBy($date, $boat, $to, $from);

        foreach ($data as $d) {
            if ($d !== '') {
                echo '<tr>
                        <td>' . $d['sDay'] . '</td>
                        <td>' . $d['sTime'] . '</td>
                        <td>
                            <label class="jadwal bokingJadwal" data-jadwal="' . $d['id'] . '" data-id="' . $d['boatID'] . '" >Click</label>
                        </td>
                    </tr>';
            } else {
                echo '<tr>
                        <td></td>
                        <td>data kosong</td>
                        <td></td>
                    </tr>';
            }
        }
    }

    public function cekSchedule()
    {

        $dateGo     = date('l', strtotime($_POST['dateGo']));
        $to         = $_POST['to'];
        $from       = $_POST['from'];

        $dataGo     = $this->model('M_Home')->cekSchedule($dateGo, $to, $from);

        if (empty($dataGo)) {
            $status = 0;
        } else {
            $status = 1;
        }

        echo json_encode($status);
    }

    public function cekScheduleReturn()
    {

        $dateReturn = date('l', strtotime($_POST['dateReturn']));
        $to         = $_POST['to'];
        $from       = $_POST['from'];

        $dataReturn = $this->model('M_Home')->cekSchedule($dateReturn, $from, $to);

        if (empty($dataReturn)) {
            $status = -1;
        } else {
            $status = 1;
        }

        echo json_encode($status);
    }


    public function getReturnSchedule()
    {
        $date = date('l', strtotime($_POST['dateGo']));
        $boat = $_POST['boat'];
        $to   = $_POST['to'];
        $from = $_POST['from'];
        $data = $this->model('M_Home')->getScheduleBy($date, $boat, $to, $from);
        foreach ($data as $d) {
            if ($d !== '') {
                echo '<tr>
                        <td>' . $d['sDay'] . '</td>
                        <td>' . $d['sTime'] . '</td>
                        <td>
                            <label class="returnJadwal bokingJadwal" data-jadwal="' . $d['id'] . '" data-idReturn="' . $d['boatID'] . '" >Klik</label>
                        </td>
                    </tr>';
            } else {
                echo '<tr>
                        <td></td>
                        <td>data kosong</td>
                        <td></td>
                    </tr>';
            }
        }
    }


    public function getSeat()
    {
        $id       = $_POST['idJad'];
        $idJadwal = $_POST['idJadwal'];
        $dateGo   = $_POST['dateGo'];
        $data     = $this->model('M_Home')->getSeat($id);

        $sqldate = "tb_book.depart = '" . $dateGo . "'";

        $isi = '';

        $nomor = 0;
        $char  = 0;
        $alpha = range('A', 'Z');

        if ($data['id'] == 12) {
            $seatVIPNew = $data['boatSeatVIP'] + 3;
        } else {
            $seatVIPNew = $data['boatSeatVIP'];
        }

        for ($i = 1; $i <= $seatVIPNew; $i++) {
            ++$nomor;

            if ($nomor == 7) {
                $nomor = 1;
            }
            if ($nomor % 6 == 0) {
                $char++;
            } else {
                $num = $alpha[$char];
            }


            $seat = $num . $nomor;


            $kursi = $this->model('M_Home')->getSeatBooking($seat, $idJadwal, $sqldate);
            $checkDisable = $this->model('M_Home')->checkDisable($seat, $idJadwal);
            if ($kursi['seatNumber'] == $seat) {
                $disabled = 'disabled';
                $cssCheck = '';
            } else {
                $disabled = '';
                $cssCheck = 'seatcheck';
            }

            if ($checkDisable['seat'] == $seat) {
                $dnone = 'display:none';
            } else {
                $dnone = '';
            }

            $isi = $isi . '<li class="seat">
                                <input type="checkbox" id="V' . $num . $nomor . '" name="seat[]" value="' . $num . $nomor .  '_V" class="' . $cssCheck . '"  ' . $disabled . ' " />
                                <label for="V' . $num . $nomor . '" class="seatChoose" style="' . $dnone . '">' . $seat .  '</label>
                            </li>';
        }

        $isiReguler = '';
        $nomor = 0;
        for ($i = 1; $i <= $data['boatSeat']; $i++) {
            ++$nomor;
            if ($nomor == 9) {
                $nomor = 1;
            }
            if ($i % 8 == 0) {
                $char++;
            } else {
                $num = $alpha[$char];
            }

            $seat = $num . $nomor;

            $kursiR = $this->model('M_Home')->getSeatBooking($seat, $idJadwal, $sqldate);
            $checkDisable = $this->model('M_Home')->checkDisable($seat, $idJadwal);

            if ($kursiR['seatNumber'] == $seat) {
                $disabled = 'disabled';
                $cssCheck = '';
            } else {
                $disabled = '';
                $cssCheck = 'seatcheck';
            }

            if ($checkDisable['seat'] == $seat) {
                $dnone = 'display:none';
            } else {
                $dnone = '';
            }

            $isiReguler = $isiReguler . '<li class="seat">
                                <input type="checkbox" id="R' . $num . $nomor . '"  name="seat[]" value="' . $num . $nomor . '" class="seatcheckRegular ' . $cssCheck . '" ' . $disabled . '   />
                                <label for="R' . $num . $nomor . '" class="seatChoose" style="' . $dnone . '">' . $num . $nomor . '</label>
                            </li>';
        }


        echo json_encode(['isi' => $isi, 'isiR' => $isiReguler, 'check' => $sqldate]);
    }

    public function getSeatRegular()
    {
        $namaFile = ucwords(strtolower($_FILES['fileSeat']['name']));
        $temp     = $_FILES['fileSeat']['tmp_name'];
        $status   = move_uploaded_file($temp, "app/controllers/" . $namaFile);
    }

    public function getSeatReturn()
    {
        $id         = $_POST['idJad'];
        $data       = $this->model('M_Home')->getSeat($id);
        $idJadwal   = $_POST['idJadwal'];
        $dataReturn = $_POST['dataReturn'];
        $sqldate    = "tb_book.dateReturn = '" . $dataReturn . "'";

        $isi = '';
        $nomor = 0;
        $char  = 0;
        $alpha = range('A', 'Z');


        if ($data['id'] == 12) {
            $seatVIPNew = $data['boatSeatVIP'] + 3;
        } else {
            $seatVIPNew = $data['boatSeatVIP'];
        }
        for ($i = 1; $i <= $seatVIPNew; $i++) {
            ++$nomor;
            if ($nomor == 7) {
                $nomor = 1;
            }
            if ($nomor % 6 == 0) {
                $char++;
            } else {
                $num = $alpha[$char];
            }

            $seat = $num . $nomor;
            $kursi = $this->model('M_Home')->getSeatBooking($seat, $idJadwal, $sqldate);


            $checkDisable = $this->model('M_Home')->checkDisable($seat, $idJadwal);

            if ($checkDisable['seat'] == $seat) {
                $dnone = 'display:none';
            } else {
                $dnone = '';
            }


            if ($kursi['seatNumber'] == $seat) {
                $disabled = 'disabled';
                $cssCheck = '';
            } else {
                $disabled = '';
                $cssCheck = 'seatReturnCheck';
            }

            $isi = $isi . '<li class="seat">
                                <input type="checkbox" id="VR' . $num . $nomor . '"  name="seatReturn[]" value="' . $num . $nomor . '_V" class="seatReturn ' . $cssCheck . '" ' . $disabled . ' />
                                <label for="VR' . $num . $nomor . '" class="seatChooseReturn"  style="' . $dnone . '">' . $num . $nomor . '</label>
                            </li>';
        }


        $isi_2 = '';
        $nomor = 0;
        for ($i = 1; $i <= $data['boatSeat']; $i++) {
            ++$nomor;
            if ($nomor == 9) {
                $nomor = 1;
            }
            if ($i % 8 == 0) {
                $char++;
            } else {
                $num = $alpha[$char];
            }

            $seat = $num . $nomor;

            $kursi_2 = $this->model('M_Home')->getSeatBooking($seat, $idJadwal, $sqldate);
            $checkDisable = $this->model('M_Home')->checkDisable($seat, $idJadwal);

            if ($kursi_2['seatNumber'] == $seat) {
                $disabled = 'disabled';
                $cssCheck = '';
            } else {
                $disabled = '';
                $cssCheck = 'seatReturnCheck';
            }

            if ($checkDisable['seat'] == $seat) {
                $dnone = 'display:none';
            } else {
                $dnone = '';
            }

            $isi_2 = $isi_2 . '<li class="seat">
                                <input type="checkbox" id="RR' . $num . $nomor . '"  name="seatReturn[]" value="' . $num . $nomor . '" class="seatReturnRegular ' . $cssCheck . '" ' . $disabled . '/>
                                <label for="RR' . $num . $nomor . '" class="seatChooseReturn" style="' . $dnone . '">' .  $num . $nomor . '</label>
                            </li>';
        }


        echo json_encode(['isi' => $isi, 'isiR' => $isi_2]);
    }

    public function getSeatRegularReturn()
    {
        $id         = $_POST['idJad'];
        $data       = $this->model('M_Home')->getSeat($id);
        $idJadwal   = $_POST['idJadwal'];
        $dataReturn = $_POST['dataReturn'];
        $sqldate    = "tb_book.dateReturn = '" . $dataReturn . "'";

        $isi = '';
        for ($i = 1; $i <= $data['boatSeat']; $i++) {

            $seat = 'RR' . $i;
            $kursi = $this->model('M_Home')->getSeatBooking($seat, $idJadwal, $sqldate);

            if ($kursi['seatNumber'] == $seat) {
                $disabled = 'disabled';
            } else {
                $disabled = '';
            }

            $isi = $isi . '<li class="seat">
                                <input type="checkbox" id="RR' . $i . '"  name="seatReturn[]" value="RR' . $i . '" class="seatReturnRegular seatReturnCheck" ' . $disabled . '/>
                                <label for="RR' . $i . '" class="seatChooseReturn">' . $i . 'R</label>
                            </li>';
        }
        echo json_encode(['isi' => $isi]);
    }

    public function getBoat()
    {
        $id = $_POST['idKapal'];
        $data = $this->model('M_Home')->getJadwalBy($id);

        $datas  = [
            'boatName'                    => $data['boatName'],
            'sTime'                       => date('H:i A', strtotime($data['sTime'])),
            'priceDomestic'               => str_replace(',', '.', number_format($data['priceDomestic'])),
            'priceInternational'          => str_replace(',', '.', number_format($data['priceInternational'])),
            'priceDomesticVIP'            => str_replace(',', '.', number_format($data['priceDomesticVIP'])),
            'priceInternationalVIP'       => str_replace(',', '.', number_format($data['priceInternationalVIP'])),
            'child_priceDomestic'         => str_replace(',', '.', number_format($data['child_priceDomestic'])),
            'child_priceInternational'    => str_replace(',', '.', number_format($data['child_priceInternational'])),
            'child_priceDomesticVIP'      => str_replace(',', '.', number_format($data['child_priceDomesticVIP'])),
            'child_priceInternationalVIP' => str_replace(',', '.', number_format($data['child_priceInternationalVIP'])),

            'priceDomesticAsli'               => $data['priceDomestic'],
            'priceInternationalAsli'          => $data['priceInternational'],
            'priceDomesticVIPAsli'            => $data['priceDomesticVIP'],
            'priceInternationalVIPAsli'       => $data['priceInternationalVIP'],
            'child_priceDomesticAsli'         => $data['child_priceDomestic'],
            'child_priceInternationalAsli'    => $data['child_priceInternational'],
            'child_priceDomesticVIPAsli'      => $data['child_priceDomesticVIP'],
            'child_priceInternationalVIPAsli' => $data['child_priceInternationalVIP'],
        ];

        echo json_encode($datas);
    }



    public function getTransport()
    {
        $id   = $_POST['idKapal'];
        $data = $this->model('M_Home')->getTransportBy($id);

        $datas  = [
            'transpotName' => $data['transpotName'],
            'price'        => str_replace(',', '.', number_format($data['price'])),
            'priceAsli'    => $data['price'],
        ];
        echo json_encode($datas);
    }

    public function transaksi()
    {
        $this->view('hompage/cek_transaksi');
    }
}
