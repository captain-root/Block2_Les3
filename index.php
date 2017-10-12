<?php
// Если был получен POST-запрос с файлом, то проверяем
if(isset($_POST['upload'])) {
    // Определяем массив со всеми файлами
    if(!empty(glob('tests/*.json'))){
        $all_files = glob('tests/*.json');
    } else{
        $all_files = [0];
    }
    // Определяем загружаемый файл
    $upload_file = 'tests/' . basename($_FILES['test_file']['name']);
    // Проверить загруженный файл с помощью условиях
    if(pathinfo($_FILES['test_file']['name'],PATHINFO_EXTENSION) !== 'json') {
        $result = "<p class='error'>Можно загрузить JSON файл.</p>";
    }
    elseif ($_FILES['test_file']['size'] > 1024) {
        $result = "<p class='error'>Размер файла слишком большой</p>";
    }
    elseif (in_array($upload_file, $all_files, true)) {
        $result = "<p class='error'>Файл с таким именем уже существует. Измените названия файла.</p>";
    }
    elseif (move_uploaded_file($_FILES['test_file']['tmp_name'], $upload_file)) {
        $result = "<p class='success'>Файл удовлетворяет условиям. Успешно загружен на сервер.</p><br><a href='list.php'>Список тестов:</a>";
    }
    else {
        $result = "<p class='error'>Произошла ошибка!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Форма для добавления теста</title>
    <style>
        .form {
            border : 1.5px solid black;
            padding: 10px;
            width: 370px;
        }
        .all-tests {
            text-decoration: none;
            border: 1.5px solid black;
            padding: 4px;
            width: 100px;
            margin: 1px 0 0 0;
        }
    </style>
</head>
<body>

<?php if(isset($_POST['upload'])): ?>
    <a href="<?php $_SERVER['HTTP_REFERER'] ?>">Назад</a>
    <?= $result ?>
    <h1>Техническая информация:</h1>
    <pre>
            <?php print_r($all_files) ?>
        <hr>
        <?php print_r($_FILES) ?>
        </pre>
<?php endif ?>

<!-- Если форма не отправлена, выводить форму загрузки -->

<?php if(!isset($_POST['create']) && !isset($_POST['upload'])): ?>

    <div class="form">
        <form id="load-json" method="POST" enctype="multipart/form-data">
            <input type="file" name="test_file" id="upload_file">
            <input type="submit" value="Добавить тест" id="submit-upload" name="upload">
        </form>
    </div>

    <div class="all-tests">
        <a href="list.php">Список тестов:</a>
    </div>

<?php endif; ?>

</body>
</html>