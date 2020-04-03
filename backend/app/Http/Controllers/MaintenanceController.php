<?php

namespace App\Http\Controllers;

use App\Model\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role == 'Tenant') 
            return Maintenance::where('tenant_id', Auth::user()->id)
                        ->with('tenant')
                        ->with('apartment')
                        ->with('maintenanceUser')
                        ->get();
        else
            return Maintenance::where('solved', false)
                        ->with('tenant')
                        ->with('apartment')
                        ->with('maintenanceUser')
                        ->get();
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $maintenance = Maintenance::with('tenant')
                            ->with('apartment')
                            ->with('maintenanceUser')
                            ->find($id);

        //If user is a tenant, and the maintenance request belongs to another user throw Unauthorized
        if(Auth::user()->role == 'Tenant' && $maintenance->tenant_id != Auth::user()->id)
            throw new HttpResponseException(response()->json([
                'errors' => "Maintenance Request doesn't belongs to this user"
            ], 403));

        return $maintenance;
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
