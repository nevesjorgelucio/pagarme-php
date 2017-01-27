<?php

namespace PagarMe\Sdk\Transaction\Request;

use PagarMe\Sdk\Card\Card;
use PagarMe\Sdk\Customer\Customer;
use PagarMe\Sdk\RequestInterface;
use PagarMe\Sdk\SplitRule\SplitRuleCollection;
use PagarMe\Sdk\SplitRule\SplitRule;
use PagarMe\Sdk\Transaction\AbstractTransaction;

class TransactionCreate implements RequestInterface
{
    /**
     * @var AbstractTransaction
     */
    protected $transaction;

    /**
     * TransactionCreate constructor.
     * @param AbstractTransaction $transaction
     */
    public function __construct(AbstractTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        $customer = $this->transaction->getCustomer();
        $transactionData = [
            'amount'         => $this->transaction->getAmount(),
            'postback_url'   => $this->transaction->getPostbackUrl(),
            'metadata' => $this->transaction->getMetadata()
        ];
        $card = $this->transaction->getCard();

        if ($card instanceof Card && empty($card->getHash()) && ! empty($this->transaction->getPaymentMethod())) {
            $transactionData['payment_method'] = $this->transaction->getPaymentMethod();
        }

        if ($customer instanceof Customer) {
            $address  = $customer->getAddress();
            $phone    = $customer->getPhone();

            $transactionData['customer'] = [
                'name'            => $customer->getName(),
                'document_number' => $customer->getDocumentNumber(),
                'email'           => $customer->getEmail(),
                'sex'             => $customer->getGender(),
                'born_at'         => $customer->getBornAt(),
                'address' => [
                    'street'        => $address['street'],
                    'street_number' => $address['street_number'],
                    'complementary' => isset($address['complementary']) ? $address['complementary']: null,
                    'neighborhood'  => $address['neighborhood'],
                    'zipcode'       => $address['zipcode']
                ],
                'phone' => [
                    'ddd'    => (string) $phone['ddd'],
                    'number' => (string) $phone['number']
                ]
            ];
        }

        if ($this->transaction->getSplitRules() instanceof SplitRuleCollection) {
            $transactionData['split_rules'] = $this->getSplitRulesInfo(
                $this->transaction->getSplitRules()
            );
        }

        return $transactionData;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return 'transactions';
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return self::HTTP_POST;
    }

    /**
     * @param SplitRuleCollection $splitRules
     * @return array
     */
    private function getSplitRulesInfo(SplitRuleCollection $splitRules)
    {
        $rules = [];

        foreach ($splitRules as $key => $splitRule) {
            if (! $splitRule instanceof SplitRule) {
                continue;
            }
            $rule = [
                'recipient_id'          => $splitRule->getRecipient()->getId(),
                'charge_processing_fee' => $splitRule->getChargeProcessingFee(),
                'liable'                => $splitRule->getLiable()
            ];

            $rules[$key] = array_merge($rule, $this->getRuleValue($splitRule));
        }

        return $rules;
    }

    /**
     * @param $splitRule
     * @return array
     */
    private function getRuleValue(SplitRule $splitRule)
    {
        if (!is_null($splitRule->getAmount())) {
            return ['amount' => $splitRule->getAmount()];
        }

        return ['percentage' => $splitRule->getPercentage()];
    }
}
