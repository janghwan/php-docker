<?php

require_once(__DIR__ . '/vendor/autoload.php');

use GuzzleHttp\Client;
use PHPHtmlParser\Dom;

function findLastRepo($prefix)
{
    $client = new Client(['base_uri' => 'https://packages.cloud.google.com']);
    $response = $client->request('GET', '/apt/dists');
    $body = $response->getBody();

    $dom = new Dom();
    $dom->load($body);
    $links = $dom->find('a[href]');

    $repos = [];
    foreach ($links as $link) {
        $href = $link->getAttribute('href');
        if (preg_match("/^{$prefix}-(\d+)-(\d+)/", $href)) {
            $repos[] = $href;
        }
    }
    return $repos[count($repos) - 1];
}

$options = getopt('p:');
$options += [
    'p' => 'gcp-php-runtime-jessie'
];
$lastRepo = findLastRepo($options['p']);
echo $lastRepo;
