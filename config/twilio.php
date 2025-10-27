<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Twilio Configuration
    |--------------------------------------------------------------------------
    |
    | Estos son los datos de configuración para enviar SMS con Twilio.
    | Puedes obtener tus credenciales desde https://www.twilio.com/console
    |
    */

    'sid' => env('TWILIO_SID'),
    'token' => env('TWILIO_TOKEN'),
    'number' => env('TWILIO_NUMBER'),

];

