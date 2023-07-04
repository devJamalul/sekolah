<x-mail::message>
# Notifikasi Persetujuan PengeluaranBiaya

No Pengeluaran Biaya : **{{ $expense->expense_number }}** <br>

Tanggal Pengeluaran Biaya : **{{ $expense->expense_date }}** <br>

Deskripsi : **{{ $expense->note }}** <br>

Nominal : **IDR {{ number_format($expense->price, 0, ', ', '.') }}** <br>

Diajukan oleh : **{{ $expense->requested_by->name }}** <br>

Status : **Menunggu Persetujuan**

<x-mail::button :url="route('expense-approval.index')">
    Beri Persetujuan
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
