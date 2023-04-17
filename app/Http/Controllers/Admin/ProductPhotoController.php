<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Support\Facades\File;


class ProductPhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $product = Product::find($id);
        $data = ProductPhoto::where('product_id',$product->id)->get();
        return view('admin.product.photo.index',compact('data','product'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $data = $id;

       return view('admin.product.photo.create',compact('data'));
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
        'photo' => 'required|image',
    ]);

    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move(public_path('admin/assets/images/users/'), $filename);
        $photo = 'public/admin/assets/images/users/' . $filename;

        ProductPhoto::create([
            'product_id' => $id,
            'photo' => $photo,
        ]);

        return redirect()->route('product-photo.index',$id)->with(['status' => true, 'message' => 'Created Successfully']);
    }
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
        $data = ProductPhoto::find($id);
        return view('admin.product.photo.edit',compact('data'));
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

    $data = ProductPhoto::find($id);
    if ($request->hasFile('photo')) {
        $destination = 'public/admin/assets/img/users/' . $data->photo;
        if (File::exists($destination)) {
            File::delete($destination);
        }
        $file = $request->file('photo');
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '.' . $extension;
        $file->move('public/admin/assets/img/users', $filename);
        $image = 'public/admin/assets/img/users/' . $filename;
    } else {
        $image = $data->photo;
    }
       $data->photo = $image;

    $data->update();

    return redirect()->route('product-photo.index', $data->product_id)
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
        ProductPhoto::destroy($id);
        return redirect()->back()->with(['status' => true, 'message' => 'Deleted Successfully']);
    }
}
