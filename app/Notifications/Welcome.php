<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Spatie\WelcomeNotification\WelcomeNotification;

class Welcome extends WelcomeNotification
{
    public function buildWelcomeNotificationMessage(): MailMessage
    {
        return (new MailMessage)
            ->subject('Bienvenido a' . env('APP_NAME') . '!')
            ->line(__('Tu empresa te ha invitado para poder acceder al sistema de fichajes'))
            ->line(__('Por favor, utiliza el siguiente enlace para crear tu contraseña y poder acceder al sistema'))
            ->action('Elegir contraseña', $this->showWelcomeFormUrl)
            ->line('Para acceder a la aplicación, deberás hacerlo con tu correo electrónico y la contraseña elegida.')
            ->line('Gracias por usar nuestra app!');
    }

}
