<?php
error_reporting(0);
// Mengatur header agar Excel mengenali karakter UTF-8 (seperti simbol atau emoji jika ada)
echo "\xEF\xBB\xBF";
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
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
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            background-color: #4F46E5;
            color: #ffffff;
            font-weight: bold;
            padding: 12px 8px;
            border: 1px solid #000000;
            text-transform: uppercase;
            font-family: Arial;
            font-size: 10pt;
        }

        td {
            padding: 8px;
            border: 1px solid #000000;
            vertical-align: top;
            font-family: Arial;
            font-size: 10pt;
        }

        /* Format Tanggal agar bisa di-filter/sort di Excel */
        .date-format {
            mso-number-format: "Short Date";
            text-align: center;
        }

        /* Format teks agar tidak berubah jadi E+ (scientific) */
        .text-format {
            mso-number-format: "\@";
        }

        .bg-gray {
            background-color: #F3F4F6;
        }
    </style>
</head>

<body>

    <div class="title">LAPORAN DATA NON-LITIGASI</div>
    <div class="subtitle">Pemerintah Kota Surabaya - BPKAD</div>
    <div class="subtitle">Periode Laporan: <?= date('d F Y'); ?></div>
    <br>

    <table>
        <thead>
            <tr>
                <th width="40">NO</th>
                <th width="300">PERMOHONAN NON-LITIGASI</th>
                <th width="300">SIMBADA</th>
                <th width="150">INSTANSI (TEAM)</th>
                <th width="150">PIC / JAKSA</th>
                <th width="100">TGL MASUK</th>
                <th width="120">BIDANG</th>
                <th width="100">STATUS</th>
                <th width="400">PROGRES TERAKHIR (KESIMPULAN)</th>
                <th width="120">TGL UPDATE</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($data as $row):
                $team = !empty($row['team_nonlit']) ? strtoupper(str_replace('_', ' ', $row['team_nonlit'])) : 'INTERNAL';
            ?>
                <tr>
                    <td style="text-align: center;"><?= $no++; ?></td>
                    <td class="text-format"><?= strtoupper($row['permohonan_nonlit']); ?></td>
                    <td class="text-format"><?= strtoupper($row['register_baru']); ?></td>
                    <td style="text-align: center;"><?= $team; ?></td>
                    <td><?= strtoupper($row['pic']); ?></td>
                    <td class="date-format"><?= date('Y-m-d', strtotime($row['tgl_nonlit'])); ?></td>
                    <td style="text-align: center;"><?= strtoupper($row['jenis'] ?? $row['bidang']); ?></td>
                    <td style="text-align: center; font-weight: bold;"><?= strtoupper($row['status']); ?></td>
                    <td><?= !empty($row['kesimpulan']) ? $row['kesimpulan'] : '-'; ?></td>
                    <td class="date-format">
                        <?= !empty($row['tgl_progres_terakhir']) ? date('Y-m-d', strtotime($row['tgl_progres_terakhir'])) : '-'; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($data)): ?>
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px;">Data tidak ditemukan berdasarkan filter yang dipilih.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br>
    <div style="text-align: right; font-style: italic; font-size: 9pt;">
        Dokumen ini dibuat otomatis melalui Sistem BPKAD pada: <?= date('d/m/Y H:i:s'); ?>
    </div>

</body>

</html>