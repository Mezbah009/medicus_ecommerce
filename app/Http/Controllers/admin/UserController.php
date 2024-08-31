<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
  public function index(Request $request){
    $users = User::where('role', 1)->latest();

    if(!empty($request->get('keyword'))){
        $users =
        $users->where('name','like','%'.$request->get('keyword').'%');
        $users->orWhere('email','like','%'.$request->get('keyword').'%');
    }
    $users = $users->latest()->paginate(10);
    return view('admin.users.list',compact('users'));
  }

  public function admin_index(Request $request){
    $users = User::where('role', 2)->latest();

    if(!empty($request->get('keyword'))){
        $users =
        $users->where('name','like','%'.$request->get('keyword').'%');
        $users->orWhere('email','like','%'.$request->get('keyword').'%');
    }
    $users = $users->latest()->paginate(10);
    return view('admin.users.admin-list',compact('users'));
  }

  public function create(Request $request){
    return view('admin.users.create');
  }
  public function store(Request $request){
    $validator = Validator::make($request->all(),[
        'name' => 'required|min:3',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:5',
        'phone' => 'required',
        'role' => 'required',

        ]);
        if($validator->passes()){
            $users = new User();
            $users -> name = $request->name;
            $users -> email = $request->email;
            $users -> phone = $request->phone;
            $users->password =  Hash::make($request->password);
            if((  $request->role) == 2){
                $users -> role = $request->role;
            }  
            $users -> status = $request->status;
            $users->save();
            $message = 'New entry added successfully.';
            session()->flash ('success',$message);

            return response()->json([
                'status'  =>  true,
                'role' => $request->role,
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
        $users= User::find($id);
        return view('admin.users.edit',compact('users') );

    }
    public function update(Request $request,$id){
        $users= User::find($id);

        if (empty($users)){
            session()->flash('error','User not found');
            return response()->json([
                'status'=> false,
                'message'=> 'User not found'
            ]);

        }
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.$id.',id',
            'phone' => 'required',
    
            ]);
            if($validator->passes()){
                $users -> name = $request->name;
                $users -> email = $request->email;
                $users -> phone = $request->phone;
                $users -> status = $request->status;
                if ($request->password != '') {
                $users->password =  Hash::make($request->password);
                }
                $users->save();

                $role = $users->role;
                if ($role == 1) {
                    $message = 'Customer updated successfully.';
                } else {
                    $message = 'User updated successfully.';
                }
                
                session()->flash ('success',$message);
    
                return response()->json([
                    'status'  =>  true,
                    'role'    => $role,
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
        $users =User::find($id);

        if (empty($users)){
            session()->flash('error','User not found');
            return response()->json([
                'status'=> false,
                'message'=> 'User not found'
            ]);

        }
        $users->delete();
        $request->session()->flash('success','User Delete successfully');

        return response()->json([
            'status' => true,
            'message' => 'Page Delete successfully',
        ]);

    }

    

}
