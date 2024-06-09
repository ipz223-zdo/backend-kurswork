<?php $this->Title = 'Пошук замовлення'; ?>
<div class="d-flex flex-wrap align-items-center justify-content-center container">
    <form action="/order/find" class="w-25" method="post">
        <div class="form-group">
            <label for="order_id">Код замовлення:</label>
            <input type="text" class="form-control mb-3" id="order_id" name="order_id" required>
        </div>
        <button type="submit" class="btn btn-primary">Знайти замовлення</button>
    </form>