<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\File;



class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   $category = Category::all();
        $data = SubCategory::with('category')->latest()->get();
        return view('admin.subCategory.index',compact('data','category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'category_id' => 'required|integer', // Added validation rule for category_id
            'name' => 'required',
            'image' => 'required',

        ]);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = 'public/admin/assets/images/users/1677742785.jpg';
        }
        $data = SubCategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'image'=>$image,
        ]);
        return redirect()->route('subcategory.index')->with(['status' => true, 'message' => 'Created Successfully']);
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
            $data = SubCategory::find($id);
            $category = Category::all();
            return view('admin.subCategory.edit', compact('data', 'category'));
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
            'category_id' => 'required',
            'name' => 'required',

        ]);

        $data = SubCategory::find($id);
        if ($request->hasFile('image')) {
            $destination = 'public/admin/assets/img/users/' . $data->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/admin/assets/img/users', $filename);
            $image = 'public/admin/assets/img/users/' . $filename;
        } else {
            $image = $data->image;
        }
        $data->category_id = $request->input('category_id');
        $data->name = $request->input('name');
        $data->image = $image;

        $data->update();
        return redirect()->route('subcategory.index')->with(['status' => true, 'message' => 'Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SubCategory::destroy($id);
        return redirect()->back()->with(['status' => true, 'message' => 'Deleted Successfully']);
    }


}
