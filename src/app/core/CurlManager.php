<?php

namespace App\core;

class CurlManager
{
    private array $options;

    public function __construct()
    {
        $this->options = [
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer ' . $_ENV['GOREST_API_KEY']
            ],
            CURLOPT_TIMEOUT => 10,
            CURLOPT_RETURNTRANSFER => true
        ];
    }

    public function get(string $url)
    {
        $curl = curl_init($url);
        curl_setopt_array($curl, $this->options);

        $records = curl_exec($curl);
        curl_close($curl);

        return json_decode($records, true);
    }

    public function post(string $url, string $data)
    {
        $curl = curl_init($url);
        curl_setopt_array($curl, $this->options);


        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $records = curl_exec($curl);
        curl_close($curl);

        return json_decode($records, true);
    }

    public function patch(string $url, string $data)
    {
        $curl = curl_init($url);
        curl_setopt_array($curl, $this->options);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $records = curl_exec($curl);
        curl_close($curl);

        return json_decode($records, true);
    }

    public function delete(string $url)
    {
        $curl = curl_init($url);
        curl_setopt_array($curl, $this->options);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');

        curl_exec($curl);
        curl_close($curl);
    }
}