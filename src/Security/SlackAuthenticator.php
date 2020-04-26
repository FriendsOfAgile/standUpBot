<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2020-04-21
 * Time: 23:36
 */

namespace App\Security;


use App\Entity\Member;
use App\Entity\User;
use App\Service\SlackSpaceService;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\SlackClient;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class SlackAuthenticator extends SocialAuthenticator
{
    /**
     * @var ClientRegistry
     */
    private $clientRegistry;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var SlackSpaceService
     */
    private $spaceService;

    public function __construct(
        ClientRegistry $clientRegistry,
        EntityManagerInterface $em,
        RouterInterface $router,
        SlackSpaceService $spaceService
    )
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
        $this->spaceService = $spaceService;
    }

    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'connect_slack_check';
    }

    public function getCredentials(Request $request)
    {
        return $this->fetchAccessToken($this->getSlackClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $slackUser = $this->getSlackClient()->fetchUserFromToken($credentials);

        $existingUser = $this->em->getRepository(User::class)
            ->findOneBy(['uid' => $slackUser->getId()]);

        if ($existingUser)
            return $existingUser;

        $slackUserData = $slackUser->toArray();
        $teamId = $slackUserData['user']['team_id'];
        if (!$teamId)
            return null;

        if (!$space = $this->spaceService->pullSpaceData($teamId, $credentials))
            throw new \Exception('Can\'t get space data');

        $timeZone = $slackUserData['user']['tz'] ?: null;

        $user = new User();
        $user->setName($slackUser->getRealName())
            ->setUid($slackUser->getId())
            ->setAvatar($slackUser->getImage48())
            ->setEmail($slackUser->getEmail())
            ->setTimeZone($timeZone)
            ->setSpace($space);

        $this->em->persist($space);
        $this->em->persist($user);
        $this->em->flush();
        return $user;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strstr($exception->getMessageKey(), $exception->getMessage());
        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        $targetUrl = $this->router->generate('home');
        return new RedirectResponse($targetUrl);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse('/login', Response::HTTP_TEMPORARY_REDIRECT);
    }

    /**
     * @return SlackClient
     */
    private function getSlackClient()
    {
        /** @var SlackClient $client */
        $client = $this->clientRegistry->getClient('slack');
        return $client;
    }
}