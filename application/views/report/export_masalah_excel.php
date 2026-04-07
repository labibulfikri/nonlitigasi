<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Permasalahan_" . date('Ymd') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html>

<head>
    <style>
        .title {
            font-family: Arial;
            font-size: 16pt;
            font-weight: bold;
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            background-color: #E11D48;
            color: #ffffff;
            padding: 10px;
            border: 1px solid #000;
        }

        td {
            padding: 8px;
            border: 1px solid #000;
            vertical-align: top;
        }
    </style>
</head>

<body>
    <div class="title">LAPORAN DATA PERMASALAHAN</div>
    <br>
    <table>
        <thead>
            <tr>
                <th width="50">NO</th>
                <th width="300">NAMA PERMASALAHAN</th>
                <th width="300">ALAMAT</th>
                <th width="150">PIC</th>
                <th width="100">TGL MASALAH</th>
                <th width="100">STATUS</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($results as $row): ?>
                <tr>
                    <td align="center"><?= $no++; ?></td>
                    <td><?= strtoupper($row->nama_masalah); ?></td>
                    <td><?= $row->alamat_masalah; ?></td>
                    <td><?= $row->pic_masalah; ?></td>
                    <td align="center"><?= date('d/m/Y', strtotime($row->tgl_masalah)); ?></td>
                    <td align="center"><?= strtoupper($row->status_masalah); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>