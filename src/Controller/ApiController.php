<?php

namespace App\Controller;

use App\Entity\Upload;
use App\Form\UploadType;
use App\Service\Worker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/upload", name="api_upload")
     */

    public function upload(Request $request)
    {
        $upload = new Upload();
        $form = $this->createForm(UploadType::class, $upload);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $db = $this->getDoctrine()->getManager();
            $upload
                ->setIsComplete(0)
                ->setStatus('Подготовка к обработке');
            $db->persist($upload);
            $db->flush();
            $url = $this->generateUrl('api_worker', ['id' => $upload->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
            $process = new Process("curl $url > /dev/null 2>&1 &");
            $process->run();

           return $this->json([
                'response' => 'success',
                'uploadId' => $upload->getId()
            ]);
        }
        return $this->json([
            'response' => 'failed',
            'error_message' => 'Url parameters not found'
        ]);

    }


    /**
     * @Route("/status", name="api_status")
     */

    public function check(Request $request) {
        $db = $this->getDoctrine()->getManager();
        if ($id = $request->get('id')) {
            if ($upload = $db->find(Upload::class, $id)) {
                if ($upload->getIsComplete() === 1) {
                    return $this->json([
                        'response' => 'success',
                        'status' => 'complete',
                        'is_end' => 1
                    ]);
                }

                return $this->json([
                    'response' => 'success',
                    'status' => $upload->getStatus()
                ]);
            }

            return $this->json([
                'response' => 'failed',
                'error_message' => 'Id is not found in database'
            ]);
        }
        return $this->json([
            'response' => 'failed',
            'error_message' => 'Id is not defined'
        ]);

    }


    /**
     * @Route("/download", name="api_download")
     */

    public function download(Request $request) {
        $db = $this->getDoctrine()->getManager();
        if ($id = $request->get('id')) {

           if ($upload = $db->find(Upload::class, $id)) {
               return $this->json([
                   'response' => 'success',
                   'path' => $upload->getPath()
               ]);
           }

            return $this->json([
                'response' => 'failed',
                'error_message' => 'Id is not fount in database'
            ]);
        }
        return $this->json([
            'response' => 'failed',
            'error_message' => 'Id is not defined'
        ]);

    }

    /**
     * @Route("/worker", name="api_worker")
     */

public function worker(Request $request) {
    $db = $this->getDoctrine()->getManager();
    $root_dir = $this->getParameter('kernel.project_dir');
    $worker = new Worker($db, $root_dir, $request->get('id'));
    $worker->work();
    return new Response(1);
}

}
