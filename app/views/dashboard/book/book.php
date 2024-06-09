<style>
    .with-tooltip {
        position: relative;
    }

    .with-tooltip::after {
        content: attr(data-tooltip-content);
        padding: 8px;
        border-radius: 4px;
        position: absolute;
        color: white;
        bottom: 115%;
        right: 0%;
        left: -150%;
        width: max-content;
        background-color: gray;
        opacity: 1;
        font-size: 1.3rem;
        visibility: hidden;
        transform: translate(-50%, 18px) scale(0.8);
        transition: visibility, opacity, transform 200ms;
    }

    .with-tooltip:hover::after {
        visibility: visible;
        opacity: 1;
        transform: translate(-50%, 0);
        z-index: 999;
    }

    .iframe_book {
        width: 55% !important;
        height: 100vh !important;
    }

    .detail_book {
        width: 90% !important;
        height: 100vh !important;
    }

    .detail_book .modal-content {
        height: 92%;
    }

    .iframe_book .modal-content {
        height: 92%;
    }

    .scan {
        margin-top: 2rem;
    }

    .scan input {
        background-color: #FFFFFF;
        background-image: none;
        border: 1px solid #e5e6e7;
        border-radius: 1px;
        color: inherit;
        padding: 6px 12px;
        transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
        width: 80%;
        font-size: 14px;
    }

    .scan input:focus {
        border-color: #1ab394 !important;
        outline: #1ab394 !important;
    }

    .d-flex {
        display: flex !important;
        align-items: center !important;
        align-content: center;
    }

    a.btn-primary {
        padding: 5px 10px;
    }
</style>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-6">
        <h2>Booking</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?= BASEURL ?>/dashboard">Home</a>
            </li>
            <li class="active">
                <b>Table Book</b>
            </li>
        </ol>
    </div>

    <?php if ($_SESSION['session_login_grade'] == "administrator" || $_SESSION['session_login_grade'] == 'staff') : ?>
        <div class="col-lg-6 scan">
            <h2></h2>
            <ol class="breadcrumb">
                <li class="d-flex">
                    <label for="">Scan Barcode : &nbsp;&nbsp; </label>
                    <input type="text" id="input_scanner">
                </li>
            </ol>
        </div>
    <?php endif; ?>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> Table Booking </h5>
                    <?php

                    if ($_SESSION['session_login_grade'] == "verified_agent" || $_SESSION['session_login_grade_real'] == "agent") : ?>
                        <div class="ibox-tools">
                            <!--<button type="button" class="btn-primary" data-toggle="modal" data-target="#add-modal"> Add Booking</button>-->
                            <a href="<?= BASEURL ?>" class="btn-primary" target="_blank">Add Booking</a>
                        </div>
                    <?php elseif ($_SESSION['session_login_grade'] == 'staff' || $_SESSION['session_login_grade'] == "administrator") : ?>

                        <div class="ibox-tools">
                            <!--<button type="button" class="btn-primary" data-toggle="modal" data-target="#add-modal"> Add Booking</button>-->
                            <a href="<?= BASEURL ?>staff" class="btn-primary" target="_blank">Add Booking</a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Orderer</th>
                                    <th>Departure Schedule</th>
                                    <th>Invoice</th>
                                    <th>Passenger</th>
                                    <th>Pay Type</th>
                                    <th>Total Pay</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php foreach ($data['book'] as $data) :
                                    if ($data['status'] == '0') {
                                        $status = "danger";
                                        $text = "Waiting Payment";
                                    } elseif ($data['status'] == '1') {
                                        $status = "warning";
                                        $text = "Waiting Schedule";
                                    } elseif ($data['status'] == '2') {
                                        $status = "info";
                                        $text = "On Depart";
                                    } elseif ($data['status'] == '3') {
                                        $status = "success";
                                        $text = "Finished";
                                    } elseif ($data['status'] == '4') {
                                        $status = "secondary";
                                        $text = "Expired";
                                    } elseif ($data['status'] == '5') {
                                        $status = "info";
                                        $text = "On Return";
                                    }
                                ?>
                                    <tr>
                                        <td><?= ++$i ?></td>
                                        <td><?= $data['nameOrderer'] ?></td>
                                        <td><?= $data['sDay'] . ", " . date('d-m-Y', strtotime($data['depart'])) . ' ' . date('H:i A', strtotime($data['sTime'])) ?></td>
                                        <td><?= $data['orderNum'] ?></td>
                                        <td><?= $data['adult'] . " adult / " . $data['child'] . " child / " . $data['infant'] . " Infant" ?></td>
                                        <td><?= $data['type'] ?></td>
                                        <td>Rp <?= number_format($data['nominal'], 0, ",", ".") ?></td>
                                        <td>
                                            <span class="badge badge-<?= $status ?>"><?= $text ?></span>
                                        </td>
                                        <td>
                                            <?php if ($_SESSION['session_login_grade'] == "administrator") : ?>
                                                <a href="<?= BASEURL ?>dashboard/detail/<?= $data['orderNum'] ?>" class="btn btn-success btn-xs">&nbsp;<i class="fas fa-info"></i>&nbsp;</a>
                                                <?php if ($data['status'] == '1') : ?>
                                                    <a href="<?= BASEURL ?>dashboard/send_transaksi/<?= $data['orderNum'] ?>" onclick="return confirm('Yakin Mau Kirim Email dan WA Kembali ?')" class="btn btn-info btn-xs"><i class="fas fa-envelope-open-text"></i></a>

                                                    <a href="<?= BASEURL ?>dashboard/book_print/<?= $data['orderNum'] ?>" class="btn btn-warning btn-xs with-tooltip" data-tooltip-content="BOARDING DEPATURE">&nbsp;<i class="fas fa-print "></i>&nbsp;</a>
                                                <?php elseif ($data['status'] == '2') : ?>
                                                    <a href="<?= BASEURL ?>dashboard/book_print/<?= $data['orderNum'] ?>" class="btn btn-warning btn-xs with-tooltip" data-tooltip-content="BOARDING DEPATURE">&nbsp;<i class="fas fa-print "></i>&nbsp;</a>
                                                    <?php if ($data['scheduleReturnID'] !== '0') : ?>
                                                        <a href="<?= BASEURL ?>dashboard/book_print_return/<?= $data['orderNum'] ?>" class="btn btn-success btn-xs with-tooltip" data-tooltip-content="BOARDING RETURN">&nbsp;<i class="fas fa-print"></i>&nbsp;</a>
                                                    <?php endif ?>
                                                <?php endif; ?>
                                            <?php elseif ($_SESSION['session_login_grade'] == 'staff') : ?>
                                                <?php if ($data['status'] == '1') : ?>
                                                    <a href="<?= BASEURL ?>dashboard/send_transaksi/<?= $data['orderNum'] ?>" onclick="return confirm('Yakin Mau Kirim Email dan WA Kembali ?')" class="btn btn-info btn-xs"><i class="fas fa-envelope-open-text"></i></a>

                                                    <a href="<?= BASEURL ?>dashboard/book_print/<?= $data['orderNum'] ?>" class="btn btn-warning btn-xs with-tooltip" data-tooltip-content="BOARDING DEPATURE">&nbsp;<i class="fas fa-print "></i>&nbsp;</a>
                                                <?php elseif ($data['status'] == '2') : ?>
                                                    <a href="<?= BASEURL ?>dashboard/book_print/<?= $data['orderNum'] ?>" class="btn btn-warning btn-xs with-tooltip" data-tooltip-content="BOARDING DEPATURE">&nbsp;<i class="fas fa-print "></i>&nbsp;</a>
                                                    <?php if ($data['scheduleReturnID'] !== 0) : ?>
                                                        <a href="<?= BASEURL ?>dashboard/book_print_return/<?= $data['orderNum'] ?>" class="btn btn-success btn-xs with-tooltip" data-tooltip-content="BOARDING RETURN">&nbsp;<i class="fas fa-print"></i>&nbsp;</a>
                                                    <?php endif ?>
                                                <?php endif; ?>


                                            <?php endif; ?>

                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#eye-modal" data-id="<?= BASEURL ?>home/transaksi/<?= $data['orderNum'] ?>">&nbsp;<i class="fas fa-eye"></i>&nbsp;</button>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Orderer</th>
                                    <th>Departure Schedule</th>
                                    <th>Invoice</th>
                                    <th>Passenger</th>
                                    <th>Pay Type</th>
                                    <th>Total Pay</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal tambah -->
<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="add-modal-label">
    <div class="modal-dialog iframe_book" role="document">
        <div class="modal-content">
            <iframe src="<?= BASEURL ?>" frameborder="0" width="100%" height="100%"></iframe>
        </div>
    </div>
</div>

<!-- modal tambah -->
<div class="modal fade" id="eye-modal" tabindex="-1" role="dialog" aria-labelledby="eye-modal-label">
    <div class="modal-dialog detail_book" role="document">
        <div class="modal-content">
            <iframe src="" id="ifram_detail" frameborder="0" width="100%" height="100%"></iframe>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.table').DataTable({
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [{
                    extend: 'copy'
                },
                {
                    extend: 'csv'
                },
                {
                    extend: 'excel',
                    title: 'Passenger_List'
                },
                {
                    extend: 'pdf',
                    title: 'Passenger_List',
                },

                {
                    extend: 'print',
                    customize: function(win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ]

        });

        $(document).delegate("[data-target='#eye-modal']", "click", function() {
            let id = $(this).attr('data-id')
            $('#ifram_detail').attr('src', id);
        });

        $('#input_scanner').val("").focus();

        // $('#input_scanner').click(() => {
        //     $('#input_scanner').val(' ');
        // })

        $('#input_scanner').keyup(function(e) {
            var tex = $(this).val();
            if (tex !== "" && e.keyCode === 13) {
                $('#input_scanner').val(tex).focus();
                window.location.href = '<?= BASEURL ?>dashboard/scan/' + tex
            }
            e.preventDefault();
        });

    });
</script>