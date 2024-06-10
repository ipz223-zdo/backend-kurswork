<?php
$this->Title = 'Головна сторінка';
$laptops = $this->paramsArray['laptops'] ?? [];
$phones = $this->paramsArray['phones'] ?? [];
$tvs = $this->paramsArray['tvs'] ?? [];

$laptopPhotos = $this->paramsArray['laptopPhotos'] ?? [];
$phonePhotos = $this->paramsArray['phonePhotos'] ?? [];
$tvPhotos = $this->paramsArray['tvPhotos'] ?? [];

function getStockStatusClasses($quantity): array
{
    $cardClass = '';
    if ($quantity > 5) {
        $stockStatus = 'Є в наявності';
        $textClass = 'text-success fw-bold';
    } elseif ($quantity > 0) {
        $stockStatus = 'Закінчується';
        $textClass = 'text-danger fw-bold';
    } else {
        $stockStatus = 'Немає в наявності';
        $textClass = 'fw-bold';
        $cardClass = 'opacity-50';
    }

    return ['stockStatus' => $stockStatus, 'textClass' => $textClass, 'cardClass' => $cardClass];
}

?>
<div class="container">
    <h4>Ноутбуки <a class="fs-6" href="/product/category/laptop">дивитися всі</a></h4>
    <div class="row">
        <?php foreach ($laptops as $laptop): ?>
            <?php $classes = getStockStatusClasses($laptop['quantity']); ?>
            <div class="card m-2 <?= $classes['cardClass'] ?> px-1" style="width: 18rem;">
                <?php if (!empty($laptopPhotos[$laptop['product_id']])): ?>
                    <?php $firstPhotoUrl = $laptopPhotos[$laptop['product_id']]['image_url']; ?>
                    <img src="/uploads/<?= htmlspecialchars($firstPhotoUrl) ?>" class="card-img-top my-3 w-100 rounded"
                         alt="<?= htmlspecialchars($laptop['name']) ?>"
                         style="height: 200px; width: auto; object-fit: contain;">
                <?php else: ?>
                    <img src="..." class="card-img-top" alt="Фото не знайдено">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="/product/view/<?= $laptop['product_id'] ?>" class="text-decoration-none text-dark">
                            <?= htmlspecialchars($laptop['name']) ?>
                        </a>
                    </h5>
                    <p class="card-text">Ціна: <?= htmlspecialchars($laptop['price']) ?> грн</p>
                    <p class="card-text <?= $classes['textClass'] ?>"><?= htmlspecialchars($classes['stockStatus']) ?></p>
                    <a href="/product/view/<?= $laptop['product_id'] ?>" class="btn btn-primary">Переглянути</a>
                    <?php if (\core\Core::isUserAdmin()): ?>
                        <button class="btn btn-danger delete-product" data-product-id="<?= $laptop['product_id'] ?>">
                            Видалити
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="container mt-5">
    <h4>Смартфони <a class="fs-6" href="/product/category/phone">дивитися всі</a></h4>
    <div class="row">
        <?php foreach ($phones as $phone): ?>
            <?php $classes = getStockStatusClasses($phone['quantity']); ?>
            <div class="card m-2 <?= $classes['cardClass'] ?> px-1" style="width: 18rem;">
                <?php if (!empty($phonePhotos[$phone['product_id']])): ?>
                    <?php $firstPhotoUrl = $phonePhotos[$phone['product_id']]['image_url']; ?>
                    <img src="/uploads/<?= htmlspecialchars($firstPhotoUrl) ?>" class="card-img-top my-3 w-100 rounded"
                         alt="<?= htmlspecialchars($phone['name']) ?>"
                         style="height: 200px; width: auto; object-fit: contain;">
                <?php else: ?>
                    <img src="..." class="card-img-top" alt="Фото не знайдено">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="/product/view/<?= $phone['product_id'] ?>" class="text-decoration-none text-dark">
                            <?= htmlspecialchars($phone['name']) ?>
                        </a>
                    </h5>
                    <p class="card-text">Ціна: <?= htmlspecialchars($phone['price']) ?> грн</p>
                    <p class="card-text <?= $classes['textClass'] ?>"><?= htmlspecialchars($classes['stockStatus']) ?></p>
                    <a href="/product/view/<?= $phone['product_id'] ?>" class="btn btn-primary">Переглянути</a>
                    <?php if (\core\Core::isUserAdmin()): ?>
                        <button class="btn btn-danger delete-product" data-product-id="<?= $phone['product_id'] ?>">
                            Видалити
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="container mt-5">
    <h4>Телевізори <a class="fs-6" href="/product/category/tv">дивитися всі</a></h4>
    <div class="row">
        <?php foreach ($tvs as $tv): ?>
            <?php $classes = getStockStatusClasses($tv['quantity']); ?>
            <div class="card m-2 <?= $classes['cardClass'] ?> px-1" style="width: 18rem;">
                <?php if (!empty($tvPhotos[$tv['product_id']])): ?>
                    <?php $firstPhotoUrl = $tvPhotos[$tv['product_id']]['image_url']; ?>
                    <img src="/uploads/<?= htmlspecialchars($firstPhotoUrl) ?>" class="card-img-top my-3 w-100 rounded"
                         alt="<?= htmlspecialchars($tv['name']) ?>"
                         style="height: 200px; width: auto; object-fit: contain;">
                <?php else: ?>
                    <img src="..." class="card-img-top" alt="Фото не знайдено">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="/product/view/<?= $tv['product_id'] ?>" class="text-decoration-none text-dark">
                            <?= htmlspecialchars($tv['name']) ?>
                        </a>
                    </h5>
                    <p class="card-text">Ціна: <?= htmlspecialchars($tv['price']) ?> грн</p>
                    <p class="card-text <?= $classes['textClass'] ?>"><?= htmlspecialchars($classes['stockStatus']) ?></p>
                    <a href="/product/view/<?= $tv['product_id'] ?>" class="btn btn-primary">Переглянути</a>
                    <?php if (\core\Core::isUserAdmin()): ?>
                        <button class="btn btn-danger delete-product" data-product-id="<?= $tv['product_id'] ?>">
                            Видалити
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php if (\core\Core::isUserAdmin()): ?>
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
         aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Підтвердіть видалення</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Ви впевнені, що хочете видалити цей товар?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Відмінити</button>
                    <button type="button" class="btn btn-danger confirm-delete">Підтвердити видалення</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    $(document).ready(function () {
        <?php if (\core\Core::isUserAdmin()): ?>
        $('.delete-product').click(function () {
            let productId = $(this).data('product-id');
            $('#confirmDeleteModal').modal('show');
            $('#confirmDeleteModal').find('.confirm-delete').data('product-id', productId);
        });

        $('.confirm-delete').click(function () {
            let productId = $(this).data('product-id');
            $.ajax({
                type: 'POST',
                url: '/product/delete/' + productId,
                success: function (data) {
                    window.location.reload();
                }
            });
        });
        <?php endif; ?>
    });
</script>