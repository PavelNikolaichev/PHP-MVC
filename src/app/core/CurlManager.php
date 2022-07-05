<?php

namespace App\core;

class CurlManager
{
    private array $options;
    private array $operation_options;

    public function __construct()
    {
        $this->options = [
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer ' . $_ENV['GOREST_API_KEY']
            ],
            CURLOPT_TIMEOUT => 3,
            CURLOPT_RETURNTRANSFER => true
        ];
        $this->operation_options = [
            'post' => [
                CURLOPT_POST => true
            ],
            'patch' => [
                CURLOPT_POST => true,
                CURLOPT_CUSTOMREQUEST => 'PATCH'
            ],
            'delete' => [
                CURLOPT_CUSTOMREQUEST => 'DELETE'
            ]
        ];
    }

    /**
     * Method to run operation, basically works as a simple wrapper/decorator.
     * @param string $operation - operation to be run.
     * @param string $url - url to run operation on.
     * @param string $data - JSON encoded data.
     * @return mixed - Decoded JSON response from the server.
     * @throws \Exception
     */
    private function run_operation(string $operation, string $url, string $data = ''): mixed
    {
        # Initialize curl
        $curl = curl_init($url);

        curl_setopt_array($curl, $this->options);

        if (isset($this->operation_options[$operation])) {
            curl_setopt_array($curl, $this->operation_options[$operation]);
        }

        if ($data) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        # Run operation
        $response = curl_exec($curl);

        # Handle errors
        if (false === $response) {
            throw new \Exception(curl_error($curl));
        }
        if (isset($response['message'])) {
            throw new \Exception($response['message']);
        }

        curl_close($curl);

        return json_decode($response, true);
    }

    /**
     * Method to send GET request.
     * @param string $url
     * @return mixed
     * @throws \Exception
     */
    public function get(string $url)
    {
        return $this->run_operation('get', $url);
    }

    /**
     * Method to send POST request.
     * @param string $url
     * @param string $data
     * @return mixed
     * @throws \Exception
     */
    public function post(string $url, string $data)
    {
        return $this->run_operation('post', $url, $data);
    }

    /**
     * Method to send PATCH request.
     * @param string $url
     * @param string $data
     * @return mixed
     * @throws \Exception
     */
    public function patch(string $url, string $data)
    {
        return $this->run_operation('patch', $url, $data);
    }

    /**
     * Method to send DELETE request.
     * @param string $url
     * @return void
     * @throws \Exception
     */
    public function delete(string $url)
    {
        $this->run_operation('delete', $url);
    }
}