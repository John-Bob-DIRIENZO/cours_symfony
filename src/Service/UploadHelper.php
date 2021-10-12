<?php

namespace App\Service;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadHelper
{
    const QUESTION_IMAGE = 'uploads/questions_images';
    const DEFAULT_IMAGE = 'images/cute_cat.jpg';

    private string $publicPath;

    public function __construct(string $publicPath)
    {
        $this->publicPath = $publicPath;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function uploadQuestionImage(UploadedFile $file): string
    {
        $destination = $this->publicPath . '/' . self::QUESTION_IMAGE;
        $originalFileName = $file->getClientOriginalName();
        $baseFileName = pathinfo($originalFileName, PATHINFO_FILENAME);
        $fileName = Urlizer::urlize($baseFileName) . '-' . uniqid() . '.' . $file->guessExtension();
        $file->move($destination, $fileName);

        return $fileName;
    }

    /**
     * @param File $file
     * @return string
     */
    public function fixtureUpload(File $file): string
    {
        $destination = $this->publicPath . '/' . self::QUESTION_IMAGE;
        $originalFileName = $file->getFilename();
        $baseFileName = pathinfo($originalFileName, PATHINFO_FILENAME);
        $fileName = Urlizer::urlize($baseFileName) . '-' . uniqid() . '.' . $file->guessExtension();

        $fs = new Filesystem();
        $fs->copy($file->getRealPath(), $destination . '/' .$fileName, true);

        return $fileName;
    }

    /**
     * @param string $filename
     * @return File
     */
    public function filenameToFile(string $filename): File
    {
        return new File($this->publicPath . '/' . self::QUESTION_IMAGE . '/' . $filename);
    }
}