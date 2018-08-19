<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 8/19/18
 * Time: 12:23 PM
 */

namespace App\Service;


use App\Entity\Upload;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Worker
{
    private $root_dir;
    private $db;
    private $uploadEntity;
    private $id;

    public function __construct(ObjectManager $manager, $root_dir, $id)
    {

        $this->root_dir = $root_dir;
        $this->db = $manager;
        $this->id = $id;
    }

    public function work() {
        $root_dir = $this->root_dir;
        $this->uploadEntity = $this->db->find(Upload::class, $this->id);
        $process = new Process(array("{$root_dir}/handler.sh", $this->uploadEntity->getPath()));
        $process->run(function ($type, $buffer) {
            $this->uploadEntity->setStatus($buffer);
            $this->db->persist($this->uploadEntity);
            $this->db->flush();
        });
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->uploadEntity->setIsComplete(1);
        $this->db->persist($this->uploadEntity);
        $this->db->flush();
    }
}