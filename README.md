![alt text](https://zoop.com.br/wp-content/themes/zoop/img/logo.svg "Zoop")

# Introdução - Zoop SDK - PHP :elephant:

SDK Não oficial Zoop PHP, para realizar integração com o gateway de pagamento.

Você pode acessar a documentação oficial da Zoop acessando esse [link](https://docs.zoop.co/).

## Índice

- [Instalação](#instalação)
- [Configuração](#configuração)
- [Transações](#transações)
  - [Criando pagamento com cartão de credito.](#criando-pagamento-com-cartão-de-credito)
  - [Criando pagamento com boleto.](#criando-pagamento-com-boleto)
  - [Listando e tratando trasações da Zoop.](#listando-e-tratando-trasações-da-zoop)
  - [Obtendo dados de uma transação.](#obtendo-dados-de-uma-transação)
- [Vendedores](#vendedores)
  - [Recuperando detalhes de todo os vendedores do Marketplace](#recuperando-detalhes-de-todo-os-vendedores-do-marketplace)
  - [Recuperando detalhes do vendedor](#recuperando-detalhes-do-vendedor)
- [Compradores](#compradores)
  - [Criando o comprador dentro do marketplace configurado.](#criando-o-comprador-dentro-do-marketplace-configurado)
  - [Listando todos os compradores do Markeplace](#listando-todos-os-compradores-do-markeplace)
  - [Recuperando detalhes do comprador](#recuperando-detalhes-do-comprador)
  - [Deletando comprador do Marketplace](#deletando-comprador-do-marketplace)
- [WebHook](#webhook)
  - [Instanciando seu cliente](#instanciando-seu-cliente)
  - [Criando o Webhook (POST)](#criando-o-webhook-post)
  - [Retornando as chamadas da Zoop (POST)](#retornando-as-chamadas-da-zoop-post)
  - [Listando todos WebHooks](#listando-todos-webhooks)
  - [Deletando WebHook](#deletando-webhook)


## Instalação

Instale a biblioteca utilizando o comando

`composer require thiagobueno/zoop-sdk`

## Configuração

Para incluir a biblioteca em seu projeto, basta fazer o seguinte:

```php
<?php
use Zoop\Core\ZoopConfig;
use Zoop\ZoopClient;

require("vendor/autoload.php");

$token = 'zpk_test_Xxxxxx'; /* Token gerado ADM Mkt Zoop */
$marketplace = 'd0024d3f01ea4xxxxxxxxxx'; /* ID do Marketplace */
$vendedor = '38e0c71e9c7c465080bxxxxxxxxx'; /** ID do vendedor do marketplace */

$client = new ZoopClient(
    ZoopConfig::configure($token, $marketplace, $vendedor)
);
```

## Transações

#### Criando pagamento com cartão de credito.

O segundo parâmetro passado para SDK é opcional e pode ser utilizado para guardar na zoop (e recuperar posteriormente via webhook) o id do pagamento local na sua aplicação.

seu_ID_VENDA é um ID gerado pela sua aplicação.

```php
try {
    $pagamento = $client->payCreditCard(array(
        'description' => 'Plano Nitro',
        'amount' => 49.99,
        'card' => array(
            'card_number' => '5201561050024014',
            'holder_name' => 'João Silva',
            'expiration_month' => '03',
            'expiration_year' => '2018',
            'security_code' => '123',
        )
    ), 'seu_ID_VENDA');
    print_r($pagamento);
} catch(\Exception $e){
    echo $e->getMessage() . PHP_EOL;
}
```

#### Criando pagamento com boleto.

Para gerar um Boleto primeiro você deve registrar um [comprador](#compradores) e adicioná-lo no lugar de ID_DO_COMPRADOR

seu_ID_VENDA é um ID gerado pela sua aplicação.

```php
try {
    $boleto = $client->generateTicket(array(
        'amount' => 42.99,
        'logo' => 'https://dashboard.zoop.com.br/assets/imgs/logo-zoop.png',
        'description' => 'Pagamento Zoop',
        'top_instructions' => 'Instruções de pagamento',
        'body_instructions' => 'Não receber após a data de vencimento.',
        'expiration_date' => (string)date('Y-m-d'),
        'payment_limit_date' => (string)date('Y-m-d')
    ),  'ID_DO_COMPRADOR', 'seu_ID_VENDA');
    print_r($boleto);
} catch(\Exception $e){
    echo $e->getMessage() . PHP_EOL;
}
```

#### Listando e tratando trasações da Zoop.

Listando todas as transações do marketplace, caso algo esteja errado a Exception irá realizar um split diretamente da mensagem enviada pela propria Zoop, facilitando manutenção e entendimento do ocorrido.

```php
try {
    $transactions = $client->getAllTransactions();
    print_r($transactions);
} catch(\Exception $e){
    echo $e->getMessage() . PHP_EOL;
}
```

#### Obtendo dados de uma transação.

Esse método retorna os dados detalhados de uma transação.

```php
try {
    $transactions = $client->getTransaction('454543534543');
    print_r($transactions);
} catch(\Exception $e){
    echo $e->getMessage() . PHP_EOL;
}
```


## Vendedores

#### Recuperando detalhes de todo os vendedores do Marketplace

```php
try {
    $vendedores = $client->getAllSellers();
    print_r($vendedores);
} catch(\Exception $e){
    echo $e->getMessage() . PHP_EOL;
}
```

#### Recuperando detalhes do vendedor

```php
try {
    $vendedor = $client->getSeller('5345634635');
    print_r($vendedor);
} catch(\Exception $e){
    echo $e->getMessage() . PHP_EOL;
}
```

## Compradores

#### Criando o comprador dentro do marketplace configurado.

```php
try {
    $comprador = $client->createBuyer([
        'first_name' => 'Ricardo Pedrosa',
        'taxpayer_id' => '11836128770', /* CPF */
        'email' => 'ricardo.pedrosa@zoop.co',
        'address' => [
            'line1' => 'Av Americas, 500',
            'line2' => 'Citta América',
            'neighborhood' => 'Barra da Tijuca',
            'city' => 'Rio de Janeiro',
            'state' => 'RJ',
            'postal_code' => '22845046',
            'country_code' => 'BR'
        ],
    ]);
    print_r($comprador);
} catch(\Exception $e){
    echo $e->getMessage() . PHP_EOL;
}
```

#### Listando todos os compradores do Markeplace

```php
try {
    $compradores = $client->getAllBuyers();
    print_r($compradores);
} catch(\Exception $e){
    echo $e->getMessage() . PHP_EOL;
}
```

#### Recuperando detalhes do comprador

```php
try {
    $comprador = $client->getBuyer('5345634635');
    print_r($comprador);
} catch(\Exception $e){
    echo $e->getMessage() . PHP_EOL;
}
```

#### Deletando comprador do Marketplace

```php
try {
    $response = $client->deleteBuyer('5345634635');
    print_r($response);
} catch(\Exception $e){
    echo $e->getMessage() . PHP_EOL;
}
```

## WebHook

#### Instanciando seu cliente

```php
<?php
use Zoop\Core\ZoopConfig;
use Zoop\ZoopClient;

require("vendor/autoload.php");

$token = 'zpk_test_Xxxxxx'; /* Token gerado ADM Mkt Zoop */
$marketplace = 'd0024d3f01ea4xxxxxxxxxx'; /* ID do Marketplace */
$vendedor = '38e0c71e9c7c465080bxxxxxxxxx'; /** ID do vendedor do marketplace */

$client = new ZoopClient(
    ZoopConfig::configure($token, $marketplace, $vendedor)
);
```

#### Criando o Webhook (POST)

Retorna o status, se o webhook foi criado com sucesso.

```php
try {
    $webhook = $client->createWebHook('https://webhook.seusite.com.br', 'WebHook da SDK');
    print_r($webhook);
} catch (\Exception $e){
    echo $e->getMessage() . PHP_EOL;
}
```

#### Retornando as chamadas da Zoop (POST)

Precisamos entender como iremos utilizar o Webhook da zoop no nosso projeto, após criarmos alguma transação como por exemplo com cartão de crédito, essa ação cria um Evento dentro da Zoop, esse evento envia para uma URL sua, como por exemplo: https://seusite.com.br/webhook a SDK ajuda você a pegar os dados enviador para seu Webhook.

```php
try {
    $webHookAlert = $client->webHookListen();
    if (isset($webHookAlert) && !empty($webHookAlert) && is_array($webHookAlert)) {
        $log = fopen('webhook.json', 'a+');
        fwrite($log, json_encode($webHookAlert));
        fclose($log);
    } else {
        echo 'o evento recebido não é valido';
    }
} catch (\Exception $e){
    echo $e->getMessage() . PHP_EOL;
}
```

#### Listando todos WebHooks

Retorna uma lista com todos os webhooks criados dentro do marketplace.

```php
try {
    $webhooks = $client->getAllWebHooks();
    print_r($webhooks);
} catch (\Exception $e){
    echo $e->getMessage() . PHP_EOL;
}
```

#### Deletando WebHook

Deleta o webhook com id passado por parametro e retorna o status, se o mesmo foi deletado ou não.

```php
try {
    $webhook = $client->deleteWebHook('45345345');
    print_r($webhook);
} catch (\Exception $e){
    echo $e->getMessage() . PHP_EOL;
}
```


## Autor

Originalmente desenvolvido por [italodeveloper](https://github.com/italodeveloper) e contribuidores.
