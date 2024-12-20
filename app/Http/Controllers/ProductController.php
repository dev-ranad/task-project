<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Traits\ShortcutTrait;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use ShortcutTrait;

    public function requestOperation($request, $action, $resource_id = null)
    {
        $request->merge([
            'purchase_price' => $request->purchase_price ? number_format((float)$request->purchase_price, 2, '.', '') : '',
            'sale_price' => $request->sale_price ? number_format((float)$request->sale_price, 2, '.', '') : '',
            'discount' => $request->discount ? number_format((float)$request->discount, 2, '.', '') : '',
            'tax' => $request->tax ? number_format((float)$request->tax, 2, '.', '') : '',
        ]);
        return $request;
    }

    public function afterOperation(Request $request, $resource = null, $action)
    {
        $this->handleProductVariant($request, $resource, $action);
    }

    public function model()
    {
        return (object) [
            'name' => 'Product',
            'model' => Product::class,
            'action' => ['index', 'store', 'show', 'update', 'delete']
        ];
    }
    public function validateData($request, $resource_id = 0)
    {
        $rules = [
            'name' => ['string', 'required'],
            'sku' => ['string', 'required'],
            'amazon' => ['string', 'required'],
            'ebay' => ['string', 'required'],
            'product_type' => ['required', 'string'],
            'stock_location' => ['required', 'string'],
            'quantity' => ['numeric', 'required'],
            'price' => ['numeric', 'required'],
        ];

        return Validator::make($request->all(), $rules);
    }
}
