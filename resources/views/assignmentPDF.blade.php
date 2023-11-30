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
</body>

</html>
