<?php

namespace App\TelegramMonolog;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class TelegramMonologBundle
 *
 * @package TelegramMonologBundle
 */
class AppTelegramMonologBundle extends Bundle
{
    public function getParent(): ?string
    {
        return null;
    }
}
