<x-mail::message>
# Notifikasi Persetujuan Biaya

Nama Biaya : **{{ $tuition->tuition_type->name }}** <br>

Nominal : **IDR {{ number_format($tuition->price, 0, ',', '.') }}** <br>

Diajukan oleh : **{{ $tuition->requested_by->name }}** <br>

Disetujui oleh : **{{ $tuition->approved_by?->name ?? '-' }}** <br>
Status : **{{ $tuition->status == 'rejected' ? 'Ditolak' : ($tuition->status == 'approved' ? 'Disetujui' : 'Menunggu Persetujuan') }}**

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
