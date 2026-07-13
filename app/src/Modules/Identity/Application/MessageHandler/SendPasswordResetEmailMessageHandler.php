<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\MessageHandler;

use App\Modules\Identity\Application\Message\SendPasswordResetEmailMessage;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
final readonly class SendPasswordResetEmailMessageHandler
{
    public function __construct(
        private MailerInterface $mailer,
        private string $mailFrom,
    ) {
    }

    public function __invoke(SendPasswordResetEmailMessage $message): void
    {
        $email = (new Email())
            ->from($this->mailFrom)
            ->to($message->email)
            ->subject('Restablece tu contraseña en PlayerTech')
            ->text(sprintf(
                "Hola %s,\n\nRecibimos una solicitud para restablecer tu contraseña.\nCompleta el proceso aquí:\n%s\n",
                $message->fullName,
                $message->resetUrl
            ));

        $this->mailer->send($email);
    }
}
