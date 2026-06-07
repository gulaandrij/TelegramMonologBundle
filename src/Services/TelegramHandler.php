<?php

declare(strict_types=1);

namespace TelegramMonolog\Bundle\Services;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\Curl;
use Monolog\Level;
use Monolog\LogRecord;

class TelegramHandler extends AbstractProcessingHandler
{
    public function __construct(
        private readonly string $token,
        private readonly string|int $chatId,
    ) {
        parent::__construct(Level::Debug, true);
    }

    /**
     * Builds the header of the API Call.
     */
    protected function buildHeader(string $content): array
    {
        return [
            'Content-Type: application/json',
            'Content-Length: ' . \strlen($content),
        ];
    }

    /**
     * Builds the body of API call.
     */
    protected function buildContent(LogRecord $record): string
    {
        $content = [
            'chat_id' => $this->chatId,
            'text' => $record->formatted,
        ];

        return \json_encode($content);
    }

    /**
     * Writes the record down to the log of the implementing handler.
     */
    protected function write(LogRecord $record): void
    {
        $content = $this->buildContent($record);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->buildHeader($content));
        curl_setopt($ch, CURLOPT_URL, sprintf('https://api.telegram.org/bot%s/sendMessage', $this->token));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        Curl\Util::execute($ch);
    }
}
