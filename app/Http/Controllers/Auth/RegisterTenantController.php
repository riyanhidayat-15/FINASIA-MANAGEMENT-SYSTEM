<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RegisterTenantController extends Controller
{
    public function show() {
        return view('auth.register-tenant');
    }

    public function register(Request $request) {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:tenants',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $slug =Str::slug($request->company_name);

        if (Tenant::where('slug', $slug)->exists()) {
            return back()->withErrors(['company_name' => 'Nama perusahaan sudah terdaftar.'])->withInput();
        }

        // Create tenant
        $tenant = \App\Models\Tenant::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Create admin user for tenant
        $tenant->users()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'tenant_id' => $tenant->id,
        ]);

        Auth::login($tenant->users()->first());

        return redirect('/' . $tenant->slug . '/admin');
    }
}
