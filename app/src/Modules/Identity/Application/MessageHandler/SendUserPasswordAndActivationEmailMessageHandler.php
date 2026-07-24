<?php

declare(strict_types=1);

namespace App\Modules\Identity\Application\MessageHandler;

use App\Modules\Identity\Application\Message\SendUserPasswordAndActivationEmailMessage;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
final readonly class SendUserPasswordAndActivationEmailMessageHandler
{
    public function __construct(
        private MailerInterface $mailer,
        private string $mailFrom,
    ) {
    }

    public function __invoke(SendUserPasswordAndActivationEmailMessage $message): void
    {
        $email = (new Email())
            ->from($this->mailFrom)
            ->to($message->email)
            ->subject('Activa tu cuenta PlayerTech')
            ->text(sprintf(
                "Hola %s,\n\nTu cuenta fue creada para PlayerTech.\nUsuario: %s\nClave: %s\nActiva tu cuenta aquí:\n%s\n",
                $message->fullName,
                $message->username,
                $message->password,
                $message->activationUrl
            ));

        $this->mailer->send($email);
    }
}
