<?php

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

$method = $_SERVER['REQUEST_METHOD'];

require_once 'db.class.php';

if(isset($base) && $base == 'banco'){
    if($method == "GET"){ //  ----> http://localhost:8080/banco/
        $db = Database::conectar();

        $query = $db->query("SELECT * FROM banco");
        $resultados = $query->fetchAll(PDO::FETCH_ASSOC);
        
        if($resultados)
            echo json_encode($resultados);
        else
            echo "Não há usuários cadastrados no banco de dados!";
    }
}

if(isset($add) && $add == 'adicionar'){ 
    if($method === 'POST'){ //  ----> http://localhost:8080/banco/adicionar
        $dados = json_decode(file_get_contents('php://input'), true);
        
        if(isset($dados['CNPJ']) && isset($dados['email']) && isset($dados['ceo']) && isset($dados['data_fundacao']) && isset($dados['numero']) && isset($dados['cep'])){
            $cnpj = $dados['CNPJ'];
            $email = $dados['email'];
            $ceo = $dados['ceo'];
            $data_fundacao = $dados['data_fundacao'];
            $numero = $dados['numero'];
            $cep = $dados['cep'];
            $complemento = isset($dados['complemento']) ? $dados['complemento'] : NULL;
            $numero_funcionarios = isset($dados['numero_funcionarios']) ? $dados['numero_funcionarios'] : NULL;
            $db = Database::conectar();

            $addUsuarioStmt = $db->prepare('INSERT INTO banco("CNPJ", "email", "ceo", "data_fundacao", "numero", "cep", "numero_funcionarios", "complemento") VALUES (:cnpj, :email, :ceo, :data_fundacao, :numero, :cep, :numero_funcionarios, :complemento)');

            $addUsuarioStmt->bindParam(':cnpj', $cnpj);
            $addUsuarioStmt->bindParam(':email', $email);
            $addUsuarioStmt->bindParam(':ceo', $ceo);
            $addUsuarioStmt->bindParam(':data_fundacao', $data_fundacao);
            $addUsuarioStmt->bindParam(':numero', $numero);
            $addUsuarioStmt->bindParam(':cep', $cep);
            $addUsuarioStmt->bindParam(':numero_funcionarios', $numero_funcionarios);
            $addUsuarioStmt->bindParam(':complemento', $complemento);
            
            
            try{
                $addUsuarioStmt->execute();
                echo "SUCESSO: Usuário inserido com sucesso!";
            }catch(PDOException $e){
                echo "ERRO: Não foi possível inserir no banco de dados. " . $e->getMessage();
            }

        }else
            echo "ERRO: Preencha todos os dados.";
    }
}

?>
