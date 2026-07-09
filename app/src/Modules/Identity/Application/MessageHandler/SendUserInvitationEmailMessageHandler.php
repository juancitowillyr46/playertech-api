<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\MessageHandler;

use App\Modules\Identity\Application\Message\SendUserInvitationEmailMessage;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
final readonly class SendUserInvitationEmailMessageHandler
{
    public function __construct(
        private MailerInterface $mailer,
        private string $mailFrom,
    ) {
    }

    public function __invoke(SendUserInvitationEmailMessage $message): void
    {
        $email = (new Email())
            ->from($this->mailFrom)
            ->to($message->email)
            ->subject('Activa tu cuenta PlayerTech')
            ->text(sprintf(
                "Hola %s,\n\nHas sido invitado a PlayerTech.\nActiva tu cuenta aquí:\n%s\n",
                $message->fullName,
                $message->activationUrl
            ));

        $this->mailer->send($email);
    }
}
