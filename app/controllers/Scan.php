<?php

use Dompdf\Dompdf;
use mikehaertl\wkhtmlto\Image;

class Scan extends Controller
{


    public function updateFinish()
    {
        $upd = $this->model('M_Book')->updateStatusFinished();
        return $upd;
    }

    public function tes($id)
    {
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);
        $data['payment']         = $this->model('M_Home')->searchTransaksi($id);
        $data['bookId']          = $data['payment']['bookID'];
        $data['tsDetail']        = $this->model('M_Home')->getTransaksiDetail($data['bookId']);
        $data['seatDetail']      = $this->model('M_Home')->getSeatDetail($data['bookId']);
        $data['transportDetail'] = $this->model('M_Home')->getTransportBy($data['tsDetail']['transportID']);
        // if ($data['tsDetail']['scheduleReturnID'] != '0' || ($data['tsDetail']['datereturn'] !== '0000-00-00' && $data['tsDetail']['datereturn'] !== NULL)) {
        //     $count = $data['tsDetail']['adult'] + $data['tsDetail']['child'];
        // } else {
        //     $count = 999;
        // }
        $this->view('dashboard/book/print_admin_manifest', $data);
    }

    public function tesApi()
    {


        $url =  "http://balibarriergatetest.easybook.com/api/DitlalaManifest";

        // $now = date('d/m/Y');
        // $signature = md5("ELREY" . $now . "EZE456VZ4Z");

        // $curl = curl_init();
        // curl_setopt($curl, CURLOPT_URL, $url);
        // curl_setopt($curl, CURLOPT_POST, true);
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // $headers = array(
        //     "Content-Type: application/json",
        //     "Signature : " . $signature,
        //     "Operator-Code : ELREY"
        // );

        // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        // $data = [
        //     "PortID"     => '517101',
        //     "OperatorID" => 'X0094',
        //     "ShipData"   => [
        //         "ShipName"            => '1',
        //         "ShipSailNumber"      => '1',
        //         "ShipSailDate"        => '080922',
        //         "ShipSailETD"         => '0',
        //         "ShipSailETA"         => '0',
        //         "ShipSailFrom"        => 'Pelabuhan Manado',
        //         "ShipSailDestination" => 'Pelabuhan Laut Dofa',
        //     ],
        //     "PassengerData" => [
        //         [
        //             "TicketID"              => 'PQZLPJMD',
        //             "BookingID"             => 'FYIZMAU0',
        //             "PassengerName"         => 'AKBAR SUKANDI',
        //             "PassengerIDType"       => 'KTP',
        //             "PassengerNRIC"         => '0',
        //             "PassengerGender"       => 'L',
        //             "PassengerAge"          => '0',
        //             "PassengerCategory"     => 'DEWASA',
        //             "PassengerAddress"      => 'jln',
        //             "PassengerSeatNumber"   => '355 A',
        //             "PassengerSeatCategory" => 'NORMAL',
        //         ],
        //     ]
        // ];



        // curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        // $resp_2 = curl_exec($curl);
        // curl_close($curl);




        // ini api untuk sales
        $url = "http://balibarriergatetest.easybook.com/api/ExternalDailySales";
        $now = date('d/m/Y');
        $signature = md5("ELJUN" . $now . "EASIELJUN");

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "Signature : $signature",
            "Operator-Code : ELJUN"
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = [
            "Sales" => array([
                "TicketID" => '2132',
                "DepartureDate" =>  date('Y-m-d H:i:s'),
                "ShipName" => 'asdasd',
                "FromSubPlaceName" => 'testing',
                "ToSubPlaceName" => 'hai',
                "PassengerName" => 'asdasd',
                "PassengerNRIC" => 'awdasd',
                "PassengerPassport" => 'asdasd',
                "PassengerContact" => "-",
                "PassengerNationality" => 'asdasd',
                "PassengerCategory" => 'asdasd',
                "Status" => "A"
            ]),
        ];

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        $resp = curl_exec($curl);
        curl_close($curl);

        $status =  json_decode($resp);


        if ($status->message !== 'Success') {
            echo 'failed';
            die;
        } else {
            echo $resp;
        }
    }

    // public function generatebarcode($id)
    // {
    //     $barcodeText = $id;
    //     $barcodeType = 'code128';
    //     $barcodeSize = '55';
    //     $printText   = true;
    //     $filepath    = "";
    //     $orientation = "horizontal";
    //     $SizeFactor  = 1;
    //     Barcode::setBarcode($filepath, $barcodeText, $barcodeSize, $orientation, $barcodeType,  $printText, $SizeFactor);
    // }

    // public function json()
    // {
    //     $image        = $_POST['image'];
    //     $location     = "public/pdf/";
    //     $image_parts  = explode(";base64,", $image);
    //     $image_base64 = base64_decode($image_parts[1]);
    //     $filename     = "screenshot_" . uniqid() . '.png';
    //     $file         = $location . $filename;

    //     file_put_contents($file, $image_base64);

    //     $this->model('M_Home')->sendwaText(ASSETS . '/pdf/' . $filename, $filename, '6287810202578');
    // }

    // public function tes()
    // {
    //     $isi = 'https://www.malasngoding.com';

    //     $penyimpanan = "public/upload/";

    //     // perintah untuk membuat qrcode dan menyimpannya dalam folder temp
    //     QRcode::png($isi, $penyimpanan . "qrcode_saya.png", QR_ECLEVEL_H, 4);

    //     // menampilkan qrcode 
    //     echo '<img src="https://cruiseipay.misigame.com/' . $penyimpanan . 'qrcode_saya.png">';
    // }

    //     public function tesimg()
    //     {

    //         // $api = "AIzaSyACdCEUrPDQBT54MRcUXWIjPCmWc_Y0xEM";
    //         // $site = 'https://cruiseipay.misigame.com/scan/tes';
    //         // $adress = "https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed?url=$site&category=CATEGORY_UNSPECIFIED&strategy=DESKTOP&key=$api";
    //         // $curl_init = curl_init($adress);
    //         // curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, true);

    //         // $response = curl_exec($curl_init);
    //         // curl_close($curl_init);

    //         // $googledata = json_decode($response, true);

    //         // $snapdata = $googledata["lighthouseResult"]["audits"]["full-page-screenshot"]["details"];
    //         // $snap = $snapdata["screenshot"];
    //         // $content = file_get_contents($snap['data']);
    //         // file_put_contents('public/pdf/coba.jpg', $content);

    //         try {
    //             ini_set('display_errors', '1');
    //             ini_set('display_startup_errors', '1');
    //             error_reporting(E_ALL);
    //             $text  = '
    // Invoice : INV-202209239590 
    // From : sanur 
    // To:nusa penida 
    // Departure (Go) : 28/09/2022 14:39 PM Boat 
    // (Go) : Kapal Matahari 
    // Passenger :  Made Wahyu Purnama Putra (V3) 
    //                     Made Wahyu Purnama Putra (V3) 
    //                     Made Wahyu Purnama Putra (V3) 
    // Phone : 6287810202578 
    // Total : *RP 20,000*';


    //             var_dump(urlencode($text));
    //             die;

    //             $curl = curl_init();

    //             curl_setopt_array($curl, array(
    //                 CURLOPT_URL => 'https://v7.woonotif.com/api/send.php?number=6287810202578&type=text&message=' . urlencode($text) . '&instance_id=' . INSTANCE_ID . '&access_token=' . ACCESSTOKENWA,
    //                 CURLOPT_RETURNTRANSFER => true,
    //                 CURLOPT_ENCODING => '',
    //                 CURLOPT_MAXREDIRS => 10,
    //                 CURLOPT_TIMEOUT => 0,
    //                 CURLOPT_FOLLOWLOCATION => true,
    //                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //                 CURLOPT_CUSTOMREQUEST => 'POST',
    //             ));

    //             $response = curl_exec($curl);

    //             curl_close($curl);
    //             var_dump($response);
    //         } catch (\Throwable $e) {
    //             var_dump($e);
    //             die;
    //         }
    //     }
}
