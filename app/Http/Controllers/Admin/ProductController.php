<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Notification;
use App\Models\PharmacyProduct;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = Product::with('subcategory.category')->latest()->get();
        // return $data;
        foreach ($data as $product) {
            $sumStock = PharmacyProduct::where('product_id', $product->id)->sum('stock');
            $product->sumStock = $sumStock;
            $product->remainingStock = $product->stock - $sumStock;
        }
        return view('admin.product.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Category::all();
        return view('admin.product.create', compact('data'));
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
            'product_name' => 'required',
            'category_id' => 'required|integer', // Added validation rule for category_id
            'subcategory_id' => 'required|integer',
            'price' => 'required',
            'stock' => 'required',
        ]);
        $data = Product::create([
            'product_name' => $request->product_name,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
        ]);
        return redirect()->route('product.index')->with(['status' => true, 'message' => 'Created Successfully']);
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
        $data = Product::with('subcategory.category')->find($id);
        // return $data;
        $subcategory = SubCategory::where('category_id', $data->subcategory->category_id)->get();
        $category = Category::all();
        return view('admin.product.edit', compact('data', 'category', 'subcategory'));
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
            'product_name' => 'required',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'price' => 'required',
            'stock' => 'required',

        ]);

        $data = Product::find($id);
        $data->product_name = $request->input('product_name');
        $data->subcategory_id = $request->input('subcategory_id');
        $data->category_id = $request->input('category_id');
        $data->price = $request->input('price');
        $data->stock = $request->input('stock');
        $data->description = $request->input('description');

        $data->update();
        return redirect()->route('product.index')->with(['status' => true, 'message' => 'Updated Successfully']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::destroy($id);
        return redirect()->back()->with(['status' => true, 'message' => 'Deleted Successfully']);

    }
    public function getSubCategory(Request $request)
    {
        $data['SubCategory'] = SubCategory::where("category_id", $request->category_id)->get();
        // dd($data['product']);
        return response()->json($data);
    }
    public function status($id)
    {
        $data = Product::find($id);
        $data->update(['status' => $data->status == 0 ? '1' : '0']);
        return redirect()->back()->with(['status' => true, 'message' => 'Updated Successfully']);
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
    public function keyup(Request $request)
    {

        $data = Product::find($request->id);
        return response()->json([
            'success' => 'Status  successfully',
            'data' => $data,
        ]);
    }

    public function updateDiscount(Request $request)
    {
        $data = Product::with('photos')->find($request->id);
        $data->d_per = $request->input('d_per');
        $data->d_price = $request->input('d_price');
        $data->start_date = $request->input('start_date');
        $data->expiry_date = $request->input('expiry_date');
        // dd($data);
        $data->update();
        // $photo = $data->photos->first()->photo;
        $body = array(
            'title'=>'Deal',
            'product_id' => $request->id,
            'product_name' => $data->product_name,
            'price' => $request->price,
            'd_per' => $request->d_per,
            'd_price' => $request->d_price,
            // 'photo' => $data->photos
            // 'type'     => 'accept',
        );
        // dd($body);

        $users = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();

        $tokens = [];

        foreach ($users as $user) {
            $tokens[] = (string) $user;
        }

        $data = [
            'registration_ids' => $tokens,
            'notification' => [
                'title' => "Request Accepted",
                'body' => "Request Accepted",
            ],
            'data' => [
                'RequestData' => $body,
            ],
        ];

        $SERVER_API_KEY = 'AAAANPu72Ro:APA91bFzYN-Qhz9k41f1qGiT3QSJu2mgV4_Nb-8NfO2ck9FEfgLBDtsLemUXrmpVr9nBj3EtNtGJnB2bvmSaD_cVrMZk-8EgMsknaihbc4oIUGpHdwNy9sDwUuC0HDa4UrW_yGpNYDX5';

        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
        // dd($response);
        // Store notification in the database
        $notification = new Notification();
        $notification->title = "Deal";
        $notification->body = json_encode($body);
        // $notification->body = $body['product_id'] . '*3#(' . $body['product_name'] . '*3#(' . $body['price'] . '*3#(' . $body['d_per'] . '*3#(' . $body['d_price'];
        // $notification->file = $body['photo'];
        $notification->save();
        $body['notification_id'] = $notification->id;

        return redirect()->route('product.index')->with(['status' => true, 'message' => 'Created Successfully']);
    }
    public function convertToDate($value = 0, $unit = 'days')
    {
        $date = Carbon::today();

        if ($unit === 'days') {
            $date = $date->addDays($value);
        } elseif ($unit === 'weeks') {
            $date = $date->addWeeks($value);
        }

        return $date->format('Y-m-d');
    }
}
