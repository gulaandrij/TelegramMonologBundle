<?php

declare(strict_types=1);

namespace TelegramMonolog\Bundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use TelegramMonolog\Bundle\DependencyInjection\TelegramMonologExtension;
use TelegramMonolog\Bundle\Services\TelegramHandler;

#[CoversClass(TelegramMonologExtension::class)]
final class TelegramMonologExtensionTest extends TestCase
{
    public function testLoadRegistersParametersAndService(): void
    {
        $container = new ContainerBuilder();
        $extension = new TelegramMonologExtension();

        $extension->load(
            [['token' => 'my-token', 'chat_id' => '999']],
            $container,
        );

        self::assertSame('my-token', $container->getParameter('monolog_telegram.token'));
        self::assertSame('999', $container->getParameter('monolog_telegram.chat_id'));

        self::assertTrue($container->hasDefinition('telegram_monolog'));
        self::assertSame(
            TelegramHandler::class,
            $container->getDefinition('telegram_monolog')->getClass(),
        );
    }
}
