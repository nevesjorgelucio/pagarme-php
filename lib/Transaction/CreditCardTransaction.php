<?php

namespace PagarMe\Sdk\Transaction;

use PagarMe\Sdk\SplitRule\SplitRuleBuilder;

class CreditCardTransaction extends AbstractTransaction
{
    use SplitRuleBuilder;

    const CREDIT_METHOD = 'credit_card';
    const DEBIT_METHOD = 'debit_card';

    /**
     * @var PagarMe\Sdk\Card\Card
     */
    protected $card;
    /**
     * @var int
     */
    protected $installments;
    /**
     * @var boolean
     */
    protected $capture;

    /**
     * @param array $transactionData
     */
    public function __construct($transactionData)
    {
        parent::__construct($transactionData);

        if (isset($transactionData['split_rules'])) {
            $obj = json_encode($transactionData['split_rules']);

            $this->splitRules = $this->buildSplitRules(json_decode($obj));
        }
    }

    /**
     * @param $paymentMethod
     * @return $this
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getCardId()
    {
        return $this->card->getId();
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getCardHash()
    {
        return $this->card->getHash();
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getInstallments()
    {
        return $this->installments;
    }

    /**
     * @return boolean
     */
    public function isCapturable()
    {
        return (bool) $this->capture;
    }
}
