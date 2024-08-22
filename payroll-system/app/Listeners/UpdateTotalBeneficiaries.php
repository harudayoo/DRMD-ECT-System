<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\BeneficiaryUpdated;
use App\Models\Barangay;

class UpdateTotalBeneficiaries
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BeneficiaryUpdated $event)
    {
        $barangay = Barangay::find($event->beneficiary->barangayID);
        $barangay->totalBeneficiaries = $barangay->beneficiaries()->count();
        $barangay->save();
    }
}
