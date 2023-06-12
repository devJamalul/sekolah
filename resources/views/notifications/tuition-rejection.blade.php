<x-mail::message>
# Notifikasi Persetujuan Biaya

Nama Biaya : **{{ $tuition->tuition_type->name }}** <br>

Nominal : **IDR {{ number_format($tuition->price, 0, ',', '.') }}** <br>

Diajukan oleh : **{{ $tuition->requested_by->name }}** <br>

Ditolak oleh : **{{ $tuition->rejector?->name ?? '-' }}** <br>
Alasan penolakan : {{ $tuition->reject_reason }}

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
