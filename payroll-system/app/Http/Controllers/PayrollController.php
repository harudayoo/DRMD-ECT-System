<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use Inertia\Inertia;

class PayrollController extends Controller
{
    public function index()
    {
        $payroll = payroll::all();
        return Inertia::render('User/PayrollForm', [
            'payrolls' => $payroll
        ]);
    }
}
