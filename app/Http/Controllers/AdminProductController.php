<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStoreRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\Input;

class AdminProductController extends Controller
{
    
    public function index(){

        $products = Product::all();
        return view("admin.products", compact('products') );

    }

    // Mostar página de edição
    public function edit(Product $product){

        return view("admin.products_edit", [
            "product" => $product
        ]);

    }

    // Recebe requisição para dar update
    public function update(ProductStoreRequest $product, Request $request){

        $input = $request->validated();

         // Verifica o arquivo e pega o diretorio real onde estará armazenado
        if( !empty($input["cover"]) && $input["cover"]->isValid() ){
            Storage::delete($product->cover ?? '');
            $file = $input["cover"];
            $path = $file->store("products");
            $input["cover"] = $path;
        }

        $product->fill($input);
        $product->save();

        return Redirect::route("admin.product");
    }

    // Mostrar página de criar
    public function create(){

        return view('admin.product_create');
        
    }

    // Recebe a requisição de criar
    public function store(ProductStoreRequest $request){

        $input = $request->validated();
        $input["slug"] = Str::slug($input["name"]);

        // Verifica o arquivo e pega o diretorio real onde estará armazenado
        if( !empty($input["cover"]) && $input["cover"]->isValid() ){
            $file = $input["cover"];
            $path = $file->store("products");
            $input["cover"] = $path;
        }

        Product::create($input);
        
        return Redirect::route("admin.product");
        
    }

    public function destroy(Product $product){

        $product->delete();
        Storage::delete($product->cover ?? '');
        return Redirect::route("admin.product");
    }

    public function destroyImage(Product $product){

        Storage::delete($product->cover ?? '');
        $product->cover = null;
        $product->save();

        return Redirect::back();



    }

}
