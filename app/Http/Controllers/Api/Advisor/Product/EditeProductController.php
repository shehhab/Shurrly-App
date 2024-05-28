<?php

namespace App\Http\Controllers\Api\Advisor\Product;

use App\Models\Skill;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Advisor\Product\AddProductRequest;
use App\Http\Requests\Advisor\Product\EditeProductRequest;
use App\Http\Resources\Advisor\Product\ProductResources;

class EditeProductController extends Controller
{
    public function __invoke(EditeProductRequest $request)
    {
        $validatedData = $request->validated();

        $user = Auth::user();
        if(empty($validatedData['product_id'])){
            return $this->handleResponse(message: 'You are not authorized to edit this product.', status: false, code: 403);

        }

        $product = Product::findOrFail($validatedData['product_id']);

        if( $product->advisor->seeker_id !== $user->id){
            return $this->handleResponse(message: 'Can not acess the product.', status: false, code: 403);

        }

        $updateData = [];

        if ($request->has('title')) {
            $updateData['title'] = $validatedData['title'];
        }

        if ($request->has('description')) {
            $updateData['description'] = $validatedData['description'];
        }

        if ($request->has('price')) {
            $updateData['price'] = $validatedData['price'];
        }

        // Update the product with the validated data
        $product->update($updateData);

        if ($request->hasFile('image')) {
            $product->clearMediaCollection('cover_product');
            $product->addMediaFromRequest('image')->toMediaCollection('cover_product');
        }


        return $this->handleResponse(status:true, code:200 ,message:'Product ', data: new ProductResources($product));
    }
}
