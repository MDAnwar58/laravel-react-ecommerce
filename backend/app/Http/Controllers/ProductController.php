<?php

namespace App\Http\Controllers;

use App\Http\Requests\Backend\Product\ProductStoreRequest;
use App\Http\Requests\Backend\Product\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    function getProduct()
    {
        $products = Product::latest()->get();
        return $products;
    }

    function store(ProductStoreRequest $request)
    {
        $slug = $this->generateSlug($request->name);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = $slug;
        $product->sub_category_id = $request->sub_category_id;
        $product->title = $request->title;
        $product->color = $request->color;
        $product->price = $request->price;
        $product->discount = $request->discount;
        $product->offer = $request->offer;
        $product->des = $request->des;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalName();
            $filename = time() . '-product-' . $extension;
            $file->move('upload/images/', $filename);
            $product->image = $filename;
        }
        $product->save();

        return response()->json([
            'success' => 'Product is created successfully!',
        ]);
    }

    function generateSlug($name)
    {
        $product = Product::where('name', $name)->get();
        if ($product->count() > 0) {
            $count = $product->count();
            $slug = Str::slug($name) . '-' . $count;
        } else {
            $slug = Str::slug($name);
        }
        return $slug;
    }

    function edit($id)
    {
        $product = Product::findOrFail(intval($id));
        return $product;
    }

    function update(ProductUpdateRequest $request, $id)
    {
        $product = Product::findOrFail(intval($id));
        if ($product->name != $request->name) {
            $slug = $this->generateSlug($request->name);
        } else {
            $slug = $product->slug;
        }
        $product->name = $request->name;
        $product->slug = $slug;
        $product->sub_category_id = $request->sub_category_id;
        $product->title = $request->title;
        $product->color = $request->color;
        $product->price = $request->price;
        $product->discount = $request->discount;
        $product->offer = $request->offer;
        $product->des = $request->des;
        if ($request->hasFile('image')) {
            $file_path = public_path() . '/upload/images/' . $product->image;

            if (File::exists($file_path)) {
                File::delete($file_path);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalName();
            $filename = time() . '-productUpdate-' . $extension;
            $file->move('upload/images/', $filename);
            $product->image = $filename;
        }
        $product->update();

        return response()->json([
            'success' => 'Product has been updated successfully!',
        ]);
    }

    function destroy($id)
    {
        $product = Product::findOrFail(intval($id));
        $file_path = public_path() . '/upload/images/' . $product->image;

        if (File::exists($file_path)) {
            File::delete($file_path);
        }
        $product->delete();

        return response()->json([
            'success' => 'Product has been deleted successfully!',
        ]);
    }
}
