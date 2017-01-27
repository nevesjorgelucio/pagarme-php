<?php

namespace PagarMe\Sdk\Transaction;

class CreditCardTransaction extends AbstractTransaction
{
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
