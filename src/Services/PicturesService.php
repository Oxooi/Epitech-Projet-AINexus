<?php

namespace App\Services;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PicturesService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    // 1200×628 pixels
    public function add(
        UploadedFile $picture,
        ?string $folder = '',
        ?int $width = 1200,
        ?int $height = 628
    ) {

        // We gave a name to the image
        $file = md5(uniqid(rand(), true)) . '.webp';

        // We get the info of the image
        $picture_infos = getimagesize($picture);

        if ($picture_infos === false) {
            throw new Exception("Error with the image size");
        }        

        // We check the image format
        switch ($picture_infos['mime']) {
            case 'image/png':
                $picture_src = imagecreatefrompng($picture);
                break;
            case 'image/jpeg':
                $picture_src = imagecreatefromjpeg($picture);
                break;
            case 'image/wbep':
                $picture_src = imagecreatefromwebp($picture);
                break;

            default:
                // If the format is invalid or isn't in the list return error
                throw new Exception("The image format is invalid. (png / jpg / webp allow)", 1);
                break;
        }

        // We resize the image
        // We get the dimension
        $imageWidth = $picture_infos[0];
        $imageHeight = $picture_infos[1];

        // We check the orientation of the image
        switch ($imageWidth <=> $imageHeight) {
            case -1: // Portait
                $squareSize = $imageWidth;
                $src_X = 0;
                $src_Y = ($imageHeight - $squareSize) / 2;
                break; 

            case 0: // Square
                $squareSize = $imageWidth;
                $src_X = 0;
                $src_Y = 0;
                break;

            case 1: // Landscape
                $squareSize = $imageHeight;
                $src_X = ($imageWidth - $squareSize) / 2;
                $src_Y = 0;
                break;

            default:
                throw new Exception("Error with the image orientation");
                break;
        }

        // We create the image
        $picture_dest = imagecreatetruecolor($width, $height);

        // We resize the image
        imagecopyresampled(
            $picture_dest,
            $picture_src,
            0,
            0,
            $src_X,
            $src_Y,
            $width,
            $height,
            $squareSize,
            $squareSize
        );

        // We save the image
        $path = $this->params->get('images_directory') . $folder;

        // We create the dest file if isn't exist yet
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        // We save the image
        imagewebp($picture_dest, $path . $file);

        $picture->move($path . '/', $file);

        // We return the image name
        return $file;
    }

    public function delete(string $file, ?string $folder = '', ?int $width = 1200 , ?int $height = 628)
    {
        if ($file !== 'default.webp') {
            $success = false;
            $path = $this->params->get('images_directory') . $folder;

            $original = $path . '/' . $file;

            if (file_exists($original)) {
                unlink($original);
                $success = true;
            }
            return $success;
        }

        return false;
    }
}
