<?php

require '../vendor/autoload.php';


echo "------ Creating Adapter & Configs --------\n";

$configData = [
    'connection' => [
        'host' => '52.70.77.21',
        'port' => 5672,
        'user' => 'ingresse',
        'pass' => '2e3b4b1a498cceb9b96e92135badb6dd1200d463',
        'vhost' => '/'
    ],
    'queues' => [
        'worker.antifraud.transaction' => [
            'passive' => false,
            'durable' => true,
            'exclusive' => false,
            'autoDelete' => false,
            'delivery_mode' => 2
        ],
        'worker.antifraud.create_user' => [
            'passive' => false,
            'durable' => true,
            'exclusive' => false,
            'autoDelete' => false,
            'delivery_mode' => 2
        ]
    ],
    'consume' => [
        'Simpler' => [
            'noLocal' => false,
            'noAck' => false,
            'exclusive' => false,
            'noWait' => false
        ],
        'Complexr' => [
            'noLocal' => false,
            'noAck' => false,
            'exclusive' => false,
            'noWait' => false
        ]
    ],
    'logger' => [
        'host' => '172.17.0.2',
        'port' => 6379,
        'key' => 'logstash',
        'channel' => 'message-queue-php'
    ]
];

$config = new Ingresse\MessageQueuePHP\Config\AMQPConfig($configData);
$adapter = new Ingresse\MessageQueuePHP\Adapter\AMQPAdapter($config);


$adapter->logger->setMessage(
                'oiiiii', 
                'warning'
            );
// echo "------- Database Connection -------\n";
// $conn = new PDO('mysql:host=localhost;dbname=ingresse', 'root', '123');
// $conn->exec("set names utf8");

// $stmtUser = $conn->prepare(
//     "select user.id user_id, user.email, 
//      substr(user.email, locate('@', user.email)+1) 
//      email_domain, user.name, user.cellphone, user.phone, 
//      user.street, user.district, user.number, user.complement,
//     user.city, user.state, user.zip from user order by user.id desc limit 10"
// );

// $stmtUser->execute();
// $users = $stmtUser->fetchAll(PDO::FETCH_ASSOC);

// $stmtSale = $conn->prepare(
//     "SELECT s.user_id,  
//         u.email, 
//         (REPLACE(s.total,'.','') * 10000) total, 
//         s.transactionId, 
//         case 
//             when s.status LIKE 'approved' then '\$success'  
//             when s.status LIKE 'declined' then '\$failure' 
//         end as status, 
//         case 
//             when s.paymenttype LIKE 'moip' then '\$moip'  
//             when s.paymenttype LIKE 'pagarme' then '\$pagar_me' 
//             when s.paymenttype LIKE 'pagseguro' then '\$pagseguro' 
//         end as paymenttype, 
//         case 
//             when po.description LIKE 'creditCard' then '\$credit_card'  
//         end as description, 
//         u.cartaoFinal, 
//         s.installments, 
//         s.paymentdetails, 
//         u.name, 
//         u.phone, 
//         u.street, 
//         u.number, 
//         u.city, 
//         u.state, 
//         u.district, 
//         u.zip
//         FROM user u  INNER JOIN sale s ON u.id = s.user_id INNER JOIN paymentOptionAlias a ON a.alias = s.paymentoption INNER JOIN paymentOption as po ON a.payment_option_id = po.id LEFT JOIN saleShipping as ss ON s.id = ss.sale_id WHERE s.paymenttype IN ('moip', 'pagarme', 'pagseguro')  AND s.status IN ('approved', 'declined') AND po.description = 'creditCard' AND s.creationdate >= '2015-08-01 00:00:00' AND s.creationdate <= '2015-08-20 23:59:50'"
// );

// $stmtSale->execute();
// $sales = $stmtSale->fetchAll(PDO::FETCH_ASSOC);
// $salesCount = count($sales);
// echo "Sales Count: {$salesCount} \n";

// echo "------ Testing Publisher --------\n";

// $transactionProducer = new Ingresse\MessageQueuePHP\Publisher\Publisher($adapter, 'worker.antifraud.transaction');

// $createUserProducer = new Ingresse\MessageQueuePHP\Publisher\Publisher($adapter, 'worker.antifraud.create_user');

// $i = 0;
// foreach ($sales as $sale){

//     $transaction = [
//         'event' => 'transaction',
//         'data'  => $sale
//     ];

//     $transactionProducer
//         ->setMessage(json_encode($transaction))
//         ->send();
//     $i += 1;

//     if ($i == 25) {
//         $i = 0;
//         sleep(1);
//     }
// }


// foreach ($users as $user){

//     $createAccount = [
//         'event' => 'create_account',
//         'data'  => $user
//     ];

//     $createUserProducer
//         ->setMessage(json_encode($createAccount))
//        ->send();
// }


echo "------- Testing Subscriber -------\n";

//$subscriber = new Ingresse\MessageQueuePHP\Subscriber\Subscriber($adapter);
//$simplerConsumer = new Ingresse\MessageQueuePHP\Subscriber\Consumer\SimplerConsumer;
//$subscriber
//    ->setConsumer($simplerConsumer)
//    ->subscribe('antifraud')
//    ->consume();
