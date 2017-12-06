<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\ElectronicBankStatement;
use PhpTwinfield\Util;

/**
 */
class ElectronicBankStatementDocument extends \DOMDocument
{
    /**
     * @var \DOMElement
     */
    private $root;

    public function __construct($version = "1.0", $encoding = "UTF-8")
    {
        parent::__construct($version, $encoding);

        $this->root = $this->createElement("statements");
        $this->appendChild($this->root);
    }

    public function addStatement(ElectronicBankStatement $electronicBankStatement)
    {
        $statement = $this->createElement("statement");
        $statement->appendChild(new \DOMAttr("target", "electronicstatements"));

        if ($electronicBankStatement->isImportDuplicate()) {
            $statement->appendChild(new \DOMAttr("importduplicate", "1"));
        }

        if ($electronicBankStatement->getIban()) {
            $statement->appendChild($this->createElement("iban", $electronicBankStatement->getIban()));
        } elseif ($electronicBankStatement->getAccount()) {
            $statement->appendChild($this->createElement("account", $electronicBankStatement->getAccount()));
        } elseif ($electronicBankStatement->getCode()) {
            $statement->appendChild($this->createElement("code", $electronicBankStatement->getCode()));
        }

        $statement->appendChild($this->createElement("date", $electronicBankStatement->getDate()->format("Ymd")));

        $statement->appendChild($this->createElement("currency", $electronicBankStatement->getCurrency()));

        $statement->appendChild($this->createElement("statementnumber", $electronicBankStatement->getStatementnumber()));

        if ($electronicBankStatement->getOffice()) {
            $statement->appendChild($this->createElement("office", $electronicBankStatement->getOffice()->getCode()));
        }

        $statement->appendChild($this->createElement("startvalue", Util::formatMoney($electronicBankStatement->getStartvalue())));
        $statement->appendChild($this->createElement("closevalue", Util::formatMoney($electronicBankStatement->getClosevalue())));

        $transactions = $this->createElement("transactions");

        foreach ($electronicBankStatement->getTransactions() as $transaction) {

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

        $statement->appendChild($transactions);

        $this->root->appendChild($statement);
    }
}