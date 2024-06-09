<?php  ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <style>
        .tengah {
            display: flex;
            justify-content: center;
            align-items: center;
            align-content: center;
            height: 100vh;
            text-align: center;
        }

        * {
            color: #333;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="tengah">

            <div>
                <img src="<?= ASSETS ?>/home/images/success.png" alt="" width="30%">
                <h1 class="mt-3">Success !!</h1>
                <h4>Thank you for your payment</h4>
                <a href="<?= BASEURL ?>" class="btn btn-primary mt-3">Back To Home</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>

</html>