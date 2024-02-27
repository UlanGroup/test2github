<?php

namespace app\Service;

use Yii;

use app\Log\Entity\Error;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;
use yii\web\UploadedFile;


class ImageService
{
    // Загрузка фото
    public function upload()
    {
        $picture = UploadedFile::getInstanceByName('picture');

        $name = 'u' . Yii::$app->user->id . '.' . $picture->extension;

        $path = './users';

        if (move_uploaded_file($picture->tempName, "$path/$name")) {
            $this->crop($name);
            chmod("$path/$name", 0755);
        } else {
            Error::error('НЕ сохранили фото', $picture->tempName);
        }

        return $name;
    }


    // Сжимаем фото (с уменьшением размера и сохранением пропорций)
    public function crop($file)
    {
        $orig = './users/' . $file;
        $mini = './users/mini/' . $file;

        $imagine = new Imagine();
        $imagine->open($orig)->thumbnail(new Box(1080, 1080))->save($orig, ['quality' => 70]);

        $size = getimagesize($orig); // Определить размер картинки и обрезать до квадрата
        $width = $size[0]; // Ширина
        $height = $size[1]; // Высота

        if ($width >= $height) {
            $x = ($size[0] - $size[1]) / 2;
            $point = new Point($x, 0);
            $box = new Box($size[1], $size[1]);
        } else {
            $y = ($size[1] - $size[0]) / 2;
            $point = new Point(0, $y);
            $box = new Box($size[0], $size[0]);
        }

        $imagine->open($orig)->crop($point, $box)->thumbnail(new Box(120, 120))->save($mini, ['quality' => 70]);
    }
}