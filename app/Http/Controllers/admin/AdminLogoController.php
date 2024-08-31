<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Logo;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminLogoController extends Controller
{
    public function index(Request $request){
        $logo = Logo::all()->first();
        // dd($logos);
        return view ('admin.logo.list', compact('logo'));

    }

    public function create(){
        return view ('admin.logo.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[]);
        if($validator->passes()){
            $logo = new Logo();
            $logo-> description = $request-> description;


            if(!empty($request->image_id))
            {
                $tempImage = TempImage::find($request-> image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);
                $newImageName= $logo->id.'-'.time(). '.' .$ext;
                $sPath =public_path().'/temp/'.$tempImage->name;
                $dPath =public_path().'/uploads/logo/'.$newImageName;
                File::copy($sPath,$dPath);
                $logo->image=$newImageName;
            }

            $logo->save();


            $request->session()->flash('success','Logo added successfully');
            return response()->json([
                'status'=>true,
                'message'=>'Logo added successfully'
            ]);
        }

        else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }

    }

    public function edit($logoId,Request $request){

        $logoId = Logo::find($logoId);
        if (empty($logoId)){
            return redirect()->route('logo.index');
        }

        return view('admin.logo.edit',compact('logoId'));

    }

    public function update($logoId,Request $request){
        $logoId = Logo::find($logoId);
        if (empty($logoId)){
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Logo not Found'
            ]);
        }

        $validator = Validator::make(request()->all(),[
        ]);
        if($validator->passes()){
            $oldImage = $logoId->image;
            if(!empty($request->image_id))
            {
                $tempImage = TempImage::find($request-> image_id);

                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);
                $newImageName= $logoId->id.'-'.time(). '.' .$ext;
                $sPath =public_path().'/temp/'.$tempImage->name;
                $dPath =public_path().'/uploads/logo/'.$newImageName;
                File::copy($sPath,$dPath);

                $logoId->image=$newImageName;

                //Delete images here
                File::delete(public_path().'/uploads/logo/'.$oldImage);
            }

            $logoId->description=$request->description;
                $logoId->save();

            $request->session()->flash('success','Logo Updated successfully');
            return response()->json([
                'status'=>true,
                'message'=>'Logo Updated successfully'
            ]);

        }
        else{
            return response()->json([
                'status'=>false,
                'errors'=>$validator->errors()
            ]);
        }

    }

}
