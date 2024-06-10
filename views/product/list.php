<?php
$this->Title = 'Список товарів';
$products = $this->paramsArray['products'] ?? [];
$photos = $this->paramsArray['photos'] ?? [];

$maxPrice = 0;
foreach ($products as $product) {
    if ($product['price'] > $maxPrice) {
        $maxPrice = $product['price'];
    }
}
?>
<?php if (!empty($error_message)) : ?>
    <div class="my-5 d-flex flex-wrap align-items-center justify-content-center container">
        <div class="alert alert-danger" role="alert">
            <?= $error_message ?>
        </div>
    </div>
<?php endif; ?>
<div class="container d-flex">
    <div class="container">
        <h4>Фільтрувати за ціною</h4>
        <div class="row">
            <div class="col-md-3">
                <label for="minPrice" class="form-label">Від: <span id="minPriceValue">0</span></label>
                <input type="range" class="form-range" min="0" value="0" max="<?= $maxPrice ?>" id="minPrice">
            </div>
            <div class="col-md-3">
                <label for="maxPrice" class="form-label">До: <span id="maxPriceValue"><?= $maxPrice ?></span></label>
                <input type="range" class="form-range" min="0" value="<?= $maxPrice ?>" max="<?= $maxPrice ?>"
                       id="maxPrice">
            </div>
        </div>
        <div>
            <button id="filterByPrice" class="btn btn-primary mt-4">Фільтрувати</button>
        </div>
    </div>

    <div class="dropdown d-flex flex-wrap align-items-center justify-content-end container">
        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="sortDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false">
            Сортувати
        </button>
        <ul class="dropdown-menu" aria-labelledby="sortDropdown">
            <li><a class="dropdown-item sort-option" data-sort="asc" href="#">Від дешевих до дорогих</a></li>
            <li><a class="dropdown-item sort-option" data-sort="desc" href="#">Від дорогих до дешевих</a></li>
            <li><a class="dropdown-item sort-option" data-sort="availability" href="#">За наявністю</a></li>
        </ul>
    </div>
</div>
    <script>
        $(document).ready(function () {
            $('.sort-option').click(function (e) {
                e.preventDefault();
                var sortType = $(this).data('sort');
                var productsContainer = $('.products-container');
                var products = productsContainer.children('.card').toArray();

                if (sortType === 'asc') {
                    products.sort(function (a, b) {
                        return parseFloat($(a).data('price')) - parseFloat($(b).data('price'));
                    });
                } else if (sortType === 'desc') {
                    products.sort(function (a, b) {
                        return parseFloat($(b).data('price')) - parseFloat($(a).data('price'));
                    });
                } else {
                    products.sort(function (a, b) {
                        return parseFloat($(b).data('quantity')) - parseFloat($(a).data('quantity'));
                    });
                }

                productsContainer.empty();
                products.forEach(function (product) {
                    productsContainer.append(product);
                });
            });
        });

        $(document).ready(function () {
            var minPriceSlider = $('#minPrice');
            var maxPriceSlider = $('#maxPrice');

            var currentMinPrice = parseFloat(minPriceSlider.val());
            var currentMaxPrice = parseFloat(maxPriceSlider.val());

            $('#minPriceValue').text(currentMinPrice);
            $('#maxPriceValue').text(currentMaxPrice);

            minPriceSlider.on('input', function () {
                var newMinPrice = parseFloat($(this).val());
                if (newMinPrice > currentMaxPrice) {
                    maxPriceSlider.val(newMinPrice);
                    currentMaxPrice = newMinPrice;
                    $('#maxPriceValue').text(newMinPrice);
                }

                currentMinPrice = newMinPrice;
                $('#minPriceValue').text(newMinPrice);
            });

            maxPriceSlider.on('input', function () {
                var newMaxPrice = parseFloat($(this).val());

                if (newMaxPrice < currentMinPrice) {
                    minPriceSlider.val(newMaxPrice);
                    currentMinPrice = newMaxPrice;
                    $('#minPriceValue').text(newMaxPrice);
                }

                currentMaxPrice = newMaxPrice;
                $('#maxPriceValue').text(newMaxPrice);
            });

            $('#filterByPrice').click(function () {
                var minPrice = parseFloat(minPriceSlider.val());
                var maxPrice = parseFloat(maxPriceSlider.val());

                $('.card').each(function () {
                    var price = parseFloat($(this).data('price'));
                    if ((isNaN(minPrice) || price >= minPrice) && (isNaN(maxPrice) || price <= maxPrice)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
    <div class="d-flex flex-wrap align-items-center justify-content-center container products-container">
        <?php foreach ($products as $product): ?>
            <?php
            if ($product['quantity'] > 5) {
                $stockStatus = 'Є в наявності';
                $textClass = 'text-success fw-bold';
                $cardClass = '';
            } elseif ($product['quantity'] > 0) {
                $stockStatus = 'Закінчується';
                $textClass = 'text-danger fw-bold';
                $cardClass = '';
            } else {
                $stockStatus = 'Немає в наявності';
                $textClass = 'fw-bold';
                $cardClass = 'opacity-50';
            }
            ?>
            <div class="card m-2 <?= $cardClass ?> px-1" style="width: 18rem;" data-price="<?= $product['price'] ?>"
                 data-quantity="<?= $product['quantity'] ?>">
                <?php if (!empty($photos[$product['product_id']])): ?>
                    <?php $firstPhotoUrl = $photos[$product['product_id']]['image_url']; ?>
                    <img src="/uploads/<?= htmlspecialchars($firstPhotoUrl) ?>" class="card-img-top my-3 w-100 rounded"
                         alt="<?= htmlspecialchars($product['name']) ?>"
                         style="height: 200px; width: auto; object-fit: contain;">
                <?php else: ?>
                    <img src="..." class="card-img-top" alt="Фото не знайдено">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="/product/view/<?= $product['product_id'] ?>" class="text-decoration-none text-dark">
                            <?= htmlspecialchars($product['name']) ?>
                        </a>
                    </h5>
                    <p class="card-text">Ціна: <?= htmlspecialchars($product['price']) ?> грн</p>
                    <p class="card-text <?= $textClass ?>"><?= htmlspecialchars($stockStatus) ?></p>
                    <a href="/product/view/<?= $product['product_id'] ?>" class="btn btn-primary">Переглянути</a>
                    <?php if (\core\Core::isUserAdmin()): ?>
                        <button class="btn btn-danger delete-product" data-product-id="<?= $product['product_id'] ?>">
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
