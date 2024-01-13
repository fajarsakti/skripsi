<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/dist/output.css" rel="stylesheet">
    <title>Surat Tugas</title>
</head>

<body>
    <table style="border: 1px solid transparent">
        <tr>
            <td>
                {{-- {{ dump(asset('build/assets/logo.png')) }} --}}
                {{-- <img src="{{ public_path('build/assets/logo.png') }}" alt="logo" width="80" height="80"> --}}
            </td>
            <td>
                <div class="header">
                    <h1 style="text-align: center">Kantor Jasa Penilai Publik Rizky Djunaedy dan Rekan Cabang Bandung
                    </h1>
                    <p style="text-align: center">Jl. Cemara VIII No. 40 A, Pasteur - Sukajadi - Bandung 40161</p>
                    <p style="text-align: center">022 - 2041844/082118188099</p>
                </div>
            </td>
        </tr>
    </table>
    <hr style="height: 2px; background-color: black; border-width:0; color:black;">
    <br>
    <div>
        <p>
            <b>Nomor Surat Tugas : KJPP{{ $assignment->no_penugasan }} </b><br>
            <b>Tanggal Surat : {{ $assignment->tanggal_penugasan }}</b>
        </p>
    </div>

    <div class="">
        <h3>Detail Surat Tugas</h3>
        <p>Surveyor yang ditugaskan : {{ $assignment->surveyors->name }}</p>
        <p>Debitur : {{ $assignment->contracts->pemberi_tugas }}</p>
    </div>

    <div>
        <h3>Deskripsi Tugas</h3>
        <p>Anda ditugaskan untuk melaksanakan penilaian terhadap aset {{ $assignment->contracts->assets->type }} yang
            berlokasi di {{ $assignment->contracts->lokasi_proyek }}. Tugas ini dimulai pada tanggal
            {{ $assignment->tanggal_penugasan }}.
        </p>
    </div>
    <p>Harap menggunakan sumber data yang valid dan terpercaya dalam melakukan penilaian. Pastikan hasil
        penilaian disusun dengan rapi dan jelas</p>
    <p>Demikian surat penugasan ini kami sampaikan. Mohon untuk segera mengkonfirmasi penerimaan surat ini dan
        memastikan pemahaman Anda mengenai tugas yang diberikan. Terima kasih atas perhatian dan kerjasamanya.</p>

    <div style="font-size: small; text-align: left; position: fixed; bottom:0;">
        Kantor Jasa Penilai Publik Rizky Djunaedy dan Rekan Cabang Bandung<br>
        Jl. Cemara VIII No. 40 A, Pasteur - Sukajadi - Bandung 40161<br>
        Telp : 022 - 2041844 | HP/WhatsApp : 082118188099< </div>
</body>

</html>
