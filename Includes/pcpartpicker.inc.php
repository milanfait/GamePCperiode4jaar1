<?php
$categories = ['CPU', 'GPU', 'Motherboard', 'RAM', 'SSDHDD', 'PSU', 'Cabinet', 'CPU_cooler', 'Monitor', 'Keyboard', 'Mouse'];
$brandsPerCategory = [
    'CPU' => ['Intel', 'AMD'],
    'GPU' => ['NVIDIA', 'AMD'],
    'Motherboard' => ['ASUS', 'MSI', 'Gigabyte'],
    'RAM' => ['Corsair', 'G.Skill', 'Kingston'],
    'SSDHDD' => ['Samsung', 'WD', 'Seagate'],
    'PSU' => ['Corsair', 'EVGA', 'Seasonic'],
    'Cabinet' => ['NZXT', 'Cooler Master'],
    'CPU_cooler' => ['Noctua', 'be quiet!'],
    'Monitor' => ['Dell', 'LG', 'Samsung'],
    'Keyboard' => ['Logitech', 'Razer', 'Corsair'],
    'Mouse' => ['Logitech', 'Razer', 'SteelSeries']
];

// Define which categories are required (exclude Monitor, Keyboard, Mouse)
$requiredCategories = array_filter($categories, function($c) {
    return !in_array($c, ['Monitor', 'Keyboard', 'Mouse']);
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PC Part Picker</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css"> <!-- jouw custom dark theme -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .filter-box, .build-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: #000000;
            margin-bottom: 20px;
        }
        .product {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        #searchResults {
            max-height: 800px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            background-color: #000000;
        }
        .build-slot {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <h2>Build a PC</h2>
    <div class="row">
        <div class="col-md-2">
            <div class="filter-box">
                <h4>Filter by Category</h4>
                <?php foreach ($categories as $category): ?>
                    <div class="checkbox">
                        <label><input type="checkbox" class="category-checkbox" value="<?= $category ?>"> <?= $category ?></label>
                    </div>
                <?php endforeach; ?>
                <hr>
                <h4>Filter by Brand</h4>
                <div id="brandFilters"></div>
            </div>
        </div>
        <div class="col-md-7">
            <input class="form-control" id="searchInput" type="text" placeholder="Search for products...">
            <br>
            <div id="searchResults">
                <!-- Products appear here -->
            </div>
        </div>
        <div class="col-md-3">
            <div class="build-box">
                <h4>Your Current Build</h4>
                <div id="currentBuild">
                    <?php foreach ($categories as $category): ?>
                        <div class="build-slot" data-category="<?= $category ?>">
                            <strong><?= $category ?>:</strong> <span class="part-name">None</span>
                            <button class="btn btn-xs btn-danger remove-part" style="display:none;">Remove</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <br>
                <button id="buyNowBtn" class="btn btn-success btn-block" disabled>Buy now</button>

            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    const brandsPerCategory = <?= json_encode($brandsPerCategory) ?>;
    const categories = <?= json_encode($categories) ?>;
    const requiredCategories = <?= json_encode(array_values($requiredCategories)) ?>;

    function updateBrandFilters(selectedCategories) {
        const brandDiv = $('#brandFilters');
        brandDiv.empty();

        let categoriesToShow = selectedCategories.length > 0 ? selectedCategories : categories;

        categoriesToShow.forEach(cat => {
            if (brandsPerCategory[cat]) {
                brandDiv.append('<strong>' + cat + '</strong><br>');
                brandsPerCategory[cat].forEach(brand => {
                    const id = `brand-${cat}-${brand}`.replace(/\s+/g, '-');
                    brandDiv.append(
                        `<div class="checkbox">
                            <label><input type="checkbox" class="brand-checkbox" value="${brand}"> ${brand}</label>
                        </div>`
                    );
                });
                brandDiv.append('<hr>');
            }
        });
    }

    function fetchProducts(query, selectedCategories, selectedBrands) {
        $.ajax({
            url: 'search_products.php',
            type: 'GET',
            data: {
                query: query,
                categories: selectedCategories,
                brands: selectedBrands
            },
            success: function(response) {
                const container = $("#searchResults");
                container.empty();

                if (response.products.length > 0) {
                    response.products.forEach(product => {
                        container.append(`
                            <div class="product">
                                <h4>${product.name}</h4>
                                <p>${product.description}</p>
                                <p>Price: $${product.price}</p>
                                <button class="btn btn-sm btn-info select-part" data-category="${product.part}" data-id="${product.id}" data-name="${product.name}">Select ${product.part}</button>
                            </div>
                        `);
                    });
                } else {
                    container.append("<p>No products found.</p>");
                }
            }
        });
    }

    function updateBuyNowButton() {
        let allRequiredFilled = true;
        $(".build-slot").each(function () {
            const category = $(this).data("category");
            if (requiredCategories.includes(category)) {
                if ($(this).find(".part-name").text() === "None") {
                    allRequiredFilled = false;
                }
            }
        });

        $("#buyNowBtn").prop("disabled", !allRequiredFilled);
    }

    $(document).ready(function () {
        updateBrandFilters([]);

        $("#searchInput").on("keyup", function () {
            const searchQuery = $(this).val();
            const selectedCategories = $(".category-checkbox:checked").map(function () {
                return this.value;
            }).get();
            const selectedBrands = $(".brand-checkbox:checked").map(function () {
                return this.value;
            }).get();
            fetchProducts(searchQuery, selectedCategories, selectedBrands);
        });

        $(document).on("change", ".category-checkbox", function () {
            const selectedCategories = $(".category-checkbox:checked").map(function () {
                return this.value;
            }).get();
            updateBrandFilters(selectedCategories);

            const searchQuery = $("#searchInput").val();
            const selectedBrands = $(".brand-checkbox:checked").map(function () {
                return this.value;
            }).get();
            fetchProducts(searchQuery, selectedCategories, selectedBrands);
        });

        $(document).on("change", ".brand-checkbox", function () {
            const searchQuery = $("#searchInput").val();
            const selectedCategories = $(".category-checkbox:checked").map(function () {
                return this.value;
            }).get();
            const selectedBrands = $(".brand-checkbox:checked").map(function () {
                return this.value;
            }).get();
            fetchProducts(searchQuery, selectedCategories, selectedBrands);
        });

        $(document).on("click", ".select-part", function () {
            const category = $(this).data("category");
            const partName = $(this).data("name");

            const slot = $(`.build-slot[data-category='${category}']`);
            slot.find(".part-name").text(partName);
            slot.find(".remove-part").show();

            updateBuyNowButton();
        });

        $(document).on("click", ".remove-part", function () {
            const slot = $(this).closest(".build-slot");
            slot.find(".part-name").text("None");
            $(this).hide();

            updateBuyNowButton();
        });

        fetchProducts('', [], []);
    });

    $("#buyNowBtn").on("click", function () {
        // Verzamel de gekozen build
        let build = {};
        $(".build-slot").each(function () {
            const category = $(this).data("category");
            const part = $(this).find(".part-name").text();
            build[category] = part;
        });

        // Voorbeeld: stuur de gebruiker naar een andere pagina met queryparams
        const query = Object.entries(build).map(([key, val]) => key + "=" + encodeURIComponent(val)).join("&");
        window.location.href = "checkout.php?" + query;
    });
</script>
</body>
</html>

<style>
    .filter-box, .build-box {
        border: 1px solid #000000;
        padding: 15px;
        border-radius: 5px;
        background-color: #000000;
        margin-bottom: 20px;
        height: 850px;
        overflow-y: auto;
    }

    .product-box {
        border: 1px solid #000000;
        padding: 10px;
        border-radius: 5px;
        background-color: #000000;
        height: 850px;
        overflow-y: auto;
    }

    .product {
        border: 1px solid #ddd;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
    }

    #searchResults {
        max-height: 800px;
        overflow-y: auto;
        padding: 10px;
    }

    .brand-section {
        margin-bottom: 10px;
    }

    .brand-section strong {
        display: block;
        margin-bottom: 5px;
        margin-top: 10px;
    }

    .build-slot {
        margin-bottom: 10px;
        padding: 5px 10px;
        background-color: #000000;
        border-left: 5px solid #FF4444;
        border-radius: 3px;
    }

    .build-box h4 {
        border-bottom: 2px solid #FF4444;
        padding-bottom: 5px;
        margin-bottom: 15px;
    }
</style>
