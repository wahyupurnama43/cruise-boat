<?php
// fungsi header dengan mengirimkan raw data excel
header("Content-type: application/vnd-ms-excel");
// membuat nama file ekspor "data-anggota.xls"
header("Content-Disposition: attachment; filename=" . date('d/m/y') . ".xls");
?>
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" border="1">
        <thead>
            <tr>
                <th colspan="9" align="center">
                    <center>
                        <?php foreach ($data['agent'] as  $value) { ?>
                            <?= $data['userAgent'] == $value['userID'] ? $value['userID'] . '( ' . $value['companyName'] . " )" : "" ?>
                        <?php } ?>
                    </center>
                </th>
            </tr>
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
        </thead>
        <tbody>
            <?php

            $i = 0;
            $total = 0;
            foreach ($data['table'] as $data) :
                $status = Controller::model('M_Report')->setStatus($data['Pstatus']);
                $createBy = "";
                if ($data['createBy'] == "0") $createBy = "GUEST";
                else $createBy = $data['createBy'];
            ?>
                <tr>
                    <td><?= ++$i ?></td>
                    <td>
                        <?php $sc = Controller::model('M_Report')->getSchedule($data['scheduleID']); ?>
                        <?= date('d M Y', strtotime($data['depart'])) ?> (<?= date('H:i', strtotime($s['sTime'])) ?>)
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
                    <?php $total = $total + $data['nominal']; ?>
                </tr>
            <?php endforeach; ?>

            <tr>
                <td colspan="8" align="right">
                    Total
                </td>
                <td>
                    Rp <?= number_format($total, 0, ",", "."); ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>