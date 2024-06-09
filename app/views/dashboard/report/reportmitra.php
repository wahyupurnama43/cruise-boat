<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Passeger Report</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?= BASEURL ?>/dashboard">Home</a>
            </li>
            <li>
                Report
            <li class="active">
                <strong>Passeger</strong>
            </li>
        </ol>
    </div>
</div>


<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> Table Report Passeger </h5>
                    <div class="ibox-tools">
                        <label for="date">Departure Date : </label>
                        <input type="date" style="border: 1px solid #dedede; border-radius: 2px; padding: 5px; margin-right: 10px" name="date" id="date" value="<?= $data['date'] ?>">
                        <!--  <label for="date_end">Schedule : </label>
                        <select style="border: 1px solid #dedede; border-radius: 2px; padding: 5px; margin-right: 10px; min-width: 130px;" name="schedule" id="schedule">
                        	<?php if (empty($data['schedule_list'])) : ?>
                        		<option disabled selected> No Schedule Found </option>
                        	<?php else : ?>
                        		<option value="all" <?= $data['schedule'] == "all" ? 'selected' : '' ?>  > All </option>
                    			<?php foreach ($data['schedule_list'] as $s) : ?>
                    				<option value="<?= $s['id'] ?>" <?= $s['id'] == $data['schedule'] ? 'selected' : '' ?> ><?= substr($s['sDay'], 0, 3) . ", " . date('H:i A', strtotime($s['sTime'])); ?></option>
                        	<?php endforeach;
                            endif; ?>
                        </select> -->
                        <button class="btn btn-info" id="search">Submit</button>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Boat Name</th>
                                    <th width="5%">Total Passanger</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($data['table'] as $d) :
                                ?>
                                    <tr>
                                        <td><?= $i++ ?></td>
                                        <td><?= ucwords($d['boatName']) ?></td>
                                        <td>
                                            <?= count(Controller::model('M_Report')->getReportMitra($data['date'], $d['id'])) ?>
                                        </td>
                                        <td><?= ucwords($d['sFrom']) ?></td>
                                        <td><?= ucwords($d['sTo']) ?></td>
                                        <td><?= date('H:i A', strtotime($d['sTime'])) ?></td>
                                        <td>
                                            <button id="showModalSchedule" data-id="<?= $d['id'] ?>" data-toggle="modal" data-target="#edit-modal" class="btn btn-info detail-schedule"><i class="fa fa-info"></i></button>
                                            <a href="<?= BASEURL ?>dashboard/print_passanger/<?= $data['date'] ?>/<?= $d['id'] ?>" class="btn btn-info detail-schedule" target="_blank"><i class="fa fa-print"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Boat Name</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Time</th>
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

<!-- modal edit -->
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
    <div class="modal-dialog" style="min-width: 700px;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
                <h3 class="modal-title" id="add-modal-label">Passenger List</h3>
                <p id="print_passenger">Print</p>
            </div>
            <div class="modal-body" id="modal-body">
                <table style="width: 100%">
                    <tr>
                        <td width="20%">Depart Date</td>
                        <td width="3%"> : </td>
                        <td width="77%" id="depart_date"></td>
                    </tr>
                    <tr>
                        <td width="20%">Boat Name</td>
                        <td width="3%"> : </td>
                        <td width="77%" id="boat_name"></td>
                    </tr>
                    <tr>
                        <td width="20%">Route</td>
                        <td width="3%"> : </td>
                        <td width="77%" id="route"></td>
                    </tr>
                    <tr>
                        <td width="20%">Captain</td>
                        <td width="3%"> : </td>
                        <td width="77%" id="captain"></td>
                    </tr>
                    <tr>
                        <td width="20%">Crew</td>
                        <td width="3%"> : </td>
                        <td width="77%" id="crew"></td>
                    </tr>

                    <tr>
                        <td style="vertical-align: top" width="20%">Passenger</td>
                        <td style="vertical-align: top" width="3%"> : </td>
                        <td width="77%">
                            <table class="" width="100%" id="passenger_list">


                            </table>
                        </td>
                    </tr>
                </table>
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

    $('.detail-schedule').on('click', function() {
        var date = $('#date').val();
        var id = $(this).data('id');
        // console.log(date, id);

        $.ajax({
            url: "<?= BASEURL ?>dashboard/getDetailMitra",
            type: 'POST',
            data: {
                id: id,
                date: date
            },
            success: function(response) {
                var data = JSON.parse(response);
                $('#depart_date').html(data.depart_date + " at " + data.time);
                $('#boat_name').html(data.boat_name);
                $('#route').html(data.route);
                $('#captain').html(data.captain);
                $('#crew').html(data.crew);

                var table = "";

                if (data.passenger == 0) {
                    $('#passenger_list').removeClass('table');
                    $('#passenger_list').html("<tr><td><em> No Passenger </em></td></tr>");
                } else {
                    var thead = '<tr><th>#</th><th>Passenger Name</th><th>Seat Number</th><th>Passenger Nationality</th></tr>';
                    var i = 0;

                    for (let pass of data.passenger) {
                        i++;


                        table = table + '<tr><td>' + i + '. </td><td>' + pass.fullName + '</td><td>' + pass.seatCategory + ' ' + pass.seatNumber + '</td><td>' + pass.nationality + '</td></tr> ';
                    }
                    $('#passenger_list').addClass('table table-bordered');
                    $('#passenger_list').html(thead + table);
                }
            }
        });
    });


    $('#search').on('click', function() {
        var date = $('#date').val();
        window.location.href = "<?= BASEURL ?>dashboard/report/mitra/" + date;
    });
</script>