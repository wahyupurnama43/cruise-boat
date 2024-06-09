<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Boat</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?= BASEURL ?>/dashboard">Home</a>
            </li>
            <li class="active">
                Table Book
            </li>

        </ol>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> Table Boat </h5>
                    <div class="ibox-tools">
                        <button type="button" class="btn-primary" data-toggle="modal" data-target="#add-modal"> Add Boat</button>
                    </div>
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Orderer</th>
                                    <th>Schedule</th>
                                    <th>Return</th>
                                    <th>Transport</th>
                                    <th>Passenger</th>
                                    <th>Departure</th>
                                    <th>Return</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($data['getAllBoat'] as $data) : ?>
                                    <?php
                                    $imgBoat = Controller::model('M_Boat')->imgBoat($data['id']);
                                    ?>
                                    <tr>
                                        <td><?= ++$i ?></td>
                                        <td><?= $data['boatName'] ?></td>
                                        <td><?= $data['boatSeat'] ?></td>
                                        <td><?= $data['boatSeatVIP'] ?></td>
                                        <td><?= $data['boatSpec'] ?></td>
                                        <td> <span class="badge badge-<?= ($data['boatStatus'] == 'enable') ? 'success'  : 'danger' ?>"> <?= $data['boatStatus'] ?> </span></td>
                                        <td>
                                            <img src="<?= BASEURL . $imgBoat['imgDirectory'] . $imgBoat['imgName'] ?>" alt="" width="100px">
                                        </td>
                                        <td><?= ($data['lastUpdateBy'] == NULL) ? $data['createBy'] :  $data['lastUpdateBy'] ?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-xs" data-id="<?= $data['id'] ?>" data-toggle="modal" data-target="#edit-modal"><i class="fa fa-edit"></i></button>
                                            <a href="<?= BASEURL ?>dashboard/delete/<?= $data['id'] ?>/tb_boat/boat" onclick="return confirm('Yakin Mau Dihapus?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Boat Name</th>
                                    <th>Capacity</th>
                                    <th>Capacity VIP</th>
                                    <th>Boat Speed</th>
                                    <th>Boat Status</th>
                                    <th>Boat Img</th>
                                    <th>By</th>
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="<?= BASEURL ?>dashboard/create_boat" class="form-horizontal" id="add-form" class="sky-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="add-modal-label">Add New Boat</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="boatName" class="col-sm-2">Boat Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="boatName" name="boatName" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="boatSeat" class="col-sm-2">Boat Capacity</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="boatSeat" name="boatSeat" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="boatSeat" class="col-sm-2">Boat Capacity VIP</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="boatSeatVIP" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="boatSpec" class="col-sm-2">Boat Speed</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="boatSpec" name="boatSpec" required></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="boatName" class="col-sm-2">Img Boat</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" name="imgBoat" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="boatSpec" class="col-sm-2">Boat Status</label>
                        <div class="col-sm-10">
                            <select name="boatStatus" class="form-control" required>
                                <option value="" selected disabled> -- Pilih Status -- </option>
                                <option value="enable">Enable</option>
                                <option value="disable">Disable</option>
                            </select>
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
            <form method="POST" action="<?= BASEURL ?>dashboard/edit_boat" id="form_edit" class="form-horizontal" class="sky-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="add-modal-label">Edit Boat</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="boatName" class="col-sm-2">Boat Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="boatName" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="boatSeat" class="col-sm-2">Boat Capacity</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="boatSeat" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="boatSeat" class="col-sm-2">Boat Capacity VIP</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="boatSeatVIP" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="boatSpec" class="col-sm-2">Boat Speed</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="boatSpec" required></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="boatName" class="col-sm-2">Img Boat</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" name="imgBoat">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="boatSpec" class="col-sm-2">Boat Status</label>
                        <div class="col-sm-10">
                            <select name="boatStatus" class="form-control" required>
                                <option value="" selected disabled> -- Pilih Status -- </option>
                                <option value="enable">Enable</option>
                                <option value="disable">Disable</option>
                            </select>
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
                    table: 'tb_boat',
                    attr: 'id',
                    id: id,
                }, //set data
                success: function(response) { //once the request successfully process to the server side it will return result here
                    res = JSON.parse(response);
                    $("#form_edit [name=\"boatName\"]").val(res.boatName);
                    $("#form_edit [name=\"boatSeat\"]").val(res.boatSeat);
                    $("#form_edit [name=\"boatSeatVIP\"]").val(res.boatSeatVIP);
                    $("#form_edit [name=\"boatSpec\"]").html(res.boatSpec);
                    $("#form_edit [name=\"boatStatus\"]").val(res.boatStatus);
                    $("#id").val(res.id);
                }
            });
        });
    });
</script>