
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

<p><?= $count ?> изображений. <?= round($size / 1024, 2) ?> килобайт</p>
