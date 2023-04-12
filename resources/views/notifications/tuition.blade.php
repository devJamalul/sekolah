<x-mail::message>
# Introduction

Nama : **{{ $student_tuition->student->name }}**

Kelas : **{{ $student_tuition->student->classrooms()->latest()->first()->grade->grade_name }} {{ $student_tuition->student->classrooms()->latest()->first()->name }}**

Biaya : **{{ $student_tuition->note }}**

Tagihan : **IDR {{ number_format($student_tuition->grand_total, 0, ',', ',') }}**

Periode : **{{ $student_tuition->period->format('F Y') }}**

<x-mail::panel>
    Abaikan jika sudah membayar tagihan di atas.
</x-mail::panel>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
