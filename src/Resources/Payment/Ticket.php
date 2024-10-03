<?php
namespace Zoop\Resources\Payment;

use Zoop\Core\Zoop;
/**
 * Ticket class
 * 
 * Essa classe é responsavel por gerar, hidratar com dados
 * estatiocos e extrair os dados de interesse final de pagamento
 * da zoop.
 * 
 * @package Zoop\Resources\Payment
 * @author thiago@nerdetcetera.com
 * @version 1.6
 */
class Ticket extends Zoop
{
    public function __construct(array $configurations)
    {
        parent::__construct($configurations);
    }

    /**
     * prepareTicket function
     *
     * Prepara o boleto e preenche o mesmo
     * com dados fixos utilizados na request para geração
     * de boletos.
     * 
     * @param array $ticket
     * @param string $userId
     * @return array
     */
    private function prepareTicket(array $ticket, $userId, $payment_type)
    {
        return [
            'amount' => $ticket['amount'],
            'currency' => 'BRL',
            'logo' => array_key_exists('logo', $ticket) ? $ticket['logo'] : null,
            'description' => $ticket['description'],
            'payment_type' => $payment_type,
            'payment_method' => [
                'top_instructions' => $ticket['top_instructions'],
                'body_instructions' => $ticket['body_instructions'],
                'expiration_date' => $ticket['expiration_date'],
                'payment_limit_date' => $ticket['payment_limit_date'],
                'due_at' => $ticket['due_at'],
                'payment_limit_at' => $ticket['payment_limit_at'],
                'billing_instructions' => [
                    'late_fee' => array_key_exists('late_fee', $ticket) ? $ticket['late_fee'] : null,
                    'interest' => array_key_exists('interest', $ticket) ? $ticket['interest'] : null,
                    'discount' => array_key_exists('discount', $ticket) ? $ticket['discount'] : null,
                ],
            ],
            'capture' => false,
            'on_behalf_of' => $this->configurations['auth']['on_behalf_of'],
            'source' => [
                'usage' => 'single_use',
                'type' => 'customer',
                'capture' => false,
                'on_behalf_of' => $this->configurations['auth']['on_behalf_of']
            ],
            'customer' => $userId,
        ];
    }

    /**
     * processTicket function
     *
     * Processa o boleto na Zoop, e retorna somente os dados
     * necessarios para pegar o boleto e mostrar dados de valor.
     *
     * @param array $ticket
     * @param string $userId
     * @param null|string $referenceId
     *
     * @return array|bool
     * @throws \Exception
     */
    private function processTicket(array $ticket, $userId, $referenceId = null, $payment_type = 'boleto')
    {
        if(!is_null($referenceId)){
            $ticket['reference_id'] = $referenceId;
        }
        try {
            $ticket = $this->prepareTicket($ticket, $userId, $payment_type);
            $request = $this->configurations['guzzle']->request(
                'POST', '/v1/marketplaces/'. $this->configurations['marketplace']. '/transactions', 
                ['json' => $ticket]
            );
            $response = \json_decode($request->getBody()->getContents(), true);
            if($response && is_array($response)){
                return [
                    'id' => $response['id'],
                    'ticketId' => $payment_type=='boleto' ? $response['payment_method']['id'] : $response['id'],
                    'status' => $response['status'],
                ];
            }
            return false;
        } catch (\Exception $e){            
            return $this->ResponseException($e);
        }
    }

    /**
     * generateTicket function
     *
     * Gera o boleto e retorna os dados principais
     * do mesmo, como codigo de barras, url para download
     * no s3 e mais.
     *
     * @param array $ticket
     * @param string $userId
     * @param null|string $referenceId
     * @param string $payment_type
     *
     * @return array|bool
     * @throws \Exception
     */
    public function generateTicket(array $ticket, $userId, $referenceId = null, $payment_type = 'boleto')
    {
        try {
            $generatedTicket = $this->processTicket($ticket, $userId, $referenceId, $payment_type);
            $request = $this->configurations['guzzle']->request(
                'GET', '/v1/marketplaces/'. $this->configurations['marketplace']. '/transactions/' . $generatedTicket['ticketId']
            );
            $response = \json_decode($request->getBody()->getContents(), true);
            if($response && is_array($response)){
                return array(
                    'payment' => array(
                        'id' => $generatedTicket['id'],
                        'ticketId' => $generatedTicket['ticketId'],
                        'url' => $payment_type=='boleto' ? $response['url'] : null,
                        'barcode' => $response['barcode'],
                        'digitable_line' => $payment_type=='boleto' ? null : $response['digitable_line'],
                        'pix' => $payment_type=='boleto' ? null : $response['emv'],
                        'status' => $generatedTicket['status']
                    ),
                    'userId' => $userId
                );
            }
            return false;
        } catch (\Exception $e){            
            return $this->ResponseException($e);
        }
    }
}
