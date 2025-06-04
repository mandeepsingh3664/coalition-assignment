<html>
<head>
    <title>Coalition Task</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/jquery.min.js') }}"></script>
</head>
<body>
<div class="container">
    <h1 class="mt-5">Product Inventory</h1>
    <form id="productForm" class="mt-4">
        @csrf
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" id="quantity" name="quantity" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="price">Price per Item</label>
            <input type="number" id="price" name="price" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Save Product</button>
    </form>

    <h2 class="mt-5">Inventory List</h2>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price per Item</th>
                <th>Date</th>
                <th>Total Value</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody id="productTable">
            @foreach($products as $index => $product)
                <tr data-id="{{ $index }}">
                    <td class="name">{{ $product['name'] }}</td>
                    <td class="quantity">{{ $product['quantity'] }}</td>
                    <td class="price">{{ $product['price'] }}</td>
                    <td>{{ $product['created_at'] }}</td>
                    <td class="total_value">{{ $product['total_value'] }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-btn">Edit</button>
                        <button class="btn btn-sm btn-success save-btn d-none">Save</button>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4"><strong>Total</strong></td>
                <td><strong>{{ array_sum(array_column($products, 'total_value')) }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function () {
    $('#productForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: '{{ route('products.store') }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                if (response.success) {
                    console.log(response);

                    const product = response.product;
                    const row = `<tr data-id="${response.total}">
                        <td class="name">${product.name}</td>
                        <td class="quantity">${product.quantity}</td>
                        <td class="price">${product.price}</td>
                        <td class="created_at">${product.created_at}</td>
                        <td class="total_value">${product.total_value}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn">Edit</button>
                            <button class="btn btn-sm btn-success save-btn d-none">Save</button>
                        </td>
                    </tr>`;
                    $('#productTable').prepend(row);
                    updateTotal();
                }
            }
        });
    });

    $(document).on('click', '.edit-btn', function () {
        const row = $(this).closest('tr');
        row.find('.edit-btn').addClass('d-none');
        row.find('.save-btn').removeClass('d-none');

        console.log(row);
        row.find('td.name, td.quantity, td.price').each(function () {
            console.log('ss');
            const value = $(this).text();
            $(this).html(`<input class="form-control" value="${value}">`);
        });
    });

    $(document).on('click', '.save-btn', function () {
        const row = $(this).closest('tr');
        console.log(row);

        const id = row.data('id');
        const name = row.find('td.name input').val();
        const quantity = parseInt(row.find('td.quantity input').val());
        const price = parseFloat(row.find('td.price input').val());
        console.log(row);

        $.ajax({
            url: '{{ route('products.update') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                name: name,
                quantity: quantity,
                price: price    
            },
            success: function (response) {
                const updated = response.product;
                console.log(updated);
                console.log(row.find('td.total_value'));


                row.find('.edit-btn').removeClass('d-none');
                row.find('.save-btn').addClass('d-none');
                
                row.find('td.name').html(updated.name);
                row.find('td.quantity').html(updated.quantity);
                row.find('td.price').html(updated.price);
                //debugger;
                row.find('td.total_value').html(updated.total_value);

                updateTotal();
            }
        });
    });

    function updateTotal() {

        console.log('UPdate Hit');
        let total = 0;
        $('#productTable tr').each(function () {
            const value = parseFloat($(this).find('.total_value').text());
            if (!isNaN(value)) total += value;
        });
        $('#productTable tr:last td:last').html('<strong>' + total.toFixed(2) + '</strong>');
    }
});
</script>
</body>
</html>