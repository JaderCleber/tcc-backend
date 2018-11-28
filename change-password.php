<?php
header("Access-Control-Allow-Origin: *");
include_once('config.php');
// verifica se o método é o esperado: POST
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  // captura os parâmetros enviados pelo front
  // utiliza a função nativa da ferramenta (php) para converter string em um hash md5
  $new = md5($_POST['new']);
  $current = md5($_POST['current']);
  $user = $_POST['user'];

  $conn->set_charset('utf8');
  // verifica se a senha atual recebida é igual a do banco
  $query = execQuery($conn, "SELECT `senha` FROM `" . $_SG['tuser'] . "` WHERE `codigo` = '$user' AND `senha` = '$current'");
  // verifica se pelo menos um registro foi recuperado do banco
  if ($query->num_rows > 0) {
    // realiza a troca da senha no banco pela nova recebida
    $query = execQuery($conn, "UPDATE `" . $_SG['tuser'] . "` SET `senha`='" . $new . "' WHERE `codigo` = '$user'");
    if ($query === true) {
      sendResponse(array("success" => true));
    } else {
      sendResponse(array("success" => false, "msg" => "Usuário não pode ser excluído" . $conn->error));
    }
  } else {
    sendResponse(array("success" => false, "msg" => "A senha atual está incorreta"));
  }
  // fecha a conexão com o banco
  $conn->close();
}

function sendResponse($response)
{
  echo json_encode($response);
}

function execQuery($conn, $sql)
{
  return $conn->query($sql);
}
?>