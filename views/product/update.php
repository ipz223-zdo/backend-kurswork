<?php
/** @var string $error_message */
/** @var \core\Controller $controller */
$this->Title = 'Змінити товар'
?>
<div class="container">
    <h1 class="mt-5">Змінити Товар</h1>
    <?php if (!empty($error_message)) : ?>
        <div class="alert alert-danger" role="alert">
            <?= $error_message ?>
        </div>
    <?php endif; ?>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Назва:</label>
            <input value="<?= htmlspecialchars($product['name'] ?? '') ?>" type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="price">Ціна:</label>
            <input value="<?= htmlspecialchars($product['price'] ?? '') ?>" type="number" class="form-control" id="price" name="price" required>
        </div>
        <div class="form-group">
            <label for="quantity">Кількість:</label>
            <input value="<?= htmlspecialchars($product['quantity'] ?? '') ?>" type="number" class="form-control" id="quantity" name="quantity" required>
        </div>
        <div class="form-group">
            <label for="type">Тип:</label>
            <select class="form-control" id="type" name="type" required>
                <option value="phone" <?= ($product['type'] ?? '') === 'phone' ? 'selected' : '' ?>>Телефон</option>
                <option value="laptop" <?= ($product['type'] ?? '') === 'laptop' ? 'selected' : '' ?>>Ноутбук</option>
                <option value="tv" <?= ($product['type'] ?? '') === 'tv' ? 'selected' : '' ?>>Телевізор</option>
            </select>
        </div>
        <?php
        $characteristics = json_decode($product['characteristics'], true);

        if (!empty($characteristics)) {
            echo "<div class='form-group'>";
            echo "<label for='characteristics'>Характеристики:</label>";
            echo "<textarea class='form-control' id='characteristics' name='characteristics' rows='5'>";
            $keys = array_keys($characteristics);
            $last_key = end($keys);
            foreach ($characteristics as $key => $value) {
                if ($key !== $last_key) {
                    echo "{$key}: {$value},\n";
                } else {
                    echo "{$key}: {$value}\n";
                }
            }
            echo "</textarea>";
            echo "</div>";
        }
        ?>
        <div class="form-group my-3">
            <button type="submit" class="btn btn-primary">Оновити продукт</button>
        </div>
        <div class="form-group">
            <label for="photos">Додати фото:</label>
            <input type="file" class="form-control-file" id="photos" name="photos[]" multiple>
        </div>
    </form>
</div>
