<?php

namespace App\Http\Controllers;

use App\Models\Products as ModelsProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Products extends Controller
{
    /**
     * Create new product
     **/
    public function create(Request $request)
    {
        $data['user'] = Auth::user();
        try {
            if ($request->isMethod('post')) {
                $request->validate([
                    'name' => 'required|string|max:255',
                    'type' => 'required|in:Item,Service',
                    'description' => 'required|string',
                    'price' => 'required|numeric|between:0,9999999.99',
                    'gst_percent' => 'required|numeric|between:0,100',
                ]);
    
                $product = new ModelsProducts();
                $product->name = $request->name;
                $product->type = $request->type;
                $product->description = $request->description;
                $product->price = $request->price;
                $product->gst_percent = $request->gst_percent;
                $product->created_by = $data['user']->id;
    
                if ($product->save()) {
                    return redirect()->route('app.product.list')->with('success', 'Product created successfully');
                }
                $data['error'] = "Failed to save product, please try again";
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['context' => $this->ReqContext($request)]);
            $data['error'] = "Failed to save product." . $th->getMessage();
        }
        return view('products.create', $data);
    }

    /**
     * List all products
     **/
    public function list()
    {
        $data['user'] = Auth::user();
        $data['products'] = ModelsProducts::join('users', 'products.created_by', '=', 'users.id')->select([
            'products.*',
            'users.name as user_name'
        ])->get();
        return view('products.list', $data);
    }
    
    /**
     * Edit Product
     * 
     **/
    public function edit(Request $request , string $uuid)
    {
        $data['user'] = Auth::user();
        $data['product'] = ModelsProducts::where('uuid' , '=' , $uuid)->first();
        try {
            if ($request->isMethod('post')) {
                $request->validate([
                    'name' => 'required|string|max:255',
                    'type' => 'required|in:Item,Service',
                    'description' => 'required|string',
                    'price' => 'required|numeric|between:0,9999999.99',
                    'gst_percent' => 'required|numeric|between:0,100',
                ]);

                $data['product']->name = $request->name;
                $data['product']->type = $request->type;
                $data['product']->description = $request->description;
                $data['product']->price = $request->price;
                $data['product']->gst_percent = $request->gst_percent;
    
                if ($data['product']->save()) {
                    return redirect()->route('app.product.list')->with('success', 'Product update successfully');
                }
                $data['error'] = "Failed to save product, please try again";
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), ['context' => $this->ReqContext($request)]);
            $data['error'] = "Failed to update product." . $th->getMessage();
        }
        return view('products.edit', $data);
    }


    /**
     * Delete Client
     * 
     * @param String $uuid unique uuid
     **/
    public function delete(Request $request ,string $uuid)
    {
        if ($uuid) {
            try {
                $product = ModelsProducts::where('uuid', '=', $uuid)->first();
                if ($product) {
                    $product->delete();
                    return redirect()->route('app.product.list')->with('success', 'Product deleted successfully');
                }
            } catch (\Throwable $th) {
                Log::error($th->getMessage(), ['context' => $this->ReqContext($request)]);
            }
            return redirect()->route('app.product.list')->with('error', 'Failed to delete product');
        }

        abort(404, 'Unknown Delete Request');
    }
}
