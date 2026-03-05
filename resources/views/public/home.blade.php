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
                <!-- Hero images: Wikimedia Commons (Nigeria). Licenses: PD / CC BY-SA. See sources in repository docs. -->
                <!-- Slide 1: Public school classroom in Nigeria -->
                <div class="carousel-item active" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/a/a0/A_classroom_of_students_%287138907393%29.jpg');">
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
                <!-- Slide 2: Children learning in Nigeria -->
                <div class="carousel-item" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/a/a4/Learning_to_read_and_write%2C_Nigeria_%2838758529475%29.jpg');">
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
                <!-- Slide 3: Digital classroom in Nigeria -->
                <div class="carousel-item" style="background-image: url('https://upload.wikimedia.org/wikipedia/commons/5/55/Introducing_digital_classroom.jpg');">
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
                    <!-- About image source: Unsplash -->
                    <img src="https://images.unsplash.com/flagged/photo-1579133311477-9121405c78dd?auto=format&fit=crop&fm=jpg&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8YWZyaWNhJTIwc2Nob29sfGVufDB8fDB8fHww&ixlib=rb-4.1.0&q=60&w=3000" alt="African school children" loading="lazy" class="img-fluid rounded shadow" style="width:100%;height:100%;object-fit:cover;object-position:center;">
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
                        <img src="https://ui-avatars.com/api/?name=Umaru+Hassan+Babayo&size=512&background=0B6623&color=ffffff&bold=true" onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=Umaru+Hassan+Babayo&size=512&background=0B6623&color=ffffff&bold=true';" class="rounded-circle mx-auto mb-3" alt="Chairman" loading="lazy" style="width: 150px; height: 150px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Alhaji Umaru Hassan Babayo</h5>
                            <p class="text-success fw-bold mb-2">Executive Chairman</p>
                            <p class="card-text small text-muted">Providing leadership and strategic direction for basic education delivery across Yobe State.</p>
                            <a href="#" class="btn btn-sm btn-outline-secondary">View Profile</a>
                        </div>
                    </div>
                </div>
                <!-- Executive Secretary -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center p-3">
                        <img src="https://ui-avatars.com/api/?name=Isa+Shettima&size=512&background=6c757d&color=ffffff&bold=true" onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=Isa+Shettima&size=512&background=6c757d&color=ffffff&bold=true';" class="rounded-circle mx-auto mb-3" alt="Secretary" loading="lazy" style="width: 150px; height: 150px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Isa Shettima</h5>
                            <p class="text-success fw-bold mb-2">Executive Secretary</p>
                            <p class="card-text small text-muted">Overseeing day-to-day administration and coordination of board activities and UBEC interventions.</p>
                            <a href="#" class="btn btn-sm btn-outline-secondary">View Profile</a>
                        </div>
                    </div>
                </div>
                <!-- Director 1 -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center p-3">
                        <img src="https://ui-avatars.com/api/?name=Name+Unknown&size=512&background=6c757d&color=ffffff&bold=true" onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=Name+Unknown&size=512&background=6c757d&color=ffffff&bold=true';" class="rounded-circle mx-auto mb-3" alt="Director" loading="lazy" style="width: 150px; height: 150px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Name unknown</h5>
                            <p class="text-success fw-bold mb-2">Director, Planning & Statistics</p>
                            <p class="card-text small text-muted">Driving data-led planning, statistics, and resource allocation for improved school outcomes.</p>
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
