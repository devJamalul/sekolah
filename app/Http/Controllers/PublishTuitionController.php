<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Tuition;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublishTuitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Terbit Biaya';
        $tuitions = Tuition::all();
        return view('pages.publish-tuition.index', compact('title', 'tuitions'));
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
        DB::beginTransaction();

        try {

            $tuitions = $request->tuitions;
            $student = Student::all();

            foreach ($tuitions as $key => $valueTuition) {

                $tuition = Tuition::where('id', $valueTuition)->first();

                foreach($student as $key => $valueStudent){

                    $checkTransaction = Transaction::where([
                        'school_id' => session('school_id'),
                        'tuition_id' => $tuition->id,
                        'student_id' => $valueStudent->id,
                        'status' => Transaction::PAID
                    ])->first();

                    if($checkTransaction){
                        continue;
                    }

                    Transaction::updateOrCreate(
                        [
                            'school_id' => session('school_id'),
                            'tuition_id' => $tuition->id,
                            'student_id' => $valueStudent->id,
                        ],
                        [
                            'price' => $tuition->price,
                        ]
                    );
                }
            }

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('publish-tuition.index')->withToastError('Eror Terbitkan Biaya!');
        }

        return redirect()->route('publish-tuition.index')->withToastSuccess('Berhasil Terbitkan Biaya!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
