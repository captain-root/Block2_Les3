<?php
// Если не был передан номер теста - возвращать на страницу со списком тестов
if (isset($_GET['number']) === false) {
    header('Location: list.php');
    exit;
}
// Получаем файл с номером из GET-запроса
$all_test = glob('tests/*.json');
$number = $_GET['number'];
$get = file_get_contents($all_test[$number]);
$test = json_decode($get, true);

if(isset($_POST['check-test'])){
    function check_test($test_file)
    {
        foreach($test_file as $key => $item){
            if(!isset($_POST['answer' . $key])){
                echo 'Необходимо решить все задания!';
                exit;
            }
        }

        $i = 0;
        $questions = 0;
        foreach($test_file as $key => $item){
            $questions++;
            // Здесь идет определение названия класса для блока с вопросом и ответом, чтобы выводить красный/зеленый фон для удобства
            // А также прибавляется 1 к переменной $i, если ответ правильный
            if($item['correct_answer'] === $_POST['answer' . $key]){
                $i++;
                $info_style = 'correct';
            } else{
                $info_style = 'incorrect';
            }
            // Выводим блока с вопросами и ответами
            echo "<div class=\"$info_style\">";
            echo 'Вопрос: ' . $item['question'] . '<br?';
            echo 'Ваш ответ: ' . $item['answers'][$_POST['answer' . $key]] . '<br>';
            echo 'Правильный ответ: ' . $item['answers'][$item['correct_answer']] . '<br>';
            echo '</div>';
            echo '<hr>';
        }
        echo '<p style="font-weight: bold;">_ИТОГО_ правилных ответов: ' . $i . 'из' . $questions . '</p>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Тест</title>
</head>
<body>

<a href="<?php echo isset($_POST['check-test']) ? $_SERVER['HTTP_REFERER'] : 'list.php' ?>">Назад</a><br>
<a href="index.php">Загрузить тест</a>

<?php if(isset($_GET['number']) && !isset($_POST['check-test'])): ?>
    <form method="post">
        <h1><?php echo basename($all_test[$number]); ?></h1>
        <?php foreach($test as $key => $item): ?>
            <fieldset>
                <legend><?= $item['question'] ?></legend>
                <label>
                    <input type="radio" name="answer<?php echo $key ?>" value="0">
                    <?= $item['answers'][0] ?>
                </label><br>
                <label>
                    <input type="radio" name="answer<?php echo $key ?>" value="1">
                    <?= $item['answers'][1] ?>
                </label><br>
                <label>
                    <input type="radio" name="answer<?php echo $key ?>" value="2">
                    <?= $item['answers'][2] ?>
                </label><br>
            </fieldset>
        <?php endforeach ?>
        <input type="submit" name="check-test" value="Проверить">
    </form>
<?php endif?>
<div>
    <?php if(isset($_POST['check-test'])) echo check_test($test); ?>
</div>
</body>
</html>