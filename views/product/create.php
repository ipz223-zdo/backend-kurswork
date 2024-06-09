<?php
/** @var string $error_message */
/** @var \core\Controller $controller */
$this->Title = 'Додати товар'
?>
<div class="container">
    <h1 class="mt-5">Створити Новий Товар</h1>
    <?php if (!empty($error_message)) : ?>
        <div class="alert alert-danger" role="alert">
            <?= $error_message ?>
        </div>
    <?php endif; ?>
    <form action="" method="post">
        <div class="form-group">
            <label for="name">Назва:</label>
            <input value="<?= $this->controller->post->name ?>" type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="price">Ціна:</label>
            <input value="<?= $this->controller->post->price ?>" type="number" class="form-control" id="price" name="price" required>
        </div>
        <div class="form-group">
            <label for="quantity">Кількість:</label>
            <input value="<?= $this->controller->post->quantity ?>" type="number" class="form-control" id="quantity" name="quantity" required>
        </div>
        <div class="form-group">
            <label for="type">Тип:</label>
            <select class="form-control" id="type" name="type" required>
                <option value="phone">Телефон</option>
                <option value="laptop">Ноутбук</option>
                <option value="tv">Телевізор</option>
            </select>
        </div>
        <div class="form-group">
            <label for="characteristics">Характеристики:</label>
            <textarea class="form-control" id="characteristics" name="characteristics" rows="5" placeholder="Введіть характеристики у форматі: Характеристика 1: значення 1, Характеристика 2: значення 2, ..."></textarea>
        </div>
        <div class="form-group my-3">
            <button type="submit" class="btn btn-primary">Створити продукт</button>
        </div>
    </form>
</div>
