<?php
/**
 * Este script lida com o registro de um novo administrador no sistema.
 * Ele inclui bibliotecas e classes, valida os dados do formulário enviado e executa ações correspondentes.
 */

// Inclui o arquivo com configurações gerais, funções ou inicializações necessárias
include '../libraries/header.php';

// Inclui o arquivo que contém a classe Authenticate, provavelmente usada para validação ou autenticação
include '../class/Authenticate.php';

/**
 * Função chamada quando a validação dos dados enviados pelo formulário é bem-sucedida.
 */
function onSuccessHandler() {

    // Declaração de variáveis globais que serão utilizadas na função
    global $config, $db, $error, $redis;

    // Instancia a classe Users, passando dependências como banco de dados e configuração
    $users = new Users($db, $config, $error, $redis);

    // Define o nome do usuário com base nos dados enviados no formulário ($_POST)
    $users->setUserName($_POST[$config->COL_userRegistration_username]);

    // Define o e-mail do usuário
    $users->setEmail($_POST[$config->COL_userRegistration_email]);

    // Define a senha do usuário
    $users->setPassword($_POST[$config->COL_userRegistration_password]);

    // Define o status do usuário como ativo ("1")
    $users->setStatus("1");

    // Adiciona o usuário como administrador e armazena a resposta
    $response = $users->addAdmin();

    // Retorna a resposta como JSON
    echo json_encode($response);
}

// Define os campos obrigatórios para o registro de um administrador
$required = array(
    $config->COL_userRegistration_username, // Campo para o nome do usuário
    $config->COL_userRegistration_email,    // Campo para o e-mail do usuário
    $config->COL_userRegistration_password  // Campo para a senha do usuário
);

// Função para validação de dados enviada pelo formulário
NvooyUtils::onSetAndEmptyCheckHandler(
    $_POST,             // Dados enviados via formulário (método POST)
    $required,          // Campos obrigatórios para validação
    $required,          // Requisitos adicionais (duplicados aqui)
    "onSuccessHandler", // Callback para quando a validação for bem-sucedida
    "onEmptyHandler",   // Callback para quando algum campo estiver vazio
    "onNotSetHandler",  // Callback para quando algum campo não estiver definido
    true                // Flag que pode indicar uma ação adicional (ex: interromper execução)
);
