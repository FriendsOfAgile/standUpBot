<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2020-04-22
 * Time: 00:10
 */

namespace App\Service;


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
     */
    public function getTeamInfo(string $teamId = null)
    {
        $options = array();
        if ($teamId)
            $options['team'] = $teamId;
        return $this->get('team.info', $options);
    }

    protected function request(string $endpoint, string $method = 'GET', array $data = array()): ?array
    {
        if (!$this->accessToken)
            throw new \Exception('Token required');

        $data['token'] = $this->accessToken;

        $client = new Client(array(
            'base_uri' => self::BASE_URL,
        ));

        $options = array(
            'headers' => array(
                'Content-Type' => 'application/x-www-form-urlencoded'
            )
        );

        if ($data != null && $method == 'POST')
            $options['form_params'] = $data;
        elseif ($data != null && $method == 'GET')
            $options['query'] = $data;


        $response = $client->request($method, $endpoint, $options);
        $response = json_decode($response->getBody()->getContents(), true);
        if (!is_array($response) || $response['ok'] === false)
            return null;

        return $response;
    }

    protected function get(string $endpoint, array $query = array()): ?array
    {
        return $this->request($endpoint, 'GET', $query);
    }

    protected function post(string $endpoint, array $data): ?array
    {
        return $this->request($endpoint, 'POST', $data);
    }
}