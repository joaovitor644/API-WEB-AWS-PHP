<?php
$method = $_SERVER['REQUEST_METHOD'];

require_once 'db.class.php';

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

if(isset($_GET['path'])){
    $path = explode("/", $_GET['path']);

    if(isset($path[0]))
        $base = $path[0];
    else
        echo "Caminho não encontrado";

    if(isset($path[1]))
        $add = $path[1];
    else
        $add = '';
}
else
    echo "Acesse o caminho , exemplo: .../banco/";



if(isset($base) && $base == 'banco'){

    if(isset($add) && $add == 'adicionar'){ 
        if($method === 'POST'){ //  ----> http://localhost:8080/banco/adicionar

            $dados = json_decode(file_get_contents('php://input'), true);

            if(isset($path[2]) && $path[2] == 'bloqueio'){ // ----> http://localhost:8080/banco/adicionar/bloqueio
               if(isset($dados['Status']) && isset($dados['Data']) && isset($dados['NUMERO']) && isset($dados['ID_CLIENTE'])){

                    $status = $dados['Status'];
                    $data = $dados['Data'];
                    $numero = $dados['NUMERO'];
                    $id_cliente = $dados['ID_CLIENTE'];
                    $cod_gerencia = isset($dados['COD_GERENCIA']) ? $dados['COD_GERENCIA'] : NULL;

                    $db = Database::conectar();

                    $addBloqueioStmt = $db->prepare('INSERT INTO mydb.Bloqueio(Status, Data, NUMERO, ID_CLIENTE, COD_GERENCIA) 
                                                     VALUES (:status, :data, :numero, :id_cliente, :cod_gerencia)');

                    $addBloqueioStmt->bindParam(':status', $status);
                    $addBloqueioStmt->bindParam(':data', $data);
                    $addBloqueioStmt->bindParam(':numero', $numero);
                    $addBloqueioStmt->bindParam(':id_cliente', $id_cliente);
                    $addBloqueioStmt->bindParam(':cod_gerencia', $cod_gerencia);

                    try {
                        $addBloqueioStmt->execute();
                        echo "SUCESSO: Bloqueio inserido com sucesso!";
                    } catch (PDOException $e) {
                        echo "ERRO: Não foi possível inserir no banco de dados. " . $e->getMessage();
                    }

                } else {
                    echo "ERRO: Preencha todos os dados obrigatórios.";
                }

            } elseif(isset($path[2]) && $path[2] == 'gerente'){ // ----> http://localhost:8080/banco/adicionar/gerente
                if(isset($dados['COD_GERENCIA']) && isset($dados['CPF_funcionario'])){

                    $cod_gerencia = $dados['COD_GERENCIA'];
                    $cpf_funcionario = $dados['CPF_funcionario'];

                    $db = Database::conectar();

                    $addGerenteStmt = $db->prepare('INSERT INTO mydb.Gerente(COD_GERENCIA, CPF_funcionario) 
                                                    VALUES (:cod_gerencia, :cpf_funcionario)');

                    $addGerenteStmt->bindParam(':cod_gerencia', $cod_gerencia);
                    $addGerenteStmt->bindParam(':cpf_funcionario', $cpf_funcionario);

                    try {
                        $addGerenteStmt->execute();
                        echo "SUCESSO: Gerente inserido com sucesso!";
                    } catch (PDOException $e) {
                        echo "ERRO: Não foi possível inserir no banco de dados. " . $e->getMessage();
                    }

                } else {
                    echo "ERRO: Preencha todos os dados obrigatórios.";
                }

            } elseif(isset($path[2]) && $path[2] == 'conta'){ // ----> http://localhost:8080/banco/adicionar/conta
                  $dados = json_decode(file_get_contents('php://input'), true);
                echo json_encode($dados) . "\n";
               if(isset($dados['NUMERO']) && isset($dados['Status']) && isset($dados['data_abertura']) && isset($dados['Conjunta']) && isset($dados['senha']) && isset($dados['COD_GERENCIA']) && isset($dados['Numero_agencia']) && isset($dados['ID_Cliente'])){
                    $numero = $dados['NUMERO'];
                    $status = $dados['Status'];
                    $data_abertura = $dados['data_abertura'];
                    $conjunta = $dados['Conjunta'];
                    $senha = $dados['senha'];
                    $cod_gerencia = $dados['COD_GERENCIA'];
                    $numero_agencia = $dados['Numero_agencia'];
                    $id_cliente = $dados['ID_Cliente'];
                    $saldo = isset($dados['saldo']) ? $dados['saldo'] : 0.0;
                    $taxa = isset($dados['Taxa']) ? $dados['Taxa'] : 0.0;

                    $db = Database::conectar();

                    $addContaStmt = $db->prepare('INSERT INTO mydb.Conta(NUMERO, Status, data_abertura, Conjunta, senha, COD_GERENCIA, saldo, Taxa, Numero_agencia, ID_Cliente) 
                                                  VALUES (:numero, :status, :data_abertura, :conjunta, :senha, :cod_gerencia, :saldo, :taxa, :numero_agencia, :id_cliente)');

                    $addContaStmt->bindParam(':numero', $numero);
                    $addContaStmt->bindParam(':status', $status);
                    $addContaStmt->bindParam(':data_abertura', $data_abertura);
                    $addContaStmt->bindParam(':conjunta', $conjunta);
                    $addContaStmt->bindParam(':senha', $senha);
                    $addContaStmt->bindParam(':cod_gerencia', $cod_gerencia);
                    $addContaStmt->bindParam(':saldo', $saldo);
                    $addContaStmt->bindParam(':taxa', $taxa);
                    $addContaStmt->bindParam(':numero_agencia', $numero_agencia);
                    $addContaStmt->bindParam(':id_cliente', $id_cliente);

                    try {
                        $addContaStmt->execute();
                        echo "SUCESSO: Conta inserida com sucesso!";
                    } catch (PDOException $e) {
                        echo "ERRO: Não foi possível inserir no banco de dados. " . $e->getMessage();
                    }

                } else {
                    echo "ERRO: Preencha todos os dados obrigatórios.";
                }

            }
        }
    }   
    if(isset($add) && $add == 'listar'){ 
        if($method === 'GET'){ //  ----> http://localhost:8080/banco/listar

            $dados = json_decode(file_get_contents('php://input'), true);

            if(isset($path[2]) && $path[2] == 'bloqueio'){ // ----> http://localhost:8080/banco/listar/bloqueio
               $db = Database::conectar();

                $query = $db->query("SELECT * FROM mydb.bloqueio");
                $resultados = $query->fetchAll(PDO::FETCH_ASSOC);
                
                if($resultados)
                    echo json_encode($resultados);
                else
                    echo "Não há bloqueios cadastrados no banco de dados!\n";
            } elseif(isset($path[2]) && $path[2] == 'gerente'){ // ----> http://localhost:8080/banco/listar/gerente
                $db = Database::conectar();

                $query = $db->query("SELECT * FROM mydb.gerente");
                $resultados = $query->fetchAll(PDO::FETCH_ASSOC);
                
                if($resultados)
                    echo json_encode($resultados);
                else
                    echo "Não há gerentes cadastrados no banco de dados!\n";
            } elseif(isset($path[2]) && $path[2] == 'conta'){ // ----> http://localhost:8080/banco/listar/conta
                $db = Database::conectar();

                $query = $db->query("SELECT * FROM mydb.conta");
                $resultados = $query->fetchAll(PDO::FETCH_ASSOC);
                
                if($resultados)
                    echo json_encode($resultados);
                else
                    echo "Não há contas cadastrados no banco de dados!\n";
            }
        }
    }
    if(isset($add) && $add == 'atualizar'){ 
        if($method === 'POST'){ //  ----> http://localhost:8080/banco/atualizar

            $dados = json_decode(file_get_contents('php://input'), true);

            if(isset($path[2]) && $path[2] == 'bloqueio'){ // ----> http://localhost:8080/banco/atualizar/bloqueio
               if(isset($dados['NUMERO']) && isset($dados['ID_CLIENTE']) && isset($dados['Status']) && isset($dados['Data'])) {
                    $numero = $dados['NUMERO'];
                    $id_cliente = $dados['ID_CLIENTE'];
                    $status = $dados['Status'];
                    $data = $dados['Data'];
                    $cod_gerencia = isset($dados['COD_GERENCIA']) ? $dados['COD_GERENCIA'] : NULL;

                    $db = Database::conectar();

                    $updateBloqueioStmt = $db->prepare('UPDATE mydb.Bloqueio 
                                                        SET Status = :status, Data = :data, COD_GERENCIA = :cod_gerencia 
                                                        WHERE NUMERO = :numero AND ID_CLIENTE = :id_cliente');

                    $updateBloqueioStmt->bindParam(':status', $status);
                    $updateBloqueioStmt->bindParam(':data', $data);
                    $updateBloqueioStmt->bindParam(':cod_gerencia', $cod_gerencia);
                    $updateBloqueioStmt->bindParam(':numero', $numero);
                    $updateBloqueioStmt->bindParam(':id_cliente', $id_cliente);

                    try {
                        $updateBloqueioStmt->execute();
                        if ($updateBloqueioStmt->rowCount() > 0) {
                            echo "SUCESSO: Bloqueio atualizado com sucesso!";
                        } else {
                            echo "ERRO: Nenhum bloqueio encontrado com o número e cliente fornecidos ou não houve alteração nos dados.";
                        }
                    } catch (PDOException $e) {
                        echo "ERRO: Não foi possível atualizar o bloqueio. " . $e->getMessage();
                    }
                } else {
                    echo "ERRO: Preencha todos os dados obrigatórios (NUMERO, ID_CLIENTE, Status e Data).";
                }

            } elseif(isset($path[2]) && $path[2] == 'gerente'){ // ----> http://localhost:8080/banco/atualizar/gerente
                if (isset($dados['COD_GERENCIA']) && isset($dados['CPF_funcionario'])) {
                    $cod_gerencia = $dados['COD_GERENCIA'];
                    $cpf_funcionario = $dados['CPF_funcionario'];

                    $db = Database::conectar();

                    $updateGerenteStmt = $db->prepare('UPDATE mydb.Gerente 
                                                       SET CPF_funcionario = :cpf_funcionario 
                                                       WHERE COD_GERENCIA = :cod_gerencia');

                    $updateGerenteStmt->bindParam(':cpf_funcionario', $cpf_funcionario);
                    $updateGerenteStmt->bindParam(':cod_gerencia', $cod_gerencia);

                    try {
                        $updateGerenteStmt->execute();
                        if ($updateGerenteStmt->rowCount() > 0) {
                            echo "SUCESSO: Gerente atualizado com sucesso!";
                        } else {
                            echo "ERRO: Nenhum gerente encontrado com o código de gerência fornecido ou não houve alteração nos dados.";
                        }
                    } catch (PDOException $e) {
                        echo "ERRO: Não foi possível atualizar o gerente. " . $e->getMessage();
                    }

                } else {
                    echo "ERRO: Preencha todos os dados obrigatórios (COD_GERENCIA e CPF_funcionario).";
                }

            } elseif(isset($path[2]) && $path[2] == 'conta'){ // ----> http://localhost:8080/banco/atualizar/conta

                if (isset($dados['NUMERO']) && isset($dados['Status']) && isset($dados['saldo']) && isset($dados['Taxa'])) {
                    $numero = $dados['NUMERO'];
                    $status = $dados['Status'];
                    $saldo = $dados['saldo'];
                    $taxa = $dados['Taxa'];

                    $db = Database::conectar();

                    $updateContaStmt = $db->prepare('UPDATE mydb.Conta 
                                                     SET Status = :status, saldo = :saldo, Taxa = :taxa 
                                                     WHERE NUMERO = :numero');

                    $updateContaStmt->bindParam(':status', $status);
                    $updateContaStmt->bindParam(':saldo', $saldo);
                    $updateContaStmt->bindParam(':taxa', $taxa);
                    $updateContaStmt->bindParam(':numero', $numero);

                    try {
                        $updateContaStmt->execute();
                        if ($updateContaStmt->rowCount() > 0) {
                            echo "SUCESSO: Conta atualizada com sucesso!";
                        } else {
                            echo "ERRO: Nenhuma conta encontrada com o número fornecido ou não houve alteração nos dados.";
                        }
                    } catch (PDOException $e) {
                        echo "ERRO: Não foi possível atualizar a conta. " . $e->getMessage();
                    }

                } else {
                    echo "ERRO: Preencha todos os dados obrigatórios (NUMERO, Status, saldo e Taxa).";
                }
            }
        }
    }
    if(isset($add) && $add == 'remover'){ 
        if($method === 'POST'){ //  ----> http://localhost:8080/banco/remover

            $dados = json_decode(file_get_contents('php://input'), true);

            if(isset($path[2]) && $path[2] == 'bloqueio'){ // ----> http://localhost:8080/banco/remover/bloqueio
               
                if(isset($dados['NUMERO']) && isset($dados['ID_CLIENTE'])) {
                    $numero = $dados['NUMERO'];
                    $id_cliente = $dados['ID_CLIENTE'];

                    $db = Database::conectar();

                    $deleteBloqueioStmt = $db->prepare('DELETE FROM mydb.Bloqueio WHERE NUMERO = :numero AND ID_CLIENTE = :id_cliente');

                    $deleteBloqueioStmt->bindParam(':numero', $numero);
                    $deleteBloqueioStmt->bindParam(':id_cliente', $id_cliente);

                    try {
                        $deleteBloqueioStmt->execute();
                        if ($deleteBloqueioStmt->rowCount() > 0) {
                            echo "SUCESSO: Bloqueio deletado com sucesso!";
                        } else {
                            echo "ERRO: Nenhum bloqueio encontrado com o número e cliente fornecidos.";
                        }
                    } catch (PDOException $e) {
                        echo "ERRO: Não foi possível deletar o bloqueio. " . $e->getMessage();
                    }

                } else {
                    echo "ERRO: Preencha os dados obrigatórios (NUMERO e ID_CLIENTE).";
                }

            } elseif(isset($path[2]) && $path[2] == 'gerente'){ // ----> http://localhost:8080/banco/remover/gerente
                if (isset($dados['COD_GERENCIA'])) {
                    $cod_gerencia = $dados['COD_GERENCIA'];

                    $db = Database::conectar();

                    $deleteGerenteStmt = $db->prepare('DELETE FROM mydb.Gerente WHERE COD_GERENCIA = :cod_gerencia');

                    $deleteGerenteStmt->bindParam(':cod_gerencia', $cod_gerencia);

                    try {
                        $deleteGerenteStmt->execute();
                        if ($deleteGerenteStmt->rowCount() > 0) {
                            echo "SUCESSO: Gerente deletado com sucesso!";
                        } else {
                            echo "ERRO: Nenhum gerente encontrado com o código de gerência fornecido.";
                        }
                    } catch (PDOException $e) {
                        echo "ERRO: Não foi possível deletar o gerente. " . $e->getMessage();
                    }

                } else {
                    echo "ERRO: Preencha o código de gerência.";
                }

            } elseif(isset($path[2]) && $path[2] == 'conta'){ // ----> http://localhost:8080/banco/remover/conta
                if (isset($dados['NUMERO'])) {
                    $numero = $dados['NUMERO'];

                    $db = Database::conectar();

                    $deleteContaStmt = $db->prepare('DELETE FROM mydb.Conta WHERE NUMERO = :numero');

                    $deleteContaStmt->bindParam(':numero', $numero);

                    try {
                        $deleteContaStmt->execute();
                        if ($deleteContaStmt->rowCount() > 0) {
                            echo "SUCESSO: Conta deletada com sucesso!";
                        } else {
                            echo "ERRO: Nenhuma conta encontrada com o número fornecido.";
                        }
                    } catch (PDOException $e) {
                        echo "ERRO: Não foi possível deletar a conta. " . $e->getMessage();
                    }

                } else {
                    echo "ERRO: Preencha o número da conta.";
                }
            }
        }
    }
    if(isset($add) && $add == 'mudar_gerente_trans' && $method === 'POST'){
    	
        // 1. adiciona um novo gerente x
        // 2. atualiza todas as contas do gerente solicitado da remoção para o novo o COD_GERENCIA x 
        // 3. Remove gerente 
        // 4. mostrar todas as contas do novo gerente

        $dados = json_decode(file_get_contents('php://input'), true);
        if(1){
                    $flag1 = 0;
                    $flag2 = 0;
                    $flag3 = 0;
                    $cod_gerencia_n = $dados['COD_GERENCIA_NEW'];
                    $cpf_funcionario_n = $dados['CPF_funcionario_NEW'];
                    $cod_gerencia_r  = $dados['COD_GERENCIA_RM'];
                    echo $cod_gerencia_n;
                    echo $cod_gerencia_r;
                    echo $cpf_funcionario_n;
                    $db = Database::conectar();
                    $db->beginTransaction();

                    $addGerenteStmt = $db->prepare('INSERT INTO mydb.Gerente(COD_GERENCIA, CPF_funcionario) 
                                                    VALUES (:cod_gerencia, :cpf_funcionario)');

                    $addGerenteStmt->bindParam(':cod_gerencia', $cod_gerencia_n);
                    $addGerenteStmt->bindParam(':cpf_funcionario', $cpf_funcionario_n);

                    try {
                        $addGerenteStmt->execute();
                        echo "SUCESSO: Gerente inserido com sucesso!";
                    } catch (PDOException $e) {
                        echo "ERRO: Não foi possível inserir no banco de dados. " . $e->getMessage();
                        $db->rollBack();
                        $fla1 = 1;
                    }
                    if(!$flag1){
                        $updateContaStmt = $db->prepare('UPDATE mydb.Conta 
                                                         SET COD_GERENCIA = :cod_gerencia_n
                                                         WHERE COD_GERENCIA = :cod_gerencia_r');

                        $updateContaStmt->bindParam(':cod_gerencia_r', $cod_gerencia_r);
                        $updateContaStmt->bindParam(':cod_gerencia_n', $cod_gerencia_n);

                        try {
                            $updateContaStmt->execute();
                            echo "SUCESSO: Contas Atualizadas Com sucesso";
                        } catch (PDOException $e) {
                            echo "ERRO: Não foi possível atualizar contas no banco de dados. " . $e->getMessage();
                            $db->rollBack();
                            $flag2 = 1;
                        }
                    }
                    if(!$flag2){


                        $deleteGerenteStmt = $db->prepare('DELETE FROM mydb.Gerente WHERE COD_GERENCIA = :cod_gerencia');
                        $deleteGerenteStmt->bindParam(':cod_gerencia', $cod_gerencia_r);

                        try {
                            $deleteGerenteStmt->execute();
                            if ($deleteGerenteStmt->rowCount() > 0) {
                                echo "SUCESSO: Gerente deletado com sucesso!";
                            } else {
                                echo "ERRO: Nenhum gerente encontrado com o código de gerência fornecido.";
                            }
                        } catch (PDOException $e) {
                            echo "ERRO: Não foi possível deletar o gerente. " . $e->getMessage();
                            $db->rollBack();
                            $flag3 = 1;
                        }

                    }
                    if(!$flag3){



                        $query = $db->query("SELECT * FROM mydb.conta WHERE COD_GERENCIA = " . "'" . $cod_gerencia_n . "'" );
                        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);
                        
                        if($resultados)
                            echo json_encode($resultados);
                        else
                            echo "Não há contas cadastrados no banco de dados!\n";
                    }
                    $db->commit();
                    

                } else {
                    echo "ERRO: Preencha todos os dados obrigatórios.";
                }
    }

}


?>
