<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2020-04-22
 * Time: 00:10
 */

namespace App\Service;


use App\Entity\Channel;
use App\Entity\StandUp;
use App\Entity\User;
use App\Traits\LoggerTrait;
use GuzzleHttp\Client;

class SlackService
{
    use LoggerTrait;

    const BASE_URL = 'https://slack.com/api/';

    /** @var string|null */
    private $accessToken;

    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @param string|null $accessToken
     * @return SlackService
     */
    public function setAccessToken(?string $accessToken): self
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @param string|null $teamId
     * @return array|null
     * @throws \Exception
     */
    public function getTeamInfo(string $teamId = null)
    {
        $options = array();
        if ($teamId)
            $options['team'] = $teamId;
        return $this->get('team.info', $options);
    }

    /**
     * @return array|null
     * @throws \Exception
     */
    public function getUsers(): ?array
    {
        $response = $this->get('users.list');
        if (!$response['ok'])
            return null;
        $result = array();
        foreach($response['members'] as $member) {
            if($member['name'] == 'slackbot' || $member['deleted'] || $member['is_bot'])
                continue;
            $result[] = array(
                'uid' => $member['id'],
                'name' => $member['real_name'],
                'timeZone' => $member['tz'],
                'email' => $member['profile']['email'],
                'avatar' => $member['profile']['image_48']
            );
        }
        return $result;
    }

    /**
     * @return Channel[]|null
     * @throws \Exception
     */
    public function listChannels(): ?array
    {
        $response = $this->get('channels.list');
        if (!$response['ok'])
            return null;
        $result = array();

        foreach ($response['channels'] as $item) {
            $result[] = array(
                'code' => $item['id'],
                'name' => $item['name'],
                'type' => 'slack'
            );
        }

        return $result;
    }

    /**
     * @param string $uid
     * @return array|null
     * @throws \Exception
     */
    public function getUser(string $uid): ?array
    {
        $response = $this->get('users.info', array(
            'user' => $uid
        ));
        if (!$response['ok'])
            return null;
        $result = null;

        if ($member = $response['user']) {
            $result = array(
                'uid' => $member['id'],
                'name' => $member['real_name'],
                'timeZone' => $member['tz'],
                'email' => $member['profile']['email'],
                'avatar' => $member['profile']['image_48']
            );
        }
        return $result;
    }

    /**
     * @param User $user
     * @param string $message
     * @return bool
     * @throws \Exception
     */
    public function postMessage(User $user, string $message): bool
    {
        $response = $this->get('chat.postMessage', array(
            'channel' => $user->getUid(),
            'text' => $message,
            'as_user' => true
        ));
        return $response['ok'] ?? false;
    }

    /**
     * @param StandUp $standUp
     * @return bool
     * @throws \Exception
     */
    public function postStandUp(StandUp $standUp): bool
    {
        $user = $standUp->getUser();

        $blocks = array();
        foreach($standUp->getAnswers() as $answer) {
            $text = sprintf("*%s*\n%s", $answer->getQuestion(), $answer->getAnswer());
            $blocks[] = array(
                'color' => $answer->getColor() == 'gray' || !$answer->getColor() ? '#c0c0c0' : $answer->getColor(),
                'blocks' => array([
                    'type' => 'section',
                    'text' => array(
                        'type' => 'mrkdwn',
                        'text' => $text
                    )
                ])
            );
        }

        $target = $standUp->getConfig()->getChannel() ?
            $standUp->getConfig()->getChannel()->getCode() : $user->getUid();

        $params = array(
            'channel' => $target,
            'text' => sprintf('%s posted update for %s stand-up', $user->getName(), $standUp->getConfig()->getName()),
            'as_user' => false,
            'icon_url' => $user->getAvatar(),
            'username' => $user->getName(),
            'attachments' => json_encode($blocks, JSON_UNESCAPED_SLASHES)
        );

        $response = $this->get('chat.postMessage', $params);
        if (!$result = $response['ok'] ?? false)
            $this->logger->error('Error on card post', array(
                'params' => $params
            ));
        return $result;
    }

    /**
     * @param string $endpoint
     * @param string $method
     * @param array $data
     * @return array|null
     * @throws \Exception
     */
    protected function request(string $endpoint, string $method = 'GET', array $data = array()): ?array
    {
        if (!$this->accessToken)
            throw new \Exception('Token required');

        $data['token'] = $this->accessToken;

        $client = new Client(array(
            'base_uri' => self::BASE_URL,
        ));

        if ($method == 'POST') {
            $options = array(
                'headers' => array(
                    'Content-Type' => 'application/json'
                )
            );
        } else {
            $options = array(
                'headers' => array(
                    'Content-Type' => 'application/x-www-form-urlencoded'
                )
            );
        }

        if ($data != null && $method == 'POST')
            $options['json'] = $data;
        elseif ($data != null && $method == 'GET')
            $options['query'] = $data;

        $response = $client->request($method, $endpoint, $options);
        $response = json_decode($response->getBody()->getContents(), true);
        if (!is_array($response) || $response['ok'] === false) {
            return null;
        }

        return $response;
    }

    /**
     * @param string $endpoint
     * @param array $query
     * @return array|null
     * @throws \Exception
     */
    protected function get(string $endpoint, array $query = array()): ?array
    {
        return $this->request($endpoint, 'GET', $query);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return array|null
     * @throws \Exception
     */
    protected function post(string $endpoint, array $data): ?array
    {
        return $this->request($endpoint, 'POST', $data);
    }
}