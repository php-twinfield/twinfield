<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\ElectronicBankStatement;
use PhpTwinfield\Util;

/**
 */
class ElectronicBankStatementDocument extends BaseDocument
{
    final protected function getRootTagName(): string
    {
        return "statements";
    }

    public function addStatement(ElectronicBankStatement $electronicBankStatement)
    {
        $statement = $this->createElement("statement");
        $statement->appendChild(new \DOMAttr("target", "electronicstatements"));

        if ($electronicBankStatement->isImportDuplicate()) {
            $statement->appendChild(new \DOMAttr("importduplicate", "1"));
        }

        if ($electronicBankStatement->getIban()) {
            $statement->appendChild($this->createNodeWithTextContent("iban", $electronicBankStatement->getIban()));
        } elseif ($electronicBankStatement->getAccount()) {
            $statement->appendChild($this->createNodeWithTextContent("account", $electronicBankStatement->getAccount()));
        } elseif ($electronicBankStatement->getCode()) {
            $statement->appendChild($this->createNodeWithTextContent("code", $electronicBankStatement->getCode()));
        }

        $statement->appendChild($this->createNodeWithTextContent("date", $electronicBankStatement->getDate()->format("Ymd")));

        $this->appendStartCloseValues($statement, $electronicBankStatement);

        $statement->appendChild($this->createNodeWithTextContent("statementnumber", $electronicBankStatement->getStatementnumber()));

        if ($electronicBankStatement->getOffice()) {
            $statement->appendChild($this->createNodeWithTextContent("office", $electronicBankStatement->getOffice()->getCode()));
        }

        $transactions = $this->createElement("transactions");

        foreach ($electronicBankStatement->getTransactions() as $transaction) {

            $node = $this->createElement("transaction");

            if ($transaction->getContraaccount()) {
                $node->appendChild($this->createNodeWithTextContent("contraaccount", $transaction->getContraaccount()));
            } elseif ($transaction->getContraiban()) {
                $node->appendChild($this->createNodeWithTextContent("contraiban", $transaction->getContraiban()));
            }

            $node->appendChild($this->createNodeWithTextContent("type", $transaction->getType()));

            if ($transaction->getReference()) {
                $node->appendChild($this->createNodeWithTextContent("reference", $transaction->getReference()));
            }

            $this->appendValueValues($node, $transaction);

            $node->appendChild($this->createNodeWithTextContent("description", $transaction->getDescription()));

            $transactions->appendChild($node);
        }

        $statement->appendChild($transactions);

        $this->rootElement->appendChild($statement);
    }
}