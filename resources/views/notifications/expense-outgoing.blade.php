<x-mail::message>
# Notifikasi Realisasi Pengeluaran Biaya

No Pengeluaran Biaya : **{{ $expense->expense_number }}** <br>

Tanggal Pengeluaran Biaya : **{{ $expense->expense_date }}** <br>

Nominal : **IDR {{ number_format($expense->expense_details()->sum(DB::raw('price * quantity')), 0, ', ', '.') }}** <br>

Diajukan oleh : **{{ $expense->requested_by->name }}** <br>

Disetujui oleh : **{{ $expense->approved_by?->name ?? '-' }}** <br>

@php
    $status = "";
    
@endphp

Status : **{{ $expense->status == 'done' ? "Sudah Direalisasi" : "Belum Direalisasi" }}**

<a href="{{$expense->file_photo}}">Lihat Bukti</a>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
