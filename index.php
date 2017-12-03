<h2>Find:</h2>
<form method="post">
  <?php
  // Вывод полей для выборки
  $cols = array('name', 'author', 'year', 'isbn', 'genre');
  foreach ($cols as $value) {
    echo "<p>".ucfirst($value).": </p><input type=\"text\" name=\"$value\">";
  }
  ?>
  <input type="submit" value="Find">
</form>

<?php
// Получаем логин и пароль для подключения
$conf = file_get_contents('config.json');
$conf = json_decode($conf, true);
// Создаем подключение
$connection = new PDO(
  "mysql:host=localhost;dbname=global;charset=utf8",
  $conf['user'],
  $conf['pass']);

  // Функция для дополнения запроса
  function BuildQuery($query, $field)
  {
    $query .= (strpos($query,'WHERE')) ? 'OR ' : ' WHERE ';
    $query = $query."$field LIKE '%$_POST[$field]%' ";
    return $query;
  }

// Создаем основу запроса
$query = "SELECT * FROM books";
// Находим заполненные поля выборки и сохраняем в отд
foreach ($cols as $value) {
  $fields = array();
  if (isset($_POST[$value]) && !empty($_POST[$value])) {
    $query = BuildQuery($query, $value);
  }
}

var_dump($query);
$select = $connection->prepare($query);
$select->execute();
$query = $select->fetchAll();

?>

<meta charset="utf-8">
<table border="1">
  <tr>
    <?php foreach ($cols as $value) {
      echo "<td><strong>$value</strong></td>";
    } ?>
  </tr>
  <?php foreach ($query as $row) {?>
    <tr>
      <?php foreach ($cols as $key) {
        echo "<td>$row[$key]</td>";
      }?>
    </tr>
  <?php }?>
</table>
