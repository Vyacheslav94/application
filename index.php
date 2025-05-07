<?php
session_start();

// Массив товаров
$products = [
    1 => ['name' => 'Розы', 'price' => 500],
    2 => ['name' => 'Тюльпаны', 'price' => 300],
    3 => ['name' => 'Лилии', 'price' => 700],
    4 => ['name' => 'Герберы', 'price' => 400],
];

// Обработка добавления в корзину
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = (int)$_POST['product_id'];
    if (isset($products[$productId])) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]++;
        } else {
            $_SESSION['cart'][$productId] = 1;
        }
    }
}

// Обработка очистки корзины
if (isset($_GET['action']) && $_GET['action'] === 'clear') {
    unset($_SESSION['cart']);
}

// Получение содержимого корзины
$cartItems = [];
$totalPrice = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $quantity) {
        $product = $products[$id];
        $subtotal = $product['price'] * $quantity;
        $cartItems[] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];
        $totalPrice += $subtotal;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <title>Магазин цветов</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #4CAF50; }
        .product { border: 1px solid #ccc; padding: 10px; margin: 10px; display: inline-block; width: 200px; vertical-align: top; }
        .cart { margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        .btn { background-color: #4CAF50; color: white; padding: 8px 12px; border: none; cursor: pointer; }
        .btn:hover { background-color: #45a049; }
    </style>
</head>
<body>
<h1>Добро пожаловать в магазин цветов</h1>

<h2>Каталог товаров</h2>
<div>
    <?php foreach ($products as $id => $product): ?>
        <div class="product">
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p>Цена: <?php echo number_format($product['price'], 0, '.', ' '); ?> руб.</p>
            <form method="post" action="">
                <input type="hidden" name="product_id" value="<?php echo $id; ?>" />
                <button type="submit" class="btn">Добавить в корзину</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

<div class="cart">
    <h2>Корзина</h2>
    <?php if (!empty($cartItems)): ?>
        <table>
            <tr>
                <th>Название</th>
                <th>Цена за шт.</th>
                <th>Количество</th>
                <th>Общая стоимость</th>
            </tr>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo number_format($item['price'], 0, '.', ' '); ?> руб.</td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo number_format($item['subtotal'], 0, '.', ' '); ?> руб.</td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3"><strong>ИТОГО</strong></td>
                <td><strong><?php echo number_format($totalPrice, 0, '.', ' '); ?> руб.</strong></td>
            </tr>
        </table>
        <a href="?action=clear" class="btn" style="margin-top:10px;">Очистить корзину</a>
    <?php else: ?>
        <p>В корзине пусто.</p>
    <?php endif; ?>
</div>
</body>
</html>
