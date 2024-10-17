<?php

namespace App\Client;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiClient
{
    private $commands = [
        'UserGet' => [
            'method' => 'GET',
            'uri' => 'users/{id}',
        ],
        'UserAdd' => [
            'method' => 'POST',
            'uri' => 'users',
        ],
        'UserUpdate' => [
            'method' => 'PATCH',
            'uri' => 'users/{id}',
        ],
        'UserDelete' => [
            'method' => 'DELETE',
            'uri' => 'users/{id}',
        ],
        'GroupGet' => [
            'method' => 'GET',
            'uri' => 'groups/{id}',
        ],
        'GroupAdd' => [
            'method' => 'POST',
            'uri' => 'groups',
        ],
        'GroupUpdate' => [
            'method' => 'PATCH',
            'uri' => 'groups/{id}',
        ],
        'GroupDelete' => [
            'method' => 'DELETE',
            'uri' => 'groups/{id}',
        ],
        'GroupReport' => [
            'method' => 'GET',
            'uri' => 'groups',
        ],
        'AddUserToGroup' => [
            'method' => 'POST',
            'uri' => 'groups/{id}/add',
        ],
        'DeleteUserFromGroup' => [
            'method' => 'DELETE',
            'uri' => 'groups/{id}/delete',
        ],
    ];
    private $host = "http://127.0.0.1:8000/";
    public $debug = true;
    
    public function __construct(
        private HttpClientInterface $client,
        private LoggerInterface $logger,
    ) {
    }

    public function __call($name, $arguments) {
        if (in_array($name, array_keys($this->commands))) {
            return call_user_func_array(function($args) use ($name) {
                $body = $args['body'] ?? '';
                $params = $args['params'] ?? [];
                $add_headers = $args['headers'] ?? [];
                $method = $this->commands[$name]['method'];
                $url = $this->host . $this->commands[$name]['uri'];
                if (isset($args['subst'])) {
                  $url = str_replace(array_keys($args['subst']), array_values($args['subst']), $url);
                }
                if (!is_null($params)) {
                    $c = (strpos($url, '?') !== false) ? '&' : '?';
                    foreach ($params as $k => $v) {
                      if (is_array($v)) {
                        foreach ($v as $subv) {
                          $url .= $c . $k . '[]=' . $subv;
                          $c = '&';
                        }
                      }
                      else {
                        $url .= $c . $k . '=' . $v;
                      }
                      $c = '&';
                    }
                }
                $headers = [
                    "Content-type: " . ($method == 'PATCH' ? "application/merge-patch+json" : "application/ld+json"),
                ];
                if (!is_null($add_headers)) {
                    $headers = array_merge($headers, $add_headers);
                }
                if ($this->debug) {
                    $this->logger->debug("Request to $url");
                    $this->logger->debug('Request body:\n', ['body' => $body]);
                }
                try {
                  $response = $this->client->request($method, $url, [
                      'json' => $body,
                      'headers' => $headers,
                  ]);
                }
                catch (\Exception $e) {
                    $this->logger->error($e->getMessage());
                    throw new ApiClientException($e->getMessage(), $e->getCode());
                }
                $res = (string)$response->getContent();
                if ($this->debug) {
                    $this->logger->debug('Response code: ' . $response->getStatusCode());
                    $this->logger->debug('Response body:\n', ['body' => json_encode(json_decode($res), JSON_PRETTY_PRINT)]);
                }
                if (preg_match('/^2\d{2}$/', $response->getStatusCode())) {
                    return json_decode($res);
                }
                throw new ApiClientException($res, $response->getStatusCode());
            }, $arguments);
        }
        else {
            throw new InvalidMethodException("Method $name is not defined in ApiClient class");
        }
    }
}