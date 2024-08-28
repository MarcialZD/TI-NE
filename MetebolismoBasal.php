<?php
class MetabolismoBasalCalculator
{
    private $peso;
    private $altura;
    private $edad;
    private $genero;

    public function __construct($peso, $altura, $edad, $genero)
    {
        $this->peso = floatval($peso);
        $this->altura = floatval($altura);
        $this->edad = intval($edad);
        $this->genero = $genero;
    }

    public function calcularBMR()
    {
        if ($this->genero == "masculino") {
            return 88.362 + (13.397 * $this->peso) + (4.799 * $this->altura) - (5.677 * $this->edad);
        } else {
            return 447.593 + (9.247 * $this->peso) + (3.098 * $this->altura) - (4.330 * $this->edad);
        }
    }
}
