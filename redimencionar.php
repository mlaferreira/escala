<?php
$arquivo = 'img/eu-id-1.jpg';

// Verificar se o arquivo existe
if (!file_exists($arquivo)) {
    die("A imagem original não foi encontrada.");
}

// Definir as dimensões desejadas da imagem redimensionada
$larguraDesejada = 300;
$alturaDesejada  = 300;

// Obter as dimensões da imagem original
list($larguraOriginal, $alturaOriginal) = getimagesize($arquivo);

// Calcular a proporção da imagem original
$ratioOriginal = $larguraOriginal / $alturaOriginal;

// Calcular as novas dimensões para manter a proporção
if ($larguraDesejada / $alturaDesejada > $ratioOriginal) {
    $larguraDesejada = $alturaDesejada * $ratioOriginal;
} else {
    $alturaDesejada = $larguraDesejada / $ratioOriginal;
}

// Criar a imagem redimensionada
$imagemFinal = imagecreatetruecolor($larguraDesejada, $alturaDesejada);
$imagemOriginal = imagecreatefromjpeg($arquivo);

// Redimensionar a imagem original para a nova imagem
imagecopyresampled($imagemFinal, $imagemOriginal, 0, 0, 0, 0, $larguraDesejada, $alturaDesejada, $larguraOriginal, $alturaOriginal);

// Caminho para salvar a imagem redimensionada
$caminhoDestino = 'img/eu-id-1-redimensionada.jpg';

// Salvar a imagem redimensionada
imagejpeg($imagemFinal, $caminhoDestino);

// Liberar a memória alocada para as imagens
imagedestroy($imagemOriginal);
imagedestroy($imagemFinal);

echo "Imagem redimensionada foi salva em: $caminhoDestino";
?>
