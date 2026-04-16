<?php
require_once 'Selecao.php';
class SelecaoController {
    private $model;
    public function __construct($db) { $this->model = new Selecao($db); }

    public function listar() { return $this->model->readAll(); }

    public function salvar() {
        if (!empty($_POST['nome'])) {
            $this->model->create($_POST['nome'], $_POST['grupo'], $_POST['titulos']);
        }
        header("Location: index.php");
    }

    public function excluir($id) {
        $this->model->delete($id);
        header("Location: index.php");
    }
}