<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Simple Shopping Basket</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.css'>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<!-- partial:index.partial.html -->
<div class="body-container">

    <!-- Basket -->
    <div class="basket-container">
        <div class="basket-count">
            <p>0</p>
        </div>
        <div class="basket-icon">
            <span class="oi oi-box"></span>
        </div>
        <div class="basket-content">
            <h3>Products</h3>
            <div class="basket-products">
                <ul>
                </ul>
            </div>
            <p class="basket-total">TOTAL:</p>
            <p class="basket-total-amount">&euro; 0</p>
            <div class="clear"></div>
            <button class="basket-checkout">CHECKOUT</button>
        </div>
    </div>

    <!-- Top Header -->
    <div class="header">
        <ul>
            <li>Bootsrap 4</li>
            <li>Iconic</li>
            <li>jQuery</li>
        </ul>
    </div>

    <div class="products">
        @foreach($products as $product)
            <div class="product">
                <img class="prod-img" src="{{ $product->image }}">
                <h2>{{ $product->title }} &euro; {{ $product->price }},-</h2>
                <p>{{ $product->description }}</p>
                <div class="clear"></div>
                <button class="basket-add"
                        data-basket-product-price="{{ $product->price }}"
                        data-basket-product-id="{{ $product->id }}"
                        data-basket-product-name="{{ $product->title }}">ADD TO CART</button>
            </div>
        @endforeach
    </div>

</div>
<!-- partial -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
<script  src="/js/script.js"></script>
</body>
</html>
