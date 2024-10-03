<?php
namespace Zoop\Core;

/**
 * ZoopConfig class
 * 
 * @method Zoop/Core/ZoopConfig::configure(string $token, string $marketplace, string $vendedor)
 * 
 * @package Zoop/Core
 * @author thiago@nerdetcetera.com
 * @version 1.6
 */
class ZoopConfig
{
    public static function configure($token, $marketplace, $vendedor)
    {
        $configurations = [
            'marketplace' => $marketplace,
            'gateway' => 'zoop',
            'base_url' => 'https://api.zoop.ws',
            'auth' => [
                'on_behalf_of' => $vendedor,
                'token' => $token
            ],
            'configurations' => [
                'limit' => 20,
                'sort' => 'time-descending',
                'offset' => 0,
                'date_range' => null,
                'date_range[gt]' => null,
                'date_range[gte]' => null,
                'date_range[lt]'=> null,
                'date_range[lte]' => null,
                'reference_id'=> null,
                'status' => null,
                'payment_type' => null,
            ],
            'guzzle' => [
                'base_uri' => 'https://api.zoop.ws',
                'timeout' => 10,
                'headers' => [
                    'Authorization' => 'Basic ' . \base64_encode($token . ':')
                ]
            ]
        ];
        return self::ClientHelper($configurations);
    }

    private static function ClientHelper(array $configurations)
    {
        $client = $configurations['guzzle'];
        unset($configurations['guzzle']);
        $configurations['guzzle'] = new \GuzzleHttp\Client($client);
        return $configurations;
    }
}