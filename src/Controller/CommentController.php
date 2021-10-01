<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{

    /**
     * @Route("/comments/{id}/vote/{direction<up|down>}", methods="POST")
     */
    public function commentVote($id, $direction, LoggerInterface $logger)
    {
        $logger->info('Coucou toi !');
        // TODO - Utiliser l'id pour query la BDD

        if ($direction === 'up') {
            $logger->info('Vote Up');
            $voteCount = rand(7, 50);
        }
        else {
            $logger->info('Vote Down');
            $voteCount = rand(0, 5);
        }

        return $this->json([
            'votes' => $voteCount
        ]);
    }
}