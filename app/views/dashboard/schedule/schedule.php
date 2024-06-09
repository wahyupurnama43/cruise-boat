<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Schedule</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?= BASEURL ?>/dashboard">Home</a>
            </li>
            <li class="active">
                Table Schedule
            </li>

        </ol>
    </div>
</div>


<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> Table Schedule </h5>
                    <?php if ($_SESSION['session_login_grade'] == "administrator") : ?>
                        <div class="ibox-tools">
                            <button type="button" class="btn-primary" data-toggle="modal" data-target="#add-modal"> Add Schedule</button>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Boat</th>
                                    <th>Start ~ From</th>
                                    <th>Day</th>
                                    <th>Time</th>
                                    <th>Price Domestic</th>
                                    <th>Price Internaional</th>
                                    <th>Price Domestic VIP</th>
                                    <th>Price Internaional VIP</th>
                                    <?php if ($_SESSION['session_login_grade'] == "administrator") echo "<th>Action</th>"; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['getAllSC'] as $datas) :  ?>
                                    <tr>
                                        <td><?= ++$i; ?></td>
                                        <td><?= $datas['boatName'] ?></td>
                                        <td><?= $datas['sFrom'] ?> ~ <?= $datas['sTo'] ?></td>
                                        <td><?= $datas['sDay'] ?></td>
                                        <td><?= $datas['sTime'] ?></td>
                                        <td>
                                            <strong> Adult :</strong> Rp. <?= number_format($datas['priceDomestic']); ?> <br>
                                            <strong> Child :</strong> Rp. <?= number_format($datas['child_priceDomestic']); ?>
                                        </td>
                                        <td>
                                            <strong> Adult :</strong> Rp. <?= number_format($datas['priceInternational']); ?><br>
                                            <strong> Child : </strong> Rp. <?= number_format($datas['child_priceInternational']); ?>
                                        </td>
                                        <td>
                                            <strong> Adult :</strong> Rp. <?= number_format($datas['priceDomesticVIP']); ?><br>
                                            <strong> Child : </strong> Rp. <?= number_format($datas['child_priceDomesticVIP']); ?>
                                        </td>
                                        <td>
                                            <strong> Adult :</strong> Rp. <?= number_format($datas['priceInternationalVIP']); ?><br>
                                            <strong> Child : </strong> Rp. <?= number_format($datas['child_priceInternationalVIP']); ?>
                                        </td>
                                        <?php if ($_SESSION['session_login_grade'] == "administrator") : ?>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-xs" data-id="<?= $datas['id'] ?>" data-toggle="modal" data-target="#edit-modal"><i class="fa fa-edit"></i></button>
                                                <a href="<?= BASEURL ?>dashboard/delete/<?= $datas['id'] ?>/<?= Encripsi::encode('encrypt', 'tb_schedule') ?>/<?= Encripsi::encode('encrypt', 'schedule') ?>" onclick="return confirm('Yakin Mau Dihapus?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Boat</th>
                                    <th>Start ~ From</th>
                                    <th>Day</th>
                                    <th>Time</th>
                                    <th>Price Domestic</th>
                                    <th>Price Internaional</th>
                                    <th>Price Domestic VIP</th>
                                    <th>Price Internaional VIP</th>
                                    <?php if ($_SESSION['session_login_grade'] == "administrator") echo "<th>Action</th>"; ?>

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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?= BASEURL ?>dashboard/create_schedule" class="form-horizontal" id="add-form" onsubmit="cek()" class="sky-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="add-modal-label">Add New Schedule</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-2">Boat</label>
                        <div class="col-sm-10">
                            <select name="boatID" class="form-control" id="boat_select">
                                <option value="0" selected disabled>-- Pilih Kapal --</option>
                                <?php foreach ($data['getAllBoat'] as $boat) : ?>
                                    <option value="<?= $boat['id'] ?>"><?= $boat['boatName'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sFrom" class="col-sm-2">Start From</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="sFrom" name="sFrom" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sTo" class="col-sm-2">Destination</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="sTo" name="sTo" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-2">Day</label>
                        <div class="col-sm-10">
                            <select name="sDay" class="form-control">
                                <option value="">-- Pilih Hari --</option>
                                <option value="Sunday">Sunday</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sTime" class="col-sm-2">Time</label>
                        <div class="col-sm-10">
                            <input type="time" class="form-control" id="sTime" name="sTime" required>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group">
                        <label for="priceDomestic" class="col-sm-2">Price Domestic</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="priceDomestic" name="priceDomestic" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="priceDomesticVIP" class="col-sm-2">Price Domestic VIP</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="priceDomesticVIP" name="priceDomesticVIP" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="priceInternational" class="col-sm-2">Price International</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="priceInternational" name="priceInternational" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="priceInternationalVIP" class="col-sm-2">Price International VIP</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="priceInternationalVIP" name="priceInternationalVIP" required>
                        </div>
                    </div>

                    <hr>

                    <!-- Child -->
                    <div class="form-group">
                        <label for="priceDomestic" class="col-sm-2">Child Price Domestic</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="priceDomestic" name="childPriceDomestic" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="priceDomesticVIP" class="col-sm-2">Child Price Domestic VIP</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="priceDomesticVIP" name="childPriceDomesticVIP" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="priceInternational" class="col-sm-2">Child Price International</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="priceInternational" name="childPriceInternational" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="priceInternationalVIP" class="col-sm-2">Child Price International VIP</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="priceInternationalVIP" name="childPriceInternationalVIP" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
        </div>
        </form>
    </div>
</div>


<!-- modal edit -->
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?= BASEURL ?>dashboard/edit_schedule" id="form_edit" class="form-horizontal" class="sky-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="add-modal-label">Edit Schedule</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="id" id="id">
                        <label for="name" class="col-sm-2">Boat</label>
                        <div class="col-sm-10">
                            <select name="boatID" class="form-control">
                                <option value="" selected disabled>-- Pilih Kapal --</option>
                                <?php foreach ($data['getAllBoat'] as $boat) : ?>
                                    <option value="<?= $boat['id'] ?>"><?= $boat['boatName'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sFrom" class="col-sm-2">Start From</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="sFrom" name="sFrom" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sTo" class="col-sm-2">Destination</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="sTo" name="sTo" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-2">Day</label>
                        <div class="col-sm-10">
                            <select name="sDay" class="form-control">
                                <option value="">-- Pilih Hari --</option>
                                <option value="Sunday">Sunday</option>
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sTime" class="col-sm-2">Time</label>
                        <div class="col-sm-10">
                            <input type="time" class="form-control" id="sTime" name="sTime" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="priceDomestic" class="col-sm-2">Price Domestic</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="priceDomestic" name="priceDomestic" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="priceDomesticVIP" class="col-sm-2">Price Domestic VIP</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="priceDomesticVIP" name="priceDomesticVIP" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="priceInternational" class="col-sm-2">Price International</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="priceInternational" name="priceInternational" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="priceInternationalVIP" class="col-sm-2">Price International VIP</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="priceInternationalVIP" name="priceInternationalVIP" required>
                        </div>
                    </div>

                    <!-- Child -->
                    <div class="form-group">
                        <label for="priceDomestic" class="col-sm-2">Child Price Domestic</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="priceDomestic" name="childPriceDomestic" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="priceDomesticVIP" class="col-sm-2">Child Price Domestic VIP</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="priceDomesticVIP" name="childPriceDomesticVIP" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="priceInternational" class="col-sm-2">Child Price International</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="priceInternational" name="childPriceInternational" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="priceInternationalVIP" class="col-sm-2">Child Price International VIP</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="priceInternationalVIP" name="childPriceInternationalVIP" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
        </div>
        </form>
    </div>
</div>

<script>
    $('#add-form').submit(function(event) {
        var boat = $('#boat_select').val();
        if (boat == null || boat == "" || boat == "0") {
            event.preventDefault();
            alert("Mohon pilih kapal");
        } else {
            $('#add-form').submit();
        }
    })


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

        $(document).delegate("[data-target='#edit-modal']", "click", function() {
            let id = $(this).attr('data-id')

            $.ajax({
                type: "POST", //we are using GET method to get data from server side
                url: '<?= BASEURL ?>dashboard/getDataBy', // get the route value
                data: {
                    model: 'M_User',
                    table: 'tb_schedule',
                    attr: 'id',
                    id: id,
                }, //set data
                success: function(response) { //once the request successfully process to the server side it will return result here
                    res = JSON.parse(response);
                    console.log(res);
                    $("#form_edit [name=\"boatID\"]").val(res.boatID);
                    $("#form_edit [name=\"sFrom\"]").val(res.sFrom);
                    $("#form_edit [name=\"sTo\"]").val(res.sTo);
                    $("#form_edit [name=\"sTime\"]").val(res.sTime);
                    $("#form_edit [name=\"sDay\"]").val(res.sDay);
                    $("#form_edit [name=\"priceInternationalVIP\"]").val(res.priceInternationalVIP);
                    $("#form_edit [name=\"priceInternational\"]").val(res.priceInternational);
                    $("#form_edit [name=\"priceDomesticVIP\"]").val(res.priceDomesticVIP);
                    $("#form_edit [name=\"priceDomestic\"]").val(res.priceDomestic);
                    $("#form_edit [name=\"childPriceInternationalVIP\"]").val(res.child_priceInternationalVIP);
                    $("#form_edit [name=\"childPriceInternational\"]").val(res.child_priceInternational);
                    $("#form_edit [name=\"childPriceDomesticVIP\"]").val(res.child_priceDomesticVIP);
                    $("#form_edit [name=\"childPriceDomestic\"]").val(res.child_priceDomestic);
                    $("#id").val(res.id);
                }
            });
        });
    });
</script>