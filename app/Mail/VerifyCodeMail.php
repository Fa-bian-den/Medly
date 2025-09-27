<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    /** @var string Código en texto plano */
    public string $code;

    /** @var int Minutos de expiración */
    public int $minutes;

    /**
     * Constructor
     *
     * @param string $code Código de verificación
     * @param int $minutes Tiempo en minutos que dura el código
     */
    public function __construct(string $code, int $minutes = 10)
    {
        $this->code = $code;
        $this->minutes = $minutes;
    }

    /**
     * Construye el correo
     */
    public function build()
    {
        return $this
            ->subject('Código de verificación')
            ->view('emails.verify_code')
            ->with([
                'code' => $this->code,
                'minutes' => $this->minutes,
            ]);
    }
}