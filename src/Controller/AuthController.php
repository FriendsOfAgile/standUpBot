<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    /**
     * @Route("/auth", name="auth")
     */
    public function index()
    {
        return $this->render('auth/index.html.twig', [
            'controller_name' => 'AuthController',
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
            'users:read'
        );
        return $clientRegistry->getClient('slack')->redirect($scopes, []);
    }

    /**
     * @Route("/connect/slack/check", name="connect_slack_check")
     */
    public function connectSlackCheck(Request $request, ClientRegistry $clientRegistry)
    {
        $client = $clientRegistry->getClient('slack');

        try {
            $user = $client->fetchUser();
            dump($user);
        } catch (IdentityProviderException $e) {
            dump($e->getMessage());
        }
        die;
    }
}
