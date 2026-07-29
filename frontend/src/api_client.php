<?php

function api_base_url(): string
{
    return getenv('API_BASE_URL') ?: 'http://******';
}

function api_request(string $method, string $path, ?array $data = null): array
{
    $url = api_base_url() . $path;

    $headers = [
        'Content-Type: application/json',
        'Accept: application/json',
    ];

    $options = [
        'http' => [
            'method' => $method,
            'header' => implode("\r\n", $headers),
            'ignore_errors' => true,
        ],
    ];

    if ($data !== null) {
        $options['http']['content'] = json_encode($data);
    }

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    $statusLine = $http_response_header[0] ?? 'HTTP/1.1 500';
    preg_match('{HTTP/\S+\s(\d{3})}', $statusLine, $match);
    $statusCode = isset($match[1]) ? (int) $match[1] : 500;

    $body = json_decode($response ?: '[]', true);

    return [
        'status' => $statusCode,
        'body' => $body,
    ];
}