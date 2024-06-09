<style>
    td,
    th {
        font-size: 0.8em;
    }

    .table>:not(:last-child)>:last-child>* {
        border-bottom: transparent;
    }
</style>

<?php
$i = 1;
$totalInter = 0;
$totaldos = 0;
foreach ($data['passenger'] as $p) {
    if ($p['region'] == 'international') {
        $totalInter = $totalInter + 1;
    } else if ($p['region'] == 'domestic') {
        $totaldos = $totaldos + 1;
    }
}

?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title><?= strtoupper(COMPANY) ?> | Passenger</title>
</head>

<body>
    <center>
        <img src="<?= ASSETS ?>/assets/img/header.png" alt="" width="300px">
        <br>
        <br>
    </center>
    <div class="table">
        <table class="table table-striped table-bordered table-hover">
            <tbody>
                <tr>
                    <td width="20%">Depart Date</td>
                    <td width="3%"> : </td>
                    <td>
                        <?= $data['depart_date']; ?> at <?= $data['time'] ?>
                    </td>
                </tr>
                <tr>
                    <td width="20%">Boat Name</td>
                    <td width="3%"> : </td>
                    <td>
                        <?= $data['boat_name'] ?>
                    </td>
                </tr>
                <tr>
                    <td width="20%">Route</td>
                    <td width="3%"> : </td>
                    <td>
                        <?= $data['route'] ?>
                    </td>
                </tr>
                <tr>
                    <td width="20%">Captain</td>
                    <td width="3%"> : </td>
                    <td>
                        <?php $d = explode(',', $data['captain']);
                        $i = 1;
                        foreach ($d as $c) {

                            echo $i++ . '. ' . $c . '<br>';
                        } ?>
                    </td>
                </tr>
                <tr>
                    <td width="20%">Crew</td>
                    <td width="3%"> : </td>
                    <td>
                        <?php $c = explode(',', $data['crew']);
                        $i = 1;
                        foreach ($c as $s) {
                            echo $i++ . '. ' . $s . '<br>';
                        } ?>
                    </td>
                </tr>
                <tr>
                    <td width="30%">Total Passanger</td>
                    <td width="3%"> : </td>
                    <td>
                        <?= count($data['passenger']) ?> People
                    </td>
                </tr>
                <tr>
                    <td width="30%">Total International Passanger</td>
                    <td width="3%"> : </td>
                    <td>
                        <?= $totalInter ?> People
                    </td>
                </tr>
                <tr>
                    <td width="30%">Total Domestic Passanger</td>
                    <td width="3%"> : </td>
                    <td>
                        <?= $totaldos ?> People
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Passenger Name</th>
                    <th>Seat Number</th>
                    <th>Passenger Nationality</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($data['passenger'] as $p) : ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $p['fullName'] ?> </td>
                        <td><?= $p['seatCategory'] . ' ' . $p['seatNumber'] ?></td>
                        <td><?= $p['nationality'] ?></td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>

    <script>
        window.print();
        // window.close();
        window.onafterprint = window.close;
    </script>
</body>

</html>