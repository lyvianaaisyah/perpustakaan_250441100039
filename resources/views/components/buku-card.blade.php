<!-- resources/view/components/buku-card.blade.php -->
@props(['judul', 'penulis', 'tahun'])
<div class="kotak">
    <h3 style="color: #1e1605;">{{ $judul }}</h3>
    <p>Penulis: <b>{{ $penulis }}</b></p>
    <p>Tahun: {{ $tahun }}</p>
    <div>
        {{ $slot }}
    </div>
</div>