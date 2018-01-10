<?php

namespace PhpTwinfield\Mappers;

use MyCLabs\Enum\Enum;
use PhpTwinfield\Util;

abstract class BaseMapper
{
    /**
     * Set a property on an object using a setter from a tag within the element.
     *
     * @param \DOMNode $node
     * @param string $tag
     * @param callable $setter Must be in the array($object, $method) syntax where method takes one argument.
     */
    protected static function setFromTagUsingCallable(\DOMNode $node, string $tag, callable $setter): void
    {
        /** @var \DOMNode|\DOMDocument $node */
        $element = $node->getElementsByTagName($tag)->item(0);

        if (empty($element)) {
            return;
        }

        $contents = $element->textContent;

        if ($contents === null) {
            return;
        }

        $contents = self::transformContent(new \ReflectionMethod(...$setter), $contents);

        call_user_func($setter, $contents);
    }

    private static function transformContent(\ReflectionMethod $reflectionMethod, string $contents)
    {
        [$parameter] = $reflectionMethod->getParameters();

        if ($parameter->getType() === null) {
            /*
             * No typehint, just return the string.
             */
            return $contents;
        }

        $parameter_class = $parameter->getType()->getName();

        if (is_subclass_of($parameter_class, Enum::class)) {
            return new $parameter_class($contents);
        }

        if ($parameter_class == \DateTimeInterface::class) {
            return Util::parseDate($contents);
        }

        return $contents;
    }
}