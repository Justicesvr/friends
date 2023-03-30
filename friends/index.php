<?php
require_once 'connec.php';
$pdo = new \PDO(DSN, USER, PASS);

$query = "SELECT * FROM friend";
$statement = $pdo->query($query);
$friends = $statement->fetchAll(PDO::FETCH_BOTH); 

var_dump($friends);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $friends = array_map('trim', $_POST);

if (empty($friends['firstname'])) {
  $friends[] = 'Le champ prénom est obligatoire';
}
if (empty($friends['lastname'])) {
  $friends[] = 'Le champ Nom est obligatoire';
}
$maxFirstnameLength = 45;
if (mb_strlen($friends['firstname']) > $maxFirstnameLength) {
    $errors[] = 'Le champ prénom doit faire moins de ' . $maxFirstnameLength . ' caractères';
}
$maxLastnameLength = 45;
if (mb_strlen($friends['lastname']) > $maxLastnameLength) {
    $errors[] = 'Le champ Nom doit faire moins de ' . $maxLastnameLength . ' caractères';
}
$query = "INSERT INTO friend (firstname, lastname)
VALUES(:firstname, :lastname)";
$statement = $pdo->prepare($query);
$statement->bindValue(':firstname', $friends['firstname'], PDO::PARAM_STR);
$statement->bindValue(':lastname', $friends['lastname'], PDO::PARAM_STR);

$statement->execute();
header('Location: index.php'); 
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire</title>
</head>
<body>
  <div class="list">
<ul>
<?php foreach ($friends as $friend) : ?>
  <li><?= $friend['firstname'] . ' ' . $friend['lastname'];?></li>
  <?php endforeach ?>
</ul>
  </div>
  <div class="formulaire">
<form  action=""  method="POST">
    <div>
      <label  for="firstname">Firstname :</label>
      <input  type="text"  id="firstname"  name="firstname" value="" maxlength="80" required>
    </div>
    <div>
      <label  for="lastname">Lastname :</label>
      <input  type="text"  id="lastname"  name="lastname"  value=""  maxlength="80" required>
    </div>
    <button>Submit</button>
  </div>
</form>
</body>
</html>