<x-mail::message>
# Introduction

Nama : **{{ $student_tuition->student->name }}**

Kelas : **{{ $student_tuition->student->classrooms()->latest()->first()->grade->grade_name }} {{ $student_tuition->student->classrooms()->latest()->first()->name }}**

Biaya : **{{ $student_tuition->note }}**

Tagihan : **IDR {{ number_format($student_tuition->grand_total, 0, ',', ',') }}**

Terbayar : **IDR  {{ number_format($student_tuition->student_tuition_payment_histories->sum('price'), 0, ',', ',') }}**

Periode : **{{ $student_tuition->period->format('F Y') }}**

Status : **BELUM LUNAS**

Sisa Pembayaran : **IDR  {{ number_format($student_tuition->grand_total - $student_tuition->student_tuition_payment_histories->sum('price'), 0, ',', ',') }}**

<x-mail::panel>
    Segera laporkan ke pihak terkait jika ada kekeliruan.
</x-mail::panel>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
