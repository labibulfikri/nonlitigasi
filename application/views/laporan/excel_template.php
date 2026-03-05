<?php
// Menghilangkan peringatan error jika ada data yang tidak lengkap
error_reporting(0);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        .title {
            font-family: Arial, sans-serif;
            font-size: 16pt;
            font-weight: bold;
            text-align: center;
        }
        .subtitle {
            font-family: Arial, sans-serif;
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
            text-transform: uppercase;
        }
        td {
            padding: 8px;
            border: 1px solid #000000;
            vertical-align: top;
        }
        /* Mencegah angka panjang berubah jadi format scientific/E+ */
        .text-format {
            mso-number-format: "\@";
        }
        .header-info {
            font-weight: bold;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body>

    <div class="title">LAPORAN DATA NON-LITIGASI</div>
    <div class="subtitle">Pemerintah Kota Surabaya - BPKAD</div>
    <br>

    <table>
       <thead>
    <tr>
        <th width="50">NO</th>
        <th width="250">PERMOHONAN NON-LITIGASI</th>
        <th width="150">PIC</th>
        <th width="100">TGL MASUK</th>
        <th width="100">BIDANG</th>
        <th width="100">STATUS</th>
        <th width="300">PROGRES TERAKHIR</th> <th width="120">TGL UPDATE</th> </tr>
</thead>
<tbody>
    <?php 
    $no = 1; 
    foreach($data as $row): 
    ?>
        <tr>
            <td style="text-align: center;"><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['permohonan_nonlit']); ?></td>
            <td><?= htmlspecialchars($row['pic']); ?></td>
            <td style="text-align: center;"><?= date('d/m/Y', strtotime($row['tgl_nonlit'])); ?></td>
            <td style="text-align: center;"><?= strtoupper($row['bidang']); ?></td>
            <td style="text-align: center;"><?= strtoupper($row['status']); ?></td>
            
            <td><?= htmlspecialchars($row['kesimpulan'] ?? '-'); ?></td>
            <td style="text-align: center;">
                <?= !empty($row['tgl_progres_terakhir']) ? date('d/m/Y', strtotime($row['tgl_progres_terakhir'])) : '-'; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
    </table>

    <br>
    <div style="text-align: right; font-style: italic;">
        Dicetak pada: <?= date('d/m/Y H:i:s'); ?>
    </div>

</body>
</html>