<?php
    require '../conexao/config.php';
    include_once 'header.php';
    date_default_timezone_set('America/Sao_paulo');
    

    // Consulta o total de registros na tabela escala_extra
    $sql_total_registros = "SELECT  COUNT(*) as total_registros FROM escala_extra";
    try {
        $stmt = $pdo->prepare($sql_total_registros);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $total_registros = $resultado['total_registros'];
        $meta_mensal     = $resultado['meta'];
    } catch (PDOException $e) {
        echo "Erro ao contar registros: " . $e->getMessage();
    }

    
   
    ?>
    


    <div class="container">
        <div class="top">
        <?php 
          $sql_lista_user = "SELECT * FROM  users";
          try{
              $sql = $pdo->query($sql_lista_user);
              if($sql->rowCount() > 0){
                  $res_User = $sql->fetchALL();
                  foreach($res_User as $item){
                    $user_id             = $item['id_user'];                           
                    $user_name           = $item['name_user'];
                    $user_matricula      = $item['matricula_user'];
                    $user_posto_gradu	 = $item['user_posto_gradu'];
                    $user_senha          = $item['senha_user'];
                    $user_thambnail      = $item['user_thambnail'];
                  }

              }else{
                  echo "NÃO HÁ DADOS CADASTRADO!";
              }  


              
          
          }catch(PDOException $erro_lista){
              echo "ERRO AO LISTAR ESCALA! ".$erro_lista->getMessage();
          }
        ?>
            <div class="infor_user">              
                <?php
                    if(!empty($user_thambnail)){
                        echo " <img src='img/".$user_thambnail."' alt='' class='infor_user_thamb'>";
                    }else{
                        echo "<i class='bx bxs-user'></i>";
                    }
                ?> 
                
                    <h1 class="infor_user_title"><a href="#" class="infor_user_link_perfil"><?=$user_name;?></a></h1>
                    <div class="infor_user_post_gradu_mat">
                        <p class="user_post"><?=$user_posto_gradu;?></p>
                        <p class="user_mat">/ <?=$user_matricula;?></p>                   
                    </div>
                
            </div>              
            
           
        </div>
        <div class="conteudo">
            <form class="form_campo" action="add.php" enctype="multipart/form-data" method="POST">
                <div class="form_input">
                    <p>Operação: <span class="icon-taxi"></span></p>
                    <input type="text" name="titulo" id="operacao" data-rules="required|min=8">
                </div>
                <div class="form_input">
                    <p>Mês: <span class="icon-calendar"></span></p>
                    <select name="mes" id="mes" data-rules="required">
                        <option value="">SELECIONE O MÊS</option>
                        <option value="01">JANEIRO</option>
                        <option value="02">FEVEREIRO</option>
                        <option value="03">MARÇO</option>
                        <option value="04">ABRIL</option>
                        <option value="05">MAIO</option>
                        <option value="06">JUNHO</option>
                        <option value="07">JULHO</option>
                        <option value="08">AGOSTO</option>
                        <option value="09">SETEMBRO</option>
                        <option value="10">OUTUBRO</option>
                        <option value="11">NOVEMBRO</option>
                        <option value="12">DEZEMBRO</option>
                    </select>
                </div>
                <div class="form_input">
                    <p>Dias: <span>* separados por ; ex.: 21;22;23</span></p>
                    <input type="text" value="" name="dias" id="dias" data-rules="required|min=2">
                </div>
                <div class="form_input">
                    <p>Hora do Serviço: <span class="icon-clock"></span></p>
                    <input type="text" value="" name="hora" id="hora_servico" data-rules="required|min=2">
                </div>
                <div class="form_input">
                    <p>Valor do Serviço: <span class="icon-banknote"></span></p>
                    <input type="text" value="" name="valor" id="valor_servico" data-rules="required|min=3">
                </div>
                
                <div class="form_input">
                    <input type="submit" name="salvar" value="Salvar" class="btn">
                </div>
            </form>
            <section>
                <section class="totalidade-somatorio">
                    <div class="somatorio">
                    
                        <?php
                        try {
                            // Mapeamento dos números dos meses para os nomes dos meses
                            $mesesNomes = array(
                                '1' => 'Janeiro',
                                '2' => 'Fevereiro',
                                '3' => 'Março',
                                '4' => 'Abril',
                                '5' => 'Maio',
                                '6' => 'Junho',
                                '7' => 'Julho',
                                '8' => 'Agosto',
                                '9' => 'Setembro',
                                '10' => 'Outubro',
                                '11' => 'Novembro',
                                '12' => 'Dezembro'
                            );
                        
                            // Consulta SQL para somar o total por mês
                            $sql = "SELECT YEAR(criacao) AS ano, MONTH(criacao) AS mes, SUM(total) AS total_mes, meta
                                    FROM escala_extra WHERE YEAR(criacao) = YEAR(CURDATE()) and status = '0' 
                                    GROUP BY YEAR(criacao), MONTH(criacao)
                                    ORDER BY ano, mes";
                        
                            // Executar a consulta
                            $stmt = $pdo->query($sql);
                        
                            // Exibir os resultados
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $ano            = $row['ano'];
                                $mes            = $row['mes'];
                                $total_mes      = $row['total_mes'];
                                $meta_financ    = $row['meta'];
                               
                                
                               
                        
                                
                                if (array_key_exists($mes, $mesesNomes)) {
                                    $mes_nome = $mesesNomes[$mes];
                                } else {
                                    $mes_nome = ''; // Caso não exista o mês no mapeamento, deixamos vazio ou você pode fornecer uma mensagem de erro
                                }
                               
                                //
                                    
                                    
                                    //echo '<div class="legenda">SEM VALOR PARA RECEBER [ '.$ano.' ]</div>';
                                    //echo "<div><p>Infelizmente ainda não tem valores a receber!</p></div>";
                                   
                                    echo '<div class="legenda">VALORES A RECEBER ANO [ '.$ano.' ]</div>';
                                    echo "<div>
                                    <p>Mês: <strong>$mes_nome</strong>, Valor a receber: <strong>R$ $total_mes</strong></p>
                                    </div>";


                                   
                            
                            }
                           
                            
                        } catch (PDOException $e) {
                            echo "Erro: " . $e->getMessage();
                        }
                    
                        ?>
                    </div>

                   
                    <div class="totalidade">
                    <div class="legenda">TOTAL DE ESCALAS</div>
                        <p><?=$total_registros;?></p>
                    </div>
                    

                    
                </section>
                <div class="rel-lista-dados">
                <?php //aqui vai mostrar todas as escalas do ano corrente
                        $sql_lista = "SELECT * FROM escala_extra WHERE YEAR(criacao) = YEAR(CURDATE()) ORDER BY id DESC;";
                        try{
                            $sql = $pdo->query($sql_lista);
                            if($sql->rowCount() > 0){
                                $res = $sql->fetchALL();

                            }else{
                                echo "NÃO HÁ DADOS CADASTRADO!";
                            }  


                            
                        
                        }catch(PDOException $erro_lista){
                            echo "ERRO AO LISTAR ESCALA! ".$erro_lista->getMessage();
                        }
                        $dia = array();
                        foreach($res as $item){
                            $es_id          = $item['id'];                           
                            $es_titulo      = $item['titulo'];
                            $es_mes         = $item['mes'];
                            $diasServ       = $item['dias'];
                            $dia            = $item['dias'];
                            $status         = $item['status'];                           
                            $total_valor    = $item['total'];
                            //$qt_dias    = explode(',', $dia);
                            $qt_dias = preg_split("/[ -,;]/", $dia);

                            $qt_dias = array_filter($qt_dias, function ($value) {
   
                                return trim($value) !== "";
                            });
                            
                           
                            $res_dias   = count($qt_dias);
                            $es_hora    = $item['hora'];
                            $es_valor   = intval($item['valor']);
                            $total      = ($res_dias * $es_valor);
                            $diaAtual  = date('d');

                            try {
                                // Suponha que você já tenha uma conexão PDO chamada $pdo
                            
                                // Suponha que você já tenha o valor do ID armazenado em $id
                                //$total = 100; // Substitua 100 pelo valor que você deseja inserir
                                $es_id          = $item['id'];  
                                $total          = ($res_dias * $es_valor);
                            
                                // Preparar a declaração SQL
                               // Preparar a declaração SQL
                            $stmt = $pdo->prepare("INSERT INTO escala_extra (id, total) VALUES (:id, :total) ON DUPLICATE KEY UPDATE total = :total");

                            // Vincular parâmetros
                            $stmt->bindParam(':id', $es_id, PDO::PARAM_INT);
                            $stmt->bindParam(':total', $total, PDO::PARAM_INT);

                            // Executar a inserção
                            $stmt->execute();

                            //echo "Inserção bem-sucedida!";
                            } catch (PDOException $e) {
                                echo "Erro: " . $e->getMessage();
                            }
                           


    ?>
                
                                <div class="card_op <?php if($status == 0)echo 'card_op-areceber';else{echo 'card_op-pago';}?>">
                                 <div class="text"><p>Diária Paga</p></div>
                                 
                                 
                                <p class="title"><span>Operação : </span> <?php  echo $es_titulo;?> R$ <?php   echo $es_valor;?>,00</p>
                                <p><span>Mês : </span> <?php echo $es_mes;?></p>
                                <p><span>horário : </span> <?php echo $es_hora;?>h</p>
                                
                                <p><span>DIAS : </span>
                                <div class="circulo">
                                    <?php  $d = explode(',', $diasServ);
                                            foreach($d as $diaDoServico){
                                                
                                                   
                                                    if($diaAtual === $diaDoServico){
                                                        echo "<span class='circulo_dias_atual'>".$diaDoServico."</span>";
                                                        echo "<div class='card_op_tirar'>Atenção: Tirar o Serviço</div>";
                                                    } else{
                                                        echo "<span class='circulo_dias'>".$diaDoServico."</span>";
                                                    }                                                  
                                             
                                                
                                               
                                           
                                            }

                                           
                                    ?>
                                </div>
                                </p>
                                
                                
                                
                                
                                <p><span>QNT SERVIÇOS : </span> <?php  echo $res_dias;?></p>                
                                <p class="VT"><span>Total : </span> R$ <?php  echo number_format($total_valor, '2', ',', '.'); ?></p>
                                <hr>
                                <div class="links">
                                    <a href="editar.php?id=<?php  echo $es_id;?>" alte="">Editar </a> <a href="deletar.php?id=<?php  echo $es_id;?>" alte=""> Deletar</a>
                                </div>
                            </div> 
                            
                            <?php
                        }
                    ?>
                                     
            
                </div>
            </section>
        </div>
        <div class="footer"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>