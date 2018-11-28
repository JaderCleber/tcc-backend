<?php

header("Access-Control-Allow-Origin: *");
include_once('config.php');
// verifica se o método é o esperado: POST
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  // captura os parâmetros enviados pelo front
  $email = $_POST['email'];
  $password = md5($_POST['password']);
  $conn->set_charset('utf8');
  // tenta recuperar usuário do banco
  $query = execQuery($conn, "SELECT `codigo`, `email`, `admin` FROM `" . $_SG['tuser'] . "` WHERE `email` = '$email' AND `senha` = '$password'");
  // verifica se pelo menos um registro foi recuperado do banco
  if ($query->num_rows > 0) {
    // percorre os registros recuperados do banco
    while ($row = $query->fetch_assoc()) {
      $user = $row;
    }
    // tenta recuperar cliente do banco
    $query = execQuery($conn, "SELECT * FROM `" . $_SG['tclient'] . "` WHERE `usuario` = '$user[codigo]'");
    // verifica se pelo menos um registro foi recuperado do banco
    if ($query->num_rows > 0) {
      // percorre os registros recuperados do banco
      while ($row = $query->fetch_assoc()) {
        $client = $row;
      }
    }
    sendResponse(array("success" => true, "user" => $user, "client" => $client));
  } else {
    // verifica se o email já existe no banco
    $query = execQuery($conn, "SELECT `codigo` FROM `" . $_SG['tuser'] . "` WHERE `email` = '$email'");
    // se nenhum registro retornar do banco cadastra o email recebido
    if ($query->num_rows === 0) {
      // cadastra o email criando uma conta de usuário
      $query = execQuery($conn, "INSERT INTO `" . $_SG['tuser'] . "` (`email`, `senha`) VALUES ('$email', '$password')");
      // se o banco responder com erros retorna mensagem de erro
      if ($query !== true) {
        sendResponse(array("success" => false, "msg" => "Um Erro Ocorreu: " . $conn->error));
      } else {
        // recupera informações geradas pelo cadastro: usuário e cliente
        $query = execQuery($conn, "SELECT `codigo`, `email`, `admin` FROM `" . $_SG['tuser'] . "` WHERE `email` = '$email' AND `senha` = '$password'");
        // percorre os registros recuperados do banco
        while ($row = $query->fetch_assoc()) {
          $user = $row;
        }
        $query = execQuery($conn, "INSERT INTO `" . $_SG['tclient'] . "` (`usuario`) VALUES ('$user[codigo]')");
        if ($query === true) {
          $query = execQuery($conn, "SELECT * FROM `" . $_SG['tclient'] . "` WHERE `usuario` = '$user[codigo]'");
          if ($query->num_rows > 0) {
            // percorre os registros recuperados do banco
            while ($row = $query->fetch_assoc()) {
              $client = $row;
            }
          }
        } else {
          sendResponse(array("success" => false, "msg" => "Um Erro Ocorreu: " . $conn->error));
        }
        sendResponse(array("success" => true, "user" => $user, "client" => $client));
      }
    } else {
      sendResponse(array("success" => false, "msg" => "Acesso Negado"));
    }
  }
}
$conn->close();

function execQuery($conn, $sql)
{
  return $conn->query($sql);
}

function sendResponse($response)
{
  echo json_encode($response);
}
?>