<?php

namespace App\Http\Controllers;

use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TenantRequest;
use App\Http\Requests\TenantEditRequest;

class TenantController extends Controller
{
    private $tenant;

    public function __construct(User $tenant)
    {
        $this->middleware('auth:api')->except(['store']);

        $this->tenant = $tenant->tenant();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->tenant->orderBy('name')->get();
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
    public function store(TenantRequest $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);

        $tenant = $this->tenant->create($data);
        $tenant = $this->tenant->find($tenant->id);

        return response()->json($tenant, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->tenant->findOrFail($id);
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
    public function update(TenantEditRequest $request, $id)
    {
        $data = $request->all();
        
        if(isset($data['password']))
            $data['password'] = bcrypt($data['password']);

        $tenant = $this->tenant->findOrFail($id);
        $tenant->update($data);

        return $this->tenant->find($tenant->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tenant = $this->tenant->findOrFail($id);
        $apartment = $tenant->apartment;
        $tenant->delete();

        if($apartment)
            $apartment->update(['occupied' => false, 'tenant_id' => null]);

        return [];
    }
}
