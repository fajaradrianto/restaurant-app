<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('category')->latest()->get();
        $categories = MenuCategory::orderBy('sort_order')->get();
        return view('admin.menus.index', compact('menus', 'categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:50',
        ]);

        $lastOrder = MenuCategory::max('sort_order') ?? 0;
        MenuCategory::create(array_merge($request->only('name', 'icon'), [
            'sort_order' => $lastOrder + 1,
        ]));

        return back()->with('success', 'Kategori menu ditambahkan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'  => 'required|exists:menu_categories,id',
            'name'         => 'required|string|max:150',
            'description'  => 'nullable|string|max:500',
            'price'        => 'required|integer|min:0',
            'is_available' => 'nullable',
        ]);

        Menu::create([
            'category_id'  => $request->category_id,
            'name'         => $request->name,
            'description'  => $request->description,
            'price'        => $request->price,
            'is_available' => $request->has('is_available'),
        ]);

        return back()->with('success', 'Menu ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $request->validate([
            'category_id'  => 'required|exists:menu_categories,id',
            'name'         => 'required|string|max:150',
            'description'  => 'nullable|string|max:500',
            'price'        => 'required|integer|min:0',
            'is_available' => 'nullable',
        ]);

        $menu->update([
            'category_id'  => $request->category_id,
            'name'         => $request->name,
            'description'  => $request->description,
            'price'        => $request->price,
            'is_available' => $request->has('is_available'),
        ]);

        return back()->with('success', 'Menu diperbarui');
    }

    public function destroy($id)
    {
        Menu::findOrFail($id)->delete();
        return back()->with('success', 'Menu dihapus');
    }
}