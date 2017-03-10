<?php

namespace PagarMe\Sdk\SplitRule;

use PagarMe\Sdk\Recipient\Recipient;

trait SplitRuleBuilder
{
    /**
     * @param array $splitRuleData
     * @return SplitRuleCollection
     */
    private function buildSplitRules($splitRuleData)
    {
        $rules = new SplitRuleCollection();

        if (is_array($splitRuleData)) {
            foreach ($splitRuleData as $rule) {
                $rule->recipient = new Recipient(['id' =>$rule->recipient_id]);

                if (property_exists($rule, 'date_created')) {
                    $rule->date_created = new \DateTime($rule->date_created);
                }

                if (property_exists($rule, 'date_updated')) {
                    $rule->date_updated = new \DateTime($rule->date_updated);
                }
                $rules[] = new SplitRule(get_object_vars($rule));
            }
        }

        return $rules;
    }
}
