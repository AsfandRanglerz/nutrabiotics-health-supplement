<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Pharmacy;
use App\Models\PharmacyProduct;
use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Http\Request;

class PharmacyProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
{
    $pharmacy = Pharmacy::find($id);
    $data = $pharmacy->products()->where('status','0')->with('subcategory.category')->with('photos')->orderBy('pivot_id', 'DESC')->get();
    // return $data;
    // $productPhotos = [];
    // foreach($data as $product){
    //     $photos = $product->photos()->get();
    //     foreach($photos as $photo){
    //         $productPhotos[] = $photo;
    //     }
    // }
    return view('admin.pharmacy.product.index', compact('data', 'pharmacy'));
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $data['pharmacy_id'] = $id;
        $data['product'] = Product::all();
        $data['category'] = Category::all();
        return view('admin.pharmacy.product.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required',
            'stock' => 'required',
            'category_id' => 'required',
            'subcategory_id' => 'required',

        ],
            [
                'category_id.required' => 'The category field is required.',
                'subcategory_id.required' => 'The sub category field is required.',
                'product_id.required' => 'The product field is required.',

            ]);

        $pharmacy = Pharmacy::find($id);
        $product = Product::find($request->product_id);
        $pharmacyProduct = PharmacyProduct::where('pharmacy_id', $pharmacy->id)->where('product_id', $product->id)->first();
        if ($pharmacyProduct) {
            // If the product already exists for the pharmacy, update the stock
            $pharmacyProduct->stock += $request->stock;
            $pharmacyProduct->save();
        } else {
            $data = PharmacyProduct::create([
                'stock' => $request->stock,
                'pharmacy_id' => $pharmacy->id,
                'product_id' => $product->id,
            ]);
        }
        return redirect()->route('pharmacyProduct.index', $pharmacy->id)
            ->with(['status' => true, 'message' => 'Created Successfully']);
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
        // return $data;
        $product = Product::where('subcategory_id', $data->product->subcategory_id)->where('category_id',$data->product->category_id)->get();
        //  return $data;
        $subcategory = SubCategory::where('category_id',$data->product->subcategory->category_id)->get();
        $category = Category::all();
        return view('admin.pharmacy.product.edit', compact('data', 'category', 'product','subcategory'));

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

        $data = PharmacyProduct::find($id)->update([
            'stock' => $request->stock,
            'pharmacy_id' => $request->pharmacy_id,
            'product_id' => $request->product_id,
        ]);

        return redirect()->route('pharmacyProduct.index', $request->pharmacy_id)
            ->with(['status' => true, 'message' => 'Updated Successfully']);
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
        return redirect()->back()->with(['status' => true, 'message' => 'Deleted Successfully']);
    }

    public function getSubCategory(Request $request)
    {
        $data['SubCategory'] = SubCategory::where("category_id", $request->category_id)->get();
        // dd($data['product']);
        return response()->json($data);
    }

    public function getProduct(Request $request)
    {
        $data['product'] = Product::where("subcategory_id", $request->subcategory_id)->where('status','0')->get();
        // dd($data['product']);
        return response()->json($data);
    }

    public function productPhoto_index($id,$product_id){
           $data = ProductPhoto::where('product_id',$product_id)->latest()->get();
           return view('admin.pharmacy.product.photo.index',compact('data'));
    }
}
