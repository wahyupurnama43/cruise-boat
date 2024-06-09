<?php
$book     = $data['book'];
$schedule = $data['schedule'];
$payment  = $data['payment'];

if (!empty($data['return'])) {
    $return = $data['return'];
}

if (!empty($data['transport'])) {
    $transport = $data['transport'];
}

$boat     = $data['boat'];
$ret_boat = $data['return_boat'];

?>

<style>
    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }

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
        right: 50%;
        left: 50%;
        width: max-content;
        background-color: gray;
        opacity: 0;
        font-size: 1.3rem;
        visibility: hidden;
        transform: translate(-50%, 18px) scale(0.8);
        transition: visibility, opacity, transform 200ms;
    }

    .with-tooltip:hover::after {
        visibility: visible;
        opacity: 1;
        transform: translate(-50%, 0);
    }
</style>


<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Booking</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?= BASEURL ?>dashboard">Home</a>
            </li>
            <li>
                <a href="<?= BASEURL ?>dashboard/book">Table Book</a>
            </li>
            <li class="active">
                <b>Booking Detail</b>
            </li>
        </ol>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> Booking Detail </h5>
                    <div class="ibox-tools">
                        <a href="<?= BASEURL ?>dashboard/book_print/<?= $payment['orderNum'] ?>" class="btn btn-warning btn-xs with-tooltip" data-tooltip-content="PRINT BOARDING PASS DEPATURE">&nbsp;<i class="fas fa-print"></i>&nbsp;</a>
                        <?php if ($book['scheduleReturnID'] !== '0') : ?>
                            <a href="<?= BASEURL ?>dashboard/book_print_return/<?= $payment['orderNum'] ?>" class="btn btn-success btn-xs with-tooltip" data-tooltip-content="PRINT BOARDING PASS RETURN">&nbsp;<i class="fas fa-print"></i>&nbsp;</a>
                        <?php endif; ?>
                        <span class="badge <?= $data['status'] ?>"><?= $data['status_text'] ?></span>
                    </div>
                </div>

                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr>
                                <td><label>Orderer Name</label></td>
                                <td><?= ucwords($book['nameOrderer']) ?></td>
                            </tr>
                            <tr>
                                <td><label>Passenger</label></td>
                                <td><?= "<strong>" . $book['adult'] . "</strong> adult / <strong>" . $book['child'] . " </strong> child" ?></td>
                            </tr>
                            <tr>
                                <td><label>Transport</label></td>
                                <td>
                                    <?php
                                    if ($book['transportID'] == '0') echo "-";
                                    else echo $transport['transpotName'] . ", " . $transport['zone'];
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Departure</label></td>
                                <td><?= date('d-m-Y', strtotime($book['depart'])) ?> <?= date('H:i A', strtotime($schedule['sTime'])) ?></td>
                            </tr>
                            <tr>
                                <td><label>Return</label></td>
                                <td>
                                    <?php
                                    if ($book['scheduleReturnID'] == '0' || $book['scheduleReturnID'] == '') {
                                        echo "-";
                                    } else {
                                        echo date('d-m-Y', strtotime($book['datereturn'])) . ' ' . date('H:i A', strtotime($return['sTime']));
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Region</label></td>
                                <td><?= $book['region'] ?></td>
                            </tr>

                            <tr>
                                <td><label>Phone</label></td>
                                <td><?= $book['phone'] ?></td>
                            </tr>
                            <tr>
                                <td><label>Status</label></td>
                                <td>
                                    <form action="<?= BASEURL ?>dashboard/updateBook" method="POST" id="formStatus">
                                        <input type="hidden" name="id" value="<?= $book['id'] ?>">
                                        <input type="hidden" name="inv" value="<?= $payment['orderNum']  ?>">
                                        <select name="status" id="status" class="form-control">
                                            <option value="0" <?= ($book['status'] == '0') ? 'selected' : ' ' ?>>Waiting Payment</option>
                                            <option value="1" <?= ($book['status'] == '1') ? 'selected' : ' ' ?>>Waiting Schedule</option>
                                            <option value="2" <?= ($book['status'] == '2') ? 'selected' : ' ' ?>>On Depart</option>
                                            <option value="5" <?= ($book['status'] == '5') ? 'selected' : ' ' ?>>On Return</option>
                                            <option value="3" <?= ($book['status'] == '3') ? 'selected' : ' ' ?>>Finished</option>
                                            <option value="4" <?= ($book['status'] == '4') ? 'selected' : ' ' ?>>Expired</option>
                                        </select>
                                    </form>
                                </td>


                            </tr>

                            <tr>
                                <td></td>
                                <td class="d-flex justify-content-between">
                                    <?php
                                    // button status untuk value di button previous or next, aslinya itu
                                    // 0 = waiting payment
                                    // 1 = Waiting Schedule
                                    // 2 = On Depart
                                    // 3 = Finished
                                    // 4 = Expired
                                    // 5 = On Return
                                    // karena on return lupa buat di rancangan database 
                                    if ($book['status'] == '0') {
                                        // 'Waiting Payment';
                                        $buttonStatus = 0;
                                    } else if ($book['status'] == '1') {
                                        // 'Waiting Schedule';
                                        $buttonStatus = 1;
                                    } else if ($book['status'] == '2') {
                                        // 'On Depart';
                                        $buttonStatus = 2;
                                    } else if ($book['status'] == '5') {
                                        // 'On Return';
                                        $buttonStatus = 3;
                                    } else if ($book['status'] == '3') {
                                        // 'Finished';
                                        $buttonStatus = 4;
                                    } else if ($book['status'] == '4') {
                                        // 'Expired';
                                        $buttonStatus = 5;
                                    }
                                    ?>
                                    <form action="<?= BASEURL ?>dashboard/updateBook" method="POST" id="formStatusBack">
                                        <input type="hidden" name="id" value="<?= $book['id'] ?>">
                                        <input type="hidden" name="status" id="backID">
                                        <input type="hidden" name="inv" value="<?= $payment['orderNum']  ?>">
                                        <button type="button" name="status" class="btn btn-danger btn-sm" id="backStatus" data-id='<?= $buttonStatus - 1 ?>'>
                                            <?php if (($buttonStatus - 1) == '0') {
                                                echo 'Waiting Payment';
                                            } else if (($buttonStatus - 1) == '1') {
                                                echo 'Waiting Schedule';
                                            } else if (($buttonStatus - 1) == '2') {
                                                echo 'On Depart';
                                            } else if (($buttonStatus - 1) == '3') {
                                                echo 'On Return';
                                            } else if (($buttonStatus - 1) == '4') {
                                                echo 'Finished';
                                            } else if (($buttonStatus - 1) == '5') {
                                                echo 'Expired';
                                            } else {
                                                echo '-';
                                            } ?></button>
                                    </form>
                                    <form action="<?= BASEURL ?>dashboard/updateBook" method="POST" id="formStatusNext">
                                        <input type="hidden" name="id" value="<?= $book['id'] ?>">
                                        <input type="hidden" name="status" id="nextID">
                                        <input type="hidden" name="inv" value="<?= $payment['orderNum']  ?>">
                                        <button type="button" class="btn btn-primary btn-sm" id="nextStatus" data-id='<?= $buttonStatus + 1 ?>'>
                                            <?php if (($buttonStatus + 1) == '0') {
                                                echo 'Waiting Payment';
                                            } else if (($buttonStatus + 1) == '1') {
                                                echo 'Waiting Schedule';
                                            } else if (($buttonStatus + 1) == '2') {
                                                echo 'On Depart';
                                            } else if (($buttonStatus + 1) == '3') {
                                                echo 'On Return';
                                            } else if (($buttonStatus + 1) == '4') {
                                                echo 'Finished';
                                            } else if (($buttonStatus + 1) == '5') {
                                                echo 'Expired';
                                            } else {
                                                echo '-';
                                            } ?></button>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="departure_detail">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Departure Detail </h5>
                        </div>
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <td><label>Boat Name</label></td>
                                        <td><?= $boat['boatName'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><label>Passenger</label></td>
                                        <td>
                                            <table style="width: 100%">
                                                <tr>
                                                    <th>Name</th>
                                                    <th style="text-align: right;">Seat</th>
                                                    <th style="text-align: right;">Nationality</th>
                                                    <th style="text-align: right;">Change Seat to :</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">&nbsp;</td>
                                                </tr>
                                                <?php foreach ($data['depart_seat'] as $seat) { ?>
                                                    <tr height="25px">
                                                        <td>
                                                            <form action="<?= BASEURL ?>dashboard/updateName" id="formUpdateName" method="POST">
                                                                <input type="hidden" name="fullNameLama" value="<?= $seat['fullName'] ?>">
                                                                <input type="hidden" name="inv" value="<?= $payment['orderNum']  ?>">
                                                                <input type="hidden" name="bookID" value="<?= $seat['bookID']  ?>">
                                                                <input type="text" value="<?= $seat['fullName'] ?>" name="fullname" id="fullName" class="form-control">
                                                            </form>
                                                        </td>

                                                        <td align="right" id="name_<?= $seat['id'] ?>"><?= $seat['seatNumber'] ?></td>
                                                        <td align="right">
                                                            <?= $seat['nationality'] ?>
                                                        </td>
                                                        <td align="right">
                                                            <select name="change_seat" id="change_seat" class="change_seat" style="min-width: 60px" data-id="<?= $seat['id'] ?>" data-realseat="<?= $seat['seatNumber'] ?>">
                                                                <?php foreach ($data['depart_seat_list'] as $s) {
                                                                    $selected = "";
                                                                    if ($s['id'] == $seat['id']) $selected = 'selected';
                                                                    echo '<option value="' . $s['seatNumber'] . '" ' . $selected . ' >' . $s['seatNumber'] . '</option>';
                                                                } ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Return Detail </h5>
                        </div>
                        <div class="ibox-content" style="text-align: center;">
                            <?php if ($book['scheduleReturnID'] != "0") : ?>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <td><label>Boat Name</label></td>
                                            <td><?= $ret_boat['boatName'] ?></td>
                                        </tr>
                                        <tr>
                                            <td><label>Passenger</label></td>
                                            <td>
                                                <table style="width: 100%">
                                                    <tr>
                                                        <th>Name</th>
                                                        <th style="text-align: right;">Seat</th>
                                                        <th style="text-align: right;">Nationality</th>
                                                        <th style="text-align: right;">Change Seat to :</th>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">&nbsp;</td>
                                                    </tr>
                                                    <?php foreach ($data['return_seat'] as $seat) { ?>
                                                        <tr height="25px">
                                                            <td>
                                                                <form action="<?= BASEURL ?>dashboard/updateName" id="formUpdateNameReturn" method="POST">
                                                                    <input type="hidden" name="fullNameLama" value="<?= $seat['fullName'] ?>">
                                                                    <input type="hidden" name="inv" value="<?= $payment['orderNum']  ?>">
                                                                    <input type="hidden" name="bookID" value="<?= $seat['bookID']  ?>">
                                                                    <input type="text" value="<?= $seat['fullName'] ?>" name="fullname" id="fullNameReturn" class="form-control">
                                                                </form>
                                                            </td>
                                                            <td id="name_<?= $seat['id'] ?>" align="right"><?= $seat['seatNumber'] ?></td>
                                                            <td align="right">
                                                                <?= $seat['nationality'] ?>
                                                            </td>
                                                            <td align="right">
                                                                <select name="change_seat" id="change_seat" class="change_seat" style="min-width: 60px" data-id="<?= $seat['id'] ?>" data-realseat="<?= $seat['seatNumber'] ?>">
                                                                    <?php foreach ($data['return_seat_list'] as $s) {
                                                                        $selected = "";
                                                                        if ($s['id'] == $seat['id']) $selected = 'selected';
                                                                        echo '<option value="' . $s['seatNumber'] . '" ' . $selected . ' >' . $s['seatNumber'] . '</option>';
                                                                    } ?>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            <?php else : ?>
                                <h4><em>No Return Schedule for this book</em></h4>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> Payment Detail </h5>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tr>
                                <td><label>Invoice Number</label></td>
                                <td><?= $payment['orderNum'] ?></td>
                            </tr>
                            <tr>
                                <td><label>Payment Type</label></td>
                                <td><?= $payment['type'] == 'CC' ? 'Credit Card' : strtoupper($payment['type']) ?></td>
                            </tr>
                            <tr>
                                <td><label>Total Pay</label></td>
                                <td>Rp <?= number_format($payment['nominal'], 0, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <td><label>Pay Status</label></td>
                                <td>
                                    <?php
                                    if ($payment['status'] == 'paid') echo '<span class="badge badge-primary">Paid</span>';
                                    elseif ($payment['status'] == 'waiting') echo '<span class="badge badge-warning">Waiting</span>';
                                    elseif ($payment['status'] == 'unpaid') echo '<span class="badge badge-info">Unpaid</span>';
                                    elseif ($payment['status'] == 'expired') echo '<span class="badge badge-danger">Expired</span>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Transaction Created at</label></td>
                                <td>
                                    <?= date('D, d-m-Y H:i:s A', strtotime($payment['dateCreate']) + 60 * 60); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Transaction Expired at</label></td>
                                <td>
                                    <?= date('D, d-m-Y H:i:s A', strtotime($payment['dateExpired'])); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Transaction Paid at</label></td>
                                <td>
                                    <?php
                                    if ($payment['datePaid'] == "" || $payment['datePaid'] == null) echo "-";
                                    else echo date('D, d-m-Y H:i:s A', strtotime($payment['datePaid']));
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('.dataTables-example').DataTable({
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
                    title: 'Passenger_List'
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


        $('#status').change(() => {
            $('#formStatus').submit();
        })

        $('#backStatus').click(() => {
            let id = $('#backStatus').attr('data-id');
            if (id == '3') {
                id = 5;
            } else if (id == '4') {
                id = 3;
            }

            if (id >= 0) {
                $('#backID').val(id);
                $('#formStatusBack').submit();
            }
        });

        $('#nextStatus').click(() => {
            let ids = $('#nextStatus').attr('data-id');
            if (ids == '4') {
                ids = 3;
            } else if (ids == '3') {
                ids = 5;
            } else if (ids == '5') {
                ids = 4;
            }

            if (ids <= 5) {
                $('#nextID').val(ids);
                $('#formStatusNext').submit();
            }
        });

        $('#fullName').change(() => {
            $('#formUpdateName').submit();
        });

        $('#fullNameReturn').change(() => {
            $('#formUpdateNameReturn').submit();
        });

        var prevIndex = "";
        $('.change_seat').on('click', function() {
            prevIndex = $(this).prop('selectedIndex');
        })

        $('.change_seat').on('change', function() {

            var book = '<?= $data['book']['id'] ?>';
            var seat = $(this).val();
            var realseat = $(this).data('realseat');
            var id = $(this).data('id');
            var name = $('#name_' + id).html();

            var inv = '<?= $payment['orderNum'] ?>';

            if (confirm("Exchange " + name + "'s seat from " + realseat + " to " + seat + "?")) {
                $.ajax({
                    url: "<?= BASEURL ?>dashboard/changeSeat",
                    type: 'POST',
                    data: {
                        seat: seat,
                        realseat: realseat,
                        id: id,
                        book: book
                    },
                    success: function(data) {
                        // console.log(data);
                        if (data == 1) {
                            window.location.reload();
                            window.location.href = '<?= BASEURL ?>dashboard/detail/' + inv + '#departure_detail';
                        } else {
                            alert('Failed to exchange seat');
                        }
                    }
                });
            } else {
                $(this).prop('selectedIndex', prevIndex);
            }
        });
    });
</script>