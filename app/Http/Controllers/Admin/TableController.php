<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::orderBy('number')->get();
        return view('admin.tables.index', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'number'   => 'required|string|unique:tables,number',
            'capacity' => 'required|integer|min:1|max:20',
        ]);

        Table::create($request->only('number', 'capacity'));

        return back()->with('success', 'Meja berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $table = Table::findOrFail($id);
        $request->validate([
            'number'   => 'required|string|unique:tables,number,' . $id,
            'capacity' => 'required|integer|min:1|max:20',
            'status'   => 'required|in:available,occupied,reserved',
        ]);

        $table->update($request->only('number', 'capacity', 'status'));

        return back()->with('success', 'Meja berhasil diperbarui');
    }

    public function destroy($id)
    {
        $table = Table::findOrFail($id);
        $table->delete();
        return back()->with('success', 'Meja berhasil dihapus');
    }
}