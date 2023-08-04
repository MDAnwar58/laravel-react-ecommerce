<?php

namespace App\Http\Controllers;

use App\Http\Requests\Backend\Brand\BrandRequest;
use App\Http\Requests\Backend\Brand\BrandUpdateRequest;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    function getBrand()
    {
        $brands = Brand::latest()->paginate(5);
        return $brands;
    }
    function store(BrandRequest $request)
    {
        $slug = $this->generateSlug($request->name);
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = $slug;
        $brand->title = $request->title;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalName();
            $filename = time() . '-brand-' . $extension;
            $file->move('upload/images/', $filename);
            $brand->image = $filename;
        }
        $brand->save();

        return response()->json([
            'success' => 'Brand created successfully!',
            'brand' => $brand
        ]);
    }
    function generateSlug($name)
    {
        $brand = Brand::where('name', $name)->get();
        if ($brand->count() > 0) {
            $count = $brand->count();
            $slug = Str::slug($name) . '-' . $count;
        } else {
            $slug = Str::slug($name);
        }
        return $slug;
    }

    function edit($id)
    {
        $brand = Brand::findOrFail(intval($id));
        return $brand;
    }

    function update(BrandUpdateRequest $request, $id)
    {
        $brand = Brand::findOrFail(intval($id));
        // generate slug
        if ($brand->name != $request->name) {
            $slug = $this->generateSlug($request->name);
        } else {
            $slug = $brand->slug;
        }

        $brand->name = $request->name;
        $brand->slug = $slug;
        $brand->title = $request->title;
        if ($request->hasFile('image')) {

            $file_path = public_path() . '/upload/images/' . $brand->image;

            if (File::exists($file_path)) {
                File::delete($file_path);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalName();
            $filename = time() . '-brandUpdate-' . $extension;
            $file->move('upload/images/', $filename);
            $brand->image = $filename;
        }
        $brand->update();

        return response()->json([
            'success' => 'Brand has been updated successfully!',
            'brand' => $brand
        ]);
    }

    function destroy($id)
    {
        $brand = Brand::findOrFail(intval($id));
        $file_path = public_path() . '/upload/images/' . $brand->image;

        if (File::exists($file_path)) {
            File::delete($file_path);
        }
        $brand->delete();

        return response()->json([
            'success' => 'Brand has been deleted successfully!'
        ]);
    }
}
