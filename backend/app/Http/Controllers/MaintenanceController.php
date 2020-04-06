<?php

namespace App\Http\Controllers;

use App\Model\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\MaintenanceRequest;
use App\Http\Requests\MaintenanceUpdateRequest;
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
    public function store(MaintenanceRequest $request)
    {
        if(Auth::user()->role == 'Maintenance')
            throw new HttpResponseException(response()->json([
                'errors' => "Maitenance users can't create maintenance requests"
            ], 403));

        $data = $request->all();

        if(Auth::user()->role == 'Tenant') {
            $data['tenant_id'] = Auth::user()->id;
            $data['apartment_id'] = Auth::user()->apartment->id;
        }

        $maintenance = Maintenance::create($data);
        $maintenance = Maintenance::with('tenant')
                            ->with('apartment')
                            ->with('maintenanceUser')
                            ->find($maintenance->id);

        return response()->json($maintenance, 201);
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
    public function update(MaintenanceUpdateRequest $request, $id)
    {
        $data = $request->all();
        unset($data['problem']);
        unset($data['tenant_id']);
        unset($data['apartment_id']);
        if(Auth::user()->role == 'Maintenance')
            $data['maintenance_user_id'] = Auth::user()->id;

        if(Auth::user()->role == 'Tenant' && isset($data['maintenance_user_id']))
            throw new HttpResponseException(response()->json([
                'errors' => [
                    'maintenance_user_id' => [
                        "A tenant can't set a maintenance user"
                    ]
                ]
            ], 403));

        $maintenance = Maintenance::find($id);

        if(Auth::user()->role == 'Tenant' && $maintenance->tenant_id != Auth::user()->id)
            throw new HttpResponseException(response()->json([
                'errors' => "Maintenance Request doesn't belongs to this user."
            ], 403));

        $maintenance->update($data);

        return Maintenance::with('tenant')
                    ->with('apartment')
                    ->with('maintenanceUser')
                    ->find($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        throw new HttpResponseException(response()->json([
            'errors' => "Maintenance Request doesn't belongs to this user."
        ], 403));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function solved(Request $request, $id)
    {
        $maintenance = Maintenance::with('tenant')
                        ->with('apartment')
                        ->with('maintenanceUser')
                        ->find($id);

        if(Auth::user()->role == 'Tenant' && $maintenance->tenant_id != Auth::user()->id)
            throw new HttpResponseException(response()->json([
                'errors' => "Maintenance Request doesn't belongs to this user"
            ], 403));

        $maintenance->solved = true;
        $maintenance->save();

        return $maintenance;
    }
}
