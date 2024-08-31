<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Image;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $sliders = Slider::latest();
        if(!empty($request->get('keyword'))){
            $sliders = $sliders->where('title','like','%'.$request->get('keyword').'%');
        }
        $sliders = $sliders->latest()->paginate(10);
        return view('admin.slider.list',compact('sliders'));
    }
    public function create()
    {
        return view('admin.slider.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'link' => 'required',
            'active' => 'required',
        ]);
        if($validator->passes()){
            $slider=new Slider();
            $slider->title = $request->title;
            $slider->description = $request->description;
            $slider->button_name = $request->button_name;
            $slider->link = $request->link;
            $slider->active=$request->active;
            $slider->save();

            //save image here
            if(!empty($request->image_id))
            {
                $tempImage = TempImage::find($request-> image_id);

                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);
                $newImageName= $slider->id.'.'.$ext;
                $sPath =public_path().'/temp/'.$tempImage->name;
                $dPath =public_path().'/uploads/slider/'.$newImageName;
                File::copy($sPath,$dPath);

                $slider->image=$newImageName;
                $slider->save();

            }
            $request->session()->flash('success','Slider added successfully');
            return response()->json([
                'status'=>true,
                'message'=>'Slider added successfully'
            ]);

        }
        else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($sliderId,Request $request)
    {
        $slider= Slider::find($sliderId);
        if(empty($slider)){
            return redirect()->route('sliders.index');
        }
        return view('admin.slider.edit',compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($sliderId,Request $request)
    {
        $slider = Slider::find($sliderId);
        if(empty($slider)){
            return response()->json([
                'status' => false,
                'notFound'=> true,
                'message' => 'Slider not found'
            ]);
        }

        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'link' => 'required',
            'active' => 'required',

        ]);
        if($validator->passes()){
            $slider->title = $request->title;
            $slider->description = $request->description;
            $slider->button_name = $request->button_name;
            $slider->link = $request->link;
            $slider->active=$request->active;
            $slider->save();
            $oldImage = $slider->image;
            //save image here
            if(!empty($request->image_id))
            {
                $tempImage = TempImage::find($request-> image_id);

                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);
                $newImageName= $slider->id.'-'.time().'.'.$ext;
                $sPath =public_path().'/temp/'.$tempImage->name;
                $dPath =public_path().'/uploads/slider/'.$newImageName;
                File::copy($sPath,$dPath);

                $slider->image=$newImageName;
                $slider->save();
                //delete old images
                File::delete(public_path().'/uploads/slider/'.$oldImage);


            }
            $request->session()->flash('success','Slider updated successfully');
            return response()->json([
                'status'=>true,
                'message'=>'Slider updated successfully'
            ]);

        }
        else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($sliderId,Request $request)
    {
        $slider = Slider::find($sliderId);
        if(empty($slider)){

            $request->session()->flash('Error','Slider not Found');
            return response()->json([
                'status'=>true,
                'message' =>'Slider not Found'
            ]);
        }
        File::delete(public_path().'/uploads/slider/'.$slider->image);
        $slider->delete();

        $request->session()->flash('success','Slider deleted successfully');
        return response()->json([
            'status'=>true,
            'message' =>'Slider deleted successfully'
        ]);

    }
}
