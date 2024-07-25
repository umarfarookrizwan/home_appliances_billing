function scanProduct() {
    const barcode = document.getElementById('barcode').value;
    
    fetch('scan.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'barcode=' + barcode
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const purchasedItems = document.getElementById('purchasedItems');
            const item = document.createElement('div');
            item.innerHTML = `
                <p>${data.product.name}</p>
                <p>Price: ₹${data.product.price}</p>
                <label for="quantity_${data.product.id}">Quantity:</label>
                <input type="number" id="quantity_${data.product.id}" name="quantities[${data.product.id}]" required>
            `;
            purchasedItems.appendChild(item);
        } else {
            alert('Product not found.');
        }
    });
}
