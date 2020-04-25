<?php

namespace App\Controller;

use App\Entity\Member;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AuthController extends AbstractController
{
    /**
     * @Route("/login", methods={"GET", "POST"}, name="login")
     */
    public function login()
    {
        if ($this->getUser())
            return $this->redirectToRoute('home');

        $availableServices = array(
            'Slack' => array(
                'image' => '/images/slack-logo.png',
                'route' => 'connect_slack_start',
            ),
            'MS Teams' => array(
                'image' => '/images/ms-teams.png',
                'route' => null
            ),
            'Discord' => array(
                'image' => '/images/discord-logo.png',
                'route' => null
            )
        );

        return $this->render("auth/index.html.twig", [
            'services' => $availableServices
        ]);
    }

    /**
     * @Route("/connect/slack", name="connect_slack_start")
     */
    public function connectSlack(ClientRegistry $clientRegistry)
    {
        $scopes = array(
            'bot',
            'channels:read',
            'team:read',
            'users:read',
            'users:read.email'
        );
        return $clientRegistry->getClient('slack')->redirect($scopes, []);
    }

    /**
     * @Route("/connect/slack/check", name="connect_slack_check")
     */
    public function connectSlackCheck(Request $request, ClientRegistry $clientRegistry)
    {
        if ($this->getUser())
            return $this->redirect('/dashboard');
        return $this->redirect('/login');
    }

    /**
     * @Route("/api/members", methods={"GET"})
     */
    public function members(SerializerInterface $serializer)
    {
        $items = $this->getDoctrine()->getRepository(Member::class)->findAll();

        return $this->json($serializer->serialize($items, 'json'));
    }
}
