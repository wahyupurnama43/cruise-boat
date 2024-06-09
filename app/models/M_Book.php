<?php

class M_Book
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM tb_book";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getAllBookInfo()
    {

        if ($_SESSION['session_login_id'] !== "admin@gmail.com" && $_SESSION['session_login_grade'] !== "staff") {
            $tambah = "WHERE tb_book.createBy = '" . $_SESSION['session_login_id'] . "'";
        } else {
            $tambah = '';
        }

        $sql = "SELECT tb_book.*, sDay, sTime, orderNum, nominal, type FROM tb_book
                LEFT JOIN tb_schedule ON tb_book.scheduleID=tb_schedule.id
                LEFT JOIN tb_payment ON tb_book.id=tb_payment.bookID   " . $tambah . "
                ORDER BY dateCreate DESC limit 20 ";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getBookDetail($id)
    {
        $sql = "SELECT * FROM tb_book WHERE tb_book.id = '$id'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function getManifest($id)
    {
        $sql = "SELECT * FROM tb_manifest WHERE bookID = '$id'";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getSeatNumber($book, $schedule, $tambah)
    {
        if ($tambah == 'return') {
            $tambahan = "AND seatReturn = '1'";
        } else {
            $tambahan = "AND seatReturn = '0'";
        }
        $sql = "SELECT * FROM tb_manifest WHERE bookID = '$book' AND scheduleID = '$schedule' " . $tambahan;
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getSeat($book, $name, $tambah)
    {
        if ($tambah == 'return') {
            $tambahan = "AND seatReturn = '1'";
        } else {
            $tambahan = "AND seatReturn = '0'";
        }
        $sql = "SELECT * FROM tb_manifest WHERE bookID = '$book' AND fullName = '$name' " . $tambahan;
        $this->db->query($sql);
        return $this->db->single();
    }

    // ini untuk ketika scan masuk detail lalu rubah status booking on depart dst
    public function updateBook($data)
    {
        $sql = "UPDATE tb_book SET status=:status WHERE id=:id"; // jika sudah berangkat dan tgl kembali tidak kosong/ada maka ubah status jadi on return
        $this->db->query($sql);
        $this->db->bind('id', $data['id']);
        $this->db->bind('status', $data['status']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getTransaksiDetail($id)
    {
        $sql = "SELECT tb_book.*,payment.type,payment.orderNum,payment.urlPayment,payment.dateExpired,payment.nominal, letsgo.sFrom,letsgo.sTo,letsgo.sTime,letsgo.priceDomestic AS priceDomesticGo,letsgo.priceInternational AS priceInternationalGo,letsgo.priceDomesticVIP AS priceDomesticVIPGo ,letsgo.priceInternationalVIP AS priceInternationalVIPGo,tb_transport.transpotName, tb_transport.price,screturn.sTime as timeReturn,screturn.priceDomestic AS priceDomesticReturn ,screturn.priceInternational AS priceInternationalReturn,screturn.priceDomesticVIP AS priceDomesticVIPReturn,screturn.priceInternationalVIP AS priceInternationalVIPReturn, boatGo.boatName as boatNameGo,boatReturn.boatName as boatNameReturn,screturn.sFrom AS SreturnFrom,screturn.sTo AS SreturnTo FROM tb_book
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

    public function kirimAPI($id)
    {

        $tsD = $this->getTransaksiDetail($id);
        $manifest = $this->getManifest($id);

        if ($tsD['status'] == "2" && $tsD['datereturn'] !== null) {
            foreach ($manifest as $m) {

                // ini api untuk sales
                $url = APIEASYBOOK . "api/ExternalDailySales";
                $now = date('d/m/Y');
                $signature = md5("ELREY" . $now . "EZE456VZ4Z");

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $headers = array(
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "Signature : $signature",
                    "Operator-Code : ELREY",
                );
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                if ($m['region'] == 'international') {
                    $pass = $m['passengerID'];
                    $passID = $m['passengerID'];
                    $pass_2 = 'Passport';
                } else {
                    $passLocal = $m['passengerID'];
                    $passID = $m['passengerID'];
                    $pass_2 = 'KTP';
                }

                $data = [
                    "Sales" => array([
                        "TicketID" => $id,
                        "DepartureDate" => date('Y-m-d', strtotime($tsD['depart'])) . ' ' . date('H:i:s', strtotime($tsD['sTime'])),
                        "ShipName" => $tsD['boatNameGo'],
                        "FromSubPlaceName" => $tsD['sTo'],
                        "ToSubPlaceName" => $tsD['sFrom'],
                        "PassengerName" => $m['fullName'],
                        "PassengerNRIC" => $passLocal,
                        "PassengerPassport" => $pass,
                        "PassengerContact" => "-",
                        "PassengerNationality" => $m['nationality'],
                        "PassengerCategory" => $m['passengerCategory'],
                        "Status" => "A",
                    ]),
                ];

                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

                $resp = curl_exec($curl);
                curl_close($curl);
                $status = json_decode($resp);

                if ($status->message !== 'Success') {
                    return -500;
                } else {
                    echo $resp;
                }

                // ini untuk api DitlalaManifest
                $url_2 = APIEASYBOOK . "api/DitlalaManifest";
                $now_2 = date('d/m/Y');
                $signature_2 = md5("ELREY" . $now_2 . "EZE456VZ4Z");

                $curl_2 = curl_init();
                curl_setopt($curl_2, CURLOPT_URL, $url_2);
                curl_setopt($curl_2, CURLOPT_POST, true);
                curl_setopt($curl_2, CURLOPT_RETURNTRANSFER, true);

                $headers_2 = array(
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "Signature : $signature_2",
                    "Operator-Code : ELREY",
                );
                curl_setopt($curl_2, CURLOPT_HTTPHEADER, $headers_2);

                if ($m['passengerCategory'] == "adult") {
                    $cate_passenger = 'Dewasa';
                } else if ($m['passengerCategory'] == "child") {
                    $cate_passenger = 'Anak';
                } else {
                    $cate_passenger = 'Bayi';
                }

                $data_2 = [
                    "PortID" => '517101',
                    "OperatorID" => 'X0094',
                    "ShipData" => [
                        "ShipName" => '1',
                        "ShipSailNumber" => '-',
                        "ShipSailDate" => date('dmy', strtotime($tsD['depart'])),
                        "ShipSailETD" => '-',
                        "ShipSailETA" => '-',
                        "ShipSailFrom" => 'Pelabuhan ' . $tsD['sFrom'],
                        "ShipSailDestination" => 'Pelabuhan ' . $tsD['sTo'],
                    ],

                    "PassengerData" => [
                        [
                            "TicketID" => $tsD['orderNum'],
                            "BookingID" => $tsD['id'],
                            "PassengerName" => $m['fullName'],
                            "PassengerIDType" => $pass_2,
                            "PassengerNRIC" => $passID,
                            "PassengerGender" => '-',
                            "PassengerAge" => '-',
                            "PassengerCategory" => $cate_passenger,
                            "PassengerAddress" => '-',
                            "PassengerSeatNumber" => $m['seatNumber'],
                            "PassengerSeatCategory" => $m['seatCategory'],
                        ],
                    ],
                ];

                curl_setopt($curl_2, CURLOPT_POSTFIELDS, json_encode($data_2));

                $resp_2 = curl_exec($curl_2);
                curl_close($curl_2);

                $status_2 = json_decode($resp_2);

                if ($status_2->message !== 'Success') {
                    return -500;
                } else {
                    echo $resp_2;
                }
            }
        } elseif ($tsD['status'] == '1') {
            foreach ($manifest as $m) {

                // ini api untuk sales
                $url = APIEASYBOOK . "api/ExternalDailySales";
                $now = date('d/m/Y');
                $signature = md5("ELREY" . $now . "EZE456VZ4Z");

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $headers = array(
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "Signature : $signature",
                    "Operator-Code : ELREY",
                );
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                if ($m['region'] == 'international') {
                    $pass = $m['passengerID'];
                    $passID = $m['passengerID'];
                    $pass_2 = 'Passport';
                } else {
                    $passLocal = $m['passengerID'];
                    $passID = $m['passengerID'];
                    $pass_2 = 'KTP';
                }

                $data = [
                    "Sales" => array([
                        "TicketID" => $id,
                        "DepartureDate" => date('Y-m-d', strtotime($tsD['depart'])) . ' ' . date('H:i:s', strtotime($tsD['sTime'])),
                        "ShipName" => $tsD['boatNameGo'],
                        "FromSubPlaceName" => $tsD['sFrom'],
                        "ToSubPlaceName" => $tsD['sTo'],
                        "PassengerName" => $m['fullName'],
                        "PassengerNRIC" => $passLocal,
                        "PassengerPassport" => $pass,
                        "PassengerContact" => "-",
                        "PassengerNationality" => $m['nationality'],
                        "PassengerCategory" => $m['passengerCategory'],
                        "Status" => "A",
                    ]),
                ];

                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

                $resp = curl_exec($curl);
                curl_close($curl);

                $status = json_decode($resp);

                if ($status->message !== 'Success') {
                    return -500;
                } else {
                    echo $resp;
                }

                // ini untuk api DitlalaManifest
                $url_2 = APIEASYBOOK . "api/DitlalaManifest";
                $now_2 = date('d/m/Y');
                $signature_2 = md5("ELREY" . $now_2 . "EZE456VZ4Z");

                $curl_2 = curl_init();
                curl_setopt($curl_2, CURLOPT_URL, $url_2);
                curl_setopt($curl_2, CURLOPT_POST, true);
                curl_setopt($curl_2, CURLOPT_RETURNTRANSFER, true);

                $headers_2 = array(
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "Signature : $signature_2",
                    "Operator-Code : ELREY",
                );
                curl_setopt($curl_2, CURLOPT_HTTPHEADER, $headers_2);

                if ($m['passengerCategory'] == "adult") {
                    $cate_passenger = 'Dewasa';
                } else if ($m['passengerCategory'] == "child") {
                    $cate_passenger = 'Anak';
                } else {
                    $cate_passenger = 'Bayi';
                }

                if ($m['seatNumber'] !== '') {
                    $sN = $m['seatNumber'];
                } else {
                    $sN = '-';
                }

                if ($m['seatCategory'] !== '') {
                    $sC = $m['seatCategory'];
                } else {
                    $sC = '-';
                }

                if ($passID !== null) {
                    $passID_new = $passID;
                } else {
                    $passID_new = '-';
                }

                if ($pass_2 !== null) {
                    $pass_2_New = $pass_2;
                } else {
                    $pass_2_New = '-';
                }

                $data_2 = [
                    "PortID" => "1",
                    "OperatorID" => 2,
                    "ShipData" => [
                        "ShipName" => "1",
                        "ShipSailNumber" => "-",
                        "ShipSailDate" => date("dmy"),
                        "ShipSailETD" => "-",
                        "ShipSailETA" => "-",
                        "ShipSailFrom" => "Pelabuhan " . $tsD['sFrom'],
                        "ShipSailDestination" => "Pelabuhan " . $tsD['sTo'],
                    ],

                    "PassengerData" => [
                        [
                            "TicketID" => $tsD['orderNum'],
                            "BookingID" => $tsD['id'],
                            "PassengerName" => $m['fullName'],
                            "PassengerIDType" => $pass_2_New,
                            "PassengerNRIC" => $passID_new,
                            "PassengerGender" => "-",
                            "PassengerAge" => "-",
                            "PassengerCategory" => $cate_passenger,
                            "PassengerAddress" => "-",
                            "PassengerSeatNumber" => $sN,
                            "PassengerSeatCategory" => $sC,
                        ],
                    ],
                ];

                curl_setopt($curl_2, CURLOPT_POSTFIELDS, json_encode($data_2));

                $resp_2 = curl_exec($curl_2);
                curl_close($curl_2);

                $status_2 = json_decode($resp_2);

                if ($status_2->message !== 'Success') {
                    return -500;
                } else {
                    echo $resp_2;
                }
            }
        }
    }

    public function kirimAPIV2($id)
    {

        $tsD = $this->getTransaksiDetail($id);
        $manifest = $this->getManifest($id);

        if ($tsD['status'] == "2" && $tsD['datereturn'] !== null) {

            $data['Sales'] = [];

            $data_2 = [
                "PortID" => '517101',
                "OperatorID" => 'X0094',
                "ShipData" => [
                    "ShipName" => $tsD['boatNameReturn'],
                    "ShipSailNumber" => "-",
                    "ShipSailDate" => date("dmy"),
                    "ShipSailETD" => "-",
                    "ShipSailETA" => "-",
                    "ShipSailFrom" => "Pelabuhan " . $tsD['SreturnFrom'],
                    "ShipSailDestination" => "Pelabuhan " . $tsD['SreturnTo'],
                ],

                "PassengerData" => [],
            ];
            foreach ($manifest as $m) {
                if ($m['seatReturn'] == '1') {

                    if ($m['region'] == 'international') {
                        $pass = $m['passengerID'];
                        $passID = $m['passengerID'];
                        $pass_2 = 'Passport';
                    } else {
                        $passLocal = $m['passengerID'];
                        $passID = $m['passengerID'];
                        $pass_2 = 'KTP';
                    }

                    array_push($data['Sales'], [
                        "TicketID" => $m['id'],
                        "DepartureDate" => date('Y-m-d', strtotime($tsD['datereturn'])) . ' ' . date('H:i:s', strtotime($tsD['timeReturn'])),
                        "ShipName" => $tsD['boatNameGo'],
                        "FromSubPlaceName" => $tsD['sTo'],
                        "ToSubPlaceName" => $tsD['sFrom'],
                        "PassengerName" => $m['fullName'],
                        "PassengerNRIC" => $passLocal,
                        "PassengerPassport" => $pass,
                        "PassengerContact" => "-",
                        "PassengerNationality" => $m['nationality'],
                        "PassengerCategory" => $m['passengerCategory'],
                        "Status" => "A",
                    ]);

                    // ========================================
                    // API KEDUA
                    // =========================================
                    if ($m['passengerCategory'] == "adult") {
                        $cate_passenger = 'Dewasa';
                    } else if ($m['passengerCategory'] == "child") {
                        $cate_passenger = 'Anak';
                    } else {
                        $cate_passenger = 'Bayi';
                    }

                    if ($m['seatNumber'] !== '') {
                        $sN = $m['seatNumber'];
                    } else {
                        $sN = '-';
                    }

                    if ($m['seatCategory'] !== '') {
                        $sC = $m['seatCategory'];
                    } else {
                        $sC = '-';
                    }

                    if ($passID !== null) {
                        $passID_new = $passID;
                    } else {
                        $passID_new = '-';
                    }

                    if ($pass_2 !== null) {
                        $pass_2_New = $pass_2;
                    } else {
                        $pass_2_New = '-';
                    }

                    array_push($data_2['PassengerData'], [
                        "TicketID" => $m['id'],
                        "BookingID" => $tsD['id'],
                        "PassengerName" => $m['fullName'],
                        "PassengerIDType" => $pass_2_New,
                        "PassengerNRIC" => $passID_new,
                        "PassengerGender" => "-",
                        "PassengerAge" => "-",
                        "PassengerCategory" => $cate_passenger,
                        "PassengerAddress" => "-",
                        "PassengerSeatNumber" => $sN,
                        "PassengerSeatCategory" => $sC,
                    ]);
                }
            }

            //==============================================================
            //
            // API SALES
            //
            // =============================================================

            // // ini api untuk sales
            $url = APIEASYBOOK . "api/ExternalDailySales";
            $now = date('d/m/Y');
            $signature = md5("ELREY" . $now . "EZE456VZ4Z");

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $headers = array(
                "Accept: application/json",
                "Content-Type: application/json",
                "Signature : $signature",
                "Operator-Code : ELREY",
            );

            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            $resp = curl_exec($curl);
            curl_close($curl);
            $status = json_decode($resp);

            if ($status->message !== 'Success') {
                return -500;
            } else {
                echo $resp;
            }

            // ==============================================================
            //
            // API DITLALA
            //
            // =============================================================

            // ini untuk api DitlalaManifest
            $url_2 = APIEASYBOOK . "api/DitlalaManifest";
            $now_2 = date('d/m/Y');
            $signature_2 = md5("ELREY" . $now_2 . "EZE456VZ4Z");

            $curl_2 = curl_init();
            curl_setopt($curl_2, CURLOPT_URL, $url_2);
            curl_setopt($curl_2, CURLOPT_POST, true);
            curl_setopt($curl_2, CURLOPT_RETURNTRANSFER, true);
            $headers_2 = array(
                "Accept: application/json",
                "Content-Type: application/json",
                "Signature : $signature_2",
                "Operator-Code : ELREY",
            );
            curl_setopt($curl_2, CURLOPT_HTTPHEADER, $headers_2);
            curl_setopt($curl_2, CURLOPT_POSTFIELDS, json_encode($data_2));
            $resp_2 = curl_exec($curl_2);
            curl_close($curl_2);
            $status_2 = json_decode($resp_2);
            if ($status_2->message !== 'Success') {
                return -500;
            } else {
                echo $resp_2;
            }
        } elseif ($tsD['status'] == '1') {

            $data['Sales'] = [];

            $data_2 = [
                "PortID" => '517101',
                "OperatorID" => 'X0094',
                "ShipData" => [
                    "ShipName" => $tsD['boatNameGo'],
                    "ShipSailNumber" => "-",
                    "ShipSailDate" => date("dmy"),
                    "ShipSailETD" => "-",
                    "ShipSailETA" => "-",
                    "ShipSailFrom" => "Pelabuhan " . $tsD['sFrom'],
                    "ShipSailDestination" => "Pelabuhan " . $tsD['sTo'],
                ],

                "PassengerData" => [],
            ];
            foreach ($manifest as $m) {
                if ($m['seatReturn'] == '0') {
                    if ($m['region'] == 'international') {
                        $pass = $m['passengerID'];
                        $passID = $m['passengerID'];
                        $pass_2 = 'Passport';
                    } else {
                        $passLocal = $m['passengerID'];
                        $passID = $m['passengerID'];
                        $pass_2 = 'KTP';
                    }

                    array_push($data['Sales'], [
                        "TicketID" => $m['id'],
                        "DepartureDate" => date('Y-m-d', strtotime($tsD['depart'])) . ' ' . date('H:i:s', strtotime($tsD['sTime'])),
                        "ShipName" => $tsD['boatNameGo'],
                        "FromSubPlaceName" => $tsD['sFrom'],
                        "ToSubPlaceName" => $tsD['sTo'],
                        "PassengerName" => $m['fullName'],
                        "PassengerNRIC" => $passLocal,
                        "PassengerPassport" => $pass,
                        "PassengerContact" => "-",
                        "PassengerNationality" => $m['nationality'],
                        "PassengerCategory" => $m['passengerCategory'],
                        "Status" => "A",
                    ]);

                    // ========================================
                    // API KEDUA
                    // =========================================
                    if ($m['passengerCategory'] == "adult") {
                        $cate_passenger = 'Dewasa';
                    } else if ($m['passengerCategory'] == "child") {
                        $cate_passenger = 'Anak';
                    } else {
                        $cate_passenger = 'Bayi';
                    }

                    if ($m['seatNumber'] !== '') {
                        $sN = $m['seatNumber'];
                    } else {
                        $sN = '-';
                    }

                    if ($m['seatCategory'] !== '') {
                        $sC = $m['seatCategory'];
                    } else {
                        $sC = '-';
                    }

                    if ($passID !== null) {
                        $passID_new = $passID;
                    } else {
                        $passID_new = '-';
                    }

                    if ($pass_2 !== null) {
                        $pass_2_New = $pass_2;
                    } else {
                        $pass_2_New = '-';
                    }

                    array_push($data_2['PassengerData'], [
                        "TicketID" => $m['id'],
                        "BookingID" => $tsD['id'],
                        "PassengerName" => $m['fullName'],
                        "PassengerIDType" => $pass_2_New,
                        "PassengerNRIC" => $passID_new,
                        "PassengerGender" => "-",
                        "PassengerAge" => "-",
                        "PassengerCategory" => $cate_passenger,
                        "PassengerAddress" => "-",
                        "PassengerSeatNumber" => $sN,
                        "PassengerSeatCategory" => $sC,
                    ]);
                }
            }

            //==============================================================
            //
            // API SALES
            //
            // =============================================================

            // ini api untuk sales
            $url = APIEASYBOOK . "api/ExternalDailySales";
            $now = date('d/m/Y');
            $signature = md5("ELREY" . $now . "EZE456VZ4Z");

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $headers = array(
                "Accept: application/json",
                "Content-Type: application/json",
                "Signature : $signature",
                "Operator-Code : ELREY",
            );

            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            $resp = curl_exec($curl);
            curl_close($curl);
            $status = json_decode($resp);

            if ($status->message !== 'Success') {
                return -500;
            } else {
                echo $resp;
            }

            // ==============================================================
            //
            // API DITLALA
            //
            // =============================================================

            // ini untuk api DitlalaManifest
            $url_2 = APIEASYBOOK . "api/DitlalaManifest";
            $now_2 = date('d/m/Y');
            $signature_2 = md5("ELREY" . $now_2 . "EZE456VZ4Z");

            $curl_2 = curl_init();
            curl_setopt($curl_2, CURLOPT_URL, $url_2);
            curl_setopt($curl_2, CURLOPT_POST, true);
            curl_setopt($curl_2, CURLOPT_RETURNTRANSFER, true);
            $headers_2 = array(
                "Accept: application/json",
                "Content-Type: application/json",
                "Signature : $signature_2",
                "Operator-Code : ELREY",
            );
            curl_setopt($curl_2, CURLOPT_HTTPHEADER, $headers_2);
            curl_setopt($curl_2, CURLOPT_POSTFIELDS, json_encode($data_2));
            $resp_2 = curl_exec($curl_2);
            curl_close($curl_2);
            $status_2 = json_decode($resp_2);
            if ($status_2->message !== 'Success') {
                return -500;
            } else {
                echo $resp_2;
            }
        }
    }

    public function updateStatus($id, $extra = "")
    {
        $status = "";

        if ($extra == "") {
            $sql_data = "SELECT * FROM tb_book WHERE id='$id'";
        } else {
            $sql_data = "SELECT tb_book.* FROM tb_payment INNER JOIN tb_book ON tb_payment.bookID = tb_book.id WHERE orderNum='$id'";
        }

        $this->db->query($sql_data);
        $book = $this->db->single();

        // 0 = waiting payment, 1 = waiting schedule, 2 = on depart, 3 = finished, 4 = expired, 5 = on return
        if ($book['status'] == "2" && $book['datereturn'] !== null) {
            $sql = "UPDATE tb_book SET status='5' WHERE id=:id"; // jika sudah berangkat dan tgl kembali tidak kosong/ada maka ubah status jadi on return
            $this->db->query($sql);
            $this->db->bind('id', $book['id']);
            $this->db->execute();
            $status = "on return";
        } elseif ($book['status'] == '1') {
            $sql = "UPDATE tb_book SET status='2' WHERE id=:id"; // jika belum berangkat
            $this->db->query($sql);
            $this->db->bind('id', $book['id']);
            $this->db->execute();
            $status = "on depart";
        } else {
            $status = "finished";
        }

        return $status;
    }

    public function updateStatusFinished()
    {

        // $now = date('Y-m-d');
        // $now_stamp = strtotime($now);

        $allData = $this->getAll();

        foreach ($allData as $book) {
            if ($book['status'] == '5') {
                // ubah on return jadi finished jika sudah berangkat balik
                $this->updateStatusFinishedProses($book['id']);
            } elseif ($book['status'] == '2' && ($book['datereturn'] == "0000-00-00" || $book['datereturn'] == null)) {
                // ubah on depart tanpa return jadi finished jika sdh berangkat
                $this->updateStatusFinishedProses($book['id']);
            }
        }
    }

    public function updateStatusFinishedProses($id)
    {
        $sql = "UPDATE tb_book SET status='3', lastUpdateBy='SISTEM' WHERE id=:id";
        $this->db->query($sql);
        $this->db->bind('id', $id);
        $this->db->execute();

        $this->db->query("UPDATE tb_manifest SET status_seat = '1' WHERE bookID = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
    }

    public function getSeatNumberByID($id, $seat)
    {
        $sql = "SELECT * FROM tb_manifest WHERE id = '$id' AND seatNumber='$seat'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function getSeatNumberBySeat($seat, $book)
    {
        $sql = "SELECT * FROM tb_manifest WHERE seatNumber = '$seat' AND bookID = '$book'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function exchangeSeat($id, $seat)
    {
        $sql = "UPDATE tb_manifest SET seatNumber=:seat WHERE id=:id";
        $this->db->query($sql);
        $this->db->bind('seat', $seat);
        $this->db->bind('id', $id);
        $this->db->execute();
    }

    public function updateName($data)
    {
        $sql = "UPDATE tb_manifest SET fullName=:fullName WHERE fullName=:fullNameLama AND bookID=:bookID";
        $this->db->query($sql);
        $this->db->bind('fullName', $data['fullname']);
        $this->db->bind('fullNameLama', $data['fullNameLama']);
        $this->db->bind('bookID', $data['bookID']);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
