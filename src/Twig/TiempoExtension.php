<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TiempoExtension extends AbstractExtension
{
    const configuracion = ['formato' => 'd/m/Y H:m:s'];
    public function getFilters(): array
    {
        return [
            new TwigFilter('tiempo', [$this, 'formatearTiempo']),
        ];
    }

    public function formatearTiempo($fecha, $configuracion = [])
    {
        $configuracion = array_merge(self::configuracion, $configuracion);
        $fechaActual = new \DateTime();
        $fechaFormateada = $fecha->format($configuracion['formato']);
        $diferenciaFechasSegundos = $fechaActual->getTimestamp() - $fecha->getTimestamp();

        if($diferenciaFechasSegundos < 60){
            $fechaFormateada = 'Creado ahora mismo';
        } elseif ($diferenciaFechasSegundos < 3600) {
            $fechaFormateada = 'Creado recientemente';
        }

        return $fechaFormateada;
    }
}
