<?php

namespace App\Actions\Invoice;

use App\Models\Invoice;

class CreateNewInvoiceNumber
{
    public function generate(): string
    {
        if (session()->missing('school_id')) return 0;

        return self::make();
    }

    public function checkInvNumber($inv_number)
    {
        $cek = Invoice::firstWhere('invoice_number', $inv_number);

        if ($cek) return false;

        return true;
    }

    public function countInv()
    {
        return Invoice::count();
    }

    public function make()
    {
        $res = "INV/" . date('Y') . "/";
        $count = self::countInv();

        while(!self::checkInvNumber($res . $count)) {
            $count++;
        }

        return $res . $count;
    }
}
