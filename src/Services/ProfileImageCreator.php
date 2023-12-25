<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ProfileImageCreator
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function getUserInitials(string $email): array
    {
        // On récupère le nom d'utilisateur à partir de l'adresse email
        [$username] = explode('@', $email);

        // On sépare le nom d'utilisateur en fonction des espaces et des points
        $name_parts = preg_split('/[\s.]+/', $username);

        // On récupère la première lettre de chaque partie du nom
        $initials = array_map(fn($part) => mb_substr($part, 0, 1), $name_parts);

        // On mets les lettres en majuscules
        $initials = array_map('strtoupper', $initials);

        return $initials;
    }

    public function create(string $initials, int $size = 400): string
    {
        $fontFile = __DIR__ . '/../../public/assets/fonts/calibri.ttf';

        // Create image with the specified size
        $image = imagecreatetruecolor($size, $size);

        // Set background color
        $backgroundColor = imagecolorallocate(
            $image,
            rand(0, 255),
            rand(0, 255),
            rand(0, 255)
        );

        // Fill background with the selected color
        imagefill($image, 0, 0, $backgroundColor);

        // Set text color to white
        $textColor = imagecolorallocate($image, 255, 255, 255);

        // Set font size
        $fontSize = $size / 2;

        // Get the bounding box of the text
        $bbox = imagettfbbox($fontSize, 0, $fontFile, $initials);

        // Calculate the width and height of the bounding box
        $textWidth = $bbox[2] - $bbox[0];
        $textHeight = $bbox[3] - $bbox[5];

        // Calculate the horizontal and vertical position of the text to center it
        $textX = ($size - $textWidth) / 2;
        $textY = ($size + $textHeight) / 2;

        // Add the text to the image
        imagettftext(
            $image,
            $fontSize,
            0,
            $textX,
            $textY,
            $textColor,
            $fontFile,
            $initials
        );

        // Create a unique filename
        $filename = uniqid() . '.png';

        // Create the path to the file
        $filepath = $this->params->get('images_directory');

        // If the directory does not exist, create it
        if (!file_exists($filepath)) {
            mkdir($filepath, 0755, true);
        }

        // Save the image to the specified path
        imagepng($image, $filepath . '/' . $filename);

        // Free memory
        imagedestroy($image);

        // Return the File
        return $filename;
    }
}
