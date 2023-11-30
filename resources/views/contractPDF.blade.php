<!DOCTYPE html>
<html>

<head>
    <title>Contract Details</title>
</head>

<body>
    <h1>Contract Details</h1>
    <p>Date: {{ $date }}</p>

    <div>
        <h2>Contract ID: {{ $contract->id ?? 'No contract found' }}</h2>
        <p>Pemberi Tugas: {{ $contract->pemberi_tugas }}</p>
        <p>Jenis Industri: {{ $contract->industries->type }}</p>
        <p>Tujuan Kontrak: {{ $contract->contract_types->type }}</p>
        <p>Lokasi Proyek: {{ $contract->lokasi_proyek }}</p>

        @if ($contract->surveys->count() > 0)
            @foreach ($contract->surveys as $survey)
                <h3>Survey ID: {{ $survey->id }}</h3>
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
    </div>
</body>

</html>
