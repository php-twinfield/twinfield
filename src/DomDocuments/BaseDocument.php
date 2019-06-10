<?php

namespace PhpTwinfield\DomDocuments;

/**
 * You should add a public function add<Type>($instance): void method which will add an instance to the rootElement, so
 * that you can send multiple elements in one go.
 */
abstract class BaseDocument extends \DOMDocument
{
    /**
     * @var \DOMElement
     */
    protected $rootElement;

    /**
     * The root tag, e.g.
     *
     * @return string
     */
    abstract protected function getRootTagName(): string;

    public function __construct($version = "1.0", $encoding = "UTF-8")
    {
        parent::__construct($version, $encoding);

        $this->rootElement = $this->createElement($this->getRootTagName());
        $this->appendChild($this->rootElement);
    }

    /**
     * Create an element and set some value as its innerText.
     *
     * Use this instead of createElement().
     *
     * @param string $tag
     * @param string|null $textContent
     * @param $object
     * @param array $methodToAttributeMap
     * @return \DOMElement
     */
    final protected function createNodeWithTextContent(string $tag, ?string $textContent, $object = null, array $methodToAttributeMap = []): \DOMElement
    {
        $element = $this->createElement($tag);

        if ($textContent != null) {
            $element->textContent = $textContent;
        }

        if (isset($object)) {
            foreach ($methodToAttributeMap as $attributeName => $method) {
                $element->setAttribute($attributeName, $object->$method());
            }
        }

        return $element;
    }
}
