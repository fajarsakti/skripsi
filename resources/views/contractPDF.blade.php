<!DOCTYPE html>
<html>

<head>
    <title>Order Details</title>
</head>

<body>
    <table style="border: 1px solid transparent">
        <tr>
            <td>
                {{-- {{ dump(asset('build/assets/logo.png')) }} --}}
                {{-- <img src="{{ public_path('build/assets/logo.png') }}" alt="image" width="80" height="80"> --}}
            </td>
            <td>
                <div class="header">
                    <h1 style="text-align: center">Kantor Jasa Penilai Publik Rizky Djunaedy dan Rekan Cabang Bandung
                    </h1>
                    <p style="text-align: center">Jl. Cemara VIII No. 40 A , Pasteur - Sukajadi - Bandung 40161</p>
                    <p style="text-align: center">022 - 2041844/082118188099</p>
                </div>
            </td>
        </tr>
    </table>
    <hr style="height: 2px; background-color: black; border-width:0; color:black;">
    <br>
    <h2>Order ID: {{ $contract->id ?? 'No order found' }}</h2>
    <p>Date: {{ $date }}</p>

    <div>
        <p>Berikut adalah laporan dari Order anda:</p>
    </div>
    <div>
        <p>Pemberi Tugas: {{ $contract->pemberi_tugas }}</p>
        <p>Jenis Industri: {{ $contract->industries->type }}</p>
        <p>Tujuan Order: {{ $contract->contract_types->type }}</p>
        <p>Lokasi Proyek: {{ $contract->lokasi_proyek }}</p>

        @if ($contract->surveys->count() > 0)
            @foreach ($contract->surveys as $survey)
                <div>
                    <p>Berikut adalah detail dari survey terhadap aset anda:</p>
                </div>
                <h3>Survey ID: {{ $survey->id }}</h3>
                {{-- <p>Nomor Penugasan : {{ $survey->assignments->no_penugasan }}</p> --}}
                <p>Surveyor: {{ $survey->surveyors->name }}</p>
                <p>Pemilik Aset: {{ $survey->pemilik_aset }}</p>
                <p>Tanggal Survey: {{ $survey->tanggal_survey }}</p>
                <p>Jenis Aset: {{ $survey->assets->type }}</p>
                <p>Keterangan Aset: {{ $survey->keterangan_aset }}</p>
                {{-- <img src="{{ asset('storage/' . $survey->gambar_aset) }}" alt="Gambar Aset"> --}}
                <p>Harga Aset: Rp. {{ number_format($survey->harga_aset, 0, '.', '.') }}</p>
            @endforeach
        @else
            <p>No surveys found</p>
        @endif

        <div>
            <p>Demikian laporan hasil survey ini kami buat. Terimakasih sudah menggunakan jasa penilaian kami</p>
        </div>
    </div>
    <div style="font-size: small; text-align: left; position: fixed; bottom:0;">
        Kantor Jasa Penilai Publik Rizky Djunaedy dan Rekan Cabang Bandung<br>
        Jl. Cemara VIII No. 40 A, Pasteur - Sukajadi - Bandung 40161<br>
        Telp : 022 - 2041844 | HP/WhatsApp : 082118188099< </div>
</body>

</html>
