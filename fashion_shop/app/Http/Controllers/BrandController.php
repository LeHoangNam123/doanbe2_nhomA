<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;        
use App\Models\Category;     
use Illuminate\Support\Facades\Validator;                                     ;
class BrandController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $brand = Brand::find($id);
        $categories = Category::all();
        return view('admin.brand.edit', compact('brand', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return redirect()->back()->with('delete', 'Không tìm thấy brand!');
        }
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'description' => 'required|string|max:255',
            'category_id' => 'required',
        ]);
        $previousImage = $brand->image;
        $brand->name = $validatedData['name'];
        $brand->description = $validatedData['description'];
        $brand->category_id = $validatedData['category_id'];
        // image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = $validatedData['name'] . '-' . time() . rand(1, 999) . '.' . $image->extension();
            $image->move(public_path('images/brand'), $filename);
            // del image
            if ($previousImage) {
                $oldImagePath = public_path('images/brand') . '/' . $previousImage;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            $brand->image = $filename;
        } else {$brand->image = $previousImage ? $previousImage : '';}
        $brand->save();
        return redirect()->route('brands.index')->with('update', 'Sửa thành công!');
    }

}
