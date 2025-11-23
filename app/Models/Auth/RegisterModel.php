<?php

/*
Dans un controleur:

$userModel = new UserModel();
$userModel->createUser($_POST);

Dans un model :

public function createAccount($data) {
    $stmt = $this->db->prepare("INSERT INTO users (nom, prenom, email) VALUES (?, ?, ?)");
    $stmt->execute([$data['nom'], $data['prenom'], $data['email']]);
}
*/
?>