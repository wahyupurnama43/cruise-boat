<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Report Agent</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?= BASEURL ?>/dashboard">Home</a>
            </li>
            <li>
                Report
            </li>
            <li class="active">
                <strong>Agent</strong>
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
        <?php if ($_SESSION['session_login_grade'] == "administrator" ||  $_SESSION['session_login_grade'] == "accounting") { ?>
            <tr>
                <td width="20%">Book By</td>
                <td width="3%">:</td>
                <td width="30%">
                    <select name="user" id="user" style="border: 1px solid #dedede; border-radius: 2px; padding: 5px; min-width: 150px; margin-top: 5px; margin-bottom: 5px;">
                        <option value="all">All</option>
                        <?php foreach ($data['agent'] as  $value) { ?>
                            <option value="<?= $value['userID'] ?>" <?= $data['userAgent'] == $value['userID'] ? "selected" : "" ?>> <?= $value['userID'] ?> (<?= $value['companyName'] ?>)</option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
        <?php } ?>
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
                    <div style="display:flex; justify-content:space-between">
                        <div>
                            <h5> Table Report Booking </h5>
                        </div>
                        <div>
                            <button class="btn btn-primary btn-xs" id="excel">Excel</button>
                        </div>
                    </div>
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Date Booked</th>
                                    <th>Invoice Num.</th>
                                    <th>Orderer</th>
                                    <th>Passanger</th>
                                    <th>Email </th>
                                    <th>Pay Type</th>
                                    <th>Trip</th>
                                    <th>Total Pay</th>
                                    <th>Booking By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $i = 0;
                                foreach ($data['table'] as $data) :
                                    $status = Controller::model('M_Report')->setStatus($data['Pstatus']);
                                    $createBy = "";
                                    if ($data['createBy'] == "0") $createBy = "GUEST";
                                    else $createBy = $data['emailBook'];
                                ?>
                                    <tr>
                                        <td><?= ++$i ?></td>
                                        <td>
                                            <?php $sc = Controller::model('M_Report')->getSchedule($data['scheduleID']); ?>
                                            <?= date('d M Y', strtotime($data['depart'])) ?> (<?= date('H:i', strtotime($sc['sTime'])) ?>)
                                            <br>
                                            <?php if ($data['datereturn'] !==  null) :  $scR = Controller::model('M_Report')->getSchedule($data['scheduleReturnID']); ?>
                                                <?= date('d M Y', strtotime($data['datereturn'])) ?> (<?= date('H:i', strtotime($scR['sTime'])) ?>)
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $data['orderNum'] ?></td>
                                        <td><?= $data['nameOrderer'] ?></td>
                                        <td>
                                            <ol>
                                                <?php
                                                $ps = Controller::model('M_Report')->getManifest($data['id']);
                                                foreach ($ps as $p) :
                                                ?>
                                                    <li><?= $p['fullName'] ?></li>
                                                <?php endforeach; ?>
                                            </ol>
                                        </td>
                                        <td><?= $createBy ?></td>
                                        <td><?= $data['type'] == "CC" ? "Credit Card" : $data['type'] ?></td>
                                        <td>
                                            <?php if ($data['datereturn'] !==  null) : ?>
                                                Depart ~ Return
                                            <?php else : ?>
                                                One Way
                                            <?php endif; ?>
                                        </td>
                                        <td>Rp <?= number_format($data['nominal'], 0, ",", ".") ?></td>
                                        <td><?= $data['createBy'] ?></td>
                                    </tr>
                                <?php endforeach; ?>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Date Booked</th>
                                    <th>Invoice Num.</th>
                                    <th>Orderer</th>
                                    <th>Passanger</th>
                                    <th>Email </th>
                                    <th>Pay Type</th>
                                    <th>Trip</th>
                                    <th>Total Pay</th>
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
                    title: '<?= date('d/m/YH:i') ?>'
                },
                {
                    extend: 'pdf',
                    title: '<?= date('d/m/YH:i') ?>'
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

        if (user == undefined) {
            user = "<?= $_SESSION['session_login_grade'] ?>"
        }

        window.location.href = "<?= BASEURL ?>dashboard/report/agent/" + start + "/" + end + "/" + user + "/" + paytype;
    });

    $('#excel').on('click', function() {
        var start = $('#date_start').val();
        var end = $('#date_end').val();
        var user = $('#user').val();
        var paytype = $('#payment_type').val();

        if (user == undefined) {
            user = "<?= $_SESSION['session_login_grade'] ?>"
        }

        window.location.href = "<?= BASEURL ?>dashboard/excel_agent/" + start + "/" + end + "/" + user + "/" + paytype;
    });
</script>