<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\Input;

class AdminProductController extends Controller
{
    
    public function index(){

        $products = Product::all();
        return view("admin.products", compact('products') );

    }

    // Mostar página de edição
    public function edit(){

        return view("admin.products_edit");

    }

    // Recebe requisição para dar update
    public function update(){

        

    }

    // Mostrar página de criar
    public function create(){

        return view('admin.product_create');
        
    }

    // Recebe a requisição de criar
    public function store(Request $request){

        $input = $request->validate([
            "name" => "string|required",
            "price" => "string|required",
            "stock" => "integer|nullable",
            "cover" => "file|nullable",
            "description" => "string|nullable"
        ]);

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
}
