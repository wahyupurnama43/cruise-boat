<?php

class Dashboard extends Controller
{

    public function __construct()
    {
        $checkUser = $this->model('M_Dashboard')->getUser($_SESSION['session_login_token']);
        // check user jika dirubah melalui session
        if ($checkUser['userID'] ==  $_SESSION['session_login_id'] && $checkUser['userName'] ==  $_SESSION['session_login']) {
            if ($_SESSION['status'] == 'true' or $_SESSION['session_login_token'] !== NULL or $_SESSION['session_login'] !== NULL or $_SESSION['session_login_id'] !== NULL or $_SESSION['session_login_grade'] !== NULL) {
            } else {
                header('Location: ' . BASEURL . 'login');
            }
        } else {
            session_unset();
            session_destroy();
            header('Location: ' . BASEURL . 'login');
        }
    }

    public function index()
    {
        if ($_SESSION['session_login_grade'] !== "syahbandar") {
            $data['books']      = $this->model('M_Dashboard')->getPersentaseBooks();
            $data['totalBooks'] = $this->model('M_Dashboard')->getTotal('tb_book');
            $data['totalUser']  = $this->model('M_Dashboard')->getTotalUser('tb_user');
            $data['totalPrice'] = $this->model('M_Dashboard')->getTotalPrice('tb_payment');

            $this->view('template/header');
            $this->view('dashboard/index', $data);
            $this->view('template/footer');
        } else {
            $this->view('template/404');
        }
    }


    public function getDataBy()
    {
        // echo json_encode($_POST);
        $data = $this->model($_POST['model'])->getUserBy($_POST['table'], $_POST['attr'], $_POST['id']);
        echo json_encode($data);
    }

    public function delete($id, $tb, $red)
    {

        $tb  = Encripsi::encode('decrypt', $tb);
        $red = Encripsi::encode('decrypt', $red);

        $status = $this->model('M_User')->delete($id, $tb);
        $img    = $this->model('M_Boat')->getImg($tb, $id);

        if ($img !== '') {
            $this->model('M_Boat')->deleteIMG($tb, $id);
            @unlink($img['imgDirectory'] . $img['imgName']);
        }


        if ($status > 0) {
            Flasher::setFlash('Data Berhasil Di Hapus', 'success');
            header('Location: ' . BASEURL . 'dashboard/' . $red . '/');
        } else {
            Flasher::setFlash('Data Gagal Di Hapus', 'error');
            header('Location: ' . BASEURL . 'dashboard/' . $red . '/');
        }
    }

    public function handle_upload_image($file)
    {
        $error                  = $file['error'];
        $size                   = $file['size'];
        $name                   = $file['name'];
        $type                   = $file['type'];
        $tmp_name               = $file['tmp_name'];
        $ext                    = pathinfo($name, PATHINFO_EXTENSION);
        $newfilename            = uniqid(rand()) . '.' . $ext;
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg');

        // jika data yang di masukkan itu benar jpg png jpeg
        if (in_array($ext, $ekstensi_diperbolehkan) == true) {
            if ($file['size'] > 0 && $file['size'] !== 0) {
                // pindahkan dari local server ke folder local public upload
                move_uploaded_file($tmp_name, 'public/upload/' . $newfilename);
                //dan return nama filenya
                $data['name']         = $newfilename;
                $data['ext']          = $ext;
                $data['imgDirectory'] = 'public/upload/';
                return $data;
            } else {
                return false;
            }
        } else {
            // jika tidak benar dia return false
            return false;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SEND API
    |--------------------------------------------------------------------------
    |
    | UNTUK LOAD SEND API DI DASHBOARD ADMIN
    |
    |--------------------------------------------------------------------------
    */

    public function sendApi()
    {
        $sendAPI = $this->model('M_Book')->kirimAPI($id);
    }


    /*
    |--------------------------------------------------------------------------
    | PAGE USER
    |--------------------------------------------------------------------------
    |
    | UNTUK LOAD PAGE USER DI DASHBOARD ADMIN
    |
    |--------------------------------------------------------------------------
    */

    public function user()
    {

        if ($_SESSION['session_login_grade'] == "administrator") {
            $data['getAllGrade'] = $this->model('M_User')->getAllGrade();
            $data['getAllUser']  = $this->model('M_User')->getAllUser();
            $data['getAllMitra'] = $this->model('M_User')->getAllMitra();
            $this->view('template/header');
            $this->view('dashboard/user/user', $data);
            $this->view('template/footer');
        } else {
            $this->view('template/404');
        }
    }

    public function create_user()
    {

        $status = $this->model('M_User')->create_user($_POST);

        if ($status > 0) {
            Flasher::setFlash('Data Berhasil Di Tambah', 'success');
            header('Location: ' . BASEURL . 'dashboard/user/');
        } else {
            Flasher::setFlash('Data Gagal Di Tambah', 'error');
            header('Location: ' . BASEURL . 'dashboard/user/');
        }
    }

    public function edit_user()
    {
        $status = $this->model('M_User')->edit_user($_POST);

        if ($status > 0) {
            Flasher::setFlash('Data Berhasil Di Update', 'success');
            header('Location: ' . BASEURL . 'dashboard/user/');
        } else {
            Flasher::setFlash('Data Gagal Di Update', 'error');
            header('Location: ' . BASEURL . 'dashboard/user/');
        }
    }

    public function send_user($id)
    {
        $status = $this->model('M_User')->sendMail($id);

        if ($status > 0) {
            Flasher::setFlash('Data Berhasil Di Update', 'success');
            header('Location: ' . BASEURL . 'dashboard/user/');
        } else {
            Flasher::setFlash('Data Gagal Di Update', 'error');
            header('Location: ' . BASEURL . 'dashboard/user/');
        }
    }


    public function create_grade()
    {
        $status = $this->model('M_User')->create_grade($_POST);

        if ($status > 0) {
            Flasher::setFlash('Data Berhasil Di Tambah', 'success');
            header('Location: ' . BASEURL . 'dashboard/user/');
        } else {
            Flasher::setFlash('Data Gagal Di Tambah', 'error');
            header('Location: ' . BASEURL . 'dashboard/user/');
        }
    }


    public function edit_grade()
    {
        $status = $this->model('M_User')->edit_grade($_POST);

        if ($status > 0) {
            Flasher::setFlash('Data Berhasil Di Update', 'success');
            header('Location: ' . BASEURL . 'dashboard/user/');
        } else {
            Flasher::setFlash('Data Gagal Di Update', 'error');
            header('Location: ' . BASEURL . 'dashboard/user/');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PAGE MITRA
    |--------------------------------------------------------------------------
    |
    | UNTUK LOAD PAGE MITRA DI DASHBOARD ADMIN
    |
    |--------------------------------------------------------------------------
    */

    public function agent()
    {
        if ($_SESSION['session_login_grade'] == "administrator") {
            $data['getAllMitra'] = $this->model('M_Mitra')->getAllUser();
            $this->view('template/header');
            $this->view('dashboard/mitra/mitra', $data);
            $this->view('template/footer');
        } else {
            $this->view('template/404');
        }
    }

    public function create_mitra()
    {

        $status = $this->model('M_Mitra')->create_mitra($_POST);

        if ($status > 0) {
            Flasher::setFlash('Data Berhasil Di Tambah', 'success');
            header('Location: ' . BASEURL . 'dashboard/agent/');
        } else {
            Flasher::setFlash('Data Gagal Di Tambah', 'error');
            header('Location: ' . BASEURL . 'dashboard/agent/');
        }
    }

    public function edit_mitra()
    {

        $status = $this->model('M_Mitra')->edit_mitra($_POST);

        if ($status > 0) {
            Flasher::setFlash('Data Berhasil Di Update', 'success');
            header('Location: ' . BASEURL . 'dashboard/agent/');
        } else {
            Flasher::setFlash('Data Gagal Di Update', 'error');
            header('Location: ' . BASEURL . 'dashboard/agent/');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PAGE BOAT
    |--------------------------------------------------------------------------
    |
    | UNTUK LOAD PAGE BOAT DI DASHBOARD ADMIN
    |
    |--------------------------------------------------------------------------
    */

    public function boat()
    {
        if ($_SESSION['session_login_grade'] == "administrator") {
            $data['getAllBoat'] = $this->model('M_Boat')->getAllBoat();
            $this->view('template/header');
            $this->view('dashboard/boat/boat', $data);
            $this->view('template/footer');
        } else {
            $this->view('template/404');
        }
    }

    public function boat_disable($id)
    {

        if ($_SESSION['session_login_grade'] == "administrator") {
            $data['getBoot'] = $this->model('M_Boat')->getBoat($id);
            $this->view('template/header');
            $this->view('dashboard/boat/disable', $data);
            $this->view('template/footer');
        } else {
            $this->view('template/404');
        }
    }

    public function disable_seat()
    {
        $status = $this->model('M_Boat')->disable_seat($_POST['seat'], $_POST['boat']);
        echo json_encode($status);
        // if ($status > 0) {
        //     Flasher::setFlash('Data Berhasil Di Tambah', 'success');
        //     header('Location: ' . BASEURL . 'dashboard/schedule/');
        // } else {
        //     Flasher::setFlash('Data Gagal Di Tambah', 'error');
        //     header('Location: ' . BASEURL . 'dashboard/schedule/');
        // }
    }

    public function create_boat()
    {
        $gambar = $this->handle_upload_image($_FILES['imgBoat']);

        if ($gambar == false) {
            Flasher::setFlash('Masukkan jpg, jpeg,png', 'error');
            header('Location: ' . BASEURL . '/dashboard/boat/');
        } else {
            $status = $this->model('M_Boat')->create_boat($_POST, $gambar);
        }

        if ($status > 0) {
            Flasher::setFlash('Data Berhasil Di Tambah', 'success');
            header('Location: ' . BASEURL . 'dashboard/boat/');
        } else {
            Flasher::setFlash('Data Gagal Di Tambah', 'error');
            header('Location: ' . BASEURL . 'dashboard/boat/');
        }
    }

    public function edit_boat()
    {
        if ($_FILES['imgBoat']['error'] > 0) {
            $gambar = '';
        } else {
            $img = $this->model('M_Boat')->getImg('tb_boat', $_POST['id']);
            if ($img !== '') {
                @unlink($img['imgDirectory'] . $img['imgName']);
            }
            $gambar = $this->handle_upload_image($_FILES['imgBoat']);
        }

        $status = $this->model('M_Boat')->edit_boat($_POST, $gambar);

        if ($status > 0) {
            Flasher::setFlash('Data Berhasil Di Update', 'success');
            header('Location: ' . BASEURL . 'dashboard/boat/');
        } else {
            Flasher::setFlash('Data Gagal Di Update', 'error');
            header('Location: ' . BASEURL . 'dashboard/boat/');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PAGE SCHEDULE
    |--------------------------------------------------------------------------
    |
    | UNTUK LOAD PAGE SCHEDULE DI DASHBOARD ADMIN
    |
    |--------------------------------------------------------------------------
    */

    public function schedule()
    {
        if ($_SESSION['session_login_grade'] == "administrator") {
            $data['getAllSC'] = $this->model('M_Schedule')->getAllSC();
            $data['getAllBoat'] = $this->model('M_Boat')->getAllBoat();
            $this->view('template/header');
            $this->view('dashboard/schedule/schedule', $data);
            $this->view('template/footer');
        } else {
            $this->view('template/404');
        }
    }

    public function create_schedule()
    {
        $status = $this->model('M_Schedule')->create_schedule($_POST);
        if ($status > 0) {
            Flasher::setFlash('Data Berhasil Di Tambah', 'success');
            header('Location: ' . BASEURL . 'dashboard/schedule/');
        } else {
            Flasher::setFlash('Data Gagal Di Tambah', 'error');
            header('Location: ' . BASEURL . 'dashboard/schedule/');
        }
    }

    public function edit_schedule()
    {
        $status = $this->model('M_Schedule')->edit_schedule($_POST);

        if ($status > 0) {
            Flasher::setFlash('Data Berhasil Di Update', 'success');
            header('Location: ' . BASEURL . 'dashboard/schedule/');
        } else {
            Flasher::setFlash('Data Gagal Di Update', 'error');
            header('Location: ' . BASEURL . 'dashboard/schedule/');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PAGE TRANSPORT
    |--------------------------------------------------------------------------
    |
    | UNTUK LOAD PAGE TRANSPORT DI DASHBOARD ADMIN
    |
    |--------------------------------------------------------------------------
    */

    public function transport()
    {
        if ($_SESSION['session_login_grade'] == "administrator") {
            $data['getAllTR'] = $this->model('M_Transport')->getAllTR();
            $this->view('template/header');
            $this->view('dashboard/transport/transport', $data);
            $this->view('template/footer');
        } else {
            $this->view('template/404');
        }
    }

    public function create_transport()
    {
        $status = $this->model('M_Transport')->create_transport($_POST);
        if ($status > 0) {
            Flasher::setFlash('Data Berhasil Di Tambah', 'success');
            header('Location: ' . BASEURL . 'dashboard/transport/');
        } else {
            Flasher::setFlash('Data Gagal Di Tambah', 'error');
            header('Location: ' . BASEURL . 'dashboard/transport/');
        }
    }

    public function edit_transport()
    {
        $status = $this->model('M_Transport')->edit_transport($_POST);

        if ($status > 0) {
            Flasher::setFlash('Data Berhasil Di Update', 'success');
            header('Location: ' . BASEURL . 'dashboard/transport/');
        } else {
            Flasher::setFlash('Data Gagal Di Update', 'error');
            header('Location: ' . BASEURL . 'dashboard/transport/');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PAGE TRANSAKSI / BOOK
    |--------------------------------------------------------------------------
    |
    | UNTUK LOAD PAGE DATA TRANSAKSI (BOOK DAN MANIFEST) DI DASHBOARD ADMIN
    |
    |--------------------------------------------------------------------------
    */

    public function book()
    {

        if ($_SESSION['session_login_grade'] !== "accounting" && $_SESSION['session_login_grade'] !== "syahbandar") {
            $data['book'] = $this->model('M_Book')->getAllBookInfo();
            $this->view('template/header');
            $this->view('dashboard/book/book', $data);
            $this->view('template/footer');
        } else {
            $this->view('template/404');
        }
    }

    public function book_print($id)
    {
        $data['payment']         = $this->model('M_Home')->searchTransaksi($id);
        $data['bookId']          = $data['payment']['bookID'];
        $data['tsDetail']        = $this->model('M_Home')->getTransaksiDetail($data['bookId']);
        $data['seatDetail']      = $this->model('M_Home')->getSeatDetail($data['bookId']);

        if ($data['tsDetail']['scheduleReturnID'] != '0' || ($data['tsDetail']['datereturn'] !== '0000-00-00' && $data['tsDetail']['datereturn'] !== NULL)) {
            $count = $data['tsDetail']['adult'] + $data['tsDetail']['child'];
        } else {
            $count = 999;
        }
        $i = 0;
        foreach ($data['seatDetail'] as $d) {
            $i++;
            if ($i <= $count) {
                if ($d['passengerCategory'] !== 'infant') {
                    echo '<script type="text/javascript" language="Javascript">window.open("' . BASEURL . 'dashboard/print_book/' . $id . '/' . $d['id'] . '/depart");</script>';
                }
            }
        }

        echo '<script type="text/javascript" language="Javascript">window.location.href = "' . BASEURL . 'dashboard/book";</script>';
    }

    public function book_print_return($id)
    {
        $data['payment']         = $this->model('M_Home')->searchTransaksi($id);
        $data['bookId']          = $data['payment']['bookID'];
        $data['tsDetail']        = $this->model('M_Home')->getTransaksiDetail($data['bookId']);
        $data['seatDetail']      = $this->model('M_Home')->getSeatDetailReturn($data['bookId']);

        if ($data['tsDetail']['scheduleReturnID'] != '0' || ($data['tsDetail']['datereturn'] !== '0000-00-00' && $data['tsDetail']['datereturn'] !== NULL)) {
            $count = $data['tsDetail']['adult'] + $data['tsDetail']['child'];
        } else {
            $count = 999;
        }
        $i = 0;
        foreach ($data['seatDetail'] as $d) {
            $i++;
            if ($i <= $count) {
                if ($d['passengerCategory'] !== 'infant') {
                    echo '<script type="text/javascript" language="Javascript">window.open("' . BASEURL . 'dashboard/print_book/' . $id . '/' . $d['id'] . '/return");</script>';
                }
            }
        }

        echo '<script type="text/javascript" language="Javascript">window.location.href = "' . BASEURL . 'dashboard/book";</script>';
    }

    public function print_book($id, $idpass, $return)
    {


        if ($return == 'return') {
            // print jika return
            $data['payment']         = $this->model('M_Home')->searchTransaksi($id);
            $data['bookId']          = $data['payment']['bookID'];
            $data['tsDetail']        = $this->model('M_Home')->getTransaksiDetail($data['bookId']);
            $data['seatDetail']      = $this->model('M_Home')->getSeatDetailReturn($data['bookId']);
            $data['transportDetail'] = $this->model('M_Home')->getTransportBy($data['tsDetail']['transportID']);
            $data['id_pass']         = $idpass;

            $this->view('dashboard/book/print_admin_manifest_return', $data);
        } else {
            // print jika depart
            $data['payment']         = $this->model('M_Home')->searchTransaksi($id);
            $data['bookId']          = $data['payment']['bookID'];
            $data['tsDetail']        = $this->model('M_Home')->getTransaksiDetail($data['bookId']);
            $data['seatDetail']      = $this->model('M_Home')->getSeatDetail($data['bookId']);
            $data['transportDetail'] = $this->model('M_Home')->getTransportBy($data['tsDetail']['transportID']);
            $data['id_pass']         = $idpass;
            $this->view('dashboard/book/print_admin_manifest', $data);
        }
    }

    public function detail($inv)
    {
        //ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

        $payment = $this->model('M_Payment')->getAllPaymentByINV($inv);
        $id      = $payment['bookID'];
        $data['book']      = $this->model('M_Book')->getBookDetail($id);



        if ($data['book'] !== false) {
            $data['manifest']  = $this->model('M_Book')->getManifest($id);
            $data['payment']   = $this->model('M_Payment')->getPaymentbyBooking($id);
            $data['transport'] = $this->model('M_Transport')->getTransport($data['book']['transportID']);
            $data['schedule']  = $this->model('M_Schedule')->getSchedule($data['book']['scheduleID']);
            $data['boat']      = $this->model('M_Boat')->getBoat($data['schedule']['boatID']);

            if ($data['book']['status'] == '0') {
                $status = "danger";
                $text = "Waiting Payment";
            } elseif ($data['book']['status'] == '1') {
                $status = "warning";
                $text = "Waiting Schedule";
            } elseif ($data['book']['status'] == '2') {
                $status = "info";
                $text = "On Depart";
            } elseif ($data['book']['status'] == '3') {
                $status = "success";
                $text = "Finished";
            } elseif ($data['book']['status'] == '4') {
                $status = "secondary";
                $text = "Expired";
            } elseif ($data['book']['status'] == '5') {
                $status = "info";
                $text = "On Return";
            }

            $data['status']      = "badge-" . $status;
            $data['status_text'] = $text;
            $data['return']      = "";

            $data['depart_seat']      = $this->model('M_Book')->getSeatNumber($id, $data['book']['scheduleID'], '');
            $data['depart_seat_list'] = $this->model('M_Book')->getSeatNumber($id, $data['book']['scheduleID'], '');
            $data['return_seat']      = "";
            $data['return_boat']      = "";

            if ($data['book']['scheduleReturnID'] != "" || $data['book']['scheduleReturnID'] != "0") {
                $data['return']           = $this->model('M_Schedule')->getSchedule($data['book']['scheduleReturnID']);
                $data['return_seat']      = $this->model('M_Book')->getSeatNumber($id, $data['book']['scheduleReturnID'], 'return');
                $data['return_seat_list'] = $this->model('M_Book')->getSeatNumber($id, $data['book']['scheduleReturnID'], 'return');
                $data['return_boat']      = $this->model('M_Boat')->getBoat($data['return']['boatID']);
            }

            $this->view('template/header');
            $this->view('dashboard/book/detail', $data);
            $this->view('template/footer');
        } else {
            Flasher::setFlash('Data Tidak Di Temukan', 'error');
            header('Location: ' . BASEURL . 'dashboard/book');
        }
    }

    public function send_transaksi($inv)
    {
        $data = $this->model('M_Home')->send_transaksi($inv);
        if ($data > 0) {
            Flasher::setFlash('Data Berhasil Di Kirim', 'success');
            header('Location: ' . BASEURL . 'dashboard/book');
        } else {
            Flasher::setFlash('Data Gagal Di Kirim', 'error');
            header('Location: ' . BASEURL . 'dashboard/book');
        }
    }

    public function updateBook()
    {
        $data = $this->model('M_Book')->updateBook($_POST);

        if ($data > 0) {
            Flasher::setFlash('Data Berhasil Di Update', 'success');
            header('Location: ' . BASEURL . 'dashboard/detail/' . $_POST['inv']);
        } else {
            Flasher::setFlash('Data Gagal Di Update', 'error');
            header('Location: ' . BASEURL . 'dashboard/detail/' . $_POST['inv']);
        }
    }

    public function cekTransaksi()
    {
        // ini untuk update transaksi ketika expired
        $data =  $this->model('M_Payment')->checkExpired();
        echo $data;
    }

    public function changeSeat()
    {
        $seatNumberChange = $_POST['seat'];
        $realSeatNumber   = $_POST['realseat'];
        $id               = $_POST['id'];
        $bookID           = $_POST['book'];
        $getExchangeSeat  = $this->model('M_Book')->getSeatNumberBySeat($seatNumberChange, $bookID);
        $exchangeID       = $getExchangeSeat['id'];
        // $exchangeSeat     = $getExchangeSeat['seatNumber'];

        // update
        $this->model('M_Book')->exchangeSeat($id, $seatNumberChange);
        $this->model('M_Book')->exchangeSeat($exchangeID, $realSeatNumber);
        $cek1 = $this->model('M_Book')->getSeatNumberByID($id, $seatNumberChange);
        $cek2 = $this->model('M_Book')->getSeatNumberByID($exchangeID, $realSeatNumber);

        if (!empty($cek1) && !empty($cek2)) echo 1;
        else echo 0;
    }

    public function updateName()
    {
        // ini belum
        $data = $this->model('M_Book')->updateName($_POST);
        if ($data > 0) {
            Flasher::setFlash('Data Berhasil Di Update', 'success');
            header('Location: ' . BASEURL . 'dashboard/detail/' . $_POST['inv']);
        } else {
            Flasher::setFlash('Data Gagal Di Update', 'error');
            header('Location: ' . BASEURL . 'dashboard/detail/' . $_POST['inv']);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PAGE LAPORAN
    |--------------------------------------------------------------------------
    |
    | UNTUK LOAD PAGE DATA SEMUA (BOOK DAN MANIFEST) DI DASHBOARD ADMIN
    |
    |--------------------------------------------------------------------------
    */

    public function getCountPassanger($date, $scheduleID)
    {
        $data = $this->model('M_Report')->getReportMitra($date, $scheduleID);
        return count($data);
    }


    public function report($type, $start = "", $end = "", $extra1 = "", $extra2 = "", $extra3 = "")
    {

        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);
        $type   = htmlspecialchars(trim($type), ENT_QUOTES);
        $start  = htmlspecialchars(trim($start), ENT_QUOTES);
        $end    = htmlspecialchars(trim($end), ENT_QUOTES);
        $extra1 = htmlspecialchars(trim($extra1), ENT_QUOTES);
        $extra2 = htmlspecialchars(trim($extra2), ENT_QUOTES);
        $extra3 = htmlspecialchars(trim($extra3), ENT_QUOTES);


        $data['start'] = $start;
        if ($start == "") {
            $start = date('Y-m-d');
            $data['start'] = $start;
        }

        $data['end'] = $end;
        if ($end == "") {
            $end = date('Y-m-d');
            $data['end'] = $end;
        }

        $data['extra1'] = $extra1;
        $data['extra2'] = $extra2;
        $data['extra3'] = $extra3;


        if ($type == "booking") {

            if ($_SESSION['session_login_grade'] !== "accounting" && $_SESSION['session_login_grade'] !== "syahbandar") {


                $user   = $extra1;
                $pay    = $extra2;
                $status = $extra3;

                $data['pay_list'] = array(
                    "all"   => "All",
                    "va"    => "Virtual Account",
                    "cc"    => "Credit Card",
                    "cash"   => "Cash",
                    "cash"   => "Cash",
                    "bon"   => "Acc/bon",
                    "edc"   => "Edc",
                    "later" => "Pay Later"
                );

                $data['getAllGrade'] = $this->model('M_User')->getAllGrade();

                // extra 1 = user, extra 2 = tipe payment, extra 3 = status payment
                if ($_SESSION['session_login_grade'] != "administrator") {
                    $user = $_SESSION['session_login_grade'];
                } else {
                    if ($user == "") $user = 'all';
                }

                if ($pay == "") $pay = 'all';
                if ($status == "") $status = 'all';

                $data['user']   = $user;
                $data['pay']    = $extra2;
                $data['status'] = $extra3;
                $data['table']  = $this->model('M_Report')->getReportBook($start, $end, $user, $pay, $status);

                $this->view('template/header');
                $this->view('dashboard/report/reportbook', $data);
                $this->view('template/footer');
            } else {
                $this->view('template/404');
            }
        } elseif ($type == "mitra") {
            if ($_SESSION['session_login_grade'] == "administrator" || $_SESSION['session_login_grade'] == "syahbandar" || $_SESSION['session_login_grade'] == "staff") {
                $data['date'] = $start;
                if ($start == "") {
                    $start = date('Y-m-d');
                    $data['date'] = $start;
                }
                $data['table'] = $this->model('M_Schedule')->getSchedulebyDate($data['date']);
                $this->view('template/header');
                $this->view('dashboard/report/reportmitra', $data);
                $this->view('template/footer');
            } else {
                $this->view('template/404');
            }
        } elseif ($type == "agent") {
            if ($_SESSION['session_login_grade'] == "accounting" || $_SESSION['session_login_grade'] == "administrator") {
                $data['agent']     = $this->model('M_Report')->getAgentBy();
                $data['userAgent'] = $extra1;
                $data['pay']       = $extra2;

                $user = $extra1;
                $pay  = $extra2;

                $data['pay_list'] = array(
                    "all"   => "All",
                    "va"    => "Virtual Account",
                    "cc"    => "Credit Card",
                    "cash"   => "Cash",
                    "bon"   => "Acc/bon",
                    "edc"   => "Edc",
                    "later" => "Pay Later"
                );

                $data['table']  = $this->model('M_Report')->getReportAgent($start, $end, $user, $pay);

                $this->view('template/header');
                $this->view('dashboard/report/reportagent', $data);
                $this->view('template/footer');
            } else {
                $this->view('template/404');
            }
        }
    }

    public function print_passanger($date, $id)
    {
        if ($_SESSION['session_login_grade'] == "accounting" || $_SESSION['session_login_grade'] == "administrator" || $_SESSION['session_login_grade'] == "staff") {
            $date                    = htmlspecialchars(trim($date), ENT_QUOTES);
            $scheduleID              = htmlspecialchars(trim($id), ENT_QUOTES);
            $data['depart_date']     = date('l, d-m-Y', strtotime($date));
            $data['schedule']        = $this->model('M_Schedule')->getSchedule($scheduleID);
            $data['boat_name']       = $data['schedule']['boatName'];
            $data['captain']         = $data['schedule']['captain'];
            $data['crew']            = $data['schedule']['crew'];
            $data['time']            = date('H:i A', strtotime($data['schedule']['sTime']));
            $data['route']           = ucwords($data['schedule']['sFrom']) . " to " . ucwords($data['schedule']['sTo']);
            $data['passenger']       = $this->model('M_Report')->getReportMitra($date, $scheduleID);
            $this->view('dashboard/report/print_passanger', $data);
        } else {
            $this->view('template/404');
        }
    }

    public function excel_agent($start, $end, $user, $pay)
    {
        if ($_SESSION['session_login_grade'] == "accounting" || $_SESSION['session_login_grade'] == "administrator") {
            $data['agent']   = $this->model('M_Report')->getUserBy();
            $data['userAgent'] = $user;
            $data['table']  = $this->model('M_Report')->getReportAgent($start, $end, $user, $pay);
            $this->view('dashboard/report/reportagent_excel', $data);
        } else {
            $this->view('template/404');
        }
    }

    public function getScheduleList()
    {
        $data = $this->model('M_Schedule')->getSchedulebyDate($_POST['date']);

        if (empty($data)) $data = 0;

        echo json_encode($data);
    }

    public function getDetailMitra()
    {
        $date                    = htmlspecialchars(trim($_POST['date']), ENT_QUOTES);
        $scheduleID              = htmlspecialchars(trim($_POST['id']), ENT_QUOTES);
        $data['depart_date']     = date('l, d-m-Y', strtotime($date));
        $data['schedule']        = $this->model('M_Schedule')->getSchedule($scheduleID);
        $data['boat_name']       = $data['schedule']['boatName'];
        $data['captain']         = $data['schedule']['captain'];
        $data['crew']            = $data['schedule']['crew'];
        $data['time']            = date('H:i A', strtotime($data['schedule']['sTime']));
        $data['route']           = ucwords($data['schedule']['sFrom']) . " to " . ucwords($data['schedule']['sTo']);
        $data['passenger']       = $this->model('M_Report')->getReportMitra($date, $scheduleID);

        if (empty($data['passenger'])) $data['passenger'] = 0;
        echo json_encode($data);
    }


    /*
    |--------------------------------------------------------------------------
    | PAGE SETTING APP
    |--------------------------------------------------------------------------
    |
    | UNTUK LOAD PAGE DATA SEMUA (BOOK DAN MANIFEST) DI DASHBOARD ADMIN
    |
    |--------------------------------------------------------------------------
    */


    public function setting()
    {

        $data['setting'] = $this->model('M_Setting')->getSetting();
        $this->view('template/header');
        $this->view('dashboard/setting/setting', $data);
        $this->view('template/footer');
    }

    public function edit_setting()
    {

        if (isset($_FILES['value'])) {
            $error                  = $_FILES['value']['error'];
            $size                   = $_FILES['value']['size'];
            $name                   = $_FILES['value']['name'];
            $type                   = $_FILES['value']['type'];
            $tmp_name               = $_FILES['value']['tmp_name'];
            $ext                    = pathinfo($name, PATHINFO_EXTENSION);
            $newfilename            = 'email-' . rand() . '.' . $ext;
            $ekstensi_diperbolehkan = array('jpg');

            // jika data yang di masukkan itu benar jpg png jpeg
            if (in_array($ext, $ekstensi_diperbolehkan) == true) {
                if ($_FILES['value']['size'] > 0 && $_FILES['value']['size'] !== 0) {
                    // pindahkan dari local server ke folder local public upload
                    move_uploaded_file($tmp_name, 'public/home/images/' . $newfilename);

                    //dan return nama filenya
                    $statusGambar = 1;
                } else {
                    $statusGambar = 0;
                }
            } else {
                // jika tidak benar dia return false
                $statusGambar = 0;
            }
            if ($statusGambar <= 0) {
                Flasher::setFlash('Gambar Harus JPG !! atau Hubungi Teknis', 'error');
                header('Location: ' . BASEURL . 'dashboard/setting/');
            }
        } else {
            $newfilename = '';
        }



        $status = $this->model('M_Setting')->edit_setting($_POST, $newfilename);
        if ($status > 0) {
            Flasher::setFlash('Data Berhasil Di Update', 'success');
            header('Location: ' . BASEURL . 'dashboard/setting/');
        } else {
            Flasher::setFlash('Data Gagal Di Update', 'error');
            header('Location: ' . BASEURL . 'dashboard/setting/');
        }
    }



    /*
    |--------------------------------------------------------------------------
    | PAGE PROFILE APP
    |--------------------------------------------------------------------------
    |
    | UNTUK LOAD PAGE DATA SEMUA (BOOK DAN MANIFEST) DI DASHBOARD ADMIN
    |
    |--------------------------------------------------------------------------
    */
    public function profile()
    {

        $data['user'] = $this->model('M_User')->getUserBy('tb_user', 'id', $_SESSION['session_login_token']);
        $data['grade'] = $this->model('M_User')->getUserBy('tb_grade', 'id', $_SESSION['session_login_grade']);

        // cek apakah userid yg login sama atau tidak jika tidak maka lempar ke login middleware(basic)
        if ($data['user']['userID'] == $_SESSION['session_login_id']) {
            $this->view('template/header');
            $this->view('dashboard/profile/profile', $data);
            $this->view('template/footer');
        } else {
            session_unset();
            session_destroy();
            header('Location: ' . BASEURL . 'login');
        }
    }


    public function editProfile()
    {
        $data = $this->model('M_User')->getUserBy('tb_user', 'id', $_SESSION['session_login_token']);


        // cek apakah userid yg login sama atau tidak jika tidak maka lempar ke login middleware(basic)
        if ($data['userID'] == $_SESSION['session_login_id']) {
            $status = $this->model('M_User')->editProfile($_POST, $data['id']);

            if ($status > 0) {
                Flasher::setFlash('Data Berhasil Di Update', 'success');
                header('Location: ' . BASEURL . 'dashboard/profile/');
            } else {
                Flasher::setFlash('Data Gagal Di Update', 'error');
                header('Location: ' . BASEURL . 'dashboard/profile/');
            }
        } else {
            // session_unset();
            // session_destroy();
            header('Location: ' . BASEURL . 'login');
        }
    }


    public function scan($inv)
    {

        $payment = $this->model('M_Payment')->getAllPaymentByINV($inv);

        $id      = $payment['bookID'];

        $data['book']      = $this->model('M_Book')->getBookDetail($id);
        if ($data['book'] !== false) {

            // // send API
            $sendAPI = $this->model('M_Book')->kirimAPIV2($id);

            if ($sendAPI == -500) {
                Flasher::setFlash('Please Repeat 5 Minutes Later', 'error');
                header('Location: ' . BASEURL . 'dashboard/book');
            } else {

                $data['manifest']  = $this->model('M_Book')->getManifest($id);
                $data['payment']   = $this->model('M_Payment')->getPaymentbyBooking($id);
                $data['transport'] = $this->model('M_Transport')->getTransport($data['book']['transportID']);
                $data['schedule']  = $this->model('M_Schedule')->getSchedule($data['book']['scheduleID']);
                $data['boat']      = $this->model('M_Boat')->getBoat($data['schedule']['boatID']);

                if ($data['book']['status'] == '0') {
                    $status = "danger";
                    $text = "Waiting Payment";
                } elseif ($data['book']['status'] == '1') {
                    $status = "warning";
                    $text = "Waiting Schedule";
                } elseif ($data['book']['status'] == '2') {
                    $status = "info";
                    $text = "On Depart";
                } elseif ($data['book']['status'] == '3') {
                    $status = "success";
                    $text = "Finished";
                } elseif ($data['book']['status'] == '4') {
                    $status = "secondary";
                    $text = "Expired";
                } elseif ($data['book']['status'] == '5') {
                    $status = "info";
                    $text = "On Return";
                }

                $data['status']      = "badge-" . $status;
                $data['status_text'] = $text;

                $data['return'] = "";

                $data['depart_seat']      = $this->model('M_Book')->getSeatNumber($id, $data['book']['scheduleID'], '');
                $data['depart_seat_list'] = $this->model('M_Book')->getSeatNumber($id, $data['book']['scheduleID'], '');
                $data['return_seat']      = "";
                $data['return_boat']      = "";

                if ($data['book']['scheduleReturnID'] != "" || $data['book']['scheduleReturnID'] != "0") {
                    $data['return']           = $this->model('M_Schedule')->getSchedule($data['book']['scheduleReturnID']);
                    $data['return_seat']      = $this->model('M_Book')->getSeatNumber($id, $data['book']['scheduleReturnID'], 'return');
                    $data['return_seat_list'] = $this->model('M_Book')->getSeatNumber($id, $data['book']['scheduleReturnID'], 'return');
                    $data['return_boat']      = $this->model('M_Boat')->getBoat($data['return']['boatID']);
                }

                $this->view('template/header');
                $this->view('dashboard/book/detail_scan', $data);
                $this->view('template/footer');
            }
        } else {
            Flasher::setFlash('Data Tidak Di Temukan', 'error');
            header('Location: ' . BASEURL . 'dashboard/book');
        }
    }

    public function scan_prosess($inv)
    {
        $upd = $this->model('M_Book')->updateStatus($inv, 'scan');
        echo $upd;
    }
}
