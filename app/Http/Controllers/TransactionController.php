<?php

namespace App\Http\Controllers;

use App\Actions\Invoice\AddToInvoice;
use App\Models\PaymentType;
use App\Models\Student;
use App\Models\StudentTuition;
use App\Models\StudentTuitionPaymentHistories;
use App\Models\StudentTuitionPaymentHistory;
use App\Models\Transaction;
use App\Notifications\PaidTuitionNotification;
use App\Notifications\PartialTuitionNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Event\Code\Throwable;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = "Transaksi Pembayaran Sekolah";
        return view('pages.transaction.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required'
            ],
            [
                'name.required' => 'Nama Siswa / NIS harus diisi'
            ]
        );
        session(['transaction_keyword' => $request->name]);

        $data['title'] = "Transaksi Pembayaran Sekolah | Keyword : " . $request->name;
        return view('pages.transaction.list', $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $transaction)
    {
        $data['title'] = "Transaksi Pembayaran Sekolah : " . $transaction->name;
        $data['student'] = $transaction;
        $data['student_tuitions'] = $transaction->student_tuitions()->whereIn('status', [
            StudentTuition::STATUS_PENDING, StudentTuition::STATUS_PARTIAL
        ])->orderBy('period')->get();
        $data['payment_types'] = PaymentType::orderBy('name')->get();
        return view('pages.transaction.store', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $transaction, AddToInvoice $addToInvoice)
    {
        $request->validate(
            [
                'student_tuition_id' => 'required|exists:student_tuitions,id',
                'payment_type_id' => 'required|exists:payment_types,id',
                'nominal' => 'required|min:1',
            ],
            [
                'student_tuition_id.required' => 'Tagihan biaya harus dipilih',
                'payment_type_id.required' => 'Metode pembayaran harus dipilih',
                'nominal.required' => 'Nominal harus diisi',
                'nominal.numeric' => 'Nominal harus diisi dengan angka saja',
                'nominal.min' => 'Nominal tidak boleh diisi 0 (nol)'
            ]
        );

        try {
            DB::beginTransaction();

            // tidak bisa transaksi kalau belum punya kelas
            if (count($transaction->classrooms) == 0) {
                throw new \ErrorException('Siswa harus memiliki kelas terlebih dahulu');
            }

            $student_tuition = StudentTuition::firstWhere([
                'student_id' => $transaction->getKey(),
                'id' => $request->student_tuition_id,
            ]);

            // cek total pembayaran sebelumnya
            $total_price = StudentTuitionPaymentHistory::where([
                'student_tuition_id' => $student_tuition->getKey()
            ])->sum('price');

            $total_payment = $request->nominal + $total_price;
            if ($total_payment > $student_tuition->grand_total) {
                $lebih = $total_payment - $student_tuition->grand_total;
                throw new \ErrorException('Pembayaran kelebihan IDR ' . number_format($lebih, '0', ',', '.'));
            } else if ($total_payment == $student_tuition->grand_total) {
                $student_tuition->status = StudentTuition::STATUS_PAID;
            } else {
                $student_tuition->status = StudentTuition::STATUS_PARTIAL;
            }

            $student_tuition->payment_type_id = $request->payment_type_id;
            $student_tuition->save();

            // input histori
            StudentTuitionPaymentHistory::create([
                'student_tuition_id' => $student_tuition->getKey(),
                'price' => formatAngka($request->nominal),
                'payment_type_id' => $request->payment_type_id,
            ]);

            // input invoice
            $addToInvoice->handle($student_tuition);

            DB::commit();

            if ($total_payment >= $student_tuition->grand_total) {
                $delay = now()->addSeconds(30);
                $transaction->notify((new PaidTuitionNotification($student_tuition, $student_tuition->student_tuition_payment_histories->sum('price')))->delay($delay));
            } else {
                $delay = now()->addSeconds(30);
                $transaction->notify((new PartialTuitionNotification($student_tuition, $student_tuition->student_tuition_payment_histories->sum('price')))->delay($delay));
            }

            return redirect()->route('transactions.show', $transaction->getKey())->withToastSuccess('Berhasil menambahkan data transaksi!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withInput()->withToastError('Ops! ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
