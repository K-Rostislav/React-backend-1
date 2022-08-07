<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function getProducts (Request $request) {
        if(empty($request->search) && !empty($request->orderBy)) {
            $products = Product::orderBy('price', $request->orderBy)->get();
        } elseif (!empty($request->search) && empty($request->orderBy)){
            $products = Product::where('name', "LIKE", "%$request->search%")->get();
        } elseif ($request->search && $request->orderBy) {
            $products = Product::where('name', "LIKE", "%$request->search%")->orderBy('price', $request->orderBy)->get();
        } elseif ($request->type || $request->composition || $request->manufacturer){
            $products;
            if($request->type){
                $type = Product::whereIn('type', $request->type)->get();
                $products = $type;
            }
            if($request->composition){
                $composition = Product::whereIn('composition', $request->composition)->get();
                $products = $composition;
            }
            if($request->manufacturer){
                $manufacturer = Product::whereIn('manufacturer', $request->manufacturer)->get();
                $products = $manufacturer;
            }
        } else {
            $products = Product::all();
        }
        return response()->json($products);
    }
    public function fullProduct ($id) {
        $product = Product::where('id', $id)->first();
        return response()->json($product);
    }
}
