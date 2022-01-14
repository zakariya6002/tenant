<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Requests\StoreTenantRequest;
use App\Http\Requests\UpdatedTenantRequest;
use App\Notifications\TenantInviteNotification;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $tenant = User::Where('role_id',2)->get();
        Return view('tenants.index', compact('tenant'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tenants.backend.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTenantRequest $request)
    {
       $user =  User::create($request->validated()+ ['role_id'=> 2, 'password'=> 'secret']);
       $url = URL::signedRoute('invitation', $user);
       $user->notify(new TenantInviteNotification($url));

        return redirect()->route('tenants.index');
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
     * @param  User  $tenant
     * @return \Illuminate\Http\Response
     */
    public function edit(User $tenant)
    {
        return view('tenants.backend.edit',compact('tenant'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $tenant
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatedTenantRequest $request, User $tenant)
    {
        $tenant->update($request->validated());

        return redirect()->route('tenants.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $tenant)
    {
        $tenant->delete();
        return redirect()->route('tenants.index');
    }
    public function invitation(User $user){
        if (!request()->hasValidSignature() || $user->password != 'secret'){
            abort(401);
        }
        auth()->login($user);
        return redirect()->route('dashboard');
    }
}
