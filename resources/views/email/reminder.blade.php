<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Notifikasi Dokumen</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            padding: 20px;
        }

        .container {
            max-width: 650px;
            background-color: #fff;
            margin: 0 auto;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #0056b3;
            margin-bottom: 10px;
        }

        p {
            margin: 12px 0;
        }

        .document-info {
            background-color: #e8f0fe;
            padding: 15px;
            border-left: 4px solid #0056b3;
            border-radius: 4px;
        }

        .highlight {
            font-weight: bold;
            color: #d9534f;
        }

        .footer {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <p>Kepada Yth.</p>
        <p>
            Tim Administrasi Dokumen<br>
            RSUD Kesehatan Kerja Provinsi Jawa Barat
        </p>

        <p>Sehubungan dengan dokumen berikut:</p>

        <div class="document-info">
            <p><strong>Nama Dokumen: {{ $document->judul }}</strong></p>
            <p>Tanggal Penetapan: {{ \Carbon\Carbon::parse($document->tanggal_penetapan)->locale('id')->isoFormat('D MMMM Y') }}</p>
            <p>Tanggal Expired: {{ \Carbon\Carbon::parse($expiredAt)->locale('id')->isoFormat('D MMMM Y') }}</p>
        </div>

        @if (str_contains($monthsText, 'expired'))
            <p>Dokumen ini <span class="highlight">{{ $monthsText }}</span>. Mohon dicatat dan ditindaklanjuti sesuai ketentuan yang berlaku.</p>
        @else
            <p>Dokumen ini <span class="highlight">{{ $monthsText }}</span>. Mohon agar segera ditinjau dan ditindaklanjuti sesuai ketentuan yang berlaku.</p>
        @endif

        <div class="footer">
            <p>Terima kasih atas perhatian dan kerja samanya.</p>
            <p>Hormat kami,<br>
            Administrator JDIH RSKK</p>
        </div>
        </div>
    </body>

    </html>
