<?php

declare(strict_types=1);

namespace App\Helper;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class QuestionHelper
{
    
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
      public function getRandomQuestions(): array
      {
           $url = 'https://api.stackexchange.com/2.3/questions?site=stackoverflow';

           $curl = curl_init();
           curl_setopt_array($curl, [
               CURLOPT_URL => $url,
               CURLOPT_CUSTOMREQUEST => 'GET',
               CURLOPT_RETURNTRANSFER => true,
           ]);

          $rawResponse = curl_exec($curl);
          $info = curl_getinfo($curl);
          curl_close($curl);

          if ($info['http_code'] !== 200) {
              return [];
          }

          $response = json_decode($rawResponse, true);

          return $response['value'];
     }
}