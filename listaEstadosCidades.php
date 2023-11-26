<?php

// Configurações do banco de dados
define('HOST', 'localhost');
define('DB', 'u485879861_escala');
define('USER', 'u485879861_limaalves');
define('PASS', 'Ma443598');

// Conexão com o banco de dados
$conn = new mysqli(HOST, USER, PASS, DB);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Função para fazer a consulta à API do IBGE
function consultarAPI($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// Consultar a lista de estados na API do IBGE
$url_estados = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados';
$estados = consultarAPI($url_estados);

// Preparar o statement para inserir os estados no banco de dados
$stmt_estado = $conn->prepare("INSERT INTO estados (sigla, nome) VALUES (?, ?)");
$stmt_estado->bind_param("ss", $sigla_estado, $nome_estado);

// Preparar o statement para inserir as cidades no banco de dados
$stmt_cidade = $conn->prepare("INSERT INTO cidades (nome, id_estado) VALUES (?, ?)");
$stmt_cidade->bind_param("si", $nome_cidade, $id_estado);

foreach ($estados as $estado) {
    $sigla_estado = $estado['sigla'];
    $nome_estado = $estado['nome'];
    $stmt_estado->execute();

    // Obter o ID do estado recém-cadastrado
    $id_estado = $stmt_estado->insert_id;

    // Consultar a lista de cidades do estado na API do IBGE
    $url_cidades = "https://servicodados.ibge.gov.br/api/v1/localidades/estados/{$estado['sigla']}/municipios";
    $cidades = consultarAPI($url_cidades);

    foreach ($cidades as $cidade) {
        $nome_cidade = $cidade['nome'];
        $stmt_cidade->execute();
    }
}

$stmt_estado->close();
$stmt_cidade->close();
$conn->close();

echo "Dados de estados e cidades salvos no banco de dados com sucesso!";
?>
