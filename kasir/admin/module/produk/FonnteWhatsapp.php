<?php

class FonnteWhatsapp
{
    protected $device_token;

    const ENDPOINTS = [
        'send_message' => 'https://api.fonnte.com/send',
    ];

    public function __construct($token)
    {
        $this->device_token = $token;
    }

    protected function makeRequest($endpoint, $params = [])
    {
        if (!$this->device_token) {
            return ['status' => false, 'error' => 'API token is required.'];
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: ' . $this->device_token,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return ['status' => false, 'error' => 'Curl Error: ' . $error];
        }

        curl_close($ch);
        $json = json_decode($response, true);

        if ($httpCode !== 200 || (isset($json['status']) && $json['status'] !== true)) {
            return [
                'status' => false,
                'error'  => $json['reason'] ?? 'Unknown error',
                'response' => $json,
            ];
        }

        return ['status' => true, 'data' => $json];
    }

    public function sendMessage($phoneNumber, $message)
    {
        return $this->makeRequest(self::ENDPOINTS['send_message'], [
            'target'  => $phoneNumber,
            'message' => $message,
        ]);
    }
}