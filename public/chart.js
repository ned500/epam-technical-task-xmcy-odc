$(document).ready(function () {
    const chart = $('#chart');
    if (!chart.length) {
        return;
    }
    new Chart(chart, {
        type: 'line',
        data: {
            labels: JSON.parse(chart.attr('data-labels')),
            datasets: [{
                label: 'Open',
                data: JSON.parse(chart.attr('data-opens')),
                backgroundColor: ['rgba(54, 162, 235, 0.2)'],
                borderColor: ['rgba(54, 162, 235, 0.2)'],
            }, {
                label: 'Close',
                data: JSON.parse(chart.attr('data-closes')),
                backgroundColor: ['rgba(254, 145, 23, 0.2)'],
                borderColor: ['rgba(254, 145, 23, 0.2)'],
            }]
        },
        options: {
            scales: {
                y: {
                    // beginAtZero: true
                }
            }
        }
    });
});
