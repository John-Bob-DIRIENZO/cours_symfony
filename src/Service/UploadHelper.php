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
    const QUESTION_REFERENCE = 'question_reference';

    private string $publicPath;
    private FilesystemOperator $defaultStorage;
    private FilesystemOperator $privateStorage;

    public function __construct(string $publicPath, FilesystemOperator $defaultStorage, FilesystemOperator $privateStorage)
    {
        $this->publicPath = $publicPath;
        $this->defaultStorage = $defaultStorage;
        $this->privateStorage = $privateStorage;
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
     * @param File $file
     * @return string
     */
    public function uploadPrivateFile(File $file): string
    {
        // Presque rien ne change
        $originalFileName = $file->getFilename();
        $baseFileName = pathinfo($originalFileName, PATHINFO_FILENAME);
        $fileName = Urlizer::urlize($baseFileName) . '-' . uniqid() . '.' . $file->guessExtension();

        $stream = fopen($file->getPathname(), 'r');
        // Sauf le lieu de stockage
        $this->privateStorage->writeStream(
            self::QUESTION_REFERENCE . '/' . $fileName,
            $stream
        );

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $fileName;
    }

    /**
     * @param string $path
     * @return resource
     * @throws \League\Flysystem\FilesystemException
     */
    public function readPrivateStream(string $path)
    {
        return $this->privateStorage->readStream($path);
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