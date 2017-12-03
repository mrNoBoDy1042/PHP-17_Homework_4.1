<h2>Find:</h2>
<form method="post">
  <?php
  // Вывод полей для выборки
  $cols = array('name', 'author', 'year', 'isbn', 'genre');
  foreach ($cols as $value) {
    echo "<p>".ucfirst($value).
    ": </p><input type=\"text\" name=\"$value\" value=\"$_POST[$value]\">";
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
    // Если WHERE в запросе уже есть, значит мы добавляем второе условие
    // и нужен OR
    $query .= (strpos($query,'WHERE')) ? 'OR ' : ' WHERE ';
    $query = $query."$field LIKE '%$_POST[$field]%' ";
    return $query;
  }

// Создаем основу запроса
$select = "SELECT * FROM books";
// Находим заполненные поля выборки
foreach ($cols as $value) {
  $fields = array();
  if (isset($_POST[$value]) && !empty($_POST[$value])) {
    $select = BuildQuery($select, $value);
  }
}

// Подгатавливаем запрос и выполняем его
$select = $connection->prepare($select);
$select->execute();
// Сохраняем результат запроса
$result = $select->fetchAll();
?>

<table border="1">
  <tr>
    <?php foreach ($cols as $value) {
      // Выводим шапку таблицы
      echo "<td><strong>$value</strong></td>";
    } ?>
  </tr>
  <?php foreach ($result as $row) {
    // Выводим строки полученные запросом
    ?>
    <tr>
      <?php foreach ($cols as $key) {
        echo "<td>$row[$key]</td>";
      }?>
    </tr>
  <?php }?>
</table>
