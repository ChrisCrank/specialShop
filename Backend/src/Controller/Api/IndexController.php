<?php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/api/index/index')]
    public function index(): Response
    {
        $number = random_int(0, 100);

        return $this->json([
            'active' => true
        ]);
    }
}
