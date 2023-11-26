<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $escala_id = $_POST["escala_id"];
    $mes = $_POST["mes"];
    $dias = $_POST["dias"];
    $hora = $_POST["hora"];
    $valor = $_POST["valor"];

    // Processar o array de dias
    $arrayDias = explode(",", $dias);

    // Calcular o valor total
    $total = count($arrayDias) * $valor;

    // Inserir os dados no banco de dados usando PDO
    try {
        $pdo = new PDO("mysql:host=seu_host;dbname=seu_banco_de_dados", "seu_usuario", "sua_senha");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO DetalhesEscala (escala_id, mes, dias, hora, valor, total)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$escala_id, $mes, $dias, $hora, $valor, $total]);

        echo "Registro inserido com sucesso!";
    } catch (PDOException $e) {
        echo "Erro ao inserir registro: " . $e->getMessage();
    }
} else {
    echo "Acesso inválido!";
}
?>
