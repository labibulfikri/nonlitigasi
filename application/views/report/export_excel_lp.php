<?php
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Polisi_" . date('Ymd') . ".xls");
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

        .subtitle {
            font-family: Arial;
            font-size: 11pt;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            background-color: #4F46E5;
            color: #ffffff;
            font-weight: bold;
            padding: 10px;
            border: 1px solid #000000;
        }

        td {
            padding: 8px;
            border: 1px solid #000000;
            vertical-align: top;
        }
    </style>
</head>

<body>
    <div class="title">LAPORAN DATA POLISI (LP)</div>
    <div class="subtitle">Pemerintah Kota Surabaya - BPKAD</div>

    <table>
        <thead>
            <tr>
                <th width="50">NO</th>
                <th width="200">NOMOR POLISI</th>
                <th width="250">JUDUL LAPORAN</th>
                <th width="150">PELAPOR</th>
                <th width="150">KEPOLISIAN</th>
                <th width="100">TGL LAPORAN</th>
                <th width="100">PIC</th>
                <th width="100">STATUS</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($results as $row): ?>
                <tr>
                    <td align="center"><?= $no++; ?></td>
                    <td style="mso-number-format:'\@';"><?= $row->nomor_polisi; ?></td>
                    <td style="text-transform: uppercase;"><?= $row->judul_laporan_polisi; ?></td>
                    <td><?= $row->pelapor; ?></td>
                    <td><?= $row->team_polisi; ?></td>
                    <td align="center"><?= date('d/m/Y', strtotime($row->tgl_laporan_polisi)); ?></td>
                    <td><?= $row->pic_laporan_polisi; ?></td>
                    <td align="center"><?= strtoupper($row->status_laporan_polisi); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>