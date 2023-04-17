<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Pharmacy;
use App\Models\PharmacyProduct;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class ProductController extends Controller
{
    public function getCategory()
    {
        $data = Category::all();
        return $this->sendSuccess('Category data', compact('data'));
    }
    // get subcategory against category
    public function getSubCategory($category_id)
    {
        $data = SubCategory::where('category_id', $category_id)->get();
        return $this->sendSuccess('Sub Category data', compact('data'));

    }
    // get product against subcategory
    public function getProduct($subcategory_id)
    {

        $data = Product::with('photos')->where([['subcategory_id', $subcategory_id], ['status', '0']])->get();
        return $this->sendSuccess('Product data', compact('data'));

    }

    // public function order($product_id){

    // }

    public function singleProduct($id)
    {
        $data = Product::with('photos')->where('id', $id)->first();

        return $this->sendSuccess('Single Product data', compact('data'));
    }
    // nearest pharmacies this product
    public function getlocation(Request $request)
    {
        $id = $request->id;
        $product = Product::with('photos')->find($id);
        if (isset($request->latitude) && isset($request->longitude)) {

            $nearestUsers = Pharmacy::whereHas('products', function ($query) use ($id) {
                $query->where('product_id', $id);
            })
                ->with(['pharmacyproduct' => function ($query) use ($id) {
                    $query->where('product_id', $id);
                }])
                ->nearestTo($request->latitude, $request->longitude)
                ->get();
        } else {
            if ($user = User::find($request->user_id)) {
                if (isset($user->latitude) && isset($user->longitude)) {
                    $lat = $user->latitude;
                    $lon = $user->longitude;
                    $nearestUsers = Pharmacy::whereHas('products', function ($query) use ($id) {
                        $query->where('product_id', $id)
                        ;
                    })
                        ->with(['pharmacyproduct' => function ($query) use ($id) {
                            $query->where('product_id', $id);
                        }])
                        ->nearestTo($lat, $lon)
                        ->get();

                } else {
                    $nearestUsers = Pharmacy::whereHas('products', function ($query) use ($id) {
                        $query->where('product_id', $id)
                        ;
                    })
                        ->with(['pharmacyproduct' => function ($query) use ($id) {
                            $query->where('product_id', $id);
                        }])
                        ->get();
                }

            } else {
                $nearestUsers = Pharmacy::whereHas('products', function ($query) use ($id) {
                    $query->where('product_id', $id)
                    ;
                })
                    ->with(['pharmacyproduct' => function ($query) use ($id) {
                        $query->where('product_id', $id);
                    }])
                    ->get();
            }

        }

        return response()->json([
            'nearestPharmacy' => $nearestUsers,
            'product' => $product,
        ]);
    }

    public function getPharmacy($id)
    {
        $pharmacy = PharmacyProduct::with('pharmacy')->with('product')->where('id', $id)->get();
        return $this->sendSuccess('Pharmacy data', compact('pharmacy'));
    }

    //Most Selling Products
    public function getMostSellingProducts()
    {
        $products = OrderItem::with('product.photos')->groupBy('product_id')
            ->select('product_id', DB::raw('count(*) as count'))
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        return $this->sendSuccess('Most Selling Product data', compact('products'));
    }
    //  Deals
    public function deal(){
        $product = Product::with('photos')->whereNotNull('expiry_date')->orderByDesc('d_per')->get();
        return $this->sendSuccess('Deals data',$product);
    }

    //  Home Page
    public function home()
    {
        $banner = Banner::limit(10)->get();
        $data = SubCategory::limit(8)->get();
        $products = OrderItem::with('product.photos')->groupBy('product_id')
            ->select('product_id', DB::raw('count(*) as count'))
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        return $this->sendSuccess('Category data', compact('data', 'products', 'banner'));
    }


}
