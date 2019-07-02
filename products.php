<?php

SESSION_START();

$_SESSION['nombre']   = 'Ramon'; 
$_SESSION['apellido'] = 'Lopez';

echo "<a href='pagina2.html'>Ir a Pagina 2</a>";
?>