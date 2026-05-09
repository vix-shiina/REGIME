<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Catalyst - Ship Faster, Scale Infinitely</title>

    <!--

    TemplateMo 618 The Catalyst

    https://templatemo.com/tm-618-the-catalyst

    Silver & Acid Light-Mode SaaS Landing Page

    -->

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Template CSS -->
    <link rel="stylesheet" href="/assets/css/templatemo-catalyst-style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <!-- ===== NAVIGATION ===== -->
    <nav class="nav" role="navigation" aria-label="Main navigation">
        <div class="nav-inner">
            <a href="#" class="nav-logo" aria-label="Catalyst Home">
                <span class="nav-logo-mark">
                    <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 8L7 4L13 8L7 12L3 8Z" fill="#111"/>
                    </svg>
                </span>
                Catalyst
            </a>
            <ul class="nav-links" id="navLinks">
                <li><a href="#metrics">Performance</a></li>
                <li><a href="#engine">Features</a></li>
                <li><a href="#pricing">Pricing</a></li>
                <li><a href="#faq">FAQ</a></li>
            </ul>
            <div class="nav-cta">
                <a href="#" class="btn-ghost">Log In</a>
                <a href="#pricing" class="btn-acid">Start Free</a>
            </div>
            <button class="nav-toggle" id="navToggle" aria-label="Toggle menu" aria-expanded="false">
                <span></span>
            </button>
        </div>
    </nav>

    <!-- ===== SECTION 1: HERO ===== -->
    <section class="hero">
        <div class="container">
            <div class="hero-badge reveal">
                <span class="hero-badge-dot"></span>
                v4.2 — Now with real-time sync
            </div>
            <h1 class="reveal">The analytics platform built for <span class="accent-word">speed</span></h1>
            <p class="hero-sub reveal">Stop waiting for insights. Catalyst delivers sub-second queries across billions of rows so your team ships decisions, not dashboards.</p>
            <div class="hero-actions reveal">
                <a href="#pricing" class="btn-acid">
                    Start Free Trial
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <a href="#" class="btn-ghost">Watch Demo</a>
            </div>

            <!-- Dashboard Mockup -->
            <div class="hero-dashboard">
                <div class="dashboard-frame">
                    <div class="dashboard-toolbar">
                        <div class="dashboard-dots">
                            <span></span><span></span><span></span>
                        </div>
                        <div class="dashboard-toolbar-tabs">
                            <span class="active">Overview</span>
                            <span>Funnels</span>
                            <span>Retention</span>
                            <span>Revenue</span>
                        </div>
                        <div style="width: 50px;"></div>
                    </div>

<div class="dashboard-body">
    <div class="dash-stat-card">
        <div class="dash-stat-label">Poids Actuel</div>
        <div class="dash-stat-value">
            <?php 
                // Affiche le dernier poids enregistré ou "0"
                echo !empty($historique) ? end($historique)['Poids'] : '0'; 
            ?> kg
        </div>
    </div>

    <div class="dash-stat-card" style="grid-column: span 2;">
        <div class="dash-stat-label">Mettre à jour mon poids</div>
        <form action="<?= base_url('dashboard/ajouterPoids') ?>" method="post" style="display: flex; gap: 10px; margin-top: 10px;">
            <input type="hidden" name="userId" value="<?= $userId ?>">
            <input type="number" step="0.1" name="poids" placeholder="Ex: 75.5" 
                   style="padding: 8px; border-radius: 5px; border: 1px solid #ccc; width: 100%;">
            <button type="submit" class="btn-acid" style="padding: 8px 15px; font-size: 14px;">OK</button>
        </form>
    </div>

    <div class="dashboard-table-area" style="padding: 20px;">
        <div class="dash-table-header">
            <span>Évolution de ma courbe</span>
        </div>
        <canvas id="evolutionChart" style="width: 100%; max-height: 250px;"></canvas>
    </div>
</div>

                </div>
            </div>
        </div>
    </section>

    <!-- ===== SECTION 2: SPEED METRICS ===== -->
    <section class="metrics" id="metrics">
        <div class="container">
            <p class="metrics-label reveal">Performance that speaks for itself</p>
            <div class="metrics-grid">
                <div class="metric-item reveal">
                    <div class="metric-number"><span class="metric-highlight">42</span><span class="metric-unit">ms</span></div>
                    <p class="metric-desc">Average query response time across 10B+ row datasets</p>
                </div>
                <div class="metric-item reveal">
                    <div class="metric-number">99.99<span class="metric-unit">%</span></div>
                    <p class="metric-desc">Guaranteed uptime SLA for all enterprise customers</p>
                </div>
                <div class="metric-item reveal">
                    <div class="metric-number">10<span class="metric-unit">x</span></div>
                    <p class="metric-desc">Faster than legacy warehouses at one-tenth the cost</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SECTION 3: THE ENGINE — BENTO GRID ===== -->
    <section class="engine" id="engine">
        <div class="container">
            <div class="section-header reveal">
                <p class="section-tag">The Engine</p>
                <h2 class="section-title">Everything your data team actually needs</h2>
            </div>
            <div class="bento-grid">
                <div class="card bento-card reveal">
                    <div class="bento-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 16l4-8 4 4 4-6"/></svg>
                    </div>
                    <h3>Real-Time Analytics</h3>
                    <p>Stream, transform, and visualize data the instant it arrives. No batching, no waiting, no stale dashboards.</p>
                    <div class="bento-card-visual">
                        <div class="mini-bars">
                            <div class="mini-bar" style="height: 35%;"></div>
                            <div class="mini-bar" style="height: 55%;"></div>
                            <div class="mini-bar" style="height: 40%;"></div>
                            <div class="mini-bar highlight" style="height: 80%;"></div>
                            <div class="mini-bar" style="height: 60%;"></div>
                            <div class="mini-bar" style="height: 45%;"></div>
                            <div class="mini-bar highlight" style="height: 95%;"></div>
                            <div class="mini-bar" style="height: 70%;"></div>
                            <div class="mini-bar" style="height: 50%;"></div>
                            <div class="mini-bar" style="height: 65%;"></div>
                        </div>
                    </div>
                </div>
                <div class="card bento-card reveal">
                    <div class="bento-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                    </div>
                    <h3>Pipeline Orchestration</h3>
                    <p>Build fault-tolerant data pipelines with visual drag-and-drop. Auto-retry, dead-letter queues, and full observability built in.</p>
                    <div class="bento-card-visual">
                        <div class="mini-pipeline">
                            <div class="mini-pipeline-node">Ingest</div>
                            <span class="mini-pipeline-arrow">→</span>
                            <div class="mini-pipeline-node">Transform</div>
                            <span class="mini-pipeline-arrow">→</span>
                            <div class="mini-pipeline-node">Deliver</div>
                        </div>
                    </div>
                </div>
                <div class="card bento-card reveal">
                    <div class="bento-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 18l6-6-6-6"/><path d="M8 6l-6 6 6 6"/></svg>
                    </div>
                    <h3>SQL-First Interface</h3>
                    <p>Write standard SQL against any source. Auto-completion, version control, and collaborative editing come standard.</p>
                    <div class="bento-card-visual">
                        <div class="mini-code">
                            <span class="keyword">SELECT</span> user_id, <span class="keyword">COUNT</span>(*)<br>
                            <span class="keyword">FROM</span> events<br>
                            <span class="keyword">WHERE</span> ts > <span class="string">'2026-01'</span><br>
                            <span class="keyword">GROUP BY</span> 1 <span class="comment">-- 42ms ⚡</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

 <section class="pricing" id="pricing">
    <div class="container">
        <div class="section-header">
            <p class="section-tag">Programmes</p>
            <h2 class="section-title">Régimes suggérés pour vous</h2>
        </div>
        
        <div class="pricing-grid">
            <?php foreach ($regimes as $r): ?>
            <div class="card pricing-card">
                <div class="pricing-plan-name"><?= $r['RegimeNom'] ?></div>
                <div class="pricing-amount">
                    <span class="pricing-currency">Ar</span>
                    <span class="pricing-value"><?= number_format($r['PrixJournaliere'], 0, '.', ' ') ?></span>
                </div>
                <p class="pricing-period">par semaine</p>
                
                <ul class="pricing-features" style="text-align: left; margin-top: 20px;">
                    <li><strong>Viande :</strong> <?= $r['Viande'] ?>%</li>
                    <li><strong>Poisson :</strong> <?= $r['Poisson'] ?>%</li>
                    <li><strong>Volaille :</strong> <?= $r['Volailles'] ?>%</li>
                </ul>
                <a href="#" class="btn-acid" style="margin-top: 20px;">Choisir</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="sports" id="sports" style="padding: 60px 0;">
    <div class="container">
        <div class="section-header">
            <p class="section-tag">Activités</p>
            <h2 class="section-title">Sports recommandés</h2>
        </div>
        
        <div class="pricing-grid">
            <?php foreach ($sports as $s): ?>
            <div class="card pricing-card">
                <div class="pricing-plan-name"><?= $s['SportNom'] ?></div>
                <div class="pricing-amount">
                    <span class="pricing-value"><?= $s['EfficacitePoids'] ?></span>
                    <span class="pricing-currency">kg</span>
                </div>
                <p class="pricing-period">par semaine</p>
                
                <ul class="pricing-features" style="text-align: left; margin-top: 20px;">
                    <li><strong>Catégorie :</strong> <?= $s['Categorie'] ?></li>
                </ul>
                <a href="#" class="btn-ghost" style="margin-top: 20px;">Sélectionner</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

    <!-- ===== SECTION 5: FAQ ===== -->
    <section class="faq" id="faq">
        <div class="container">
            <div class="section-header reveal">
                <p class="section-tag">FAQ</p>
                <h2 class="section-title">Common questions, straight answers</h2>
            </div>
            <div class="faq-controls reveal">
                <button class="faq-ctrl-btn" id="faqExpandAll">Expand All</button>
                <button class="faq-ctrl-btn" id="faqCollapseAll">Collapse All</button>
            </div>
            <div class="faq-list">
                <div class="faq-item reveal">
                    <button class="faq-question" aria-expanded="false">
                        How long does the free trial last?
                        <span class="faq-icon"><svg viewBox="0 0 14 14" fill="none"><path d="M7 1v12M1 7h12" stroke="#111" stroke-width="1.5" stroke-linecap="round"/></svg></span>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">Every plan includes a 14-day free trial with full access to all features. No credit card required to get started. At the end of the trial, you can choose a plan or export your data.</div>
                    </div>
                </div>
                <div class="faq-item reveal">
                    <button class="faq-question" aria-expanded="false">
                        Can I switch plans later?
                        <span class="faq-icon"><svg viewBox="0 0 14 14" fill="none"><path d="M7 1v12M1 7h12" stroke="#111" stroke-width="1.5" stroke-linecap="round"/></svg></span>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">Yes. Upgrade or downgrade any time from your account settings. When upgrading, you only pay the prorated difference. When downgrading, the remaining balance is applied as credit toward future invoices.</div>
                    </div>
                </div>
                <div class="faq-item reveal">
                    <button class="faq-question" aria-expanded="false">
                        What data sources do you support?
                        <span class="faq-icon"><svg viewBox="0 0 14 14" fill="none"><path d="M7 1v12M1 7h12" stroke="#111" stroke-width="1.5" stroke-linecap="round"/></svg></span>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">Catalyst connects to 50+ data sources out of the box, including PostgreSQL, MySQL, BigQuery, Snowflake, Redshift, S3, Kafka, and most popular REST APIs. Custom connectors are available on the Enterprise plan.</div>
                    </div>
                </div>
                <div class="faq-item reveal">
                    <button class="faq-question" aria-expanded="false">
                        Is my data secure?
                        <span class="faq-icon"><svg viewBox="0 0 14 14" fill="none"><path d="M7 1v12M1 7h12" stroke="#111" stroke-width="1.5" stroke-linecap="round"/></svg></span>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">Absolutely. All data is encrypted at rest (AES-256) and in transit (TLS 1.3). We are SOC 2 Type II certified and GDPR compliant. Enterprise customers also get dedicated infrastructure with VPC peering and custom encryption keys.</div>
                    </div>
                </div>
                <div class="faq-item reveal">
                    <button class="faq-question" aria-expanded="false">
                        Do you offer onboarding support?
                        <span class="faq-icon"><svg viewBox="0 0 14 14" fill="none"><path d="M7 1v12M1 7h12" stroke="#111" stroke-width="1.5" stroke-linecap="round"/></svg></span>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-inner">Pro customers get access to our onboarding team for the first 30 days. Enterprise customers receive a dedicated solutions engineer for the full implementation. All plans include access to our docs, tutorials, and community forum.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SECTION 6: CTA BANNER ===== -->
    <section class="cta-banner">
        <div class="container">
            <div class="cta-banner-inner reveal">
                <p class="cta-banner-tag">Get started today</p>
                <h2>Ready to accelerate your data?</h2>
                <p>Join 2,000+ teams shipping faster with sub-second analytics. Free for 14 days.</p>
                <div class="cta-banner-actions">
                    <a href="#" class="btn-acid">
                        Start Free Trial
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <a href="#" class="btn-dark-ghost">Talk to Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== SECTION 7: FOOTER ===== -->
    <footer class="footer" id="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a href="#" class="nav-logo" aria-label="Catalyst Home">
                        <span class="nav-logo-mark">
                            <svg viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 8L7 4L13 8L7 12L3 8Z" fill="#111"/>
                            </svg>
                        </span>
                        Catalyst
                    </a>
                    <p class="footer-brand-desc">The analytics platform built for speed. Sub-second queries, real-time pipelines, and infinite scale.</p>
                </div>
                <div>
                    <h4 class="footer-col-title">Resources</h4>
                    <ul class="footer-links">
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">API Reference</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Changelog</a></li>
                        <li><a href="#">Status Page</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="footer-col-title">Support</h4>
                    <ul class="footer-links">
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Community Forum</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">System Status</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="footer-col-title">Connect</h4>
                    <ul class="footer-links">
                        <li><a href="#">Twitter / X</a></li>
                        <li><a href="#">GitHub</a></li>
                        <li><a href="#">LinkedIn</a></li>
                        <li><a href="#">YouTube</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="footer-copy">&copy; 2026 Catalyst Inc. All rights reserved. Design: <a href="https://templatemo.com/tm-618-the-catalyst" target="_blank" rel="nofollow">TemplateMo</a></p>
                <ul class="footer-bottom-links">
                    <li><a href="#">Privacy</a></li>
                    <li><a href="#">Terms</a></li>
                    <li><a href="#">Cookies</a></li>
                </ul>
            </div>
        </div>
    </footer>

  <script src="/assets/js/templatemo-catalyst-script.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const canvasElement = document.getElementById('evolutionChart');
            
            if (canvasElement) {
                const ctx = canvasElement.getContext('2d');
                
                // On récupère les données PHP une seule fois
                const labels = <?= json_encode(array_column($historique, 'DateEvolution')) ?>;
                const weights = <?= json_encode(array_column($historique, 'Poids')) ?>;

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Évolution du poids (kg)',
                            data: weights,
                            borderColor: '#bada55', // Vert "Acid" du template
                            backgroundColor: 'rgba(186, 218, 85, 0.1)',
                            fill: true,
                            tension: 0.3,
                            borderWidth: 3,
                            pointRadius: 5,
                            pointBackgroundColor: '#111'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false } // On cache la légende pour un look plus SaaS
                        },
                        scales: {
                            y: { 
                                beginAtZero: false,
                                title: { display: true, text: 'Poids (kg)', font: { weight: 'bold' } },
                                grid: { color: 'rgba(0, 0, 0, 0.05)' }
                            },
                            x: { 
                                title: { display: true, text: 'Date' },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>