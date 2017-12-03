<?php
$conf = file_get_contents('config.json');
$conf = json_decode($conf, true);
$connection = new PDO("mysql:host=localhost;dbname=global;charset=utf8", $conf['user'], $conf['pass']);
$query = "SELECT * FROM books";
$cols = array('id', 'name', 'author', 'year', 'isbn', 'genre');
?>
<meta charset="utf-8">
<table border="1">
  <tr>
    <?php foreach ($cols as $value) {
      echo "<td><strong>$value</strong></td>";
    } ?>
  </tr>
  <?php foreach ($connection->query($query) as $row) {?>
    <tr>
      <?php foreach ($cols as $key) {
        echo "<td>$row[$key]</td>";
      }?>
    </tr>
  <?php }?>
</table>
