// Example dynamic data for the dashboard
document.addEventListener("DOMContentLoaded", function () {
    const totalProducts = document.getElementById('total-products');
    const totalOrders = document.getElementById('total-orders');
    const totalSuppliers = document.getElementById('total-suppliers');
    const totalUsers = document.getElementById('total-users');
    const totalWarehouses = document.getElementById('total-warehouses');

    // Simulating an API call to get dynamic data (example)
    setTimeout(() => 
	{
        	totalProducts.textContent = '1,500'; // Example dynamic value
       		 totalOrders.textContent = '450'; // Example dynamic value
        	totalSuppliers.textContent = '20'; // Example dynamic value
        	totalUsers.textContent = '80'; // Example dynamic value
         	totalWarehouses.textContent = '50'; // Example dynamic value
    	}, 1000);
});
