<?php $this->Title = 'Список замовлень'; ?>

<div class="container">
    <h1>Список замовлень</h1>
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Фільтр за статусом">
    <table id="orderTable" class="table table-striped">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">ID користувача</th>
            <th scope="col">Номер телефону</th>
            <th scope="col">Адреса</th>
            <th scope="col">Метод оплати</th>
            <th scope="col">Загальна сума</th>
            <th scope="col">Статус</th>
            <th scope="col">Дата створення</th>
            <th scope="col">Дата оновлення</th>
            <th scope="col">Статус оплати</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
            <tr onclick="window.location='/order/view/<?= $order->order_id ?>'">
                <th scope="row"><?= $order->order_id ?></th>
                <td><?= $order->user_id ?></td>
                <td><?= $order->phone_number ?></td>
                <td><?= $order->address ?></td>
                <td><?= $order->payment_method ?></td>
                <td><?= $order->total_amount ?></td>
                <td>
                    <?= translateStatus($order->status) ?>
                </td>
                <td><?= $order->created_at ?></td>
                <td><?= $order->updated_at ?></td>
                <td>
                    <?= translatePaymentStatus($order->payment_status) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Сортування за натисканням на заголовки стовпців
        $('#orderTable th').click(function () {
            var table = $(this).parents('table').eq(0);
            var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()));
            this.asc = !this.asc;
            if (!this.asc) {
                rows = rows.reverse();
            }
            for (var i = 0; i < rows.length; i++) {
                table.append(rows[i]);
            }
        });

        function comparer(index) {
            return function (a, b) {
                var valA = getCellValue(a, index),
                    valB = getCellValue(b, index);
                return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.localeCompare(valB);
            };
        }

        function getCellValue(row, index) {
            return $(row).children('td').eq(index).text();
        }

        // Фільтрація за статусом замовлення
        $("#searchInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#orderTable tbody tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>

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

