<!DOCTYPE html>
<html>

<head>
    <title>Surat Tugas</title>
</head>

<body>
    <div class="header">
        <h1 style="text-align: center">Kantor Jasa Penilai Publik Rizky Djunaedy dan Rekan Cabang Bandung</h1>
        <p style="text-align: center">Jl. Cemara VIII No. 40 A , Pasteur - Sukajadi - Bandung 40161</p>
        <p style="text-align: center">022 - 2041844/082118188099</p>
    </div>
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
        <p>Harap menggunakan sumber data yang valid dan terpercaya dalam melakukan penilaian. Pastikan laporan hasil
            penilaian disusun dengan rapi dan jelas</p>
        <p>Demikian surat penugasan ini kami sampaikan. Mohon untuk segera mengkonfirmasi penerimaan surat ini dan
            memastikan pemahaman Anda mengenai tugas yang diberikan. Terima kasih atas perhatian dan kerjasamanya.</p>
</body>

</html>
