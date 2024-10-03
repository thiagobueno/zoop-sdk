<?php
namespace Zoop\Resources\MarketPlace;

use Zoop\Zoop;
/**
 * Class Sellers
 * 
 * Essa classe é resposavel por lidar com os usuarios
 * dentro do marketplace ao nivel do marketplace zoop.
 * 
 * @package Zoop\Resources\MarketPlace
 * @author Thiago - thiago@nerdetcetera.com
 * @version 1.6.3
 */
class Sellers extends Zoop
{
    public function __construct(array $configurations)
    {
        parent::__construct($configurations);
    }

    /**
     * getSeller function
     *
     * Pega os dados de um vendedor utilizando seu id
     * como paramtro.
     *
     * @param string|int $sallerId
     *
     * @return void
     * @throws \Exception
     */
    public function getSeller($sallerId)
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/sellers/'. $sallerId
            );
            $response = \json_decode($request->getBody()->getContents(), true);
            if($response && is_array($response)){
                return $response;
            }
            return false;
        } catch (\Exception $e){            
            return $this->ResponseException($e);
        }
    }

    /**
     * getAllSellers function
     *
     * Lista todos os vendedores do marketplace
     * 
     * @throws \Exception
     * @return array|void
     */
    public function getAllSellers()
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/sellers'
            );
            $response = \json_decode($request->getBody()->getContents(), true);
            if($response && is_array($response)){
                return $response;
            }
            return false;
        } catch (\Exception $e){            
            return $this->ResponseException($e);
        }
    }
}