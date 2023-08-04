<?php

namespace App\Http\Controllers;

use App\Http\Requests\Backend\Category\CategoryRequest;
use App\Http\Requests\Backend\Category\CategoryUpdateRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    function getCategory()
    {
        $brands = Category::with('brand')->latest()->paginate(3);
        return $brands;
    }
    function store(CategoryRequest $request)
    {
        $slug = $this->generateSlug($request->name);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = $slug;
        $category->brand_id = $request->brand_id;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalName();
            $filename = time() . '-category-' . $extension;
            $file->move('upload/images/', $filename);
            $category->image = $filename;
        }
        $category->save();

        return response()->json([
            'success' => 'Category is created successfully!',
        ]);
    }
    function generateSlug($name)
    {
        $category = Category::where('name', $name)->get();
        if ($category->count() > 0) {
            $count = $category->count();
            $slug = Str::slug($name) . '-' . $count;
        } else {
            $slug = Str::slug($name);
        }
        return $slug;
    }

    function edit($id)
    {
        $category = Category::findOrFail(intval($id));
        $brands = Brand::latest()->get();
        return [
            $category,
            $brands
        ];
    }

    function update(CategoryUpdateRequest $request, $id)
    {
        $category = Category::findOrFail(intval($id));
        // generate slug
        if ($category->name != $request->name) {
            $slug = $this->generateSlug($request->name);
        } else {
            $slug = $category->slug;
        }
        $category->name = $request->name;
        $category->slug = $slug;
        $category->brand_id = $request->brand_id;
        if ($request->hasFile('image')) {

            $file_path = public_path() . '/upload/images/' . $category->image;

            if (File::exists($file_path)) {
                File::delete($file_path);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalName();
            $filename = time() . '-categoryUpdate-' . $extension;
            $file->move('upload/images/', $filename);
            $category->image = $filename;
        }
        $category->update();

        return response()->json([
            'success' => 'Category has been updated successfully!'
        ]);
    }

    function destroy($id)
    {
        $category = Category::findOrFail(intval($id));
        // $file_path = public_path() . '/upload/images/' . $category->image;

        // if (File::exists($file_path)) {
        //     File::delete($file_path);
        // }
        // $category->delete();

        return response()->json([
            'success' => 'Category has been deleted successfully!'
        ]);
    }
}
