<?php

namespace TelegramMonologBundle\Services;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class TelegramHandler extends AbstractProcessingHandler
{

    private $token;

    private $chatId;

    /**
     * TelegramHandler constructor.
     *
     * @param string $token
     * @param $chatId
     */
    public function __construct(
        string $token,
        $chatId
//,
//        int $level = Logger::DEBUG,
//        bool $bubble = true
    ) {
        parent::__construct(Logger::DEBUG, true);
        $this->token = $token;
        $this->chatId = $chatId;
    }
    /**
     * Builds the header of the API Call.
     *
     * @param string $content
     *
     * @return array
     */
    protected function buildHeader($content)
    {
        return [
            'Content-Type: application/json',
            'Content-Length: '. \strlen($content),
        ];
    }
    /**
     * Builds the body of API call.
     *
     * @param array $record
     *
     * @return string
     */
    protected function buildContent(array $record)
    {
        $content = [
            'chat_id' => $this->chatId,
            'text' => $record['formatted'],
        ];
//        if ($this->formatter instanceof HtmlFormatter) {
//            $content['parse_mode'] = 'HTML';
//        }
        return \json_encode($content);
    }
    /**
     * Writes the record down to the log of the implementing handler
     *
     * @param  array $record
     * @return void
     */
    protected function write(array $record)
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
