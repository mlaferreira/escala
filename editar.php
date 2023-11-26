<?php
require '../conexao/config.php';

// Verifica se o ID foi passado na URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta o registro com o ID fornecido
    $sql_lista = "SELECT * FROM escala_extra WHERE id = :id";

    try {
        $stmt = $pdo->prepare($sql_lista);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        // Verifica se o registro foi encontrado
        if ($stmt->rowCount() > 0) {
            // Recupera os dados do registro
            $registro = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            // Redireciona caso o registro não seja encontrado
            header("Location: https://ourihost.com.br/escala/");
            exit;
        }
    } catch (PDOException $erro_lista) {
        echo "ERRO AO LISTAR ESCALA! " . $erro_lista->getMessage();
    }
} else {
    // Redireciona caso o ID não seja passado na URL
    header("Location: https://ourihost.com.br/escala/");
    exit;
}

// Verifica se o formulário foi enviado
if (isset($_POST['salvar'])) {
    // Verifica se o ID foi passado no formulário
    if (!empty($_POST['id'])) {
        $id     = $_POST['id'];
        // Recupere os dados do formulário
        $titulo = $_POST['titulo'];
        $mes    = $_POST['mes'];
        $dias   = $_POST['dias'];
        $hora   = $_POST['hora'];
        $valor  = $_POST['valor'];
        $status = $_POST['status'];
        $meta   = $_POST['meta'];

        // Atualize o registro no banco de dados
        $sql_ed = "UPDATE escala_extra SET titulo = :titulo, mes = :mes, dias = :dias, hora = :hora, valor = :valor, status = :status WHERE id = :id";

        try {
            $stmt = $pdo->prepare($sql_ed);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':titulo', $titulo);
            $stmt->bindValue(':mes', $mes);
            $stmt->bindValue(':dias', $dias);
            $stmt->bindValue(':hora', $hora);
            $stmt->bindValue(':valor', $valor);
            $stmt->bindValue(':status', $status);
           
            $stmt->execute();

            // Redireciona de volta para a página de visualização após a edição bem-sucedida
            header("Location: https://ourihost.com.br/escala/");
            exit;
        } catch (PDOException $erro_ed) {
            echo "ERRO AO EDITAR ESCALA! " . $erro_ed->getMessage();
        }
    } else {
        echo 'ERRO AO ATUALIZAR DADOS!';
    }
}

include_once 'header.php';
?>

<div class="container">
    <div class="top">
        <h1>Olá <span>LIMA ALVES</span> EDITAR SUA ESCALA!</h1>
    </div>
    <div class="conteudo">
        <form class="form_campo" action="" enctype="multipart/form-data" method="POST">
            <input type="hidden" name="id" value="<?php echo $registro['id']; ?>">
            <div class="form_input">
                <p>Operação: <span class="icon-taxi"></span></p>
                <input type="text" name="titulo" value="<?php echo $registro['titulo']; ?>" id="operacao" data-rules="required|min=8">
            </div>
            <div class="form_input">
                <p>Mês: <span class="icon-calendar"></span></p>
                <input type="text" name="mes" value="<?php echo $registro['mes']; ?>" id="mes" data-rules="required|min=3">
            </div>
            <div class="form_input">
                <p>Dias: <span>* separados por ; ex.: 21;22;23</span></p>
                <input type="text" name="dias" value="<?php echo $registro['dias']; ?>" id="dias" data-rules="required|min=2">
            </div>
            <div class="form_input">
                <p>Hora do serviço: <span class="icon-clock"></span></p>
                <input type="text" name="hora" value="<?php echo $registro['hora']; ?>" id="hora_servico" data-rules="required|min=2">
            </div>
            <div class="form_input">
                <p>Valor do serviço: <span class="icon-banknote"></span></p>
                <input type="text" name="valor" value="<?php echo $registro['valor']; ?>" id="valor_servico" data-rules="required|min=3">
            </div>
           
            <div class="form_input">
                <p>Status: <span class="icon-banknote"></span></p>
                <select name="status" id="status" data-rules="required">
                        <option value="0">NÃO PAGO</option>
                        <option value="1">PAGO</option>                        
                    </select>
            </div>
            <div class="form_input">
                <input type="submit" name="salvar" value="Salvar" class="btn">
            </div>
        </form>
        <p class="content-btn-cancelar"><a href="index.php" class="btn-cancelar">Cancelar edição</a></p>
    </div>
    <div class="footer"></div>
</div>

<?php include_once 'footer.php'; ?>
