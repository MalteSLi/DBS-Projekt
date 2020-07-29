var myChartObject = document.getElementById('myChart');

var chart = new Chart (myChartObject, {
    type: 'line',
    data: {
        labels: ["datum1","datum2","datum3","datum4"],
        datasets: [{
            label: "Datensatz1",
            fill: false,
            backgroundColor: '#B3D6C6',
            borderColor: '#B3D6C6',
            data: [1,2,3,4],
        },{
            label: "Datensatz2",
            fill: false,
            backgroundColor: '#B3D6C6',
            borderColor: 'rgba(255,0,0,1)',
            data: [2,3,4,5],
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});