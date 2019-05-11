<?php

namespace PhpTwinfield\DomDocuments;

use PhpTwinfield\Invoice;
use PhpTwinfield\Secure\AuthenticatedConnection;

/**
 * The Document Holder for making new XML Invoice. Is a child class
 * of DOMDocument and makes the required DOM tree for the interaction in
 * creating a new Invoice.
 *
 * @package PhpTwinfield
 * @subpackage Invoice\DOM
 * @author Willem van de Sande <W.vandeSande@MailCoupon.nl>, extended by Yannick Aerssens <y.r.aerssens@gmail.com>
 */
class InvoicesDocument extends BaseDocument
{
    final protected function getRootTagName(): string
    {
        return "salesinvoices";
    }

    /**
     * Turns a passed Invoice class into the required markup for interacting
     * with Twinfield.
     *
     * This method doesn't return anything, instead just adds the Invoice to
     * this DOMDOcument instance for submission usage.
     *
     * @access public
     * @param Invoice $invoice
     * @param AuthenticatedConnection $connection
     * @return void | [Adds to this instance]
     */
    public function addInvoice(Invoice $invoice, $connection)
    {
        $invoiceElement = $this->createElement('salesinvoice');
        $this->rootElement->appendChild($invoiceElement);

        // Make header element
        $headerElement = $this->createElement('header');
        $invoiceElement->appendChild($headerElement);

        $calculateOnly = $invoice->getCalculateOnly();

        if (!empty($calculateOnly)) {
            $headerElement->setAttribute('calculateonly', $calculateOnly);
        }

        $raiseWarning = $invoice->getRaiseWarning();

        if (!empty($raiseWarning)) {
            $headerElement->setAttribute('raisewarning', $raiseWarning);
        }

        $headerElement->appendChild($this->createNodeWithTextContent('bank', $invoice->getBankToCode()));
        $headerElement->appendChild($this->createNodeWithTextContent('currency', $invoice->getCurrencyToCode()));
        $headerElement->appendChild($this->createNodeWithTextContent('customer', $invoice->getCustomerToCode()));
        $headerElement->appendChild($this->createNodeWithTextContent('deliveraddressnumber', $invoice->getDeliverAddressNumber()));
        $headerElement->appendChild($this->createNodeWithTextContent('duedate', $invoice->getDueDateToString()));
        $headerElement->appendChild($this->createNodeWithTextContent('footertext', $invoice->getFooterText()));
        $headerElement->appendChild($this->createNodeWithTextContent('headertext', $invoice->getHeaderText()));
        $headerElement->appendChild($this->createNodeWithTextContent('invoiceaddressnumber', $invoice->getInvoiceAddressNumber()));
        $headerElement->appendChild($this->createNodeWithTextContent('invoicedate', $invoice->getInvoiceDateToString()));
        $headerElement->appendChild($this->createNodeWithTextContent('invoicenumber', $invoice->getInvoiceNumber()));
        $headerElement->appendChild($this->createNodeWithTextContent('invoicetype', $invoice->getInvoiceTypeToCode()));
        $headerElement->appendChild($this->createNodeWithTextContent('office', $invoice->getOfficeToCode()));
        $headerElement->appendChild($this->createNodeWithTextContent('paymentmethod', $invoice->getPaymentMethod()));
        $headerElement->appendChild($this->createNodeWithTextContent('period', $invoice->getPeriod()));
        $headerElement->appendChild($this->createNodeWithTextContent('status', $invoice->getStatus()));

        $invoiceTypeApiConnector = new \PhpTwinfield\ApiConnectors\InvoiceTypeApiConnector($connection);
        $invoiceVatType = $invoiceTypeApiConnector->getInvoiceTypeVatType($invoice->getInvoiceTypeToCode());

        $articleApiConnector = new \PhpTwinfield\ApiConnectors\ArticleApiConnector($connection);

        $lines = $invoice->getLines();

        if (!empty($lines)) {
            // Make lines element
            $linesElement = $this->createElement('lines');
            $invoiceElement->appendChild($linesElement);

            // Go through each line assigned to the invoice
            foreach ($lines as $line) {
                // Makes new line element
                $lineElement = $this->createElement('line');
                $linesElement->appendChild($lineElement);

                $id = $line->getID();

                if (!empty($id)) {
                    $lineElement->setAttribute('id', $id);
                }

                $lineElement->appendChild($this->createNodeWithTextContent('allowdiscountorpremium', $line->getAllowDiscountOrPremiumToString()));
                $lineElement->appendChild($this->createNodeWithTextContent('article', $line->getArticleToCode()));
                $lineElement->appendChild($this->createNodeWithTextContent('description', $line->getDescription()));
                $lineElement->appendChild($this->createNodeWithTextContent('dim1', $line->getDim1ToCode()));
                $lineElement->appendChild($this->createNodeWithTextContent('freetext1', $line->getFreeText1()));
                $lineElement->appendChild($this->createNodeWithTextContent('freetext2', $line->getFreeText2()));
                $lineElement->appendChild($this->createNodeWithTextContent('freetext3', $line->getFreeText3()));
                $lineElement->appendChild($this->createNodeWithTextContent('performancedate', $line->getPerformanceDateToString()));
                $lineElement->appendChild($this->createNodeWithTextContent('performancetype', $line->getPerformanceType()));
                $lineElement->appendChild($this->createNodeWithTextContent('quantity', $line->getQuantity()));
                $lineElement->appendChild($this->createNodeWithTextContent('subarticle', $line->getSubArticleToSubCode()));
                $lineElement->appendChild($this->createNodeWithTextContent('units', $line->getUnits()));

                if ($invoiceVatType == 'inclusive') {
                    $lineElement->appendChild($this->createNodeWithTextContent('unitspriceinc', $line->getUnitsPriceIncToFloat()));
                } else {
                    $lineElement->appendChild($this->createNodeWithTextContent('unitspriceexcl', $line->getUnitsPriceExclToFloat()));
                }

                $article = $articleApiConnector->get($line->getArticleToCode(), $invoice->getOffice());

                if ($article->getAllowChangeVatCode() == true) {
                    $lineElement->appendChild($this->createNodeWithTextContent('vatcode', $line->getVatCodeToCode()));
                }
            }
        }
    }
}