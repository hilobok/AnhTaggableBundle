<?php

namespace Anh\TaggableBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function searchAction(Request $request)
    {
        return new JsonResponse($this->container->get('anh_taggable.manager')
            ->getTagRepository()
            ->search($request->query->get('term', ''), false, 'name', 100)
        );
    }
}
