<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2020-05-12
 * Time: 17:08
 */

namespace App\Doctrine;


use App\Entity\Channel;
use App\Service\SlackService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class ChannelEntityListener
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var SlackService
     */
    private $service;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(Security $security, SlackService $service, EntityManagerInterface $em)
    {

        $this->security = $security;
        $this->service = $service;
        $this->em = $em;
    }

    /**
     * @param Channel $object
     */
    public function prePersist($object)
    {
        if (!$object->getId() && $object->getCode()) {
            try {
                $list = $this->service->listChannels();
                foreach ($list as $item) {
                    if ($item['code'] == $object->getCode()) {
                        $object->setName($item['name'])
                            ->setType($item['type']);
                    }
                }
            } catch (\Exception $e) {}
        }
    }

}