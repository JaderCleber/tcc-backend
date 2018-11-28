<?php

header("Access-Control-Allow-Origin: *");
include_once('config.php');
// verifica se o método é o esperado: POST
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  // captura os parâmetros enviados pelo front
  $name = $_POST['name'];
  $cep = $_POST['cep'];
  $address = $_POST['address'];
  $number = $_POST['number'];
  $state = $_POST['state'];
  $city = $_POST['city'];
  $cpf = $_POST['cpf'];
  $phone = $_POST['phone'];
  $client = $_POST['client'];
  // $email = $_POST['email'];

  $conn->set_charset('utf8');
  // tenta atualizar informações do cliente no banco
  $query = execQuery($conn, "UPDATE `" . $_SG['tclient'] . "` 
  SET `nome`='" . $name . "', 
  `cep`='" . $cep . "', 
  `endereco`='" . $address . "',
  `numero`='" . $number . "',
  `estado`='" . $state . "',
  `municipio`='" . $city . "',
  `cpf`='" . $cpf . "',
  `telefone`='" . $phone . "' WHERE `id` = '$client'");
  // se o banco executou sem erros
  if ($query === true) {
    sendResponse(array("success" => true));
  } else {
    sendResponse(array("success" => false, "msg" => "Usuário não pode ser excluído" . $conn->error));
  }
  echo json_encode($json);
  $conn->close();
}

function execQuery($conn, $sql)
{
  return $conn->query($sql);
}

function sendResponse($response)
{
  echo json_encode($response);
}
?>