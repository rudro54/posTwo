<?php

namespace App\Http\Controllers;


use App\Models\Customer;
use App\Models\Product;
use Exception;
use App\Models\Invoice;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\InvoiceProduct;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    function InvoicePage(): View
    {
        return view('pages.dashboard.invoice-page');
    }

    function SalePage(): View
    {
        return view('pages.dashboard.sale-page');
    }


    function InvoiceCreate(Request $request)
    {

        DB::beginTransaction();

        try {
            $user_id = $request->header('id');
            $total = $request->input('total');
            $discount = $request->input('discount');
            $vat = $request->input('vat');
            $payable = $request->input('payable');
            $customer_id = $request->input('customer_id');


            $invoice = Invoice::create([
                'total' => $total,
                'discount' => $discount,
                'vat' => $vat,
                'payable' => $payable,
                'user_id' => $user_id,
                'customer_id' => $customer_id

            ]);

            $invoiceId = $invoice->id;

            $products = $request->input('products');

            foreach ($products as $EachProduct) {

                InvoiceProduct::create([
                    'invoice_id' => $invoiceId,
                    'user_id' => $user_id,
                    'product_id' => $EachProduct['product_id'],
                    'qty' => $EachProduct['qty'],
                    'sale_price' => $EachProduct['sale_price'],
                    'name' => $EachProduct['name'],


                ]);

            }

            DB::commit();
            return 1;


        } catch (Exception $e) {

            DB::rollBack();
            return $e->getMessage();

        }




    }

    function InvoiceSelect(Request $request)
    {
        $user_id = $request->header('id');
        return Invoice::where('user_id', $user_id)->with('customer')->get();

    }

    function InvoiceDetails(Request $request)
    {
        $user_id = $request->header('id');
        $customerDetails = Customer::where('user_id', $user_id)->where('id', $request->input('cus_id'))->first();
        $invoiceTotal = Invoice::where('user_id', $user_id)->where('id', $request->input('inv_id'))->first();
        $invoiceProduct = InvoiceProduct::where('user_id', $user_id)->where('invoice_id', $request->input('inv_id'))->get();




        return array(
            'customer' => $customerDetails,
            'invoice' => $invoiceTotal,
            'product' => $invoiceProduct,


        );
    }


    function InvoiceDelete(Request $request)
    {

        DB::beginTransaction();

        try {

            $user_id = $request->header('id');
            InvoiceProduct::where('user_id', $user_id)->where('invoice_id', $request->input('inv_id'))->delete();
            Invoice::where('id', $request->input('inv_id'))->delete();
            DB::commit();
            return 1;
        } catch (Exception $e) {
            DB::rollBack();
            return 0;

        }



    }





}
