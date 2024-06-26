<?php
/** @var string $error_message */
$this->Title = 'Вхід на сайт'
?>
<div class="d-flex flex-wrap align-items-center justify-content-center container">
    <form class="my-5 w-50" method="post" action="">
        <h1>Вхід на сайт</h1>
        <?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?= $error_message ?>
            </div>
        <?php endif; ?>
        <div class="mb-3">
            <label for="inputEmail1" class="form-label">Логін / email</label>
            <input name="login" type="email" class="form-control" id="inputEmail1" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
            <label for="inputPassword1" class="form-label">Пароль</label>
            <input name="password" type="password" class="form-control" id="inputPassword1">
        </div>
        <button type="submit" class="btn btn-primary">Увійти</button>
    </form>
</div>
