<?php
$sql_lista = 'SELECT * FROM `escala_extra` order by id desc;';
try{
    $sql = $pdo->prepare($sql_lista);
    $sql->execute();

}catch(PDOException $erro_lista){
    echo "ERRO AO LISTAR ESCALA! ".$erro_lista->getMessag();
}