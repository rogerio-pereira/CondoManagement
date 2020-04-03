<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Model\User;
use App\Model\Payment;
use App\Model\Apartment;
use Illuminate\Http\Request;
use App\Model\Useful\DateConversion;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PaymentRequest;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role == 'Tenant') {
            return Payment::where('tenant_id', Auth::user()->id)->get();
        }

        return Payment::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentRequest $request)
    {
        $data = $request->all();

        User::validateTenant($data['tenant_id']);

        $apartment = Apartment::find($data['apartment_id']);
        $apartment->setTenant($data['tenant_id']);

        $data['value'] = $apartment->price;
        $data['due_at'] = Carbon::createFromFormat('Y-m-d', $data['start_date'])->startOfMonth()->toDateString();

        for($i=0; $i<$data['installments']; $i++) {
            Payment::create($data);
            $data['due_at'] = DateConversion::newDateByPeriod($data['due_at'], 'Monthly')->toDateString();
        }

        $payments = Payment::where('apartment_id', $data['apartment_id'])
                        ->where('tenant_id', $data['tenant_id'])
                        ->with('apartment')
                        ->with('tenant')
                        ->get();

        return response()->json($payments, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
