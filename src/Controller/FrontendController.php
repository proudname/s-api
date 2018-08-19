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
        $status = 'Id is not set';
        $response = 'failed';
        $successId = '';
        if ($id = $request->get('id')) {
            $db = $this->getDoctrine()->getManager();
           if ($upload = $db->find(Upload::class, $id)) {
               $status = $upload->getStatus();
               $successId = $id;
               $response = 'success';
           }
           else {
               $status = 'Id is not found';
           }
        }
        return $this->render('frontend/status.html.twig', [
            'response' => $response,
            'status' => $status,
            'id' => $successId
        ]);
    }
    /**
     * @Route("/frontend/download", name="frontend_download")
     */
    public function download(Request $request)
    {
        $status = 'Id is not set';
        $path = '';
        if ($id = $request->get('id')) {
            $db = $this->getDoctrine()->getManager();
            if ($upload = $db->find(Upload::class, $id)) {
                $status = "Успех";
                $path = $upload->getPath();
            }
            else {
                $status = 'Id is not found';
            }
        }
        return $this->render('frontend/download.html.twig', [
            'status' => $status,
            'link' => $path
        ]);
    }
}
