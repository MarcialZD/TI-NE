<?php
//metodo index a String
function indexArticulo($index)
{
    switch ($index)
    {
        case 0: return "Seleccionar artículo";

           
        case 1: return "Proteína de Suero de Leche  (Sabor Chocolate)";

           
        case 2: return "Proteína de Suero de Leche (Sabor Vainilla)";

          
        case 3: return "Proteína de Caseína (Sabor Fresa)";

         
        case 4: return "Proteína de Caseína (Sabor Chocolate)";

          
        case 5: return "Proteína de Guisante (Sabor Natural)";

           
        case 6: return "Proteína de Guisante (Sabor Vainilla)";

           
        case 7: return "Proteína de Soja (Sabor Chocolate)";

           
        case 8: return "Proteína de Arroz (Sabor Natural)";

            
        case 9: return "Proteína Vegana (Mezcla de Guisante y Arroz, Sabor Cookies y Crema)";

            

        default:
            break;
    }
}

function indexPrecio($index)
{
    switch ($index)
    {
        case 0: return 0;

           
        case 1: return 25.99;

           
        case 2: return 26.99;

            
        case 3: return 29.99;

            
        case 4: return 30.99;

            
        case 5: return 22.99;

           
        case 6: return 23.99;

           
        case 7: return 21.99;

           
        case 8: return 24.99;

           
        case 9: return 28.99;

            

        default:
            break;
    }
}




