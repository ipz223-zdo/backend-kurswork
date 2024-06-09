<?php
/** @var string $error_message */
/** @var \core\Controller $controller */
$product = $this->paramsArray['product'] ?? null;
$photos = $this->paramsArray['photos'] ?? [];
$this->Title = htmlspecialchars($product['name'] ?? 'Product');
if ($product) {
    ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <?php if (!empty($photos)): ?>
                    <div id="productPhotosCarousel" class="carousel carousel-dark  slide" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <?php foreach ($photos as $index => $photo): ?>
                                <button type="button" data-bs-target="#productPhotosCarousel"
                                        data-bs-slide-to="<?= $index ?>" <?= $index === 0 ? 'class="active" aria-current="true"' : '' ?>
                                        aria-label="Slide <?= $index + 1 ?>"></button>
                            <?php endforeach; ?>
                        </div>
                        <div class="carousel-inner" style="height: 600px;">
                            <?php foreach ($photos as $index => $photo): ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <div style="position: relative;">
                                        <img src="/uploads/<?= htmlspecialchars($photo['image_url']) ?>"
                                             class="d-block w-100 rounded" alt="Product Image"
                                             style="height: 600px; width: auto; object-fit: contain;">
                                        <?php if (\core\Core::isUserAdmin()): ?>
                                            <button type="button" class="btn btn-outline-danger delete-photo"
                                                    style="position: absolute; top: 10px; right: 10px;"
                                                    data-photo-id="<?= htmlspecialchars($photo['image_id'] ?? '') ?>"
                                                    data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                     fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"></path>
                                                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"></path>
                                                </svg>
                                                <span class="visually-hidden">Button</span>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <p>Не знайдено фото для цього товару.</p>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <?php
                        $buttonClass = '';
                        if ($product['quantity'] > 5) {
                            $stockStatus = 'Є в наявності';
                            $textClass = 'text-success fw-bold';
                        } elseif ($product['quantity'] > 0 && $product['quantity'] <= 5) {
                            $stockStatus = 'Закінчується';
                            $textClass = 'text-danger fw-bold';
                        } else {
                            $stockStatus = 'Немає в наявності';
                            $textClass = 'fw-bold';
                            $buttonClass = 'disabled';
                        }
                        ?>
                        <h5 class="card-title <?= $textClass ?>">
                            <a href="/product/view/<?= $product['product_id'] ?>"
                               class="text-decoration-none text-dark">
                                <?= htmlspecialchars($product['name'] ?? '') ?>
                            </a>
                        </h5>
                        <p class="card-text">Ціна: <?= htmlspecialchars($product['price'] ?? '') ?> грн</p>
                        <p class="card-text">Тип: <?= htmlspecialchars($product['type'] ?? '') ?></p>
                        <p class="card-text <?= $textClass ?>"><?= htmlspecialchars($stockStatus) ?></p>
                        <p class="card-text">В наявності: <?= htmlspecialchars($product['quantity'] ?? '') ?></p>

                        <?php if (!empty($product['characteristics'])): ?>
                            <?php $characteristics = json_decode($product['characteristics'], true); ?>
                            <h6 class="card-subtitle mb-2 text-muted">Характеристики:</h6>
                            <ul class="list-group">
                                <?php foreach ($characteristics as $key => $value): ?>
                                    <li class="list-group-item"><?= htmlspecialchars($key) ?>
                                        : <?= htmlspecialchars($value) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <form action="/cart/add/<?= $product['product_id'] ?>" method="post" class="d-flex align-items-center">
                            <label >
                                <input type="number" name="quantity" value="1" min="1" max="<?= $product['quantity'] ?>" class="form-control me-2" style="width: 70px;">
                            </label>
                            <button type="submit" class="btn btn-success ml-2 my-3" <?= $buttonClass ?>>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                     class="bi bi-cart4 me-2" viewBox="0 0 16 16">
                                    <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5M3.14 5l.5 2H5V5zM6 5v2h2V5zm3 0v2h2V5zm3 0v2h1.36l.5-2zm1.11 3H12v2h.61zM11 8H9v2h2zM8 8H6v2h2zM5 8H3.89l.5 2H5zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0"></path>
                                </svg>
                                Додати в кошик
                            </button>
                        </form>
                        <?php if (\core\Core::isUserAdmin()): ?>
                            <button type="button" class="btn btn-warning mr-2 edit-product"
                                    data-product-id="<?= htmlspecialchars($product['product_id'] ?? '') ?>">Змінити
                            </button>
                            <button type="button" class="btn btn-danger delete-product"
                                    data-product-id="<?= htmlspecialchars($product['product_id'] ?? '') ?>">Видалити
                            </button>
                        <?php endif; ?>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
} else {
    ?>
    <div class="container">
        <div class="alert alert-danger mt-5" role="alert">
            Товар не знайдено
        </div>
    </div>
    <?php
}
?>
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel"
     aria-hidden="true">
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
                        window.location.href = '/';
                    }
                });
            });
        });
    </script>
<?php endif; ?>
<?php if (\core\Core::isUserAdmin()): ?>
    <script>
        $(document).ready(function () {
            $('.edit-product').click(function () {
                let productId = $(this).data('product-id');
                window.location.href = '/product/update/' + productId;
            });
        });
    </script>
<?php endif; ?>
<?php if (\core\Core::isUserAdmin()): ?>
    <script>
        $(document).ready(function () {
            $('.delete-photo').click(function () {
                let photoId = $(this).data('photo-id');
                $('#confirmDeleteModal').find('.confirm-delete').data('photo-id', photoId);
            });

            $('.confirm-delete').click(function () {
                let photoId = $(this).data('photo-id');
                $.ajax({
                    type: 'POST',
                    url: '/photo/delete/' + photoId,
                    success: function (data) {
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                    }
                });
            });
        });
    </script>
<?php endif; ?>
<?php if (\core\Core::isUserAdmin()): ?>
    <script>
        $(document).ready(function () {
            $('.delete-photo').click(function () {
                let photoId = $(this).data('photo-id');
                $('#confirmDeletePhotoModal').modal('show');
                $('#confirmDeletePhotoModal').find('.confirm-delete-photo').data('photo-id', photoId);
            });

            $('.confirm-delete-photo').click(function () {
                let photoId = $(this).data('photo-id');
                $.ajax({
                    type: 'POST',
                    url: '/photo/delete/' + photoId,
                    success: function (data) {
                        window.location.reload();
                    }
                });
            });
        });
    </script>
<?php endif; ?>
