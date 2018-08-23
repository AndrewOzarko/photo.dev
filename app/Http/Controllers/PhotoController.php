<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    const PHOTO_PATH = 'public/photo';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('photo');
    }

    public function upload(Request $request)
    {
        $this->validate($request,
            [
                'file' => 'required | mimes:jpeg,bmp,png',
            ]
        );

        $photo = $request->file('file');
        $ext = $photo->getClientOriginalExtension();
        $photoName = md5($photo->getClientOriginalName().''.time()). '.' . $ext;

        Photo::add(['photo_name' => $photoName]);

        Storage::putFileAs(self::PHOTO_PATH, $photo, $photoName);

        return response()->json(['success' => true, 'photo' => $photoName], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request,
            [
                'file' => 'required | mimes:jpeg,bmp,png',
            ]
        );

        dd($request);

        $photo = $request->file('file');
        $ext = $photo->getClientOriginalExtension();
        $photoName = md5($photo->getClientOriginalName().''.time()). ' ' . $ext;

//        $photo = new Photo;
//        $photo->photo_name = $photoName;
//        $photo->save();

        Storage::putFileAs(self::PHOTO_PATH, $photo, $photoName);

        return response()->json(['success' => true], 200);
    }

    public function destroy(Photo $photo)
    {
        //
    }
}
