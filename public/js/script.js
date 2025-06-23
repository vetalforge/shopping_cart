$(document).ready(function () {
    sb_set_basket_events();
});

function sb_set_basket_events() {
    // Set scroll for basket
    $(window).scroll(function () {
        if ($(window).scrollTop() === 0) {
            $(".basket-container").removeClass("basket-scrolling");
        } else {
            if (!$(".basket-container").hasClass("basket-scrolling")) {
                $(".basket-container").toggleClass("basket-scrolling");
            }
        }
    });

    // Restore items from session
    restoreItems();

    // Set Icon open and close
    $(".basket-icon").click(function () {
        $(this).parent().toggleClass("open-basket");
    });

    // Set add to basket events
    $(".basket-add").click(function () {
        addItem($(this).data("basket-product-id"));

        // Front renewing
        sb_add_to_basket(
            $(this).data("basket-product-id"),
            $(this).data("basket-product-name"),
            $(this).data("basket-product-price")
        );
    });
}

function sb_add_to_basket(pid, name, price) {
    if (sb_product_not_exist(pid)) {
        let shortName = name;

        if (name.length > 10) {
            shortName = name.substring(15, 0) + "...";
        }

        $(".basket-products ul").append(
            $("<li>").append(
                $("<span>", {"class": "oi oi-x remove-product", "data-id": pid}).click(function () {
                    removeItem(pid);
                    sb_remove_from_basket($(this));
                }),
                $("<input>", {"type": "number", "min": "1", "data-id": pid}).val(1).change(function () {
                    updateItem(pid, parseInt(this.value));
                    sb_sum_total();
                }),
                shortName,
                $("<span>", {"class": "amount"}).text("\u20AC " + price)
            ).data("price", price).data("pid", pid)
        );
    }

    sb_sum_total();
    sb_update_basket_amount();
}

function sb_product_not_exist(pid) {
    var notFound = true;
    $(".basket-products ul").find("li").each(function () {
        if ($(this).data("pid") == pid) {
            var val = Number($(this).find("input").val()) + 1;
            $(this).find("input").val(val);
            notFound = false;
            return false;
        } else {
            notFound = true;
        }
    });
    return notFound;
}

function sb_remove_from_basket(product) {
    $(product).parent().remove();
    sb_sum_total();
    sb_update_basket_amount();
}

function sb_sum_total() {
    var total = 0;
    $(".basket-products ul").find("li").each(function () {
        var amount = Number($(this).find("input").val());
        total = total + (amount * Number($(this).data("price")));
    });
    $(".basket-total-amount").text("\u20AC " + total);
}

function sb_update_basket_amount() {
    $(".basket-count p").text($(".basket-products ul").find("li").length);
}

/* --- Fetch section --- */
function restoreItems() {
    fetch('/cart', {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(function (data) {
            console.log(data)
            data.forEach(function (item) {
                for (let i = 0; i < item.quantity; i++) {
                    sb_add_to_basket(item.product_id, item.title, item.price);
                }
            });
        })
        .catch(err => console.error('Error:', err));
}

function addItem(itemProductId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: itemProductId,
            quantity: 1
        })
    })
        .then(response => response.json())
        .then(data => console.log(data))
        .catch(err => console.error('Error:', err));
}

function updateItem(itemProductId, quantity) {
    fetch(`/cart/update/${itemProductId}`, {
        method: 'PUT',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: itemProductId,
            quantity: quantity
        })
    })
        .then(response => response.json())
        .then(data => console.log(data))
        .catch(err => console.error('Error:', err));
}

function removeItem(itemProductId) {
    fetch(`/cart/remove/${itemProductId}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: itemProductId,
        })
    })
        .then(response => response.json())
        .then(data => console.log(data))
        .catch(err => console.error('Error:', err));
}
