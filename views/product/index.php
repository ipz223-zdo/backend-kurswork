<?php
$this->Title = 'Головна сторінка';
$products = $this->paramsArray['products'] ?? [];
$photos = $this->paramsArray['photos'] ?? [];
?>
<div class="d-flex flex-wrap align-items-center justify-content-center container">
    <?php foreach ($products as $product): ?>
        <div class="card m-2 px-3" style="width: 18rem;">
            <?php if (!empty($photos[$product->product_id])): ?>
                <?php $firstPhotoUrl = $photos[$product->product_id]['image_url'];
                ?>
                <img src="/uploads/<?= htmlspecialchars($firstPhotoUrl) ?>" class="card-img-top my-3 w-100 rounded" alt="<?= htmlspecialchars($product->name) ?>" style="height: 200px; width: auto; object-fit: contain;">
            <?php else: ?>
                <img src="..." class="card-img-top" alt="Фото не знайдено">
            <?php endif; ?>
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($product->name) ?></h5>
                <p class="card-text">Ціна: <?= htmlspecialchars($product->price) ?> грн</p>
                <p class="card-text">Кількість: <?= htmlspecialchars($product->quantity) ?></p>
                <a href="/product/view/<?= $product->product_id ?>" class="btn btn-primary">Переглянути</a>
                <?php if (\core\Core::isUserAdmin()): ?>
                    <button class="btn btn-danger delete-product" data-product-id="<?= $product->product_id ?>">
                        Видалити
                    </button>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
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
<?php if (\core\Core::isUserAdmin()): ?>
    <script>
        $(document).ready(function () {
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
        });
    </script>
<?php endif; ?>
