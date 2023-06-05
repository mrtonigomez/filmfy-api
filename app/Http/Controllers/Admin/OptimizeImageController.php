<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Image;

class OptimizeImageController extends Controller
{
    public function index()
    {
        return view("file_upload.fileUpload");
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('image');
        $imageName = time().'.'.$image->extension();

        $destinationPathThumbnail = public_path('movie_images_thumbs');
        $img = Image::make($image->path());
        $img->resize(338, 500, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPathThumbnail.'/'.$imageName);

        return view("file_upload.fileUpload");
    }
}
