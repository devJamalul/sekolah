<?php

namespace App\Console\Commands;

use App\Jobs\PublishMonthlyTuitions;
use App\Models\AcademicYear;
use App\Models\School;
use App\Models\Tuition;
use Illuminate\Bus\Batch;
use Illuminate\Console\Command;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;

// use Illuminate\Database\Eloquent\Builder;

class PublishTuition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tuition:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish tuition(s) into student_tuitions';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->line('Publish tuitions');
        $this->line('----------------');
        $schools = School::with('tuition_types')->latest()->get();
        foreach ($schools as $school) {
            // academic year
            $academic_year = AcademicYear::withoutGlobalScopes()->where('school_id', $school->getKey())->active()->first();

            if (!$academic_year) {
                continue;
            }

            $jobs = [];
            $this->line('');
            $this->line('Administration for ' . $school->school_name);
            $this->line('----------------');

            // recurring tuition_types
            $tuition_types = $school->tuition_types()->withoutGlobalScopes()->where('recurring', true)->get();
            if (count($tuition_types) >= 1) {
                $this->line('Recurring Tuition Types');
            } else {
                $this->line('Recurring Tuition Types: None');
            }
            $this->line('-----------------------');
            foreach ($tuition_types as $tuition_type) {
                $this->line('Tuition Type: ' . $tuition_type->name);
                $tuitions = $tuition_type->tuitions()->withoutGlobalScopes()->where('academic_year_id', $academic_year->getKey())->get();
                foreach ($tuitions as $tuition) {
                    $this->line('Tuition: ' . $tuition->getKey());
                    $jobs[] = new PublishMonthlyTuitions(
                        tuition: $tuition,
                        date: now()
                    );
                    // PublishMonthlyTuitions::dispatch($tuition, now());
                }
            }

            $this->line('-----------------------');

            // additional tuitions where created_at <= 21
            $tanggal = now();
            $tuitions = Tuition::withoutGlobalScopes()
                ->with('tuition_type')
                ->where('school_id', $school->getKey())
                ->where('academic_year_id', $academic_year->getKey())
                ->whereNotNull('approval_by')
                ->whereHas('tuition_type', function ($query) {
                    $query->withoutGlobalScopes()->where('recurring', false);
                })
                ->whereDate('created_at', '>=', $tanggal->firstOfMonth())
                ->whereDate('updated_at', '<=', now())
                ->get();
            if (count($tuitions) >= 1) {
                $this->line('Additional Tuition');
            } else {
                $this->line('Additional Tuition: None');
            }
            foreach ($tuitions as $tuition) {
                $this->line('Tuition: ' . $tuition->getKey());
                $jobs[] = new PublishMonthlyTuitions(
                    tuition: $tuition,
                    date: now()
                );
            }

            // Job batching
            Bus::batch($jobs)->then(function (Batch $batch) use ($school) {
                // All jobs completed successfully...
                Log::withContext([
                    'context' => 'Publish tuitions',
                    'school' => $school->school_name,
                    'period' => now()->addMonth()->startOfMonth(),
                ])->info("Publish tuitions completed successfully...");
            })->catch(function (Batch $batch, Throwable $e) use ($school) {
                // First batch job failure detected...
                Log::withContext([
                    'context' => 'Publish tuitions',
                    'school' => $school->school_name,
                    'period' => now()->addMonth()->startOfMonth(),
                    'error' => $e->getMessage()
                ])->error("Publish tuitions failure detected...");
            })->finally(function (Batch $batch) use ($school) {
                // The batch has finished executing...
                Log::withContext([
                    'context' => 'Publish tuitions',
                    'school' => $school->school_name,
                    'period' => now()->addMonth()->startOfMonth(),
                ])->info("Publish tuitions finished executing...");
            })
                ->name('Publish tuitions for school : ' . $school->school_name)
                ->dispatch();
        }
    }
}
