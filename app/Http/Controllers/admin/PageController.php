<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Page;


class PageController extends Controller
{
    public function index(Request $request){
        $pages = Page::latest();
        if(!empty($request->get('keyword'))){
               $pages =
               $pages->where('name','like','%'.$request->get('keyword').'%');
        }
        $pages = $pages->latest()->paginate(10);
        return view('admin.pages.list', compact('pages'));
    }
    public function create(){
        return view('admin.pages.create');
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required',

    ]);
    if($validator->passes()){
        $page = new Page();
        $page -> name = $request->name;
        $page -> slug = $request->slug;
        $page -> content = $request->contentNote;
        $page->save();
        $message = 'Page addeed successfully.';
        session()->flash ('success',$message);

        return response()->json([
            'status'  =>  true,
            'message' => $message
        ]);
    }else{
        return response()->json([
            'status'  =>  false,
            'errors'=>$validator->errors()
        ]);
    }
    }
    public function edit(Request $request,$id){
        $pages= Page::find($id);
        return view('admin.pages.edit',compact('pages') );
    }


    public function update(Request $request,$id){
        $pages= Page::find($id);

        if (empty($pages)){
            session()->flash('error','Page not found');
            return response()->json([
                'status'=> false,
                'message'=> 'page not found'
            ]);

        }
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required',

            ]);
            if($validator->passes()){
                $pages -> name = $request->name;
                $pages -> slug = $request->slug;
                $pages -> content =$request->content;
                $pages->save();
                $message = 'Page updated successfully.';
                session()->flash ('success',$message);

                return response()->json([
                    'status'  =>  true,
                    'message' => $message
                ]);
            }else{
                return response()->json([
                    'status'  =>  false,
                    'errors'=>$validator->errors()
                ]);
                }
    }

    public function destroy($id, Request $request){
        $pages = Page::find($id);

        if (empty($pages)){
            session()->flash('error','Page not found');
            return response()->json([
                'status'=> false,
                'message'=> 'Pages not found'
            ]);

        }
        $pages->delete();
        $request->session()->flash('success','Page Delete successfully');

        return response()->json([
            'status' => true,
            'message' => 'Page Delete successfully',
        ]);

    }
}
