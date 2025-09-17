<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Tampilkan semua role
    public function index()
    {
        // sementara dummy
        return view('roles.index');
    }

    // Form buat create role
    public function create()
    {
        return view('roles.create');
    }

    // Simpan role baru
    public function store(Request $request)
    {
        // validasi & simpan dummy
        // Role::create($request->all());

        return redirect()->route('role.index')->with('success', 'Role berhasil ditambahkan!');
    }

    // Tampilkan detail role
    public function show($id)
    {
        return view('roles.show', compact('id'));
    }

    // Form edit role
    public function edit($id)
    {
        return view('roles.edit', compact('id'));
    }

    // Update role
    public function update(Request $request, $id)
    {
        // validasi & update dummy
        // Role::findOrFail($id)->update($request->all());

        return redirect()->route('role.index')->with('success', 'Role berhasil diperbarui!');
    }

    // Hapus role
    public function destroy($id)
    {
        // Role::destroy($id);

        return redirect()->route('role.index')->with('success', 'Role berhasil dihapus!');
    }
}
