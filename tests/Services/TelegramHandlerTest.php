<?php

declare(strict_types=1);

namespace TelegramMonolog\Bundle\Tests\Services;

use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use TelegramMonolog\Bundle\Services\TelegramHandler;

#[CoversClass(TelegramHandler::class)]
final class TelegramHandlerTest extends TestCase
{
    public function testHandleSendsFormattedRecordToTelegram(): void
    {
        $requests = [];
        $client = new MockHttpClient(function (string $method, string $url, array $options) use (&$requests): MockResponse {
            $requests[] = ['method' => $method, 'url' => $url, 'options' => $options];

            return new MockResponse('{"ok":true}');
        });

        $handler = new TelegramHandler('SECRET-TOKEN', 4242, $client);
        $handler->handle($this->createRecord('something happened'));

        self::assertCount(1, $requests);
        self::assertSame('POST', $requests[0]['method']);
        self::assertSame(
            'https://api.telegram.org/botSECRET-TOKEN/sendMessage',
            $requests[0]['url'],
        );

        $body = json_decode((string) $requests[0]['options']['body'], true, 512, \JSON_THROW_ON_ERROR);
        self::assertSame(4242, $body['chat_id']);
        self::assertStringContainsString('something happened', $body['text']);

        self::assertContains('Content-Type: application/json', $requests[0]['options']['headers']);
    }

    public function testChatIdMayBeAString(): void
    {
        $requests = [];
        $client = new MockHttpClient(function (string $method, string $url, array $options) use (&$requests): MockResponse {
            $requests[] = $options;

            return new MockResponse('{"ok":true}');
        });

        $handler = new TelegramHandler('token', '@my_channel', $client);
        $handler->handle($this->createRecord('hello'));

        $body = json_decode((string) $requests[0]['body'], true, 512, \JSON_THROW_ON_ERROR);
        self::assertSame('@my_channel', $body['chat_id']);
    }

    public function testHandlerProcessesEveryLevel(): void
    {
        $handler = new TelegramHandler('token', 1, new MockHttpClient());

        self::assertTrue($handler->isHandling($this->createRecord('debug', Level::Debug)));
        self::assertTrue($handler->isHandling($this->createRecord('error', Level::Error)));
    }

    public function testConstructsWithoutAnInjectedClient(): void
    {
        $handler = new TelegramHandler('token', 1);

        self::assertInstanceOf(TelegramHandler::class, $handler);
    }

    private function createRecord(string $message, Level $level = Level::Info): LogRecord
    {
        return new LogRecord(
            new \DateTimeImmutable(),
            'app',
            $level,
            $message,
        );
    }
}
