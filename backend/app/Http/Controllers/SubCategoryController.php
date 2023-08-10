<?php

namespace App\Http\Controllers;

use App\Http\Requests\Backend\SubCategory\SubCategoryRequest;
use App\Http\Requests\Backend\SubCategory\SubCategoryUpdateRequest;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    function getSubCategory()
    {
        $subcategory = SubCategory::latest()->with('category')->paginate(3);
        return $subcategory;
    }
    function getCategoryForSubCategory()
    {
        $subcategory = Category::latest()->get();
        return $subcategory;
    }
    function store(SubCategoryRequest $request)
    {
        $slug = $this->generateSlug($request->name);

        $subCategory = new SubCategory();
        $subCategory->name = $request->name;
        $subCategory->slug = $slug;
        $subCategory->category_id = $request->category_id;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalName();
            $filename = time() . '-subCategory-' . $extension;
            $file->move('upload/images/', $filename);
            $subCategory->image = $filename;
        }
        $subCategory->save();

        return response()->json([
            'success' => 'SubCategory is created successfully!',
        ]);
    }
    function generateSlug($name)
    {
        $subCategory = SubCategory::where('name', $name)->get();
        if ($subCategory->count() > 0) {
            $count = $subCategory->count();
            $slug = Str::slug($name) . '-' . $count;
        } else {
            $slug = Str::slug($name);
        }
        return $slug;
    }

    function edit($id)
    {
        $subCategory = SubCategory::findOrFail(intval($id));
        $categories = Category::latest()->get();
        return [
            $subCategory,
            $categories
        ];
    }
    function update(SubCategoryUpdateRequest $request, $id)
    {
        $subCategory = SubCategory::findOrFail(intval($id));
        if ($subCategory->name != $request->name) {
            $slug = $this->generateSlug($request->name);
        } else {
            $slug = $subCategory->slug;
        }

        $subCategory->name = $request->name;
        $subCategory->slug = $slug;
        $subCategory->category_id = $request->category_id;
        if ($request->hasFile('image')) {
            $file_path = public_path() . '/upload/images/' . $subCategory->image;

            if (File::exists($file_path)) {
                File::delete($file_path);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalName();
            $filename = time() . '-subCategoryUpdate-' . $extension;
            $file->move('upload/images/', $filename);
            $subCategory->image = $filename;
        }
        $subCategory->update();

        return response()->json([
            'success' => 'SubCategory has been updated successfully!',
        ]);
    }

    function destroy($id)
    {
        $subCategory = SubCategory::findOrFail(intval($id));
        $file_path = public_path() . '/upload/images/' . $subCategory->image;

        if (File::exists($file_path)) {
            File::delete($file_path);
        }
        $subCategory->delete();

        return response()->json([
            'success' => 'SubCategory has been deleted successfully!'
        ]);
    }
}
