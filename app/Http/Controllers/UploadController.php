<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/assets/images/');
            $image->move($destinationPath, $name);

            $data = array();
            $data['link'] = $request->getSchemeAndHttpHost() . '/assets/images/' . $name;
            return ['success'=>true, 'data'=>$data, 'status'=>200];
        }
        
        return ['success' => false, 'status' => 500];
    }
}
