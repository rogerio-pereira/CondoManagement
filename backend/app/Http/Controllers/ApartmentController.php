<?php

namespace App\Http\Controllers;

use App\Model\User;
use App\Model\Apartment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApartmentRequest;
use App\Http\Requests\ApartmentEditRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Apartment::orderBy('name')->with('tenant')->get();
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
    public function store(ApartmentRequest $request)
    {
        $data = $request->all();

        if(isset($data['tenant_id']))
            $this->validateTenant($data['tenant_id']);

        $apartment = Apartment::create($data);
        $apartment = $apartment->find($apartment->id)->with('tenant')->get();

        return response()->json($apartment->first(), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $apartment = Apartment::findOrFail($id);
        return $apartment->with('tenant')->get()->first();
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
    public function update(ApartmentRequest $request, $id)
    {
        $data = $request->all();

        if($data['occupied'] == false)
            $data['tenant_id'] = null;

        if(isset($data['tenant_id']))
            $this->validateTenant($data['tenant_id']);

        $apartment = Apartment::findOrFail($id);
        $apartment->update($data);

        return Apartment::find($id)->with('tenant')->get()->first();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $apartment = Apartment::findOrFail($id);
        $apartment->delete();

        return [];
    }

    private function validateTenant($userId)
    {
        $user = User::find($userId);

        if(!$user->isTenant()) {
            $errors = ['tenant_id' => ['The selected user is not a tenant.']];
            throw new HttpResponseException(response()->json(['errors' => $errors], 422));
        }
    }
}
