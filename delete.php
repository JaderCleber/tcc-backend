<?php
header("Access-Control-Allow-Origin: *");
include_once('config.php');
// verifica se o método é o esperado: POST
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  // captura os parâmetros enviados pelo front
  $client = $_POST['client'];
  $user = $_POST['user'];
  // tenta deletar cliente do banco
  $query = execQuery($conn, "DELETE FROM `" . $_SG['tclient'] . "` WHERE `id` = '$client'");
  if ($query === true) {
    // tenta deletar usuário do banco
    $query = execQuery($conn, "DELETE FROM `" . $_SG['tuser'] . "` WHERE `codigo` = '$user'");
    if ($query === true) {
      sendResponse(array("success" => true));
    } else {
      sendResponse(array("success" => false, "msg" => "Usuário não pode ser excluído" . $conn->error));
    }
  } else {
    sendResponse(array("success" => false, "msg" => "Conta não pode ser excluída" . $conn->error));
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