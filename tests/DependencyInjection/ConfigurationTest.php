<?php

declare(strict_types=1);

namespace TelegramMonolog\Bundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;
use TelegramMonolog\Bundle\DependencyInjection\Configuration;

#[CoversClass(Configuration::class)]
final class ConfigurationTest extends TestCase
{
    public function testValidConfigurationIsAccepted(): void
    {
        $config = $this->process([
            'token' => 'my-token',
            'chat_id' => '12345',
        ]);

        self::assertSame('my-token', $config['token']);
        self::assertSame('12345', $config['chat_id']);
    }

    public function testTokenIsRequired(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->process(['chat_id' => '12345']);
    }

    public function testChatIdIsRequired(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->process(['token' => 'my-token']);
    }

    /**
     * @param array<string, mixed> $config
     *
     * @return array<string, mixed>
     */
    private function process(array $config): array
    {
        return (new Processor())->processConfiguration(new Configuration(), [$config]);
    }
}
