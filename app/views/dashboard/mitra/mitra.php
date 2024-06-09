<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-8">
        <h2>Agent</h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?= BASEURL ?>/dashboard">Home</a>
            </li>
            <li class="active">
                Table Agent
            </li>

        </ol>
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> Table Agent </h5>
                    <div class="ibox-tools">
                        <button type="button" class="btn-primary" data-toggle="modal" data-target="#add-modal"> Add Agent</button>
                    </div>
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Perusahaan</th>
                                    <th>Alamat Perusahaan</th>
                                    <th>Nomor / WA Perusahaan</th>
                                    <th>Nama Lengkap (PIC)</th>
                                    <th>Link Website (optional)</th>
                                    <th>Sosmed Facebook/IG (optional) </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['getAllMitra'] as $data) :  ?>
                                    <tr>
                                        <td><?= ++$i; ?></td>
                                        <td><?= $data['companyName'] ?></td>
                                        <td><?= $data['companyAddress'] ?> </td>
                                        <td><?= $data['companyPhone'] ?> </td>
                                        <td><?= $data['pic'] ?> </td>
                                        <td><?= $data['companyWeb'] ?> </td>
                                        <td><?= $data['companySosmed'] ?> </td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-xs" data-id="<?= $data['id'] ?>" data-toggle="modal" data-target="#edit-modal"><i class="fa fa-edit"></i></button>
                                            <a href="<?= BASEURL ?>dashboard/delete/<?= $data['id'] ?>/<?= Encripsi::encode('encrypt', 'tb_mitra') ?>/<?= Encripsi::encode('encrypt', 'agent') ?>" onclick="return confirm('Yakin Mau Dihapus?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Perusahaan</th>
                                    <th>Alamat Perusahaan</th>
                                    <th>Nomor / WA Perusahaan</th>
                                    <th>Nama Lengkap (PIC)</th>
                                    <th>Link Website</th>
                                    <th>Sosmed (Facebook/IG) </th>
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
            <form method="POST" action="<?= BASEURL ?>dashboard/create_mitra" class="form-horizontal" id="add-form" class="sky-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="add-modal-label">Add New Agent</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-2">Nama Perusahaan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name_p" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-2">Nomor / WA Perusahaan</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="name" name="wa_p" required placeholder="Tolong format phone 62812345678 !!">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nama_lengkap" class="col-sm-2">Nama Lengkap (PIC)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="desc" class="col-sm-2">Alamat Perusahaan</label>
                        <div class="col-sm-10">
                            <textarea name="alamat_p" id="desc" class="form-control" cols="3" rows="3" required></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alamat_website" class="col-sm-2">Alamat Website (optional)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat_website" name="alamat_website">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sosmed" class="col-sm-2">Sosmed facebook/ig (optional)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="sosmed" name="sosmed">
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
            <form method="POST" action="<?= BASEURL ?>dashboard/edit_mitra" id="form_edit" class="form-horizontal" class="sky-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="add-modal-label">Edit Agent</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2">Nama Perusahaan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name_p" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-2">Nomor / WA Perusahaan </label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="name" name="wa_p" required placeholder="(Tolong format phone 62812345678 !!)">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nama_lengkap" class="col-sm-2">Nama Lengkap (PIC)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="desc" class="col-sm-2">Alamat Perusahaan</label>
                        <div class="col-sm-10">
                            <textarea name="alamat_p" id="desc" class="form-control" cols="3" rows="3" required></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alamat_website" class="col-sm-2">Alamat Website (optional)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat_website" name="alamat_website">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sosmed" class="col-sm-2">Sosmed facebook/ig (optional)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="sosmed" name="sosmed">
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
                    table: 'tb_mitra',
                    attr: 'id',
                    id: id,
                }, //set data
                success: function(response) { //once the request successfully process to the server side it will return result here
                    res = JSON.parse(response);
                    $("#form_edit [name=\"name_p\"]").val(res.companyName);
                    $("#form_edit [name=\"wa_p\"]").val(res.companyPhone);
                    $("#form_edit [name=\"nama_lengkap\"]").val(res.pic);
                    $("#form_edit [name=\"alamat_website\"]").val(res.companyWeb);
                    $("#form_edit [name=\"sosmed\"]").val(res.companySosmed);
                    $("#form_edit [name=\"alamat_p\"]").html(res.companyAddress);
                    $("#id").val(res.id);
                }
            });
        });
    });
</script>