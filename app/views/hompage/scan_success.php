<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>TEST</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="zignatic">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <script src="<?= ASSETS ?>/home/js/jquery-3.3.1.min.js"></script>
</head>

<body>
    <input type="hidden" name="status" id="status" value="<?= $data['msg'] ?>">

    <script>
        var status = $('#status').val();
        Swal.fire({
            title: "SCAN SUCCESS",
            text: "Your status is now "+status,
            type: "success",
        }).then(function (result) {
            if (result.value){
                window.location = "<?= BASEURL ?>";
            }
        })
    </script>
</body>

</html>