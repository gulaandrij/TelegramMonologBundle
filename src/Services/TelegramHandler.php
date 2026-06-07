<?php

declare(strict_types=1);

namespace TelegramMonolog\Bundle\Services;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TelegramHandler extends AbstractProcessingHandler
{
    private readonly HttpClientInterface $httpClient;

    public function __construct(
        private readonly string $token,
        private readonly string|int $chatId,
        ?HttpClientInterface $httpClient = null,
    ) {
        parent::__construct(Level::Debug, true);
        $this->httpClient = $httpClient ?? HttpClient::create();
    }

    /**
     * Builds the body of the API call.
     *
     * @return array<string, mixed>
     */
    protected function buildContent(LogRecord $record): array
    {
        return [
            'chat_id' => $this->chatId,
            'text' => $record->formatted,
        ];
    }

    /**
     * Writes the record down to the log of the implementing handler.
     */
    protected function write(LogRecord $record): void
    {
        $this->httpClient->request(
            'POST',
            sprintf('https://api.telegram.org/bot%s/sendMessage', $this->token),
            ['json' => $this->buildContent($record)],
        );
    }
}
