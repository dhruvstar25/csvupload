<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\csvupload;

class uploadcontroller extends Controller
{
    public function create()
    {
        return view("uploads");
    }

    public function store(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $input = new csvupload();
        $input->email = $request->input('email');

        if ($request->hasfile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('file/', $filename);
            $input->filepath =  strval($filename);
        }
        $input->save();
        return back()->with('success','Images are being uploaded, you will get an email when it is done"');
        
    }
}
