<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class PhotoController extends Controller
{
    const PHOTO_PATH = 'public/photo';
    const WATERMARK_PATH = 'public/watermark';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('photo');
    }


    /**
     * Загрузка фотки
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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


    /**
     * Завантаження водяного знаку і обрізання фотки при бажанні
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function watermark(Request $request)
    {
        $this->validate($request,
            [
                'watermarkType' => 'required | integer',
                'imagename' => 'required | string | exists:photos,photo_name'
            ]
        );

        $watermarkType = $request->watermarkType;
        $photoName = $request->imagename;

        if ($watermarkType == 0) {

            $this->validate($request,
                [
                    'photofile' => 'required | mimes:jpeg,bmp,png',
                ]
            );

            $this->setImageWatermark($request->file('photofile'), $photoName);
        }

        if($watermarkType == 1) {
            $this->validate($request,
                [
                    'waterText' => 'required | string',
                ]
            );

            $this->setTextWatermark($request->waterText, $photoName);

        }

        if(!empty($request->crop) && $request->crop === "true") {
            $this->validate($request,
                [
                    'crop_width' => 'required | integer | min:100',
                    'crop_height' => 'required | integer | min:100'
                ]
            );

            $this->smartCrop($request->crop_width, $request->crop_height, $photoName);
        }

        return response()->json(['success' => true], 200);

    }


    /**
     * Типу розумне обрізання фотки. Викор метод з intervention Image
     * Знайшов на хабрі класний алгоритм але не став його реалізовавувати...
     * @param $cropWidth
     * @param $cropHeight
     * @param $photoName
     */
    private function smartCrop($cropWidth, $cropHeight, $photoName)
    {
        $img = Image::make(public_path() . '/storage/photo/' . $photoName);
        $img->fit($cropWidth, $cropHeight, function ($constraint) {
            $constraint->upsize();
        });
        $img->save(public_path() . '/storage/photo/' . $photoName);
    }

    /**
     * Метод для нанесення на основну фотку водяного знаку картинки
     * @param $watermark
     * @param $photoName
     */
    private function setImageWatermark($watermark, $photoName)
    {
        $ext = $watermark->getClientOriginalExtension();
        $watermarkName = md5($watermark->getClientOriginalName().''.time()). '.' . $ext;

        Storage::putFileAs(self::WATERMARK_PATH, $watermark, $watermarkName);

        $img = Image::make(public_path() . '/storage/photo/' . $photoName);

        $water = Image::make(public_path() . '/storage/watermark/' . $watermarkName);
        $img->insert($water, 'bottom-right');
        $img->save(public_path() . '/storage/photo/' . $photoName);
        Storage::delete(self::WATERMARK_PATH.'/'.$watermarkName);
    }

    /**
     * Метод для нанесення на основну фотку водяного знаку тексту
     * @param $waterText
     * @param $photoName
     */
    private function setTextWatermark($waterText, $photoName)
    {
        $img = Image::make(public_path() . '/storage/photo/' . $photoName);

        $rgb = $this->getBackgroud($img->pickColor(120, 120));
        $color = ($rgb == true) ? '#000000' : '#ffffff';

        $img->text($waterText, 120, 120, function($font) use ($color) {
            $font->file('fonts/Raleway-Regular.ttf');
            $font->size(24);
            $font->color($color);
            $font->align('center');
            $font->valign('top');
            $font->angle(45);
        });
        $img->save(public_path() . '/storage/photo/' . $photoName);
    }

    /**
     * Методи визначає чи світлий фон чи темний
     * @param array $rgb
     * return true - якщо фон світлий
     * return false - якщо фон темний
     */
    private function getBackgroud(array $rgb)
    {
        if (1 - (0.299 * $rgb[0] + 0.587 * $rgb[1] + 0.114 * $rgb[2]) / 255 < 0.5) {
            return true;
        } else {
            return false;
        }
    }

}
