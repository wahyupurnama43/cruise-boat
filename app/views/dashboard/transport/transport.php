<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Transport</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?= BASEURL ?>/dashboard">Home</a>
            </li>
            <li class="active">
                Table Transport
            </li>

        </ol>
    </div>
</div>


<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> Table Transport </h5>
                    <?php if ($_SESSION['session_login_grade'] == "administrator") : ?>
                        <div class="ibox-tools">
                            <button type="button" class="btn-primary" data-toggle="modal" data-target="#add-modal"> Add Transport</button>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Transport</th>
                                    <th>Zone</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <?php if ($_SESSION['session_login_grade'] == "administrator") echo "<th>Action</th>"; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['getAllTR'] as $datas) :  ?>
                                    <tr>
                                        <td><?= ++$i; ?></td>
                                        <td><?= $datas['transpotName'] ?></td>
                                        <td><?= $datas['zone'] ?></td>
                                        <td><?= number_format($datas['price']) ?></td>
                                        <td><?= $datas['status'] ?></td>
                                        <?php if ($_SESSION['session_login_grade'] == "administrator") : ?>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-xs" data-id="<?= $datas['id'] ?>" data-toggle="modal" data-target="#edit-modal"><i class="fa fa-edit"></i></button>
                                                <a href="<?= BASEURL ?>dashboard/delete/<?= $datas['id'] ?>/<?= Encripsi::encode('encrypt', 'tb_transport') ?>/<?= Encripsi::encode('encrypt', 'transport') ?>" onclick="return confirm('Yakin Mau Dihapus?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Transport</th>
                                    <th>Zone</th>
                                    <th>Price</th>
                                    <th>Status</th>
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
            <form method="POST" action="<?= BASEURL ?>dashboard/create_transport" class="form-horizontal" id="add-form" class="sky-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="add-modal-label">Add New Transport</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="transpotName" class="col-sm-2">Transport</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="transpotName" name="transpotName" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="zone" class="col-sm-2">Zone</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="zone" name="zone" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="price" class="col-sm-2">Price</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="boatSpec" class="col-sm-2">Boat Status</label>
                        <div class="col-sm-10">
                            <select name="status" class="form-control" required>
                                <option value="" selected disabled> -- Pilih Status -- </option>
                                <option value="enable">Enable</option>
                                <option value="disable">Disable</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
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
            <form method="POST" action="<?= BASEURL ?>dashboard/edit_transport" id="form_edit" class="form-horizontal" class="sky-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="add-modal-label">Edit Transport</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="transpotName" class="col-sm-2">Transport</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="transpotName" name="transpotName" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="zone" class="col-sm-2">Zone</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="zone" name="zone" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="price" class="col-sm-2">Price</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="boatSpec" class="col-sm-2">Boat Status</label>
                        <div class="col-sm-10">
                            <select name="status" class="form-control" required>
                                <option value="" selected disabled> -- Pilih Status -- </option>
                                <option value="enable">Enable</option>
                                <option value="disable">Disable</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
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
                    table: 'tb_transport',
                    attr: 'id',
                    id: id,
                }, //set data
                success: function(response) { //once the request successfully process to the server side it will return result here
                    res = JSON.parse(response);
                    $("#form_edit [name=\"transpotName\"]").val(res.transpotName);
                    $("#form_edit [name=\"zone\"]").val(res.zone);
                    $("#form_edit [name=\"price\"]").val(res.price);
                    $("#form_edit [name=\"status\"]").val(res.status);
                    $("#id").val(res.id);
                }
            });
        });
    });
</script>