<?php
namespace Zoop;

use Zoop\Core\Zoop;
/**
 * ZoopClient class
 *
 * Essa classe é resonsavel por ser usada como primeira camada
 * uma especie de guarda chuvas para todas as classes registradas
 * dentro de aplicação em formato de bundle, os bundles (classes que
 * podem ser chaamdas seus metodos publicos por essa class cliente
 * são registrados dentro da classe Abstrata extendida pelo cliente
 * Zoop\Zoop).
 * 
 * @method \Zoop\Resources\MarketPlace\Buyers createBuyer(array $user)
 * @method \Zoop\Resources\MarketPlace\Buyers getAllBuyers()
 * @method \Zoop\Resources\MarketPlace\Buyers getBuyer(string $id)
 * @method \Zoop\Resources\MarketPlace\Buyers deleteBuyer(string $id)
 * 
 * @method \Zoop\Resources\MarketPlace\Transactions getAllTransactions()
 * @method \Zoop\Resources\MarketPlace\Transactions getTransaction(string $transaction)
 * 
 * @method \Zoop\Resources\MarketPlace\Sellers getAllSellers()
 * @method \Zoop\Resources\MarketPlace\Sellers getSeller(string $sallerId)
 * 
 * @method \Zoop\Resources\Payment\CreditCard payCreditCard(array $card, string $referenceId = null)
 * @method \Zoop\Resources\Payment\Ticket generateTicket(array $ticket, string $userId, string $referenceId = null)
 * 
 * @method \Zoop\Resources\WebHook\WebHook getAllWebHooks()
 * @method \Zoop\Resources\WebHook\WebHook createWebHook(string $url, string $description)
 * @method \Zoop\Resources\WebHook\WebHook deleteWebHook(string $webhookId)
 * @method \Zoop\Resources\WebHook\WebHook webHookListen()
 *
 * @method \Zoop\Resources\Transfers\Transfers getTransfers(string $sellerId)
 * @method \Zoop\Resources\Transfers\Transfers getAllTransfers()
 * @method \Zoop\Resources\Transfers\Transfers getTransfer(string $transferId)
 * @method \Zoop\Resources\Transfers\Transfers getTransactions(string $transferId)
 * 
 * @package ZoopClient
 * @author Thiago - thiago@nerdetcetera.com
 * @version 1.6.3
 */
class ZoopClient extends Zoop 
{
    public function __construct($configurations)
    {
        parent::__construct($configurations);
    }
}