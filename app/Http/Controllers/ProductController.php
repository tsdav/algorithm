<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentValidation;
use App\Http\Requests\ImageValidation;
use App\Http\Requests\StoreValidateRequest;
use App\Http\Requests\UpdateValidateRequest;
use App\Models\File;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

class ProductController extends Controller
{
    public function getAll(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'perPage' => 'integer',
            'order' => 'string',
            'cat' ,
            'date_from',
            'date_to',
            'min_price',
            'max_price',
            'search' => '3 hat nish minimum'
        ]);
//        $perPage = count($request['perPage']);

        /**
         * Order by created at desc
         */
        if($request['order'] == 'up'){
            return response()->json([
                'products' => DB::table('products')->OrderBy('created_at','desc')->paginate(5),
            ]);
        }

        /**
         * Order by created at Asc
         */
        if($request['order'] == 'down'){
            return response()->json([
                'products' => DB::table('products')->OrderBy('created_at','asc')->paginate(5),
            ]);
        }
        else {
            return response()->json([
               'message' => 'some input data is invalid'
            ],500);
        }
    }

    public function store(StoreValidateRequest $request, ImageValidation $req_image, DocumentValidation $req_doc): \Illuminate\Http\JsonResponse
    {
            $product = new Products();

            $product->category_id = $request['category_id'];
            $product->name = $request['name'];
            $product->price = $request['price'];

            $product->save();

            $user_name = $request->user()->name;

            $array = [];

            if(count($req_image->file('image')) > count($req_doc->file('document'))){
                for($i = 0 ; $i < count($req_image->file('image')) ; $i++ ){

                        $file = new File();

                        $fileName = time() . $i . '.' . $req_image->file('image')[$i]->getClientOriginalExtension();
                        $filePath = $req_image->file('file')[$i]->storeAs('uploads/user'. '/' . $user_name .'/images' , $fileName, 'public');

                        $file->image_path = $filePath;

                        $array[$i] = Storage::url($file->image_path);
                        if($i < count($req_doc->file('document'))) {

                            $docName = time() . $i . '.' . $req_doc->file('document')[$i]->getClientOriginalExtension();
                            $docPath = $req_doc->file('document')[$i]->storeAs('uploads/user' . '/' . $user_name . '/documents' , $docName, 'public');

                            $file->document_path = $docPath;
                        }else $file->document_path = "No attachment";

                        $file->product_id = $product->id;
                        $file->save();
                }
                return response()->json([
                    'message' => 'The item was created',
                    'product' => $product,
                    'image_urls' => $array
                ], Response::HTTP_CREATED);
            }else if(count($req_doc->file('document')) > count($req_image->file('image'))){
                for($i = 0 ; $i < count($req_doc->file('document')) ; $i++ ){

                    $file = new File();

                    $docName = time() . $i . '.' .$req_doc->file('document')[$i]->getClientOriginalExtension();
                    $docPath = $req_doc->file('document')[$i]->storeAs('uploads/user' . '/' . $user_name . '/documents' , $docName, 'public');

                    $file->document_path = $docPath;

                    if($i < count($req_image->file('image'))) {

                        $fileName = time() . $i . '.' . $req_image->file('image')[$i]->getClientOriginalExtension();
                        $filePath = $req_image->file('file')[$i]->storeAs('uploads/user'. '/' . $user_name .'/images' , $fileName, 'public');

                        $file->image_path = $filePath;

                        $array[$i] = Storage::url($file->image_path);

                    }else $file->image_path = "No attachment";

                    $file->product_id = $product->id;
                    $file->save();
                }
                return response()->json([
                    'message' => 'The item was created',
                    'product' => $product,
                    'image_urls' => $array
                ], Response::HTTP_CREATED);

            }else{
                for($i = 0 ; $i < count($req_image->file('image')) ; $i++ ) {

                    $file = new File();

                    $fileName = time() . $i . '.' . $req_image->file('image')[$i]->getClientOriginalExtension();
                    $filePath = $req_image->file('image')[$i]->storeAs('uploads/user' . '/' . $user_name . '/images', $fileName, 'public');

                    $file->image_path = $filePath;

                    $array[$i] = Storage::url($file->image_path);

                    $docName = time() . $i . '.' . $req_doc->file('document')[$i]->getClientOriginalExtension();
                    $docPath = $req_doc->file('document')[$i]->storeAs('uploads/user' . '/' . $user_name . '/documents', $docName, 'public');

                    $file->document_path = $docPath;

                    Storage::url($file->image_path);

                    $file->product_id = $product->id;
                    $file->save();
                }
                return response()->json([
                    'message' => 'The item was created',
                    'product' => $product,
                    'image_urls' => $array
                ], Response::HTTP_CREATED);
            }
    }

    public function update(UpdateValidateRequest $request,$id): \Illuminate\Http\JsonResponse
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

    public function destroy(Request $request, $id): \Illuminate\Http\JsonResponse
    {

            Products::find($id)->delete();

            return response()->json('The item was deleted');
    }
    public function buy(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        $product_id = $request['product_id'];

        $user ->product()->attach($product_id);

        return response()->json([
            'message' => 'You successfully bought this item',
            'product' => Products::find($product_id)
        ]);
    }
    public function getProds(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();
        return response()->json([
            'product' => $user->product
        ]);
    }
    public  function downloadImage($id): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {

        $files = File::where('product_id',$id)->get();

        $medias = new ZipArchive();

        $medias->open($id . '.zip',ZipArchive::CREATE);


        foreach ($files as $file){

            if($file->image_path != "No attachment"){

                $image_content = file_get_contents('storage/'.$file->image_path);

                $medias->AddFromString($file->image_path,$image_content);

            }
            if($file->document_path != "No attachment"){

                $document_content = file_get_contents('storage/'.$file->document_path);

                $medias->AddFromString($file->document_path,$document_content);

            }
        }
        $medias->close();

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=images.zip');
        header('Content-Length: ' . filesize('images.zip'));

        readfile($id . '.zip');

        unlink($id . '.zip');


    }
}
