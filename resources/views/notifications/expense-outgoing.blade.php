<x-mail::message>
# Notifikasi Realisasi Pengeluaran Biaya

No Pengeluaran Biaya : **{{ $expense->expense_number }}** <br>

Tanggal Pengeluaran Biaya : **{{ $expense->expense_date }}** <br>

Deskripsi : **{{ $expense->note }}** <br>

Nominal : **IDR {{ number_format($expense->price, 0, ', ', '.') }}** <br>

Diajukan oleh : **{{ $expense->requested_by->name }}** <br>

Disetujui oleh : **{{ $expense->approved_by?->name ?? '-' }}** <br>

Status : **{{ $expense->status == 'done' ? "Sudah Direalisasi" : "Belum Direalisasi" }}**

<a href="{{$expense->file_photo}}">Lihat Bukti</a>

<x-mail::button :url="route('expense-outgoing.index')">
Masuk ke Realisasi Uang Sekolah
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
