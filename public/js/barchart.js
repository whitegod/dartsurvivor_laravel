// Example for Chart.js
const moveoutsData = window.moveoutsPerMonth || {};

// Generate last 12 months labels (Jan, Feb, ...) without year
const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
const months = [];
const now = new Date();
for (let i = 11; i >= 0; i--) {
    const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
    months.push(monthNames[d.getMonth()]);
}

// Fill data for each month, use 0 if not present
const dataKeys = [];
for (let i = 11; i >= 0; i--) {
    const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
    dataKeys.push(d.toISOString().slice(0, 7));
}
const data = dataKeys.map(month => moveoutsData[month] ? moveoutsData[month] : 0);

const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Program Moveouts',
            data: data,
            backgroundColor: 'rgba(0, 0, 0, 0.8)'
        }]
    },
    options: {
        scales: {
            x: {
                grid: {
                    display: false // Hide vertical grid lines
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    // Only show integer values on y-axis
                    callback: function(value) {
                        if (Number.isInteger(value)) {
                            return value;
                        }
                    },
                    stepSize: 1
                }
            }
        }
    }
});