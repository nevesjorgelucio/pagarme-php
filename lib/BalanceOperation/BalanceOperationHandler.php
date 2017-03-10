<?php

namespace PagarMe\Sdk\BalanceOperation;

use PagarMe\Sdk\AbstractHandler;
use PagarMe\Sdk\BankAccount\BankAccount;
use PagarMe\Sdk\BalanceOperation\Request\BalanceOperationList;
use PagarMe\Sdk\BalanceOperation\Request\BalanceOperationGet;
use PagarMe\Sdk\BalanceOperation\Operation;
use PagarMe\Sdk\BalanceOperation\Movement;

class BalanceOperationHandler extends AbstractHandler
{
    use \PagarMe\Sdk\BalanceOperation\OperationBuilder;

    /**
     * @param null $page
     * @param null $count
     * @param null $status
     * @param null $recipient
     * @param null $start
     * @param null $end
     * @return array
     * @throws \PagarMe\Sdk\ClientException
     */
    public function getList($page = null, $count = null, $status = null, $recipient = null, $start = null, $end = null)
    {
        $request = new BalanceOperationList($page, $count, $status, $recipient, $start, $end);

        $response = $this->client->send($request);
        $operations = [];

        foreach ($response as $operationData) {
            $operations[] = $this->buildOperation($operationData);
        }

        return $operations;
    }

    /**
     * @param int $balanceOperationId
     * @return Operation
     */
    public function get($balanceOperationId)
    {
        $request = new BalanceOperationGet($balanceOperationId);

        $response = $this->client->send($request);

        return $this->buildOperation($response);
    }
}
