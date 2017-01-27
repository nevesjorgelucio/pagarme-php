<?php

namespace PagarMe\Sdk\Transaction;

trait TransactionBuilder
{
    use \PagarMe\Sdk\SplitRule\SplitRuleBuilder;
    use \PagarMe\Sdk\Card\CardBuilder;

    /**
     * @param $transactionData
     * @return BoletoTransaction|CreditCardTransaction
     * @throws UnsupportedTransaction
     */
    private function buildTransaction($transactionData)
    {
        if (isset($transactionData->split_rules)) {
            $transactionData->split_rules = $this->buildSplitRules(
                $transactionData->split_rules
            );
        }

        if (isset($transactionData->card)) {
            $transactionData->card = $this->buildCard($transactionData->card);
        }
        $transactionData->metadata = $this->parseMetadata($transactionData);

        $transactionData->date_created = new \DateTime(
            $transactionData->date_created
        );
        $transactionData->date_updated = new \DateTime(
            $transactionData->date_updated
        );

        if ($transactionData->payment_method == BoletoTransaction::PAYMENT_METHOD) {
            $transactionData->boleto_expiration_date = new \DateTime(
                $transactionData->boleto_expiration_date
            );

            return new BoletoTransaction(get_object_vars($transactionData));
        }
        $availableMethod = [
            CreditCardTransaction::CREDIT_METHOD,
            CreditCardTransaction::DEBIT_METHOD
        ];

        if (in_array($transactionData->payment_method, $availableMethod)) {
            $creditCard = new CreditCardTransaction(get_object_vars($transactionData));

            if (empty($creditCard->getCardHash()) && empty($creditCard->getPaymentMethod())) {
                $creditCard->setPaymentMethod(CreditCardTransaction::CREDIT_METHOD);
            }

            return $creditCard;
        }

        throw new UnsupportedTransaction(
            sprintf(
                'Transaction type: %s, is not supported',
                $transactionData->payment_method
            ),
            1
        );
    }

    /**
     * @param array $transactionData
     * @return array
     */
    private function parseMetadata($transactionData)
    {
        if (!isset($transactionData->metadata)) {
            return [];
        }

        if (is_null($transactionData->metadata)) {
            return [];
        }

        if (is_array($transactionData->metadata)) {
            return $transactionData->metadata;
        }

        return get_object_vars($transactionData->metadata);
    }
}
