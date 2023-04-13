<?php

namespace App\Jobs;

use App\Models\Classroom;
use App\Models\StudentTuition;
use App\Models\StudentTuitionDetail;
use App\Models\StudentTuitionMaster;
use App\Models\Tuition;
use App\Notifications\TuitionNotification;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class PublishMonthlyTuitions implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Tuition $tuition,
        public Carbon $date,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        session(['school_id' => $this->tuition->school_id]);
        // classrooms
        $classrooms = Classroom::withoutGlobalScopes()->where([
            'academic_year_id' => $this->tuition->academic_year_id,
            'grade_id' => $this->tuition->grade_id
        ])->get();
        $tanggal = $this->date->addMonth()->startOfMonth();
        foreach ($classrooms as $classroom) {
            // students
            foreach ($classroom->students as $student) {
                // cek student_tuition_master, ada atau tidak
                $stm = StudentTuitionMaster::firstWhere([
                    'student_id' => $student->id,
                    'tuition_id' => $this->tuition->getKey(),
                ]);
                $price = $stm->price ?? $this->tuition->price;

                // Student Tuitions
                $student_tuition = StudentTuition::updateOrCreate(
                    [
                        'school_id' => $classroom->school_id,
                        'student_id' => $student->getKey(),
                        'period' => $tanggal,
                        'note' => $this->tuition->tuition_type->name,
                    ],
                    [
                        'grand_total' => $price
                    ]
                );
                $student_tuition->bill_number   = self::generateBillNumber($student_tuition);
                $student_tuition->save();

                // Student Tuition Details
                StudentTuitionDetail::updateOrCreate(
                    [
                        'student_tuition_id' => $student_tuition->getKey(),
                        'tuition_id' => $this->tuition->getKey(),
                        'student_id' => $student->getKey()
                    ],
                    [
                        'price' => $price
                    ]
                );

                // Notification
                $delay = now()->addSeconds(30);
                $student->notify((new TuitionNotification($student_tuition))->delay($delay));
            }
        }
    }

    public function generateBillNumber(StudentTuition $tuition)
    {
        $tahun = date('Y');
        $sekolah = str($tuition->school_id)->padLeft('3', 0);
        $bulan = self::translateMonth($tuition->period->format('m'));
        $nomor = str($tuition->getKey())->padLeft('5', 0);

        return "$tahun/$sekolah/$bulan/$nomor";
    }

    public function translateMonth(int $month)
    {
        $res = [
            '',
            'I',
            'II',
            'III',
            'IV',
            'V',
            'VI',
            'VII',
            'VIII',
            'IX',
            'X',
            'XI',
            'XII'
        ];
        return $res[$month];
    }
}
