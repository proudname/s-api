<?php

namespace App\Controller;

use App\Entity\Upload;
use App\Form\UploadType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FrontendController extends AbstractController
{
    /**
     * @Route("/frontend/upload", name="frontend_upload")
     */
    public function upload()
    {
        $upload = new Upload();
        $form = $this->createForm(UploadType::class, $upload);
        return $this->render('frontend/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/frontend/status", name="frontend_status")
     */
    public function status(Request $request)
    {
        $status = 'Id not set';
        if ($id = $request->get('id')) {
            $db = $this->getDoctrine()->getManager();
           if ($upload = $db->find(Upload::class, $id)) {
               $status = $upload->getStatus();
           }
           else {
               $status = 'Id not found';
           }
        }
        return $this->render('frontend/status.html.twig', [
            'status' => $status,
        ]);
    }
    /**
     * @Route("/frontend/upload", name="frontend_download")
     */
    public function download(Request $request)
    {
        $status = 'Id not set';
        $path = '';
        if ($id = $request->get('id')) {
            $db = $this->getDoctrine()->getManager();
            if ($upload = $db->find(Upload::class, $id)) {
                $status = 1;
                $path = $upload->getPath();
            }
            else {
                $status = 'Id not found';
            }
        }
        return $this->render('frontend/status.html.twig', [
            'status' => $status,
            'path' => $path
        ]);
    }
}
