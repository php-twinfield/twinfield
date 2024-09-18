<?php

declare(strict_types=1);

namespace Dummy;

use Psr\Http\Message\StreamInterface;

final class StringStream implements StreamInterface
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function close(): void
    {
    }

    public function detach()
    {
    }

    public function getSize(): ?int
    {
    }

    public function tell(): int
    {
    }

    public function eof(): bool
    {
    }

    public function isSeekable(): bool
    {
    }

    public function seek(int $offset, int $whence = SEEK_SET): void
    {
    }

    public function rewind(): void
    {
    }

    public function isWritable(): bool
    {
    }

    public function write(string $string): int
    {
    }

    public function isReadable(): bool
    {
    }

    public function read(int $length): string
    {
    }

    public function getContents(): string
    {
    }

    public function getMetadata(?string $key = null)
    {
    }
}