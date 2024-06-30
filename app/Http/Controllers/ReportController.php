<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    function ReportPage(): View
    {
        return view('pages.dashboard.report-page');
    }

    function SalesReport(Request $request)
    {

        $user_id = $request->header('id');
        $FromDate = date('Y-m-d', strtotime($request->FromDate));
        $ToDate = date('Y-m-d', strtotime($request->ToDate));


        $total = Invoice::where('user_id', $user_id)->whereDate('created_at', '>=', $FromDate)->whereDate('created_at', '<=', $ToDate)->sum('total');
        $vat = Invoice::where('user_id', $user_id)->whereDate('created_at', '>=', $FromDate)->whereDate('created_at', '<=', $ToDate)->sum('vat');
        $payable = Invoice::where('user_id', $user_id)->whereDate('created_at', '>=', $FromDate)->whereDate('created_at', '<=', $ToDate)->sum('payable');
        $discount = Invoice::where('user_id', $user_id)->whereDate('created_at', '>=', $FromDate)->whereDate('created_at', '<=', $ToDate)->sum('discount');



        $list = Invoice::where('user_id', $user_id)
            ->whereDate('created_at', '>=', $FromDate)
            ->whereDate('created_at', '<=', $ToDate)
            ->with('customer')
            ->get();



        $data = [

            'payable' => $payable,
            'discount' => $discount,
            'total' => $total,
            'vat' => $vat,
            'list' => $list,
            'FromDate' => $FromDate,
            'ToDate' => $ToDate

        ];

        $pdf = Pdf::loadView('report.SalesReport', $data);

        return $pdf->download('invoice.pdf');



    }
}
