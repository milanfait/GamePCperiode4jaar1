<?php
// Just brands, since no categories on prebuilt page
$brands = ['Intel', 'AMD', 'ASUS', 'MSI', 'Gigabyte', 'Corsair', 'NVIDIA', 'Samsung', 'Logitech', 'Razer'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Prebuilt PC Search</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css"> <!-- jouw custom dark theme -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .filter-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
            height: 850px;
            overflow-y: auto;
            margin-bottom: 20px;
        }
        .product-box {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            background-color: #fff;
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
    </style>
</head>
<body>
<div class="container-fluid">
    <h2>Prebuilt PC Search</h2>
    <div class="row">
        <div class="col-md-3">
            <div class="filter-box">
                <h4>Filter by Brand</h4>
                <div id="brandFilters">
                    <?php foreach ($brands as $brand): ?>
                        <div class="checkbox">
                            <label><input type="checkbox" class="brand-checkbox" value="<?= $brand ?>"> <?= $brand ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <input class="form-control" id="searchInput" type="text" placeholder="Search for prebuilt PCs...">
            <br>
            <div id="searchResults">
                <!-- Prebuilt products appear here -->
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    function fetchPrebuilt(query, selectedBrands) {
        $.ajax({
            url: 'search_prebuilt.php',
            type: 'GET',
            data: {
                query: query,
                brands: selectedBrands
            },
            success: function(response) {
                const container = $("#searchResults");
                container.empty();

                if (response.prebuilts.length > 0) {
                    response.prebuilts.forEach(prebuilt => {
                        container.append(`
                            <div class="product">
                                <h4>${prebuilt.name}</h4>
                                <p>${prebuilt.description}</p>
                                <p>Brand: ${prebuilt.brand || 'Unknown'}</p>
                                <p>Price: $${prebuilt.price}</p>
                                <button class="btn btn-sm btn-primary buy-now" data-id="${prebuilt.id}">Buy Now</button>
                            </div>
                        `);
                    });
                } else {
                    container.append("<p>No prebuilts found.</p>");
                }
            }
        });
    }

    $(document).ready(function() {
        $("#searchInput").on("keyup", function() {
            const searchQuery = $(this).val();
            const selectedBrands = $(".brand-checkbox:checked").map(function() {
                return this.value;
            }).get();
            fetchPrebuilt(searchQuery, selectedBrands);
        });

        $(document).on("change", ".brand-checkbox", function() {
            const searchQuery = $("#searchInput").val();
            const selectedBrands = $(".brand-checkbox:checked").map(function() {
                return this.value;
            }).get();
            fetchPrebuilt(searchQuery, selectedBrands);
        });

        $(document).on("click", ".buy-now", function() {
            const id = $(this).data('id');
            alert("You clicked buy on prebuilt ID: " + id);
            // Redirect or handle checkout here
        });

        // Initial load with no filter
        fetchPrebuilt('', []);
    });
</script>
</body>
</html>
