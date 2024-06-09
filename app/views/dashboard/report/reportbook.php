<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Booking Report</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?= BASEURL ?>/dashboard">Home</a>
            </li>
            <li>
                Report
            </li>
            <li class="active">
                <strong>Booking</strong>
            </li>

        </ol>
    </div>
</div>


<div class="wrapper wrapper-content animated fadeInRight">
    <table>
        <tr>
            <td width="20%">Date</td>
            <td width="3%">:</td>
            <td width="30%">
                <input type="date" style="border: 1px solid #dedede; border-radius: 2px; padding: 5px; min-width: 150px;" name="date_start" id="date_start" value="<?= $data['start'] ?>">
            </td>
            <td width="10%">Until</td>
            <td width="3%">:</td>
            <td>
                <input type="date" style="border: 1px solid #dedede; border-radius: 2px; padding: 5px; min-width: 150px;" name="date_end" id="date_end" value="<?= $data['end'] ?>">
            </td>
        </tr>
        <?php if ($_SESSION['session_login_grade'] == "administrator") { ?>
            <tr>
                <td width="20%">Book By</td>
                <td width="3%">:</td>
                <td width="30%">
                    <select name="user" id="user" style="border: 1px solid #dedede; border-radius: 2px; padding: 5px; min-width: 150px; margin-top: 5px; margin-bottom: 5px;">
                        <option value="all" <?= $data['user'] == 'all' ? "selected" : "" ?>>All</option>
                        <option value="administrator" data-id="" <?= $data['user'] == 'administrator' ? "selected" : "" ?>>Administrator</option>
                        <option value="staff" data-id="" <?= $data['user'] == 'staff' ? "selected" : "" ?>>Staff</option>
                        <?php foreach ($data['getAllGrade'] as $p) : ?>
                            <option value="<?= $p['id'] ?>" data-id="<?= $p['grade'] ?>" <?= $data['user'] ==  $p['id'] ? "selected" : "" ?>><?= $p['nameGrade'] ?></option>
                        <?php endforeach; ?>
                        <!-- <option value="verified_agent">Verified Agent</option> -->
                        <option value="syahbandar" data-id="" <?= $data['user'] == 'syahbandar' ? "selected" : "" ?>>Syahbandar</option>
                        <option value="accounting" data-id="" <?= $data['user'] == 'accounting' ? "selected" : "" ?>>Accounting</option>
                    </select>
                </td>
            </tr>
        <?php } ?>
        <tr>
            <td width="20%">Status</td>
            <td width="3%">:</td>
            <td width="30%">
                <select name="status" id="status" style="border: 1px solid #dedede; border-radius: 2px; padding: 5px; min-width: 150px; margin-top: 5px; margin-bottom: 5px;">
                    <option value="all">All</option>
                    <option value="waiting" <?= $data['status'] == 'waiting' ? "selected" : "" ?>>Waiting</option>
                    <option value="paid" <?= $data['status'] == 'paid' ? "selected" : "" ?>>Paid</option>
                    <option value="expired" <?= $data['status'] == 'expired' ? "selected" : "" ?>>Expired</option>
                </select>
            </td>
        </tr>
        <tr>
            <td width="20%">Payment Type</td>
            <td width="3%">:</td>
            <td width="40%" class="d-flex">
                <select name="payment_type" id="payment_type" style="border: 1px solid #dedede; border-radius: 2px; padding: 5px; min-width: 150px; ">
                    <?php foreach ($data['pay_list'] as $key => $value) { ?>
                        <option value="<?= $key ?>" <?= $data['pay'] == $key ? "selected" : "" ?>><?= $value ?></option>
                    <?php } ?>
                </select>
                <button class="btn btn-info btn-sm" style="margin: 5px;" id="search">Submit</button>
            </td>
        </tr>

    </table>
    <div class="row" style="margin-top: 15px;">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> Table Report Booking </h5>
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Orderer</th>
                                    <th>Email </th>
                                    <th>Hak Akses</th>
                                    <th>Invoice Num.</th>
                                    <th>Pay Type</th>
                                    <th>Total Pay</th>
                                    <th>Date Booked</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $i = 0;
                                foreach ($data['table'] as $data) :
                                    $status = Controller::model('M_Report')->setStatus($data['Pstatus']);
                                    $createBy = "";
                                    if ($data['createBy'] == "0") $createBy = "GUEST";
                                    else $createBy = $data['createBy'];
                                ?>
                                    <tr>
                                        <td><?= ++$i ?></td>
                                        <td><?= $data['nameOrderer'] ?> <strong> <?= ($data['relat_agent'] != 'administrator' && $data['relat_agent'] != 'syahbandar' && $data['relat_agent'] != 'accounting' && $data['relat_agent'] !== 'staff'  && $data['relat_agent'] != NULL) ? '(' . $data['relat_agent'] . ')' : '' ?></strong></td>
                                        <td><?= $createBy ?></td>
                                        <td><?= $data['grade'] ?></td>
                                        <td><?= $data['orderNum'] ?></td>
                                        <!-- <td><?= $data['adult'] . " adult / " . $data['child'] . " child" ?></td> -->
                                        <td><?= $data['type'] == "CC" ? "Credit Card" : $data['type'] ?></td>
                                        <td>Rp <?= number_format($data['nominal'], 0, ",", ".") ?></td>
                                        <td><?= date('d M Y', strtotime($data['dateCreate'])) ?></td>
                                        <td>
                                            <span class="badge badge-<?= $status['status'] ?>"><?= $status['text'] ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Orderer</th>
                                    <th>Email </th>
                                    <th>Hak Akses </th>
                                    <th>Invoice Num.</th>
                                    <th>Pay Type</th>
                                    <th>Total Pay</th>
                                    <th>Date Booked</th>
                                    <th>Status</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
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
                    title: 'Booking_list'
                },
                {
                    extend: 'pdf',
                    title: 'Booking_list',
                    customize: function(doc) {
                        var objLayout = {};
                        objLayout['hLineWidth'] = function(i) {
                            return .8;
                        };
                        objLayout['vLineWidth'] = function(i) {
                            return .5;
                        };
                        objLayout['hLineColor'] = function(i) {
                            return '#aaa';
                        };
                        objLayout['vLineColor'] = function(i) {
                            return '#aaa';
                        };
                        objLayout['paddingLeft'] = function(i) {
                            return 8;
                        };
                        objLayout['paddingRight'] = function(i) {
                            return 8;
                        };
                        doc.content[0].layout = objLayout;
                    }
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
    });

    $('#search').on('click', function() {
        var start = $('#date_start').val();
        var end = $('#date_end').val();
        var user = $('#user').val();
        var paytype = $('#payment_type').val();
        var status = $('#status').val();

        if (user == undefined) {
            user = "<?= $_SESSION['session_login_grade'] ?>"
        }

        window.location.href = "<?= BASEURL ?>dashboard/report/booking/" + start + "/" + end + "/" + user + "/" + paytype + '/' + status;
    });
</script>