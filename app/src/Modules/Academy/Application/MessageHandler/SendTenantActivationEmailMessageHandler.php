<?php

declare(strict_types=1);

namespace App\Modules\Academy\Application\MessageHandler;

use App\Modules\Academy\Application\Message\SendTenantActivationEmailMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
final readonly class SendTenantActivationEmailMessageHandler
{
    public function __construct(
        private MailerInterface $mailer,
        private string $mailFrom,
    ) {
    }

    public function __invoke(SendTenantActivationEmailMessage $message): void
    {
        $email = (new Email())
            ->from($this->mailFrom)
            ->to($message->contactEmail)
            ->subject('Activa tu cuenta PlayerTech')
            ->text(sprintf(
                "Hola %s,\n\nTu academia %s fue registrada.\nActiva tu cuenta aquí:\n%s\n",
                $message->contactName,
                $message->academyName,
                $message->activationUrl
            ));

        $this->mailer->send($email);
    }
}
