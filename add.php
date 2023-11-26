<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../conexao/config.php';

// Mapeamento dos números dos meses para os nomes dos meses
$mesesNomes = array(
    '01' => 'Janeiro',
    '02' => 'Fevereiro',
    '03' => 'Março',
    '04' => 'Abril',
    '05' => 'Maio',
    '06' => 'Junho',
    '07' => 'Julho',
    '08' => 'Agosto',
    '09' => 'Setembro',
    '10' => 'Outubro',
    '11' => 'Novembro',
    '12' => 'Dezembro'
);

$titulo = strip_tags(trim($_POST['titulo'] ?? ''));
$mes    = strip_tags(trim($_POST['mes'] ?? ''));
$dias   = strip_tags(trim($_POST['dias'] ?? ''));
$hora   = strip_tags(trim($_POST['hora'] ?? ''));
$valor  = strip_tags(trim($_POST['valor'] ?? ''));
$fez    = date('Y-m-d H:i:s');
$status = 0;


if (!empty($titulo) && !empty($mes) && !empty($dias) && !empty($hora) && !empty($valor)) {
    // Verifica se o número do mês existe no mapeamento e, se existir, obtém o nome do mês correspondente
    if (array_key_exists($mes, $mesesNomes)) {
        $mes_nome = $mesesNomes[$mes];
    } else {
        $mes_nome = ''; // Caso não exista o mês no mapeamento, deixamos vazio ou você pode fornecer uma mensagem de erro
    }

    $valor = str_replace(['.', ','], ['', '.'], $valor);
    $valor = floatval($valor);

    $sql = 'INSERT INTO escala_extra (titulo, mes, dias, hora, valor, criacao, status) ';
    $sql .= 'VALUES (:titulo,  :mes_nome, :dias, :hora, :valor, :fez, :status)';
    try {
        $query = $pdo->prepare($sql);
        $query->bindValue(':titulo', $titulo, PDO::PARAM_STR);       
        $query->bindValue(':mes_nome', $mes_nome, PDO::PARAM_STR); // Salva o nome do mês no banco de dados
        $query->bindValue(':dias', $dias, PDO::PARAM_STR);
        $query->bindValue(':hora', $hora, PDO::PARAM_STR);
        $query->bindValue(':valor', $valor, PDO::PARAM_STR);
        $query->bindValue(':fez', $fez, PDO::PARAM_STR);
        $query->bindValue(':status', $status);
       
        $query->execute();
       
        header("Location: https://ourihost.com.br/escala/");
        exit;

    } catch (PDOException $error_insert) {
        echo 'ERROR AO CADASTRAR ' . $error_insert->getMessage();
    }
} else {
    header("Location: https://ourihost.com.br/escala/");
    exit;
}

