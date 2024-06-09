<?php $this->Title = 'Кошик'; ?>

<div class="container">
    <h1>Ваш кошик</h1>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <?php if (empty($items)): ?>
        <p>Ваш кошик порожній</p>
    <?php else: ?>
        <table class="table">
            <thead>
            <tr>
                <th>Товар</th>
                <th>Ціна</th>
                <th>Кількість</th>
                <th>Сума</th>
                <th>Дії</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($item['price']) ?> грн</td>
                    <td>
                        <?= htmlspecialchars($item['quantity']) ?>
                        <a href="/cart/increase/<?= $item['product_id'] ?>" class="btn btn-sm btn-success">+</a>
                        <a href="/cart/decrease/<?= $item['product_id'] ?>" class="btn btn-sm btn-warning">-</a>
                    </td>
                    <td><?= htmlspecialchars($item['price'] * $item['quantity']) ?> грн</td>
                    <td>
                        <a href="/cart/remove/<?= $item['product_id'] ?>" class="btn btn-sm btn-danger">Видалити</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <h3>Сума до сплати: <?= htmlspecialchars($total) ?> грн</h3>
        <a href="/cart/clear" class="btn btn-danger">Очистити кошик</a>
        <button type="button" class="btn btn-success" onclick="showOrderForm()">Оформити замовлення</button>
        <form id="orderForm" style="display: none;" action="/order/add" method="post">
            <div class="form-group">
                <label for="phone_number">Телефон:</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number" value="0123456789" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email"
                       value="<?= htmlspecialchars($userData['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Адреса:</label>
                <textarea class="form-control" id="address" name="address" required></textarea>
            </div>
            <div class="form-group">
                <label for="payment_method">Метод оплати:</label>
                <select class="form-control" id="payment_method" name="payment_method" required>
                    <option value="credit_card">Кредитна картка</option>
                    <option value="cash">Готівка</option>
                    <option value="paypal">PayPal</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success my-3">Відправити</button>
        </form>
    <?php endif; ?>
</div>

<script>
    function showOrderForm() {
        document.getElementById('orderForm').style.display = 'block';
    }
</script>
