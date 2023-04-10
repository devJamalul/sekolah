<?php

namespace App\Http\Controllers\Datatables;

use App\Models\PaymentType;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class PaymentTypeDatatables extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $paymentType = PaymentType::with('school');
        return DataTables::of($paymentType)
            ->addColumn('action', function ($row) {
                $data = [
                    'edit_url'     => route('payment-type.edit', ['payment_type' => $row->id]),
                    'delete_url'   => route('payment-type.destroy', ['payment_type' => $row->id]),
                    'redirect_url' => route('payment-type.index')
                ];
                return view('components.datatable-action', $data);
            })->toJson();
    }
}
