<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\StudentTuition;
use App\Models\StudentTuitionPaymentHistory;

class BendaharaController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $data['monthly_earnings_label'] = 'EARNINGS (MONTHLY)';
        $data['monthly_earnings_icon'] = 'fas fa-dollar-sign';
        $data['monthly_earnings_class'] = 'success';
        $data['monthly_earnings'] = StudentTuitionPaymentHistory::whereMonth('created_at', now())->sum('price');

        $data['unpaid_earnings_label'] = 'UNPAID EARNINGS';
        $data['unpaid_earnings_icon'] = 'fas fa-calendar';
        $data['unpaid_earnings_class'] = 'danger';
        $data['unpaid_earnings'] = StudentTuition::with('student_tuition_payment_histories')->where('status', StudentTuition::STATUS_PENDING)->sum('grand_total');

        $data['partial_earnings_label'] = 'PARTIAL EARNINGS';
        $data['partial_earnings_icon'] = 'fas fa-calendar';
        $data['partial_earnings_class'] = 'dark';
        $data['partial_earnings'] = 0;
        $partials = StudentTuition::with('student_tuition_payment_histories')->where('status', StudentTuition::STATUS_PARTIAL)->get();
        foreach ($partials as $partial) {
            $grand_total = $partial->grand_total;
            $paid = $partial->student_tuition_payment_histories->sum('price');
            $data['partial_earnings'] += $grand_total - $paid;
        }

        $data['unpaid_tuitions_label'] = 'TOTAL UNPAID TUITIONS';
        $data['unpaid_tuitions_icon'] = 'fas fa-users';
        $data['unpaid_tuitions_class'] = 'primary';
        $data['unpaid_tuitions'] = StudentTuition::select('student_id')->whereIn('status', [StudentTuition::STATUS_PARTIAL, StudentTuition::STATUS_PENDING])->distinct('student_id')->count();

        return view('pages.home.bendahara', $data);
    }
}
