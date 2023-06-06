<x-mail::message>
# Notifikasi Persetujuan PengeluaranBiaya

No Pengeluaran Biaya : **{{ $expense->expense_number }}** <br>

Tanggal Pengeluaran Biaya : **{{ $expense->expense_date }}** <br>

Nominal : **IDR {{ number_format($expense->expense_details()->sum(DB::raw('price * quantity')), 0, ', ', '.') }}** <br>

Diajukan oleh : **{{ $expense->requested_by->name }}** <br>

Disetujui oleh : **{{ $expense->approved_by?->name ?? '-' }}** <br>

@php
    $status = "";
    
@endphp

Status : **{{ $expense->status == 'outgoing' ? "Sudah Direlisasi" : "Belum Direlasiasi" }}**

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
