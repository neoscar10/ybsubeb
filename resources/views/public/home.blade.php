@extends('layouts.public')

@section('content')
    <!-- C) HERO SECTION -->
    <!-- C) HERO SECTION -->
    <header id="home" class="hero-section">
        <style>
            /* Scoped Hero Styles */
            .hero-section .carousel-item {
                height: 80vh;
                min-height: 600px;
                background-size: cover;
                background-position: center;
                position: relative;
            }
            .hero-section .hero-overlay {
                background: rgba(0, 0, 0, 0.5); /* 0.5 opacity overlay */
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
            }
            .hero-section .hero-content {
                color: #fff; /* Ensure text is white */
                position: relative;
                z-index: 2;
            }
        </style>
        <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            </div>
            <div class="carousel-inner">
                <!-- Slide 1 -->
                <div class="carousel-item active" style="background-image: url('{{ asset('assets/hero-1.jpg') }}');">
                    <div class="hero-overlay">
                        <div class="container">
                            <div class="hero-content">
                                <h1 class="hero-title">Revitalizing Basic Education in Yobe State</h1>
                                <p class="hero-text">Ensuring every child has access to quality, free, and compulsory basic education through infrastructure development and teacher training.</p>
                                <a href="#schools" class="btn btn-light btn-lg fw-bold text-success">View Statistics</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Slide 2 -->
                <div class="carousel-item" style="background-image: url('{{ asset('assets/hero-2.jpg') }}');">
                    <div class="hero-overlay">
                        <div class="container">
                            <div class="hero-content">
                                <h1 class="hero-title">Needs Assessment Portal</h1>
                                <p class="hero-text">A data-driven approach to identifying and solving the most critical challenges in our schools.</p>
                                <a href="#needs" class="btn btn-warning btn-lg fw-bold text-dark">Submit Needs</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Slide 3 -->
                <div class="carousel-item" style="background-image: url('{{ asset('assets/hero-3.jpg') }}');">
                    <div class="hero-overlay">
                        <div class="container">
                            <div class="hero-content">
                                <h1 class="hero-title">Transparency & Accountability</h1>
                                <p class="hero-text">Open access to school data, intervention reports, and financial records.</p>
                                <a href="#reports" class="btn btn-light btn-lg fw-bold text-success">View Reports</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <div class="trusted-badge">
            <div class="container">
                <i class="bi bi-shield-check me-2"></i> Official Website of the Yobe State Government - State Universal Basic Education Board
            </div>
        </div>
    </header>

    <!-- D) KEY STATISTICS STRIP -->
    <section class="stats-strip py-5">
        <div class="container">
            <div class="row g-0">
                <div class="col-6 col-md-2 stat-box">
                    <i class="bi bi-buildings stat-icon"></i>
                    <span class="stat-number">1,200+</span>
                    <span class="stat-label">Schools</span>
                </div>
                <div class="col-6 col-md-2 stat-box">
                    <i class="bi bi-people stat-icon"></i>
                    <span class="stat-number">450k+</span>
                    <span class="stat-label">Pupils</span>
                </div>
                <div class="col-6 col-md-2 stat-box">
                    <i class="bi bi-person-badge stat-icon"></i>
                    <span class="stat-number">12k+</span>
                    <span class="stat-label">Teachers</span>
                </div>
                <div class="col-6 col-md-2 stat-box">
                    <i class="bi bi-map stat-icon"></i>
                    <span class="stat-number">17</span>
                    <span class="stat-label">LGAs Covered</span>
                </div>
                <div class="col-6 col-md-2 stat-box">
                    <i class="bi bi-door-open stat-icon"></i>
                    <span class="stat-number">5,000+</span>
                    <span class="stat-label">Classrooms</span>
                </div>
                <div class="col-6 col-md-2 stat-box">
                    <i class="bi bi-file-earmark-text stat-icon"></i>
                    <span class="stat-number">850+</span>
                    <span class="stat-label">Needs Submitted</span>
                </div>
            </div>
        </div>
    </section>

    <!-- E) ABOUT SUBEB -->
    <section id="about">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="section-title">About SUBEB Yobe</h2>
                    <p class="lead text-muted">Committed to laying a solid foundation for the future leaders of Yobe State.</p>
                    <p>The Yobe State Universal Basic Education Board (SUBEB) is a parastatal of the Yobe State Government charged with the responsibility of managing Basic Education in the state. We work in collaboration with the Universal Basic Education Commission (UBEC) to ensure free, compulsory, and universal basic education for every child of school-going age.</p>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Overseeing Early Childhood Care and Development (ECCD)</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Recruitment, training, and welfare of teachers</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Infrastructural development and maintenance</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Data planning and quality assurance</li>
                    </ul>
                    <a href="#" class="btn btn-outline-success mt-3">Read More About Us</a>
                </div>
                <div class="col-lg-6">
                    <img src="https://via.placeholder.com/600x400/f8f9fa/006400?text=About+SUBEB+Image" alt="About SUBEB" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- F) MANDATE / FUNCTIONS -->
    <section id="mandate" class="bg-light-gray">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Our Mandate & Functions</h2>
                <p class="text-muted">Core responsibilities entrusted to the Board.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="mandate-card rounded">
                        <i class="bi bi-eye mandate-icon"></i>
                        <h5>School Supervision</h5>
                        <p class="small text-muted">Regular monitoring and supervision of primary and junior secondary schools to ensure compliance with standards.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mandate-card rounded">
                        <i class="bi bi-bricks mandate-icon"></i>
                        <h5>Infrastructure Support</h5>
                        <p class="small text-muted">Construction and renovation of classrooms, provision of furniture, and creating conducive learning environments.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mandate-card rounded">
                        <i class="bi bi-mortarboard mandate-icon"></i>
                        <h5>Teacher Development</h5>
                        <p class="small text-muted">Continuous professional development, training workshops, and welfare management for teaching staff.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mandate-card rounded">
                        <i class="bi bi-bar-chart-line mandate-icon"></i>
                        <h5>Data & Planning</h5>
                        <p class="small text-muted">Collection, analysis, and management of educational data for effective planning and policy making.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mandate-card rounded">
                        <i class="bi bi-award mandate-icon"></i>
                        <h5>Quality Assurance</h5>
                        <p class="small text-muted">Ensuring curriculum delivery meets national standards and improving learning outcomes.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mandate-card rounded">
                        <i class="bi bi-people-fill mandate-icon"></i>
                        <h5>Community Engagement</h5>
                        <p class="small text-muted">Mobilizing communities and SBMCs to actively participate in school management and development.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- G) LEADERSHIP -->
    <section id="leadership">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Board Leadership</h2>
                <p class="text-muted">Meet the team driving educational excellence.</p>
            </div>
            <div class="row justify-content-center">
                <!-- Chairman -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center p-3">
                        <img src="https://via.placeholder.com/150x150/e9ecef/333?text=Photo" class="rounded-circle mx-auto mb-3" alt="Chairman" style="width: 150px; height: 150px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Prof. Musa Aliyu</h5>
                            <p class="text-success fw-bold mb-2">Executive Chairman</p>
                            <p class="card-text small text-muted">Leading the vision for educational transformation in Yobe State with over 20 years of academic and administrative experience.</p>
                            <a href="#" class="btn btn-sm btn-outline-secondary">View Profile</a>
                        </div>
                    </div>
                </div>
                <!-- Executive Secretary -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center p-3">
                        <img src="https://via.placeholder.com/150x150/e9ecef/333?text=Photo" class="rounded-circle mx-auto mb-3" alt="Secretary" style="width: 150px; height: 150px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Dr. Fatima Ibrahim</h5>
                            <p class="text-success fw-bold mb-2">Executive Secretary</p>
                            <p class="card-text small text-muted">Overseeing the day-to-day administration and implementation of board policies and UBEC interventions.</p>
                            <a href="#" class="btn btn-sm btn-outline-secondary">View Profile</a>
                        </div>
                    </div>
                </div>
                <!-- Director 1 -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center p-3">
                        <img src="https://via.placeholder.com/150x150/e9ecef/333?text=Photo" class="rounded-circle mx-auto mb-3" alt="Director" style="width: 150px; height: 150px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Alh. Sani Ahmed</h5>
                            <p class="text-success fw-bold mb-2">Director, Planning & Statistics</p>
                            <p class="card-text small text-muted">Spearheading data-driven strategies for school placement, enrollment, and resource allocation.</p>
                            <a href="#" class="btn btn-sm btn-outline-secondary">View Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- H) DEPARTMENTS (Accordion) -->
    <section id="departments" class="bg-light-gray">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h2 class="section-title">Departments & Units</h2>
                    <p>Our organizational structure is designed to ensure efficiency and specialization in service delivery.</p>
                    <a href="#" class="btn btn-success">View Organogram</a>
                </div>
                <div class="col-lg-8">
                    <div class="accordion" id="deptAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Planning, Research & Statistics (PRS)
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#deptAccordion">
                                <div class="accordion-body">
                                    Responsible for conducting school census, data analysis, strategic planning, and monitoring of projects. This department serves as the brain of the board.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Quality Assurance
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#deptAccordion">
                                <div class="accordion-body">
                                    Ensures that educational standards are maintained in all schools through regular inspections, monitoring, and evaluation of teaching and learning processes.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Works & Physical Planning
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#deptAccordion">
                                <div class="accordion-body">
                                    Handles the design, construction, and maintenance of school buildings, classrooms, and other physical infrastructure across the state.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Finance & Accounts
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#deptAccordion">
                                <div class="accordion-body">
                                    Manages the financial resources of the board, ensuring transparency, accountability, and compliance with financial regulations.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- I) PROGRAMMES / INTERVENTIONS -->
    <section id="programmes">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Key Interventions</h2>
                <p class="text-muted">Ongoing programmes to uplift education standards.</p>
            </div>
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="timeline-item">
                        <h5>Whole School Rehabilitation</h5>
                        <p class="text-muted small">Comprehensive renovation of dilapidated school structures across 17 LGAs.</p>
                    </div>
                    <div class="timeline-item">
                        <h5>Teacher Professional Development (TPD)</h5>
                        <p class="text-muted small">Quarterly training workshops for teachers on modern pedagogical methods and ICT.</p>
                    </div>
                    <div class="timeline-item">
                        <h5>Better Education Service Delivery for All (BESDA)</h5>
                        <p class="text-muted small">World Bank supported programme to reduce out-of-school children and improve literacy.</p>
                    </div>
                    <div class="timeline-item">
                        <h5>School Feeding Programme</h5>
                        <p class="text-muted small">Provision of nutritious meals to pupils to encourage enrollment and retention.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- J) SCHOOLS & DATA -->
    <section id="schools" class="bg-light-gray">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="section-title mb-0">Schools & Data</h2>
                    <p class="text-muted small mb-0">Transparent access to school statistics.</p>
                </div>
                <span class="badge bg-success">Last Updated: Dec 2025</span>
            </div>

            <!-- Filter UI -->
            <div class="card p-3 mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select class="form-select">
                            <option selected>Select LGA...</option>
                            <option>Damaturu</option>
                            <option>Potiskum</option>
                            <option>Bade</option>
                            <!-- Add LGAs -->
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select">
                            <option selected>School Type...</option>
                            <option>Primary</option>
                            <option>JSS</option>
                            <option>Nomadic</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Search school name...">
                    </div>
                    <div class="col-md-2">
                        <button id="applyFilter" class="btn btn-success w-100">Apply Filter</button>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="table-responsive bg-white shadow-sm">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>School Name</th>
                            <th>LGA</th>
                            <th>Type</th>
                            <th>Pupils (M/F)</th>
                            <th>Teachers</th>
                            <th>Classrooms</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Placeholder Rows -->
                        <tr>
                            <td>Central Primary School Damaturu</td>
                            <td>Damaturu</td>
                            <td>Primary</td>
                            <td>1,200 (600/600)</td>
                            <td>45</td>
                            <td>20</td>
                            <td><span class="badge bg-success">Active</span></td>
                        </tr>
                        <tr>
                            <td>Model JSS Potiskum</td>
                            <td>Potiskum</td>
                            <td>JSS</td>
                            <td>850 (400/450)</td>
                            <td>30</td>
                            <td>15</td>
                            <td><span class="badge bg-success">Active</span></td>
                        </tr>
                        <tr>
                            <td>Gashua Nomadic School</td>
                            <td>Bade</td>
                            <td>Nomadic</td>
                            <td>150 (80/70)</td>
                            <td>5</td>
                            <td>4</td>
                            <td><span class="badge bg-warning text-dark">Needs Renovation</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-3 text-end">
                <small class="text-muted me-3">Data verified by PRS Department.</small>
                <a href="#" class="text-success text-decoration-none small"><i class="bi bi-exclamation-circle"></i> Request Data Correction</a>
            </div>
        </div>
    </section>

    <!-- K) NEEDS ASSESSMENT -->
    <section id="needs">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 mb-4">
                    <h2 class="section-title">Needs Assessment</h2>
                    <p class="lead">A bottom-up approach to school development.</p>
                    <p>We have digitized the process of identifying school needs. Principals can now submit requests directly to the board for review.</p>
                    
                    <div class="alert alert-info mt-4">
                        <h6><i class="bi bi-shield-lock me-2"></i> Audit & Accountability</h6>
                        <p class="small mb-0">All submissions are tracked and audited. False claims will be penalized.</p>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('login') }}" class="btn btn-success btn-lg">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Access Portal
                        </a>
                        <button class="btn btn-outline-dark">
                            <i class="bi bi-file-pdf me-2"></i> Download Guidelines
                        </button>
                    </div>
                </div>
                <div class="col-lg-7">
                    <!-- Process Steps -->
                    <div class="row text-center">
                        <div class="col-md-3 process-step">
                            <div class="step-number">1</div>
                            <h6 class="fw-bold">Open Window</h6>
                            <p class="small text-muted">Chairman declares submission window open.</p>
                        </div>
                        <div class="col-md-3 process-step">
                            <div class="step-number">2</div>
                            <h6 class="fw-bold">Submission</h6>
                            <p class="small text-muted">Principals submit needs with photo evidence.</p>
                        </div>
                        <div class="col-md-3 process-step">
                            <div class="step-number">3</div>
                            <h6 class="fw-bold">Review</h6>
                            <p class="small text-muted">Directors review and prioritize requests.</p>
                        </div>
                        <div class="col-md-3 process-step">
                            <div class="step-number">4</div>
                            <h6 class="fw-bold">Approval</h6>
                            <p class="small text-muted">Final approval and inclusion in budget.</p>
                        </div>
                    </div>
                    
                    <!-- Role Cards -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <div class="card p-3 h-100 border-success">
                                <h6 class="text-success fw-bold">Principal</h6>
                                <ul class="small ps-3 mb-0">
                                    <li>Submit Needs</li>
                                    <li>Update School Data</li>
                                    <li>View Status</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card p-3 h-100 border-primary">
                                <h6 class="text-primary fw-bold">Director</h6>
                                <ul class="small ps-3 mb-0">
                                    <li>Review Submissions</li>
                                    <li>Recommend Action</li>
                                    <li>Generate Reports</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card p-3 h-100 border-dark">
                                <h6 class="text-dark fw-bold">Chairman</h6>
                                <ul class="small ps-3 mb-0">
                                    <li>Approve Projects</li>
                                    <li>Monitor Dashboard</li>
                                    <li>Policy Oversight</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- L) REPORTS & DOWNLOADS -->
    <section id="reports" class="bg-light-gray">
        <div class="container">
            <h2 class="section-title mb-4">Reports & Downloads</h2>
            <div class="row g-3">
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title"><i class="bi bi-file-earmark-pdf text-danger me-2"></i> Annual Report 2024</h6>
                            <p class="small text-muted mb-3">Comprehensive summary of activities.</p>
                            <a href="#" class="btn btn-sm btn-outline-dark w-100">Download (2.5MB)</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title"><i class="bi bi-file-earmark-excel text-success me-2"></i> School Census Data</h6>
                            <p class="small text-muted mb-3">Raw data of schools and enrollment.</p>
                            <a href="#" class="btn btn-sm btn-outline-dark w-100">Download (500KB)</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title"><i class="bi bi-file-text text-primary me-2"></i> Procurement Guidelines</h6>
                            <p class="small text-muted mb-3">Standard bidding documents.</p>
                            <a href="#" class="btn btn-sm btn-outline-dark w-100">Download (1.2MB)</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title"><i class="bi bi-file-earmark-pdf text-danger me-2"></i> Needs Assessment Summary</h6>
                            <p class="small text-muted mb-3">Report on identified gaps.</p>
                            <a href="#" class="btn btn-sm btn-outline-dark w-100">Download (3.0MB)</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- M) NEWS -->
    <section id="news">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title mb-0">Latest News</h2>
                <a href="#" class="btn btn-outline-success btn-sm">View All News</a>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="https://via.placeholder.com/400x250/e9ecef/333?text=News+Image" class="card-img-top" alt="News">
                        <div class="card-body">
                            <span class="badge bg-success mb-2">Projects</span>
                            <h5 class="card-title">SUBEB Commissions 50 New Classrooms</h5>
                            <p class="card-text small text-muted">The Executive Chairman flagged off the commissioning of newly constructed classrooms in Damaturu...</p>
                            <p class="card-text"><small class="text-muted">Dec 15, 2025</small></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="https://via.placeholder.com/400x250/e9ecef/333?text=News+Image" class="card-img-top" alt="News">
                        <div class="card-body">
                            <span class="badge bg-warning text-dark mb-2">Training</span>
                            <h5 class="card-title">Teacher Professional Development Workshop</h5>
                            <p class="card-text small text-muted">Over 500 teachers participated in the 3-day workshop on modern teaching techniques...</p>
                            <p class="card-text"><small class="text-muted">Dec 10, 2025</small></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="https://via.placeholder.com/400x250/e9ecef/333?text=News+Image" class="card-img-top" alt="News">
                        <div class="card-body">
                            <span class="badge bg-info text-dark mb-2">Policy</span>
                            <h5 class="card-title">New Guidelines for School Feeding</h5>
                            <p class="card-text small text-muted">The Board has released new operational guidelines to ensure quality and hygiene...</p>
                            <p class="card-text"><small class="text-muted">Dec 05, 2025</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- N) GALLERY -->
    <section id="gallery" class="bg-light-gray">
        <div class="container">
            <h2 class="section-title mb-4">Photo Gallery</h2>
            <div class="row g-2">
                <div class="col-6 col-md-3">
                    <img src="https://via.placeholder.com/300x300/e9ecef/333?text=Gallery+1" class="img-fluid rounded" alt="Gallery">
                </div>
                <div class="col-6 col-md-3">
                    <img src="https://via.placeholder.com/300x300/e9ecef/333?text=Gallery+2" class="img-fluid rounded" alt="Gallery">
                </div>
                <div class="col-6 col-md-3">
                    <img src="https://via.placeholder.com/300x300/e9ecef/333?text=Gallery+3" class="img-fluid rounded" alt="Gallery">
                </div>
                <div class="col-6 col-md-3">
                    <img src="https://via.placeholder.com/300x300/e9ecef/333?text=Gallery+4" class="img-fluid rounded" alt="Gallery">
                </div>
            </div>
        </div>
    </section>

    <!-- O) CONTACT US -->
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 mb-4">
                    <h2 class="section-title">Contact Us</h2>
                    <p class="text-muted">We are here to serve you. Reach out to us for enquiries and support.</p>
                    
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-geo-alt-fill fs-3 text-success me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Office Address</h6>
                                <p class="small text-muted mb-0">PMB 1020, Gujba Road, Damaturu, Yobe State.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-envelope-fill fs-3 text-success me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Email Address</h6>
                                <p class="small text-muted mb-0">info@subeb.yb.gov.ng</p>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-telephone-fill fs-3 text-success me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Phone Number</h6>
                                <p class="small text-muted mb-0">+234 800 SUBEB YOBE</p>
                            </div>
                        </div>
                    </div>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-clock-fill fs-3 text-success me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Office Hours</h6>
                                <p class="small text-muted mb-0">Mon - Fri: 8:00 AM - 4:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card border-0 shadow">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Send us a Message</h5>
                            <form class="needs-validation" novalidate>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="subject" class="form-label">Subject</label>
                                        <input type="text" class="form-control" id="subject" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="message" class="form-label">Message</label>
                                        <textarea class="form-control" id="message" rows="5" required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-success">Send Message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
