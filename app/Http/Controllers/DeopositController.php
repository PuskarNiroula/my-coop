<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeopositController extends AbstractController
{
    #[Route('/deoposit')]
    public function index(): Response
    {
        return $this->render('deoposit/index.html.twig');
    }
}
