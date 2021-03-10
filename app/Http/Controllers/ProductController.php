<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function getAll(){
        $products = Products::all();
        return response()->json([
            'products' => $products
        ]);
    }
    public function store(Request $request)
    {
        $product = Products::create([
                'category_id' => $request->input('category_id'),
                'name' => $request->input('name'),
                'price' => $request->input('price'),
            ]);
//        $products = Products::find($id);

        return response()->json([
                'message' => 'The item was created',
                'product' => $product
            ], Response::HTTP_CREATED);
    }
    public function show($id)
    {
        $products = Products::find($id);
        return response()->json([
            'products' => $products
        ]);
    }

    public function update(Request $request,$id)
    {

            $ProductToUpdate = Products::find($id);
                if(!empty($request['category_id'])){
                    $ProductToUpdate->category_id = $request['category_id'];
                }
                if(!empty($request['name'])){
                    $ProductToUpdate->name = $request['name'];
                }
                if(!empty($request['price'])){
                    $ProductToUpdate->price = $request['price'];
                }
                $ProductToUpdate->save();
                return response()->json([
                    'message' => 'The item was successfully updated',
                    'products' =>   $ProductToUpdate
                ]);
    }
    public function destroy(Request $request, $id){
            Products::find($id)->delete();
            return response()->json('The item was deleted');
    }
}
