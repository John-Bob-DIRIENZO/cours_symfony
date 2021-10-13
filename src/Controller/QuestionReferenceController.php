<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\QuestionReference;
use App\Service\UploadHelper;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class QuestionReferenceController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UploadHelper $helper
     * @Route("admin/question/references/{id}/upload", name="app_upload_question_reference", methods={"POST"})
     */
    public function uploadReference(Question               $question,
                                    Request                $request,
                                    EntityManagerInterface $entityManager,
                                    UploadHelper           $helper,
                                    ValidatorInterface     $validator)
    {
        /** @var UploadedFile $file */
        $file = $request->files->get('reference');

        $violations = $validator->validate($file, [
            new NotBlank(),
            new File([
                'mimeTypes' => [
                    'image/*',
                    'application/pdf',
                ]
            ])
        ]);

        if ($violations->count() > 0) {
            $this->addFlash('error', $violations[0]->getMessage());
        } else {
            $filename = $helper->uploadPrivateFile($file);
            $originalFilename = $file->getClientOriginalName();
            $questionReference = new QuestionReference();
            $questionReference->setQuestion($question)
                ->setFilename($filename)
                ->setOriginalFilename($originalFilename)
                ->setMimeType($file->getMimeType() ?? 'application/octet-stream');

            $entityManager->persist($questionReference);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_question_edit', [
            'slug' => $question->getSlug()
        ]);
    }

    /**
     * @Route("admin/question/references/{id}/download", name="app_download_question_reference", methods={"GET"})
     * @IsGranted("ROLE_ADMIN_QUESTION")
     */
    public function downloadReference(QuestionReference $reference, UploadHelper $helper)
    {
        $response = new StreamedResponse();
        $response->setCallback(function () use ($reference, $helper) {
            $outputStream = fopen('php://output', 'wb');
            $fileStream = $helper->readPrivateStream($reference->getFilePath());

            stream_copy_to_stream($fileStream, $outputStream);
        });
        $response->headers->set('Content-Type', $reference->getMimeType());
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_INLINE,
            Urlizer::urlize($reference->getOriginalFilename())
        );
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}
