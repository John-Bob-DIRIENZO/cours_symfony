<?php

namespace App\Service;

use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadHelper
{
    const QUESTION_IMAGE = 'uploads/questions_images';
    const DEFAULT_IMAGE = 'images/cute_cat.jpg';

    private string $publicPath;
    private FilesystemOperator $defaultStorage;

    public function __construct(string $publicPath, FilesystemOperator $defaultStorage)
    {
        $this->publicPath = $publicPath;
        $this->defaultStorage = $defaultStorage;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function uploadQuestionImage(UploadedFile $file, string $existingFilename = null): string
    {
        //$destination = $this->publicPath . '/' . self::QUESTION_IMAGE;
        $originalFileName = $file->getClientOriginalName();
        $baseFileName = pathinfo($originalFileName, PATHINFO_FILENAME);
        $fileName = Urlizer::urlize($baseFileName) . '-' . uniqid() . '.' . $file->guessExtension();
        //$file->move($destination, $fileName);

        if ($existingFilename) {
            $this->defaultStorage->delete(self::QUESTION_IMAGE . '/' . $existingFilename);
        }

        $stream = fopen($file->getPathname(), 'r');
        $this->defaultStorage->writeStream(
            self::QUESTION_IMAGE . '/' . $fileName,
            $stream
        );
        if (is_resource($stream)) {
            fclose($stream);
        }

        return $fileName;
    }

    /**
     * @param File $file
     * @return string
     */
    public function fixtureUpload(File $file): string
    {
        // $destination = $this->publicPath . '/' . self::QUESTION_IMAGE;
        $originalFileName = $file->getFilename();
        $baseFileName = pathinfo($originalFileName, PATHINFO_FILENAME);
        $fileName = Urlizer::urlize($baseFileName) . '-' . uniqid() . '.' . $file->guessExtension();

        // Je crée un stream
        $stream = fopen($file->getPathname(), 'r');
        $this->defaultStorage->writeStream(
            self::QUESTION_IMAGE . '/' . $fileName,
            $stream
        );

        // Et je n'oublie pas de le fermer après
        if (is_resource($stream)) {
            fclose($stream);
        }

        // $fs = new Filesystem();
        // $fs->copy($file->getRealPath(), $destination . '/' .$fileName, true);

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