<!-- HTML-форма для загрузки изображения -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://unpkg.com/@blaze/css@12.2.0/dist/blaze/blaze.css">
	<link rel="stylesheet" href="css/main.css">
	<script type="module" src="https://unpkg.com/@blaze/atoms@13.1.0/dist/blaze-atoms/blaze-atoms.esm.js"></script>
	<script nomodule="" src="https://unpkg.com/@blaze/atoms@13.1.0/dist/blaze-atoms/blaze-atoms.js"></script>
	<title>Превью</title>
	<link rel="stylesheet" href="css/menu.css">
</head>
<body>
	<ul class="snip1143">
	  <li><a href="index.php" data-hover="Загрузка фото">Загрузка фото</a></li>
	  <li class="current"><a href="preview.php" data-hover="Актуальные превью">Актуальные превью</a></li>
	</ul>
	
</body>
</html>

<?php
// Путь к папке с изображениями
$folder = 'uploads/preview/';
$origfolder = 'uploads/';
// Получение списка файлов в папке
$files =  array_diff(scandir($folder), array('.', '..', '.DS_Store', 'desktop.ini'));;

// Количество изображений на одной странице
$imagesPerPage = 1;

// Получение номера активной страницы
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// Определение индексов начала и конца изображений на текущей странице
$startIndex = ($page - 1) * $imagesPerPage;
$endIndex = $startIndex + $imagesPerPage;

// Получение подмассива изображений для текущей страницы
$images = array_slice($files, $startIndex, $imagesPerPage);

// Отображение изображений
foreach ($images as $image) {
    // Генерирование ссылки на полное изображение
    //$fullImage = $folder . $image;
    echo '<a href="'.$origfolder.$image.'" target="_blank">';
    // Отображение превью изображения
    echo '<img src="'.$folder.$image.'">';
    echo '</a>';
}

// Отображение навигации по страницам
$totalImages = count($files);
$totalPages = ceil($totalImages / $imagesPerPage);

echo '<span class="c-input-group">';
for ($i = 1; $i <= $totalPages; $i++) {
    // Определение класса активной страницы
    $activeClass = ($i == $page) ? 'active' : '';
    echo '<a href="?page='.$i.'" class="'.$activeClass.'">';
	echo '<button type="button" class="c-button c-button--ghost u-super">'.$i.'</button>';
	echo '</a>';
}
echo '</div>';
?>