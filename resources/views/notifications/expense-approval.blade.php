<x-mail::message>
# Notifikasi Persetujuan PengeluaranBiaya

No Pengeluaran Biaya : **{{ $expense->expense_number }}** <br>

Tanggal Pengeluaran Biaya : **{{ $expense->expense_date }}** <br>

Deskripsi : **{{ $expense->note }}** <br>

Nominal : **IDR {{ number_format($expense->price, 0, ', ', '.') }}** <br>

Diajukan oleh : **{{ $expense->requested_by->name }}** <br>

Disetujui oleh : **{{ $expense->approved_by?->name ?? '-' }}** <br>
@php
    $status = "";
    if ($expense->deleted_at) {
        $status = 'Ditolak';
    }
    elseif ($expense->approval_by) {
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
