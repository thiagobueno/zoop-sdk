<?php
namespace Zoop\Resources\Transfers;

use Zoop\Zoop;
/**
 * Class Transfers
 * 
 * Essa classe é responsável por listar as transferênscias do usuário
 * 
 * @package Zoop\Transfers
 * @author thiago@nerdetcetera.com
 * @version 1.3
 */
class Transfers extends Zoop
{
    public function __construct(array $configurations)
    {
        parent::__construct($configurations);
    }

    /**
     * getTransfers function
     *
     * Listar transferências por seller
     *
     * @param string|int $sellerId
     *
     * @return void
     * @throws \Exception
     */
    public function getTransfers($sellerId)
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/sellers/'. $sellerId .'/transfers'
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
     * getAllTransfers function
     *
     * Listar transferências por marketplace
     * 
     * @throws \Exception
     * @return array|void
     */

    public function getAllTransfers()
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/transfers'
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
     * getTransfer function
     *
     * Recuperar detalhes de transferência
     *
     * @param string|int $transferId
     *
     * @return void
     * @throws \Exception
     */
    public function getTransfer($transferId)
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/transfers/'. $transferId
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
     * getTransactions function
     *
     * Listar transações associadas a transferência
     *
     * @param string|int $transferId
     *
     * @return void
     * @throws \Exception
     */
    public function getTransactions($transferId)
    {
        try {
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/transfers/'. $transferId .'/transactions'
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