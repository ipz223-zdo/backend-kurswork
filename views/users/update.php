<?php
/** @var string $error_message */
/** @var \core\Controller $controller */

use core\Core;

$this->Title = 'Зміна ваших даних';
$userData = Core::get()->session->get('user');
?>
<div class="d-flex flex-wrap align-items-center justify-content-center container">
    <form class="my-5 w-50" method="post" action="">
        <h1>Зміна ваших даних</h1>
        <?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?= $error_message ?>
            </div>
        <?php endif; ?>
        <div class="mb-3">
            <label for="inputEmail1" class="form-label">Логін / email</label>
            <input value="<?= htmlspecialchars($userData['login']) ?>" name="login" type="email" class="form-control"
                   id="inputEmail1" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
            <label for="inputPassword1" class="form-label">Пароль</label>
            <input value="<?= htmlspecialchars($userData['password']) ?>" name="password" type="password" class="form-control" id="inputPassword1">
        </div>
        <div class="mb-3">
            <label for="inputPassword2" class="form-label">Пароль (ще раз)</label>
            <input value="<?= htmlspecialchars($userData['password']) ?>" name="password2" type="password" class="form-control" id="inputPassword2">
        </div>
        <div class="mb-3">
            <label for="inputFirstName" class="form-label">Ім'я</label>
            <input value="<?= htmlspecialchars($userData['firstname']) ?>" name="firstname" type="text" class="form-control"
                   id="inputFirstName">
        </div>
        <div class="mb-3">
            <label for="inputLastName" class="form-label">Прізвище</label>
            <input value="<?= htmlspecialchars($userData['lastname']) ?>" name="lastname" type="text" class="form-control"
                   id="inputLastName">
        </div>
        <button type="submit" class="btn btn-primary">Зареєструватися</button>
    </form>
</div>
