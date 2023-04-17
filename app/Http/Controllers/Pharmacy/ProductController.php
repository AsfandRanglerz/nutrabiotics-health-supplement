<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Pharmacy;
use App\Models\PharmacyProduct;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authId = Auth::guard('pharmacy')->id();
        $pharmacy = Pharmacy::find($authId);
        $data = $pharmacy->products()->where('status', '0')->with('subcategory.category')->orderBy('pivot_id', 'DESC')->get();
        // $data['company_id'] = $id;
        // $data = PharmacyProduct::where('product_id', $id)->orderby('id', 'DESC')->get();

        return view('pharmacy.product.index', compact('data', 'pharmacy'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['pharmacy_id'] = Auth::guard('pharmacy')->id();
        $data['product'] = Product::all();
        $data['category'] = Category::all();
        return view('pharmacy.product.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'product_id' => 'required',
            'stock' => 'required',

        ],
            [
                'category_id.required' => 'The category field is required.',
                'subcategory_id.required' => 'The sub category field is required.',
                'product_id.required' => 'The product field is required.',
            ]);

        $pharmacy = Auth::guard('pharmacy')->id();
        $product = Product::find($request->product_id);
        $pharmacyProduct = PharmacyProduct::where('pharmacy_id', $pharmacy)->where('product_id', $product->id)->first();
        if ($pharmacyProduct) {
            // If the product already exists for the pharmacy, update the stock
            $pharmacyProduct->stock += $request->stock;
            $pharmacyProduct->save();
        } else {
            // If the product doesn't exist for the pharmacy, create a new record
            $data = PharmacyProduct::create([
                'stock' => $request->stock,
                'pharmacy_id' => $pharmacy,
                'product_id' => $product->id,
            ]);
        }

        return redirect()->route('pharmacy.product.index')
            ->with('success', 'Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = PharmacyProduct::with('product.subcategory.category')->find($id);
        $product = Product::where('subcategory_id', $data->product->subcategory_id)->where('category_id', $data->product->category_id)->get();
        $subcategory = SubCategory::where('category_id', $data->product->subcategory->category_id)->get();
        $category = category::all();
        return view('pharmacy.product.edit', compact('data', 'category', 'product', 'subcategory'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required',
            'stock' => 'required',
            // 'category_id' =>'required'
        ],
            [
                'category_id.required' => 'The category field is required.',
            ]);
        $pharmacy = Auth::guard('pharmacy')->id();
        $data = PharmacyProduct::find($id)->update([
            'stock' => $request->stock,
            'pharmacy_id' => $pharmacy,
            'product_id' => $request->product_id,
        ]);

        return redirect()->route('pharmacy.product.index')
            ->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PharmacyProduct::destroy($id);
        return redirect()->back()->with('success', 'Deleted Successfully');
    }

    public function getSubCategory(Request $request)
    {
        $data['SubCategory'] = SubCategory::where("category_id", $request->category_id)->get();
        return response()->json($data);
    }

    public function getProduct(Request $request)
    {
        $data['product'] = Product::where("subcategory_id", $request->subcategory_id)->where('status', '0')->get();
        // dd($data['product']);
        return response()->json($data);
    }
    public function productPhoto_index($id, $product_id)
    {
        $data = ProductPhoto::where('product_id', $product_id)->latest()->get();
        return view('pharmacy.product.photo.index', compact('data'));
    }
    public function discount(Request $request)
    {
        $data = Product::find($request->id);
        return response()->json([
            'success' => 'Status successfully',
            'data' => [
                'price' => $data->price,
                'id' => $data->id,
                'd_per' => $data->d_per,
                'd_price' => $data->d_price,
                'start_date' => $data->start_date,
                'expiry_date' => $data->expiry_date,

            ],
        ]);
    }
}
