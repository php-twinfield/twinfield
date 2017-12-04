<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\ElectronicBankStatement;
use PhpTwinfield\Util;

/**
 */
class ElectronicBankStatementDocument extends \DOMDocument
{
    public function addStatement(ElectronicBankStatement $statement)
    {
        $root = $this->createElement("statement");
        $root->appendChild(new \DOMAttr("target", "electronicstatements"));

        if ($statement->isImportDuplicate()) {
            $root->appendChild(new \DOMAttr("importduplicate", "1"));
        }

        if ($statement->getIban()) {
            $root->appendChild($this->createElement("iban", $statement->getIban()));
        } elseif ($statement->getAccount()) {
            $root->appendChild($this->createElement("account", $statement->getAccount()));
        } elseif ($statement->getCode()) {
            $root->appendChild($this->createElement("code", $statement->getCode()));
        }

        $root->appendChild($this->createElement("date", $statement->getDate()->format("Ymd")));

        $root->appendChild($this->createElement("currency", $statement->getCurrency()));

        $root->appendChild($this->createElement("statementnumber", $statement->getStatementnumber()));

        if ($statement->getOffice()) {
            $root->appendChild($this->createElement("office", $statement->getOffice()->getCode()));
        }

        $root->appendChild($this->createElement("startvalue", Util::formatMoney($statement->getStartvalue())));
        $root->appendChild($this->createElement("closevalue", Util::formatMoney($statement->getClosevalue())));

        $transactions = $this->createElement("transactions");

        foreach ($statement->getTransactions() as $transaction) {

            $node = $this->createElement("transaction");

            if ($transaction->getContraaccount()) {
                $node->appendChild($this->createElement("contraaccount", $transaction->getContraaccount()));
            } elseif ($transaction->getContraiban()) {
                $node->appendChild($this->createElement("contraiban", $transaction->getContraiban()));
            }

            $node->appendChild($this->createElement("type", $transaction->getType()));
            $node->appendChild($this->createElement("reference", $transaction->getReference()));
            $node->appendChild($this->createElement("debitcredit", $transaction->getDebitcredit()));
            $node->appendChild($this->createElement("value", Util::formatMoney($transaction->getValue()->absolute())));
            $node->appendChild($this->createElement("description", $transaction->getDescription()));

            $transactions->appendChild($node);
        }

        $root->appendChild($transactions);

        $this->appendChild($root);
    }
}