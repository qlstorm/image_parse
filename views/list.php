
<table>
    <?php foreach ($imageList as $row) { ?>
        <tr>
            <?php foreach ($row as $image) { ?>
                <td>
                    <img src="<?= $image ?>">
                </td>
            <?php } ?>
        </tr>
    <?php } ?>
</table>

<p><?= $count ?> <?= Text::spell($count, 'изображение', 'изображения', 'изображений') ?>. Размер <?= round($size / 1024, 2) ?> килобайт</p>
