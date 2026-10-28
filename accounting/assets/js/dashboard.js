$(document).ready(function () {
    // Sample data for demonstration
    const totalSales = <?= json_encode($totalSales) ?>;
    const totalExpenses = 12000; // Replace this with actual dynamic expense value if available

    // Chart data
    const ctx = document.getElementById('financialOverviewChart').getContext('2d');
    const financialOverviewChart = new Chart(ctx, {
        type: 'bar', // You can change to 'line' or 'pie' based on your preference
        data: {
            labels: ['Total Sales', 'Total Expenses'],
            datasets: [{
                label: 'Financial Overview',
                data: [totalSales, totalExpenses],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)', // Sales color
                    'rgba(255, 99, 132, 0.2)' // Expenses color
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Amount ($)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Categories'
                    }
                }
            }
        }
    });
});
