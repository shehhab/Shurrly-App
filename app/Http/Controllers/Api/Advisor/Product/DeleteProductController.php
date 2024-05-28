<?php

namespace App\Http\Controllers\Api\Advisor\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeleteProductController extends Controller
{
    public function __invoke(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required',
        ]);

        $user = Auth::user();

        try {
            $product = Product::findOrFail($validatedData['product_id']);

            if ($product->advisor->seeker_id !== $user->id) {
                return $this->handleResponse(message: 'You are not authorized to delete this product.', status: false, code: 403);
            }

            DB::table('product_skill')->where('product_id', $product->id)->delete();



            $product->delete();

            return $this->handleResponse(status: true, code: 200, message: 'Product deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->handleResponse(message: 'Product not found', status: false, code: 404);
        }
    }

}
