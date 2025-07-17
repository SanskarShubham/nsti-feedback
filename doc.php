<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSTI Howrah - System & Data Infographic</title>
    <link href="images/favicon.png" rel="icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #E0E1DD;
        }
        .chart-container {
            position: relative;
            width: 100%;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            height: 40vh;
            max-height: 450px;
        }
        @media (min-width: 768px) {
            .chart-container {
                height: 350px;
            }
        }
        .flow-arrow {
            color: #415A77;
            font-size: 2rem;
            line-height: 1;
        }
        .flow-card {
            border: 2px solid #778DA9;
            background-color: #FFFFFF;
        }
    </style>
</head>
<body class="text-gray-800">

    <div class="container mx-auto p-4 md:p-8">

        <header class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold text-[#0D1B2A] mb-2">NSTI Howrah Digital Feedback Ecosystem</h1>
            <p class="text-lg text-[#415A77]">An Infographic on the Student Feedback System and Institutional Data</p>
        </header>

        <section id="kpis" class="mb-16">
            <h2 class="text-3xl font-bold text-center text-[#0D1B2A] mb-8">System at a Glance</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 text-center">
                <div class="bg-[#1B263B] p-6 rounded-lg shadow-lg">
                    <p class="text-6xl font-extrabold text-white">542</p>
                    <p class="text-xl text-[#E0E1DD] mt-2">Total Students</p>
                </div>
                <div class="bg-[#1B263B] p-6 rounded-lg shadow-lg">
                    <p class="text-6xl font-extrabold text-white">33</p>
                    <p class="text-xl text-[#E0E1DD] mt-2">Active Teachers</p>
                </div>
                <div class="bg-[#1B263B] p-6 rounded-lg shadow-lg">
                    <p class="text-6xl font-extrabold text-white">31</p>
                    <p class="text-xl text-[#E0E1DD] mt-2">Active Trades</p>
                </div>
                <div class="bg-[#1B263B] p-6 rounded-lg shadow-lg">
                    <p class="text-6xl font-extrabold text-white">8</p>
                    <p class="text-xl text-[#E0E1DD] mt-2">Core Subjects</p>
                </div>
            </div>
        </section>

        <section id="distribution" class="mb-16">
            <h2 class="text-3xl font-bold text-center text-[#0D1B2A] mb-8">Student & Program Distribution</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-[#0D1B2A] mb-4 text-center">Student Enrollment by Program</h3>
                    <p class="text-center text-[#415A77] mb-4">The majority of students are enrolled in CITS programs, highlighting its significance within the institution's offerings.</p>
                    <div class="chart-container" style="height: 300px; max-height: 300px;">
                        <canvas id="programDistributionChart"></canvas>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-[#0D1B2A] mb-4 text-center">Top 10 Trades by Student Enrollment</h3>
                     <p class="text-center text-[#415A77] mb-4">The "Fitter" and "Draughtsman (Civil)" trades have the highest student counts, indicating strong demand in these vocational areas.</p>
                    <div class="chart-container" style="height: 400px; max-height: 400px;">
                        <canvas id="tradeDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <section id="feedback-flow" class="mb-16">
            <h2 class="text-3xl font-bold text-center text-[#0D1B2A] mb-8">The Secure Feedback Ecosystem</h2>
            <p class="text-center text-[#415A77] max-w-3xl mx-auto mb-10">The system is built around a secure and role-based workflow, ensuring student anonymity while providing actionable insights for teachers and administrators.</p>
            <div class="flex flex-col items-center">
                <div class="flow-card p-4 rounded-lg shadow-md text-center w-full md:w-3/4 lg:w-1/2">
                    <p class="font-bold text-lg text-[#0D1B2A]">1. Student Initiates Feedback</p>
                    <p class="text-[#415A77]">Student enters their unique 8-digit Attendance ID (from Aadhar) for validation.</p>
                </div>
                <div class="flow-arrow my-2">&#x2193;</div>
                <div class="flow-card p-4 rounded-lg shadow-md text-center w-full md:w-3/4 lg:w-1/2">
                    <p class="font-bold text-lg text-[#0D1B2A]">2. System Validation & Data Fetch</p>
                    <p class="text-[#415A77]">The system verifies the ID and dynamically displays the student's assigned teachers and subjects.</p>
                </div>
                <div class="flow-arrow my-2">&#x2193;</div>
                <div class="flow-card p-4 rounded-lg shadow-md text-center w-full md:w-3/4 lg:w-1/2">
                    <p class="font-bold text-lg text-[#0D1B2A]">3. Feedback Submission</p>
                    <p class="text-[#415A77]">Student provides a 1-5 rating and detailed remarks for a selected teacher/subject.</p>
                </div>
                <div class="flow-arrow my-2">&#x2193;</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full mt-2">
                    <div class="flex flex-col items-center">
                         <div class="flow-card p-4 rounded-lg shadow-md text-center w-full">
                            <p class="font-bold text-lg text-[#0D1B2A]">4a. Teacher Review (Anonymous)</p>
                            <p class="text-[#415A77]">The assigned teacher logs in and views the rating and remarks. Student identity is never revealed.</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="flow-card p-4 rounded-lg shadow-md text-center w-full">
                            <p class="font-bold text-lg text-[#0D1B2A]">4b. Admin Review (Full Access)</p>
                            <p class="text-[#415A77]">Admin logs in and can view all feedback with full details, including student identity for traceability and analysis.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="tech" class="mb-16">
            <h2 class="text-3xl font-bold text-center text-[#0D1B2A] mb-8">System Architecture & Security</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-[#0D1B2A] mb-4 text-center">Technology Stack</h3>
                    <ul class="space-y-3 text-[#415A77]">
                        <li class="flex items-center"><span class="font-bold text-[#0D1B2A] w-32">Backend:</span> PHP</li>
                        <li class="flex items-center"><span class="font-bold text-[#0D1B2A] w-32">Frontend:</span> HTML, CSS, JavaScript</li>
                        <li class="flex items-center"><span class="font-bold text-[#0D1B2A] w-32">Database:</span> MariaDB</li>
                        <li class="flex items-center"><span class="font-bold text-[#0D1B2A] w-32">Server Env:</span> XAMPP (Local), Apache (Live)</li>
                        <li class="flex items-center"><span class="font-bold text-[#0D1B2A] w-32">Deployment:</span> FTP via WinSCP</li>
                    </ul>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-[#0D1B2A] mb-4 text-center">Security & Data Integrity</h3>
                     <ul class="space-y-3 text-[#415A77]">
                        <li class="flex items-start"><span class="text-2xl mr-3 text-[#00A6FB]">&#x2713;</span><div><span class="font-bold text-[#0D1B2A]">Secure ID Validation:</span> Uses the last 8 digits of Aadhar as a unique, non-guessable attendance ID.</div></li>
                        <li class="flex items-start"><span class="text-2xl mr-3 text-[#00A6FB]">&#x2713;</span><div><span class="font-bold text-[#0D1B2A]">Password Hashing:</span> Employs bcrypt for hashing user passwords, a strong and industry-standard algorithm.</div></li>
                        <li class="flex items-start"><span class="text-2xl mr-3 text-[#00A6FB]">&#x2713;</span><div><span class="font-bold text-[#0D1B2A]">Role-Based Access:</span> Strict access control ensures teachers only see relevant, anonymous feedback, while admins have managed oversight.</div></li>
                         <li class="flex items-start"><span class="text-2xl mr-3 text-[#00A6FB]">&#x2713;</span><div><span class="font-bold text-[#0D1B2A]">Student Anonymity:</span> The system is designed to protect student privacy by never revealing their identity to teachers.</div></li>
                    </ul>
                </div>
            </div>
        </section>

    </div>

    <script>
        const chartTooltipPlugin = {
            tooltip: {
                callbacks: {
                    title: function(tooltipItems) {
                        const item = tooltipItems[0];
                        let label = item.chart.data.labels[item.dataIndex];
                        if (Array.isArray(label)) {
                            return label.join(' ');
                        } else {
                            return label;
                        }
                    }
                }
            }
        };

        function wrapLabels(label, maxWidth) {
            const words = label.split(' ');
            const lines = [];
            let currentLine = '';
            words.forEach(word => {
                if ((currentLine + word).length > maxWidth) {
                    lines.push(currentLine.trim());
                    currentLine = '';
                }
                currentLine += word + ' ';
            });
            lines.push(currentLine.trim());
            return lines;
        }

        const programData = {
            labels: ['CITS', 'CTS'],
            datasets: [{
                label: 'Number of Students',
                data: [473, 69],
                backgroundColor: ['#00A6FB', '#415A77'],
                borderColor: '#E0E1DD',
                borderWidth: 2
            }]
        };

        const tradeData = {
            labels: [
                'Fitter',
                'Draughtsman (Civil)',
                'Electrician',
                'Welder',
                'Mechanic Motor Vehicle',
                'Computer Software Applications',
                'Mechanic Refrigeration & Air-Conditioning',
                'Reading of Drawing and Arithmetic',
                'Surveyor',
                'Turner'
            ],
            datasets: [{
                label: 'Number of Students',
                data: [98, 48, 48, 38, 20, 24, 23, 29, 24, 22],
                backgroundColor: '#00A6FB',
                borderColor: '#0D1B2A',
                borderWidth: 1
            }]
        };
        
        tradeData.labels = tradeData.labels.map(label => wrapLabels(label, 16));

        new Chart(document.getElementById('programDistributionChart'), {
            type: 'doughnut',
            data: programData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: chartTooltipPlugin
            }
        });

        new Chart(document.getElementById('tradeDistributionChart'), {
            type: 'bar',
            data: tradeData,
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: chartTooltipPlugin,
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                           color: '#D1D5DB'
                        }
                    },
                    y: {
                       grid: {
                           display: false
                        }
                    }
                }
            }
        });
    </script>

</body>
</html>
