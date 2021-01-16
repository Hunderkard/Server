@component('mail::message')
# Solicitud de cambio de contraseña.

Haz click en el botón de abajo para cambiar la contraseña.

@component('mail::button', 
            ['url' => 'http://localhost:4200/res-password-reset?token=' . $token])
Cambiar contraseña
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
