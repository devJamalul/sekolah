<x-mail::message>
# Notifikasi Persetujuan Biaya

Nama Biaya : **{{ $tuition->tuition_type->name }}** <br>

Nominal : **IDR {{ number_format($tuition->price, 0, ',', '.') }}** <br>

Diajukan oleh : **{{ $tuition->requested_by->name }}** <br>

Disetujui oleh : **{{ $tuition->approved_by?->name ?? '-' }}** <br>
@php
    $status = "";
    if ($tuition->deleted_at) {
        $status = 'Ditolak';
    }
    elseif ($tuition->approval_by) {
        $status = 'Disetujui';
    }
    else {
        $status = "Menunggu Persetujuan";
    }
@endphp
Status : **{{ $status }}**

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
