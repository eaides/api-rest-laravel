<?php

namespace App\Http\Controllers\Seller;

use App\Product;
use App\Seller;
use App\Transformers\ProductTransformer;
use App\User;
use App\Helpers\Helper;
use App\Http\Controllers\ApiController;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:'.ProductTransformer::class)
            ->only(['store','update']);
        $this->middleware('scope:manage-products')->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Seller  $seller
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Seller $seller)
    {
        if (request()->user()->tokenCan('read-general') ||
            request()->user()->tokenCan('scope:manage-products')
        ) {
            $products = $seller->products;

            return $this->showAll($products);
        }
        throw new AuthenticationException;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $seller
     *      we will use User instead Seller because can be
     *      that a User yet create any product (remember: Seller
     *      -that extends User- IS an User with at least one product
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, User $seller)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'quantity' => ['required','integer','min:1'],
            'status' => ['in:'.Product::PRODUCT_AVAILABLE.','.Product::PRODUCT_UNAVAILABLE],
            'image' => [
                'image',
                Rule::dimensions()->maxWidth(2000)->maxHeight(2000),
                'max:10240',
            ],
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        /**
         * Can fix data if needed, like:
         *
         * $data['status'] = Product::PRODUCT_UNAVAILABLE
         *
         */
        $data['image'] = null;
        if ($request->has('image'))
        {
            $data['image'] = Helper::storeAndReSizeImg($request, 'image');
        }

        // at the creation, the status will be not available. In update products
        // can be chane to available, only if the product has at least one category
        // the category can not be assigned during the creation.
        $data['status'] = Product::PRODUCT_UNAVAILABLE;
//        if ($request->missing('status'))
//        {
//            $data['status'] = Product::PRODUCT_UNAVAILABLE;
//        }

        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->showOne($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     *      we will use for updates Seller because the User has, at least
     *      this product. Can not update that is not ours (owner by the
     *      passed seller id
     * @param  \App\Product $product
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        $rules = [
            'name' => ['string', 'max:255'],
            'description' => ['string', 'max:1000'],
            'quantity' => ['integer','min:1'],
            'status' => ['in:'.Product::PRODUCT_AVAILABLE.','.Product::PRODUCT_UNAVAILABLE],
            'image' => [
                'image',
                Rule::dimensions()->maxWidth(2000)->maxHeight(2000),
                'max:10240',
            ],
        ];

        $this->validate($request, $rules);

        $this->sellerVerify($seller, $product);

        $product->fill($request->only([
            // status and image will be asigned later
            'name', 'description', 'quantity'
        ]));

        if ($request->has('status'))
        {
            $product->status = $request->status;
            // after change status, if available and has not categories
            if ($product->isAvailable() && $product->categories()->count() == 0)
            {
                return $this->errorResponse("An active product must have at least one category", 409);
            }
        }

        if ($request->hasFile('image'))
        {
            Storage::delete($product->image);

            $product->image = Helper::storeAndReSizeImg($request, 'image');
        }

        if ($product->isClean())
        {
            return $this->errorUpdateNoChanges();
        }

        $product->save();

        return $this->showOne($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     *      we will use for updates Seller because the User has, at least
     *      this product. Can not update that is not ours (owner by the
     *      passed seller id
     * @param  \App\Product $product
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->sellerVerify($seller, $product);

        // @todo must remove only for permanently remove
        Storage::delete($product->image);

        $product->delete();

        return $this->showOne($product);
    }

    /**
     * @param Seller $seller
     * @param Product $product
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    protected function sellerVerify(Seller $seller, Product $product)
    {
        if ($product->seller_id != $seller->id)
        {
            throw new HttpException(422,"The seller specified is not the real product's seller");
        }
    }
}
