<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['photo_name'];


    /**
     * Метод для збереження в бд імені фотки
     * @param array $fields
     * @return Photo
     */
    public static function add(array $fields)
    {
        $photo = new self();
        $photo->fill($fields);
        $photo->save();

        return $photo;
    }
}
