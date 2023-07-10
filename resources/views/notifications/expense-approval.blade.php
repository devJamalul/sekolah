<x-mail::message>
# Notifikasi Persetujuan PengeluaranBiaya

No Pengeluaran Biaya : **{{ $expense->expense_number }}** <br>

Tanggal Pengeluaran Biaya : **{{ $expense->expense_date }}** <br>

Deskripsi : **{{ $expense->note }}** <br>

Nominal : **IDR {{ number_format($expense->price, 0, ', ', '.') }}** <br>

Diajukan oleh : **{{ $expense->requested_by->name }}** <br>

Disetujui oleh : **{{ $expense->approved_by?->name ?? '-' }}**

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

<x-mail::button :url="route('expense-approval.index')">
Masuk ke Persetujuan Uang Sekolah
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
