<?php $this->Title = 'Перегляд замовлення'; ?>

<div class="container">
    <h1>Деталі замовлення #<?= $order->order_id ?></h1>
    <div class="card">
        <div class="card-body">
            <p class="card-text"><strong>Номер замовлення:</strong> <?= $order->order_id ?></p>
            <?php if (\core\Core::isUserAdmin()) : ?>
                <p class="card-text"><strong>Телефон:</strong> <?= $order->phone_number ?></p>
                <p class="card-text"><strong>Адреса:</strong> <?= $order->address ?></p>
            <?php endif; ?>
            <p class="card-text"><strong>Метод оплати:</strong> <?= translatePaymentMethod($order->payment_method) ?>
            </p>
            <p class="card-text"><strong>Загальна сума:</strong> <?= $order->total_amount ?> грн</p>
            <p class="card-text"><strong>Статус:</strong> <?= translateStatus($order->status) ?></p>
            <p class="card-text"><strong>Статус оплати:</strong> <?= translatePaymentStatus($order->payment_status) ?>
            </p>
            <p class="card-text"><strong>Дата створення:</strong> <?= $order->created_at ?></p>
            <p class="card-text"><strong>Дата оновлення:</strong> <?= $order->updated_at ?></p>
            <p class="card-text"><strong>Список товірів:</strong></p>
            <ul class="list-group">
                <?php foreach ($order->products_json as $product): ?>
                    <li class="list-group-item">
                        <?= $product['name'] ?> (Ціна: <?= $product['price'] ?> грн,
                        Кількість: <?= $product['quantity'] ?>)
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="mt-3">
                <?php if (\core\Core::isUserAdmin()) : ?>
                    <?php if ($order->status === 'pending' || $order->status === 'accepted'): ?>
                        <?php if(!\core\Core::isUserAdmin()) : ?>
                        <form action="/order/cancel/<?= $order->order_id ?>" method="post" style="display: inline;">
                            <button type="submit" class="btn btn-danger">Відмінити</button>
                        </form>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="dropdown d-inline">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Змінити статус
                        </button>
                        <div class="dropdown-menu" aria-labelledby="changeStatusDropdown">
                            <a class="dropdown-item" href="/order/updatestatus/<?= $order->order_id ?>/pending">Очікується</a>
                            <a class="dropdown-item" href="/order/updatestatus/<?= $order->order_id ?>/accepted">Прийнято</a>
                            <a class="dropdown-item" href="/order/updatestatus/<?= $order->order_id ?>/shipped">Відправлено</a>
                            <a class="dropdown-item" href="/order/updatestatus/<?= $order->order_id ?>/delivered">Доставлено</a>
                            <a class="dropdown-item" href="/order/updatestatus/<?= $order->order_id ?>/canceled">Скасовано</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
function translatePaymentMethod($method): string
{
    return match ($method) {
        'credit_card' => 'Кредитна картка',
        'cash' => 'Готівка',
        'paypal' => 'PayPal',
        default => $method,
    };
}

function translateStatus($status): string
{
    return match ($status) {
        'pending' => 'Очікується',
        'accepted' => 'Прийнято',
        'shipped' => 'Відправлено',
        'delivered' => 'Доставлено',
        'canceled' => 'Скасовано',
        default => $status,
    };
}

function translatePaymentStatus($status)
{
    switch ($status) {
        case 'pending':
            return 'Очікується';
        case 'paid':
            return 'Оплачено';
        case 'failed':
            return 'Не вдалося';
        default:
            return $status;
    }
}
?>
