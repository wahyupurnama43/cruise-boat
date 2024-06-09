<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Booking Cruise</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="zignatic">

    <!-- DATE-PICKER -->
    <link rel="stylesheet" href="<?= ASSETS ?>/home/vendor/date-picker/css/datepicker.min.css">

    <!-- STYLE CSS -->
    <link rel="stylesheet" href="<?= ASSETS ?>/home/css/style.css">

    <script src="https://kit.fontawesome.com/e216f95262.js" crossorigin="anonymous"></script>

    <script src="<?= ASSETS ?>/home/js/jquery-3.3.1.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        body {
            overflow-x: hidden;

            font-family: "Poppins-Regular";
            font-size: 14px;
            margin: 0;
            color: #999;
            background: url("<?= ASSETS ?>/home/images/bg-staff.png") no-repeat center center;
            height: 100vh;
            background-size: cover;
            display: flex;
            align-items: center;
        }

        .list-group-item {
            position: relative;
            display: block;
            padding: 10px 15px;
            margin-bottom: -1px;
            background-color: #fff;
            border: 1px solid #ddd;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #aaa;
            border-radius: 4px;
            height: 42px !important;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            display: block;
            padding-left: 8px;
            padding-right: 20px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin-top: 7px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 26px;
            position: absolute;
            top: 8px !important;
            right: 1px;
            width: 20px;
        }

        .select-wa {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 10px;
            font-size: 15px;
            margin-right: 20px;
            border: none;
            padding: 0 10px;
        }



        .lds-ring {
            position: relative;
            height: 45px;
        }

        .lds-ring div {
            box-sizing: border-box;
            display: block;
            position: absolute;
            width: 27px;
            height: 27px;
            margin: 8px;
            border: 5px solid #333;
            border-radius: 50%;
            animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
            border-color: #3377c0 transparent transparent transparent;
        }

        .lds-ring div:nth-child(1) {
            animation-delay: -0.45s;
        }

        .lds-ring div:nth-child(2) {
            animation-delay: -0.3s;
        }

        .lds-ring div:nth-child(3) {
            animation-delay: -0.15s;
        }

        @keyframes lds-ring {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loading-payment {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loading-payment p {
            margin-left: 4rem;
        }
    </style>
</head>

<body>

    <div class="row">
        <div class="flash-data" data-flashdata="<?= Flasher::flash(true); ?>"></div>
    </div>

    <div class="wrapper">
        <form action="<?= BASEURL ?>home/create_book" id="wizard" method="POST" name="form_name" class="position-relative">

            <!-- Home -->
            <h4></h4>
            <section>
                <h3>Booking Boat BY STAFF</h3>
                <div class="ov-scroll">
                    <div class="form-row">
                        <div class="form-col">
                            <label for="">
                                From
                            </label>
                            <div class="form-holder">
                                <i class="fas fa-map-marked"></i>
                                <select name="from" class="form-control " id="from">
                                    <option value="" disabled selected>--choose--</option>
                                    <?php foreach ($data['schedule'] as $sc) :  ?>
                                        <option value="<?= $sc['sFrom'] ?>"><?= $sc['sFrom'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="invalid-feedback alert-form-1">
                                Please Complete The Data !!
                            </div>
                        </div>
                        <div class="form-col">
                            <label for="">
                                To
                            </label>
                            <div class="form-holder">
                                <i class="fas fa-map-marked"></i>
                                <select name="to" id="to" class="form-control">
                                    <option value="" disabled selected>--choose--</option>
                                </select>
                            </div>
                            <div class="invalid-feedback alert-form-2">
                                Please Complete The Data !!
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <div class="form-row type-ticket">
                                <div class="form-col">
                                    <label for="">
                                        Adult
                                    </label>
                                    <div class="quantity">
                                        <a href="#" id="quantity__minus1" class="quantity__minus"><span>-</span></a>
                                        <input name="quantity1" type="text" class="quantity__input" id="quantity__input1">
                                        <a href="#" id="quantity__plus1" class="quantity__plus"><span>+</span></a>
                                    </div>

                                </div>
                                <div class="form-col">
                                    <label for="">
                                        Children
                                    </label>
                                    <div class="quantity">
                                        <a href="#" id="quantity__minus2" class="quantity__minus"><span>-</span></a>
                                        <input name="quantity2" type="text" id="quantity__input2" class="quantity__input">
                                        <a href="#" id="quantity__plus2" class="quantity__plus"><span>+</span></a>
                                    </div>
                                </div>

                                <div class="form-col">
                                    <label for="">
                                        Infant
                                    </label>
                                    <div class="quantity">
                                        <a href="#" id="quantity__minus3" class="quantity__minus"><span>-</span></a>
                                        <input name="quantity3" type="text" class="quantity__input" id="quantity__input3">
                                        <a href="#" id="quantity__plus3" class="quantity__plus"><span>+</span></a>
                                    </div>

                                </div>
                            </div>
                            <div class="invalid-feedback alert-form-3">
                                Please Complete The Data !!
                            </div>
                        </div>
                        <div class="form-col">
                            <label for="">
                                Choose Category
                            </label>
                            <div class="form-holder">
                                <i class="fas fa-globe"></i>
                                <select name="categori" id="categori" class="form-control">
                                    <option value="" disabled selected>--choose--</option>
                                    <option value="international">International</option>
                                    <option value="domestic">Domestic</option>
                                </select>
                            </div>
                            <div class="invalid-feedback alert-form-4">
                                Please Complete The Data !!
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-col">
                            <label for="">
                                Date Go
                            </label>
                            <div class="form-holder">
                                <i class="fas fa-calendar-week"></i>
                                <input name="dateGo" id="dateGo" type="date" class="form-control">
                            </div>
                            <div class="invalid-feedback alert-form-5">
                                Please Complete The Data !!
                            </div>
                        </div>
                        <div class="form-col d-none" id="return">
                            <label for="">
                                Date Return
                            </label>
                            <div class="form-holder">
                                <i class="fas fa-calendar-week"></i>
                                <input name="dataReturn" id="dataReturn" class="form-control" type="date">
                            </div>
                            <div class="invalid-feedback alert-form-6">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-check">
                            <input type="hidden" id="status" value="false">
                            <input class="form-check-input" type="checkbox" id="oneWay">
                            <label class="form-check-label" for="oneWay">
                                Return
                            </label>
                        </div>
                    </div>
                </div>
            </section>

            <!-- schedule -->
            <h4></h4>
            <section>
                <h3 style="margin-bottom: 37px;">Choose Departure Schedule</h3>
                <div class="notif" id="notif">
                    Please Choose Schedule
                </div>
                <div class="grid ov-scroll">
                    <?php foreach ($data['boat'] as $boat) : ?>
                        <div class="grid-container-item">
                            <div class="grid-item">
                                <?php $img = Controller::model('M_Home')->getImg($boat['id']); ?>
                                <div class="thumb" style="background: url('<?= BASEURL . $img['imgDirectory'] ?>/<?= $img['imgName'] ?>'); background-size:cover; background-position:center; background-repeat:no-repeat; ">
                                </div>
                                <div class="heading">
                                    <?= $boat['boatName'] ?>
                                </div>
                            </div>
                            <div class="d-block" id="schedule">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Day</th>
                                            <th scope="col">Time</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="load_jadwal<?= $boat['id'] ?>">

                                    </tbody>
                                </table>

                            </div>
                        </div>

                        <script>
                            $('#next').click(function() {
                                var dateGo = $('#dateGo').val();
                                var from = $('#from').val();
                                var to = $('#to').val();
                                var boat = '<?= $boat['id'] ?>'
                                $('#load_jadwal' + boat).html('');
                                // console.log(date);
                                $.ajax({
                                    type: 'POST', // Metode pengiriman data menggunakan POST
                                    url: '<?= BASEURL ?>home/getSchedule', // File yang akan memproses data
                                    data: {
                                        dateGo: dateGo,
                                        boat: boat,
                                        to: to,
                                        from: from,
                                    }, // Data yang akan dikirim ke file pemroses
                                    success: function(response) { // Jika berhasil
                                        // var res = JSON.parse(response)
                                        // console.log(response);

                                        if (response == '') {
                                            $('#load_jadwal' + boat).append('<tr><td colspan="3">Schedule Not Available</td></tr>')
                                        } else {
                                            $('#load_jadwal' + boat).append(response); // Berikan hasil ke id kota
                                        }
                                        // })
                                    }
                                });
                            });
                        </script>

                    <?php endforeach; ?>

                </div>
            </section>

            <!-- Seat -->
            <h4></h4>
            <section>
                <h3 style="margin-bottom: 37px;">Please select a seat Depart</h3>
                <div class="notif" id="notifSeat"></div>
                <div class="grid ov-scroll">
                    <div class="plane">
                        <div class="cockpit">
                            <h1>VIP Seat</h1>
                        </div>
                        <div class="exit exit--front fuselage">
                        </div>

                        <ol class="vip-seat cabin fuselage">
                            <div class="loading-payment" id="lodingSeat" style="display:none;  margin-left: 7.5rem;">
                                <div class="lds-ring">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                            <li class="row row--1">
                                <ol class="vip-seats" type="A" id="load_seat">

                                </ol>
                            </li>
                        </ol>

                        <div class="exit--back fuselage">
                        </div>
                        <div class="cockpit-back">
                        </div>
                    </div>
                    <div class="plane planeR">
                        <div class="cockpit">
                            <h1>Regular Seat</h1>
                        </div>
                        <div class="exit--front fuselage">
                        </div>
                        <ol class="regular-seat cabin fuselage">
                            <div class="loading-payment" id="lodingSeat2" style="display:none;  margin-left: 8.7rem;">
                                <div class="lds-ring">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                            <li class="row row--1">
                                <ol class="seats" type="A" id="load_seat_regular">
                                </ol>
                            </li>
                        </ol>
                        <div class="exit--back fuselage">
                        </div>
                        <div class="cockpit-back">
                        </div>
                    </div>
                </div>
            </section>

            <!-- schedule return -->
            <h4></h4>
            <section>
                <h3 style="margin-bottom: 37px;">Choose Return Schedule</h3>
                <div class="notif" id="notif">
                    Please Choose Schedule
                </div>
                <div class="grid ov-scroll">
                    <?php foreach ($data['boat'] as $boat) : ?>
                        <div class="grid-container-item">
                            <div class="grid-item">
                                <?php $img = Controller::model('M_Home')->getImg($boat['id']); ?>
                                <div class="thumb" style="background: url('<?= $img['imgDirectory'] ?>/<?= $img['imgName'] ?>'); background-size:cover; background-position:center; background-repeat:no-repeat; ">
                                </div>
                                <div class="heading">
                                    <?= $boat['boatName'] ?>
                                </div>
                            </div>
                            <div class="d-block">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Day</th>
                                            <th scope="col">Time</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="load_schedule_2<?= $boat['id'] ?>">

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <script>
                            $('#oneWay').change(function() {
                                if (this.checked) {
                                    $('#next').click(function() {
                                        // console.log(date);
                                        var dateReturn = $('#dataReturn').val();
                                        var from = $('#from').val();
                                        var to = $('#to').val();
                                        var boat = '<?= $boat['id'] ?>'
                                        $('#load_schedule_2' + boat).html('');
                                        $.ajax({
                                            type: 'POST', // Metode pengiriman data menggunakan POST
                                            url: '<?= BASEURL ?>home/getReturnSchedule', // File yang akan memproses data
                                            data: {
                                                dateGo: dateReturn,
                                                boat: boat,
                                                to: from,
                                                from: to,
                                            }, // Data yang akan dikirim ke file pemroses
                                            success: function(response) { // Jika berhasil
                                                // var res = JSON.parse(response)
                                                // console.log(res);
                                                if (response == '') {
                                                    $('#load_schedule_2' + boat).append('<tr><td colspan="3">Schedule Not Available</td></tr>')
                                                } else {
                                                    $('#load_schedule_2' + boat).append(response); // Berikan hasil ke id kota
                                                }
                                                // })
                                            }
                                        });
                                    });
                                }
                            })
                        </script>
                    <?php endforeach; ?>

                </div>
            </section>

            <!-- Seat -->
            <h4></h4>
            <section>
                <h3 style="margin-bottom: 37px;">Please select a seat Return</h3>
                <div class="notif" id="notifSeatReturn"></div>
                <div class="grid ov-scroll">
                    <div class="plane">
                        <div class="cockpit">
                            <h1>VIP Seat</h1>
                        </div>
                        <div class="exit exit--front fuselage">
                        </div>

                        <ol class="vip-seat cabin fuselage">
                            <div class="loading-payment" id="lodingSeatReturn" style="display:none;    margin-left: 7.5rem;">
                                <div class="lds-ring">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                            <li class="row row--1">
                                <ol class="vip-seats" type="A" id="load_seat_return_2">

                                </ol>
                            </li>
                        </ol>

                        <div class=" exit--back fuselage">
                        </div>
                        <div class="cockpit-back">
                        </div>
                    </div>
                    <div class="plane planeR">
                        <div class="cockpit">
                            <h1>Regular Seat</h1>
                        </div>
                        <div class="exit exit--front fuselage">
                        </div>
                        <ol class="regular-seat cabin fuselage">
                            <div class="loading-payment" id="lodingSeatReturn2" style="display:none;    margin-left: 8.7rem;">
                                <div class="lds-ring">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                            <li class="row row--1">
                                <ol class="seats" type="A" id="load_seat_regular_return">
                                </ol>
                            </li>
                        </ol>
                        <div class=" exit--back fuselage">
                        </div>
                        <div class="cockpit-back">
                        </div>
                    </div>
                </div>
            </section>

            <!-- detail orderer -->
            <h4></h4>
            <section>
                <h3>Details Orderer</h3>
                <div class="ov-scroll px-4">
                    <div class="form-col">
                        <label for="">
                            Agent
                        </label>
                        <div class="form-holder">
                            <i class="fas fa-user-edit"></i>
                            <input type="text" id="gsearchsimple" class="form-control" name="namefirst" required>

                        </div>
                        <div class="invalid-feedback alert-form-7">
                            Please Complete The Data !!
                        </div>
                        <ul class="list-group">

                        </ul>
                    </div>
                    <br>
                    <div class="form-row">
                        <div class="form-col">
                            <label for="">
                                Email
                            </label>
                            <div class="form-holder">
                                <i class="fas fa-envelope"></i>
                                <input type="email" class="form-control" name="email" id="email" required>
                            </div>
                            <div class="invalid-feedback alert-form-9">
                                Please Complete The Data !!
                            </div>
                        </div>
                        <div class="form-col">
                            <label for="">
                                Country
                            </label>
                            <div class="form-holder">
                                <select name="country" class=" js-example-basic-single form-control" id="country">
                                    <option value="" disabled selected>-- Choose --</option>
                                    <?php foreach ($data['nm_code'] as $key => $v) : ?>
                                        <option value="<?= $v['nicename'] ?>"><?= $v['nicename'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="invalid-feedback alert-form-10">
                                Please Complete The Data !!
                            </div>
                        </div>
                    </div>

                    <div class="form-col">
                        <label for="">
                            Phone / Whatsapp (WhatsApp number must be filled for send e-tickets)
                        </label>
                        <div class="form-row">
                            <div class="form-col" style="margin-right: 0px; ">
                                <div class="form-holder">
                                    <select name="format_number_country" class="js-example-basic-single form-control" id="">
                                        <?php foreach ($data['nm_code'] as $key => $v) : ?>
                                            <option value="<?= $v['phonecode'] ?>" <?= ($v['phonecode'] == '62' ? 'selected' : '') ?>>+<?= $v['phonecode'] ?> (<?= $v['nicename'] ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-holder">
                                    <input type="number" class="form-control" name="wa" id="wa" required placeholder="Please use the format 812345678" style="padding-left: 10px;">
                                </div>
                                <div class="invalid-feedback alert-form-10">
                                    Please Complete The Data !!
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <h4>Passanger</h4>
                    <hr style="margin-bottom: 20px; border: 1px solid #ccc;">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="above">
                        <label class="form-check-label" for="above">
                            Use The Data Above
                        </label>
                    </div>
                    <br>
                    <div id="passegerInput">
                        <input type="hidden" id="adult">
                        <input type="hidden" id="children">
                    </div>
                </div>
            </section>

            <!-- Transport -->
            <h4></h4>
            <section>
                <h3>Additional Services</h3>
                <div class="notif" id="notifTransport"></div>
                <div class="ov-scroll px-4">
                    <table class="table table-striped options_cart">
                        <tbody>

                            <?php foreach ($data['transport'] as $ts) :  ?>
                                <tr>
                                    <td>
                                        <i class="fas fa-plus-circle" style="font-size: 2em;"></i>
                                    </td>
                                    <td>
                                        <?= $ts['transpotName'] ?> <strong> + Rp <?= number_format($ts['price']) ?>*</strong>
                                        <br><small><?= $ts['zone'] ?></small>
                                    </td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" name="transport" value="<?= $ts['id'] ?>">
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                </tr>
                            <?php endforeach; ?>



                        </tbody>
                    </table>
                </div>
            </section>

            <!-- payment -->
            <h4></h4>
            <section>
                <h3>Payment Method</h3>
                <div class="notif" id="notifPayment"></div>

                <div class="ov-scroll">
                    <label class="task-collapse">
                        <input type="checkbox" class="toggle-collapse">
                        <div class="heading">Details Order</div>
                        <div class="collapse">
                            <div class="content">
                                <div class="grid payment">
                                    <table class="table d-desktop">
                                        <tr>
                                            <td>
                                                From
                                            </td>
                                            <td>
                                                <strong id="Dfrom"> Sanur</strong>
                                            </td>
                                            <td>
                                                To
                                            </td>
                                            <td>
                                                <strong id="Dto"> Lembongan </strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Name Orderer
                                            </td>
                                            <td>
                                                <strong id="Dorder"> Wahyu Purnama</strong>
                                            </td>
                                            <td>
                                                Phone
                                            </td>
                                            <td>
                                                <strong id="Dphone"> 08767123123</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Email
                                            </td>
                                            <td>
                                                <strong id="Demail"> siapp86@gmail.com</strong>
                                            </td>

                                            <td>
                                                Category Passanger
                                            </td>
                                            <td>
                                                <strong id="categoryPassaanger"> - </strong>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>
                                                Date Go
                                            </td>
                                            <td>
                                                <strong id="DjadwalGO"> 20 Jun 2022 ~ 21 Jun 2022</strong>
                                            </td>

                                            <td>
                                                Date Return
                                            </td>
                                            <td>
                                                <strong id="DjadwalReturn"> 20 Jun 2022 ~ 21 Jun 2022</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Boat (Go)
                                            </td>
                                            <td>
                                                <strong id="Dboat">No Boat</strong>
                                            </td>
                                            <td>
                                                Boat (Return)
                                            </td>
                                            <td>
                                                <strong id="DboatReturn">-</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Seat (Go)
                                            </td>
                                            <td>
                                                <strong id="Dseat">No Boat</strong>
                                            </td>
                                            <td>
                                                Seat (Return)
                                            </td>
                                            <td>
                                                <strong id="DseatReturn">-</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Passenger
                                            </td>
                                            <td>
                                                <strong>
                                                    <ol id="Dpassager"></ol>
                                                </strong>
                                            </td>

                                            <td>
                                                Price Passenger
                                            </td>
                                            <td>
                                                <strong id="DpricePasseger">-</strong>
                                            </td>

                                        </tr>


                                        <tr>
                                            <td>
                                                Transport
                                            </td>
                                            <td>
                                                <strong id="Dtransport">-</strong>
                                            </td>

                                            <td>
                                                Price Transport
                                            </td>
                                            <td>
                                                <strong id="DpriceTransport">-</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="4">
                                                <hr>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="2">

                                            </td>
                                            <td>
                                                Total
                                            </td>
                                            <td>
                                                <strong id="totalBayar">Rp 300.000</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="4">
                                                <hr>
                                            </td>
                                        </tr>

                                    </table>

                                    <table class="table d-mobile">
                                        <tr>
                                            <td width="100%">
                                                From
                                            </td>
                                            <td>
                                                <strong id="DfromM"> Sanur</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                To
                                            </td>
                                            <td>
                                                <strong id="DtoM"> Lembongan </strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Name Orderer
                                            </td>
                                            <td>
                                                <strong id="DorderM"> Wahyu Purnama</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Phone
                                            </td>
                                            <td>
                                                <strong id="DphoneM"> 08767123123</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Email
                                            </td>
                                            <td>
                                                <strong id="DemailM"> siapp86@gmail.com</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Category Passanger
                                            </td>
                                            <td>
                                                <strong id="categoryPassaangerM"> - </strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Date Go
                                            </td>
                                            <td>
                                                <strong id="DjadwalDepartM"> 20 Jun 2022 ~ 21 Jun 2022</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Date Return
                                            </td>
                                            <td>
                                                <strong id="DjadwalreturnM"> 20 Jun 2022 ~ 21 Jun 2022</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Boat (Go)
                                            </td>
                                            <td>
                                                <strong id="DboatM">No Boat</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Boat (Return)
                                            </td>
                                            <td>
                                                <strong id="DboatReturnM">-</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Seat (Go)
                                            </td>
                                            <td>
                                                <div style="width: 150px; word-wrap: break-word !important;">
                                                    <strong id="DseatM">No Boat</strong>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Seat (Return)
                                            </td>
                                            <td style="word-wrap: break-word">
                                                <div style="width: 150px; word-wrap: break-word !important;">
                                                    <strong id="DseatReturnM">-</strong>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Passenger
                                            </td>
                                            <td>
                                                <strong>
                                                    <ol id="DpassagerM"></ol>
                                                </strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Price Passenger
                                            </td>
                                            <td>
                                                <strong id="DpricePassegerM">-</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Transport
                                            </td>
                                            <td>
                                                <strong id="DtransportM">-</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Price Transport
                                            </td>
                                            <td>
                                                <strong id="DpriceTransportM">-</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="4">
                                                <hr>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                Total
                                            </td>
                                            <td>
                                                <strong id="totalBayarM">Rp 999.999</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="4">
                                                <hr>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </label>

                    <div style="display: flex; justify-content: center; align-content: center; align-items: center;">
                        <input type="hidden" value="" id="agent_user">
                        <input type="hidden" value="" id="agent_user_internasional">

                        <div class="loading-payment" id="loding" style="display:none;">
                            <div class="lds-ring">
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                            <p>Please Wait</p>
                        </div>
                        <div class="d-flex gap-3 jscc" style="" id="list_payment">
                            <?php if ($_SESSION['session_login_grade'] == "administrator") :  ?>
                                <div class="d-flex">
                                    <input type="radio" name="payment" id="cc" value="cc">
                                    <label for="cc" style="background: url('<?= ASSETS ?>/assets/payment/cc.png'); background-size:cover; background-position:center; background-repeat:no-repeat;  width: 86px;height: 59px; "></label>
                                </div>
                                <div class="d-flex">
                                    <input type="radio" name="payment" id="bca" value="bca">
                                    <label for="bca" style="background: url('<?= ASSETS ?>/assets/payment/bca.png'); background-size:cover; background-position:center; background-repeat:no-repeat;  width: 86px;height: 59px; "></label>
                                </div>
                                <div class="d-flex">
                                    <input type="radio" name="payment" id="bni" value="bni">
                                    <label for="bni" style="background: url('<?= ASSETS ?>/assets/payment/bni.png'); background-size:cover; background-position:center; background-repeat:no-repeat;  width: 86px;height: 59px; "></label>
                                </div>
                                <div class="d-flex">
                                    <input type="radio" name="payment" id="permata" value="permata">
                                    <label for="permata" style="background: url('<?= ASSETS ?>/assets/payment/permata.png'); background-size:cover; background-position:center; background-repeat:no-repeat; width: 86px;height: 59px; "></label>
                                </div>
                                <div class="d-flex">
                                    <input type="radio" name="payment" id="bri" value="bri">
                                    <label for="bri" style="background: url('<?= ASSETS ?>/assets/payment/bri.png'); background-size:cover; background-position:center; background-repeat:no-repeat; width: 86px;height: 59px; "></label>
                                </div>
                                <div class="d-flex">
                                    <input type="radio" name="payment" id="mandiri" value="mandiri">
                                    <label for="mandiri" style="background: url('<?= ASSETS ?>/assets/payment/mandiri.png'); background-size:cover; background-position:center; background-repeat:no-repeat;  width: 86px;height: 59px; "></label>
                                </div>
                                <div class="d-flex">
                                    <input type="radio" name="payment" id="qris" value="qris">
                                    <label for="qris" style="background: url('<?= ASSETS ?>/assets/payment/qris.png'); background-size:cover; background-position:center; background-repeat:no-repeat;  width: 86px;height: 59px; "></label>
                                </div>
                                <div class="d-flex">
                                    <input type="radio" name="payment" id="bsi" value="bsi">
                                    <label for="bsi" style="background: url('<?= ASSETS ?>/assets/payment/bsi.png'); background-size:cover; background-position:center; background-repeat:no-repeat;  width: 86px;height: 59px; "></label>
                                </div>

                                <div class="d-flex">
                                    <input type="radio" name="payment" id="cimb" value="cimb">
                                    <label for="cimb" style="background: url('<?= ASSETS ?>/assets/payment/cimb.png'); background-size:cover; background-position:center; background-repeat:no-repeat;  width: 86px;height: 59px; "></label>
                                </div>
                            <?php endif; ?>
                            <div class="d-flex">
                                <input type="radio" name="payment" id="cash" value="cash">
                                <label for="cash" style="background: url('<?= ASSETS ?>/assets/payment/cash.png'); background-size:cover; background-position:center; background-repeat:no-repeat;  width: 86px;height: 59px; "></label>
                            </div>
                            <div class="d-flex">
                                <input type="radio" name="payment" id="bon" value="bon">
                                <label for="bon" style="background: url('<?= ASSETS ?>/assets/payment/bon.png'); background-size:cover; background-position:center; background-repeat:no-repeat;  width: 86px;height: 59px; "></label>
                            </div>
                            <div class="d-flex">
                                <input type="radio" name="payment" id="edc" value="edc">
                                <label for="edc" style="background: url('<?= ASSETS ?>/assets/payment/edc.png'); background-size:cover; background-position:center; background-repeat:no-repeat;  width: 86px;height: 59px; "></label>
                            </div>
                            <div class="d-flex">
                                <input type="radio" name="payment" id="qris_manual" value="qris_manual">
                                <label for="qris_manual" style="background: url('<?= ASSETS ?>/assets/payment/qris.png'); background-size:cover; background-position:center; background-repeat:no-repeat;  width: 86px;height: 59px; "></label>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </section>

            <div class="d-flex justify-content-between mt-5">
                <button type="button" class="btn-action back" id="back">Back</button>
                <button type="button" class="btn-action" id="next" data-id="checkSchedule"> Next</button>
                <input type="hidden" id="id_dateReturn" name="iddateReturn">
                <input type="hidden" id="idKapal" name="idKapal">
                <input type="hidden" id="idKapalReturn" name="idKapalReturn">
                <input type="hidden" id="subTotal" name="idTotal">
            </div>

        </form>
    </div>
    <script src="https://kit.fontawesome.com/e216f95262.js" crossorigin="anonymous"></script>


    <!-- JQUERY STEP -->
    <script src="<?= ASSETS ?>/home/js/jquery.steps.js"></script>

    <!-- DATE-PICKER -->
    <script src="<?= ASSETS ?>/home/vendor/date-picker/js/datepicker.js"></script>
    <script src="<?= ASSETS ?>/home/vendor/date-picker/js/datepicker.en.js"></script>

    <script src="<?= ASSETS ?>/home/js/main.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const flashData = $('.flash-data').data('flashdata');
        if (flashData) {
            Swal.fire({
                title: 'Error!',
                text: flashData.pesan,
                icon: 'error',
                confirmButtonText: 'Close'
            })
        };
    </script>

    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();

            $('#gsearchsimple').keyup(function() {
                $('#agent_user').val(0);
                var query = $('#gsearchsimple').val();
                $('#detail').html('');
                $('.list-group').css('display', 'block');
                $.ajax({
                    url: "<?= BASEURL ?>home/search_agent",
                    method: "POST",
                    data: {
                        query: query
                    },
                    success: function(data) {
                        $('.list-group').html(data);
                    }
                })
                if (query.length == 0) {
                    $('.list-group').css('display', 'none');
                }
            });


            $(document).on('click', '.gsearch', function() {
                var email = $(this).text();
                var id = $(this).attr('data-id');

                $.ajax({
                    url: "<?= BASEURL ?>dashboard/getDataBy",
                    method: "POST",
                    data: {
                        table: "tb_grade",
                        model: 'M_User',
                        attr: "id",
                        id: id,
                    },
                    success: function(data) {
                        let res = JSON.parse(data);
                        $('#agent_user').val(res.diskon);
                        $('#agent_user_internasional').val(res.diskon_internasional);
                        // $('.list-group').html(data);
                    }
                })
                $('#gsearchsimple').val(email);
                $('.list-group').css('display', 'none');
            });


        });
        $(document).ready(function() {
            const minus = $('#quantity__minus1');
            const plus = $('#quantity__plus1');
            const input = $('#quantity__input1');
            minus.click(function(e) {
                e.preventDefault();
                var value = input.val();
                if (value > 0) {
                    value--;
                }
                input.val(value);
            });

            plus.click(function(e) {
                e.preventDefault();
                var value = input.val();
                value++;
                input.val(value);
            })

            const minus2 = $('#quantity__minus2');
            const plus2 = $('#quantity__plus2');
            const input2 = $('#quantity__input2');
            minus2.click(function(e) {
                e.preventDefault();
                var value2 = input2.val();
                if (value2 > 0) {
                    value2--;
                }
                input2.val(value2);
            });

            plus2.click(function(e) {
                e.preventDefault();
                var value2 = input2.val();
                value2++;
                input2.val(value2);
            })

            const minus3 = $('#quantity__minus3');
            const plus3 = $('#quantity__plus3');
            const input3 = $('#quantity__input3');
            minus3.click(function(e) {
                e.preventDefault();
                var value3 = input3.val();
                if (value3 > 0) {
                    value3--;
                }
                input3.val(value3);
            });

            plus3.click(function(e) {
                e.preventDefault();
                var value3 = input3.val();
                value3++;
                input3.val(value3);
            })

            $('#dataReturn').change(function() {
                $('#id_dateReturn').val($(this).val());

            })

            $('#oneWay').change(function() {
                if (this.checked) {
                    $('#status').val('true')
                    $('#return').removeClass('d-none');
                    $('#return').addClass('d-block');
                } else {
                    $('#id_dateReturn').val('')
                    $('#status').val('false')
                    $('#return').removeClass('d-block');
                    $('#return').addClass('d-none');
                }
            })

            $('#above').change(function() {
                if (this.checked) {
                    let nameFisrt = $('#gsearchsimple').val();
                    $('#passagerAdult1').val(nameFisrt);
                } else {
                    $('#passagerAdult1').val('');
                }
            })

            $('#from').change(function() { // Jika Select Box id provinsi dipilih
                var from = $(this).val(); // Ciptakan variabel provinsi
                $.ajax({
                    type: 'POST', // Metode pengiriman data menggunakan POST
                    url: '<?= BASEURL ?>home/getTo', // File yang akan memproses data
                    data: {
                        sFrom: from,
                    }, // Data yang akan dikirim ke file pemroses
                    success: function(response) { // Jika berhasil
                        $('#to').html(response); // Berikan hasil ke id kota
                        // })
                    }
                });
            });

            // $('.jadwal').change(() => {
            //     let id = $(this).val()
            //     $(this).data('id', id)
            // })


            $(function() {
                var dtToday = new Date();

                var month = dtToday.getMonth() + 1;
                var day = dtToday.getDate();
                var year = dtToday.getFullYear();
                if (month < 10)
                    month = '0' + month.toString();
                if (day < 10)
                    day = '0' + day.toString();

                var maxDate = year + '-' + month + '-' + day;

                // or instead:
                // var maxDate = dtToday.toISOString().substr(0, 10);

                $('#dateGo').attr('min', maxDate);
                $('#dataReturn').attr('min', maxDate);
            });

        });
    </script>
</body>

</html>