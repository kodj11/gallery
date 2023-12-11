<?php
function preview($file, $extension, $destinationFile)
{
	// Создание превью размером 200x200 пикселей
	$thumbnailWidth = 200;
	$thumbnailHeight = 200;

	// Определение пути к превью изображению
	$thumbnailPath = 'uploads/preview/' . $file;
	// Путь ориг фотки
	$origpath = $destinationFile;
	// Загрузка исходного изображения
	if ($extension == 'jpeg' or $extension == 'jpg'){
		$sourceImage = imagecreatefromjpeg($origpath);
	}
	else if ($extension == 'png') {
		$sourceImage = imagecreatefrompng($origpath);
	}
	// Создание пустого превью изображения
	$thumbnailImage = imagecreatetruecolor($thumbnailWidth, $thumbnailHeight);

	// Копирование исходного изображения в превью с изменением размера
	imagecopyresampled($thumbnailImage, $sourceImage, 0, 0, 0, 0, $thumbnailWidth, $thumbnailHeight, imagesx($sourceImage), imagesy($sourceImage));

	// Получение текущей даты и времени
	$currentDateTime = date('Y-m-d H:i:s');
	
	// Нанесение текущей даты и времени на превью изображения
	$font = 'C:\OSPanel\domains\image\Roboto.ttf';
	$fontColor = imagecolorallocate($thumbnailImage, 255, 255, 255);
	$fontSize = 12;
	$marginLeft = 10;
	$marginBottom = 10;
	imagettftext($thumbnailImage, $fontSize, 0, $marginLeft, $thumbnailHeight - $marginBottom, $fontColor, $font, $currentDateTime);
	
	imagejpeg($thumbnailImage, $thumbnailPath);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Проверяем наличие загруженного файла и ошибок
    if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0){
		$allowedExtensions = array("jpg", "jpeg", "png", "gif"); // Разрешенные расширения файлов
        $fileName0 = $_FILES["image"]["name"]; // Получаем имя загруженного файла
		$extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION); // Получаем расширение загруженного файла
		// Проверяем, является ли расширение допустимым
        if(in_array($extension, $allowedExtensions)){
            $tempFile = $_FILES["image"]["tmp_name"]; // Временное имя файла
            $destinationFolder = "uploads/"; // Папка для сохранения изображений
            $fileName = $fileName0; // Полное имя файла
            $destinationFile = $destinationFolder . $fileName; // Полный путь к файлу
			// Проверяем есть ли такой файл в папке
			if (file_exists($destinationFile)) {
				echo "<blaze-alert open dismissible type=\"warning\">Такой файл уже есть в папке!</blaze-alert>";
			} else {
				// Проверяем, удалось ли переместить файл в указанную папку
				if(move_uploaded_file($tempFile, $destinationFile)){
					preview($fileName0, $extension, $destinationFile);
					// Путь к загруженному изображению
					$uploadedImage = $destinationFile;
					// Путь к изображению водяного знака
					$watermarkImage = 'w.jpg';
					// Загрузка изображения
					if ($extension == 'jpeg' or $extension == 'jpg'){
						$image = imagecreatefromjpeg($uploadedImage);
					}
					else if ($extension == 'png') {
						$image = imagecreatefrompng($uploadedImage);
					} 
					// Загрузка водяного знака
					$watermark = imagecreatefromjpeg($watermarkImage);
					// Получение размеров загруженного изображения и водяного знака
					$imageWidth = imagesx($image);
					$imageHeight = imagesy($image);
					$watermarkWidth = imagesx($watermark);
					$watermarkHeight = imagesy($watermark);
					// Расчет позиции водяного знака
					$x = $imageWidth - $watermarkWidth - 10;   // Горизонтальное смещение
					$y = $imageHeight - $watermarkHeight - 10; // Вертикальное смещение
					// Наложение водяного знака на загруженное изображение
					imagecopy($image, $watermark, $x, $y, 0, 0, $watermarkWidth, $watermarkHeight);
					// Сохранение измененного изображения
					$outputImage =  $destinationFile;
					imagejpeg($image, $outputImage, 90);
					// Очистка памяти, освобождение ресурсов
					imagedestroy($image);
					imagedestroy($watermark);
				} else {
					echo "Произошла ошибка при загрузке изображения.";
				}
			}
        } else {
            echo "Недопустимое расширение файла. Допустимы только JPG, JPEG и PNG, GIF.";
        }
    } else {
        echo "Произошла ошибка при загрузке файла.";
    }
}
?>

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
	<title>Загрузка фото</title>
	<link rel="stylesheet" href="css/menu.css">
</head>
<body>
	<ul class="snip1143">
	  <li class="current"><a href="index.php" data-hover="Загрузка фото">Загрузка фото</a></li>
	  <li><a href="preview.php" data-hover="Актуальные превью">Актуальные превью</a></li>
	</ul>
	
    <form method="POST" enctype="multipart/form-data">
        
		<div class="c-file-upload c-file-upload--drop">
		  Drop or click to upload your files
		  <input type="file" name="image" accept="image/*" required>
		</div>
		<span class="c-input-group">
		  <input type="submit" class="c-button c-button--brand" value="Загрузить">
		  <button type="submit" class="c-button c-button--brand">Чистка</button>
		</span>
    </form>
</body>
</html>