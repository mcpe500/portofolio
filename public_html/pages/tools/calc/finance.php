<?php
/**
 * Finance Calculator (Compound Interest + ROI)
 */
?>
<div class="flex flex-col h-[calc(100vh-64px)] bg-gray-50 dark:bg-slate-900">
    <!-- Toolbar -->
    <div class="flex items-center justify-between px-4 py-2 bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <span class="material-symbols-outlined">payments</span>
            Finance Calculator
        </h1>
    </div>

    <div class="flex flex-1 overflow-hidden">
        <div class="w-full max-w-6xl mx-auto p-4 flex flex-col md:flex-row gap-6">
            
            <!-- Inputs -->
            <div class="w-full md:w-1/3 bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-gray-200 dark:border-slate-700 p-6 overflow-y-auto">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Parameters</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Initial Investment</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-400">$</span>
                            <input type="number" id="principal" value="10000" class="w-full pl-8 pr-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Monthly Contribution</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-400">$</span>
                            <input type="number" id="contribution" value="500" class="w-full pl-8 pr-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Interest Rate (Annual %)</label>
                        <div class="relative">
                            <span class="absolute right-3 top-2.5 text-gray-400">%</span>
                            <input type="number" id="rate" value="7" class="w-full pl-3 pr-8 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Time Period (Years)</label>
                        <input type="number" id="years" value="10" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-primary outline-none">
                    </div>

                    <button onclick="calculate()" class="w-full py-3 bg-primary text-white rounded-xl font-bold hover:bg-primary/90 transition-all shadow-lg shadow-primary/20 mt-4">
                        Calculate Growth
                    </button>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-slate-700">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Total Invested</p>
                            <p class="text-lg font-bold text-gray-900 dark:text-white" id="total-invested">$0</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Interest Earned</p>
                            <p class="text-lg font-bold text-green-600 dark:text-green-400" id="total-interest">$0</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs text-gray-500">Future Value</p>
                            <p class="text-3xl font-black text-primary" id="future-value">$0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart -->
            <div class="w-full md:w-2/3 bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-gray-200 dark:border-slate-700 p-6 flex flex-col">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Growth Projection</h2>
                <div class="flex-1 relative min-h-[300px]">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let chartInstance = null;

    function calculate() {
        const principal = parseFloat(document.getElementById('principal').value) || 0;
        const contribution = parseFloat(document.getElementById('contribution').value) || 0;
        const rate = parseFloat(document.getElementById('rate').value) || 0;
        const years = parseFloat(document.getElementById('years').value) || 0;

        const labels = [];
        const investedData = [];
        const interestData = [];
        const totalData = [];

        let currentBalance = principal;
        let totalInvested = principal;

        for (let i = 0; i <= years; i++) {
            labels.push(`Year ${i}`);
            investedData.push(totalInvested);
            totalData.push(currentBalance);
            interestData.push(currentBalance - totalInvested);

            // Calculate next year
            if (i < years) {
                for (let m = 0; m < 12; m++) {
                    currentBalance += contribution;
                    currentBalance *= (1 + (rate / 100) / 12);
                    totalInvested += contribution;
                }
            }
        }

        // Update UI
        document.getElementById('total-invested').textContent = formatMoney(totalInvested);
        document.getElementById('total-interest').textContent = formatMoney(currentBalance - totalInvested);
        document.getElementById('future-value').textContent = formatMoney(currentBalance);

        // Update Chart
        updateChart(labels, investedData, interestData);
    }

    function formatMoney(amount) {
        return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD', maximumFractionDigits: 0 }).format(amount);
    }

    function updateChart(labels, invested, interest) {
        const ctx = document.getElementById('growthChart').getContext('2d');
        
        if (chartInstance) {
            chartInstance.destroy();
        }

        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#94a3b8' : '#64748b';
        const gridColor = isDark ? '#334155' : '#e2e8f0';

        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Total Invested',
                        data: invested,
                        borderColor: '#94a3b8',
                        backgroundColor: '#94a3b8',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Interest Earned',
                        data: interest.map((val, i) => val + invested[i]), // Stacked visually
                        borderColor: '#10b981',
                        backgroundColor: '#10b981',
                        fill: '-1', // Fill to previous dataset
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                scales: {
                    x: {
                        grid: { color: gridColor },
                        ticks: { color: textColor }
                    },
                    y: {
                        grid: { color: gridColor },
                        ticks: {
                            color: textColor,
                            callback: function(value) {
                                return '$' + value / 1000 + 'k';
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    // For the stacked visual hack, we need to show the actual value, not the stacked value
                                    let val = context.parsed.y;
                                    if (context.datasetIndex === 1) {
                                        val = val - context.chart.data.datasets[0].data[context.dataIndex];
                                    }
                                    label += new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(val);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    // Initial calculation
    calculate();
</script>
