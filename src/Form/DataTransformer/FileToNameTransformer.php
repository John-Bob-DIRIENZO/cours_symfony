<?php

namespace App\Form\DataTransformer;

use App\Service\UploadHelper;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileToNameTransformer implements DataTransformerInterface
{
    private UploadHelper $helper;

    public function __construct(UploadHelper $helper)
    {
        $this->helper = $helper;
    }

    public function reverseTransform($value): ?string
    {
        if ($value === null) {
            return '';
        }

        if (!$value instanceof UploadedFile) {
            throw new \LogicException('Vous êtes censé passer un fichier...');
        }

        return $this->helper->uploadQuestionImage($value);
    }

    public function transform($value)
    {
        if (!$value) {
            return null;
        }
        return $this->helper->filenameToFile($value);
    }

}