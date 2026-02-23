@extends('layouts.frontend')

@section('title', 'Contact Us')

@section('meta')
<meta name="description" content="Contact us – send your feedback, suggestions, or get in touch with our team.">
@endsection

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
        </ol>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4 slide-in-left">Contact Us</h1>
                <p class="lead mb-4 slide-in-left">
                    Get in touch, share your suggestions, or report an issue. We're here to listen.
                </p>
                <div class="slide-in-left">
                    <a href="#contact-form" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-envelope me-2"></i>Send Message
                    </a>
                    <a href="#contact-info" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-phone me-2"></i>Contact Info
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-comments fa-10x opacity-75 slide-in-right"></i>
            </div>
        </div>
    </div>
</section>

<!-- Contact Information Section -->
<section class="py-5" id="contact-info">
    <div class="container">
        <h2 class="section-header text-center">Contact Information</h2>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <i class="fas fa-map-marker-alt fa-3x text-theme mb-3"></i>
                        <h5>Address</h5>
                        <p class="mb-0">
                            {{ config('app.name') }}<br>
                            194, Paratap Nagar, Sanganer<br>
                            Jaipur - 302031<br>
                            India
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <i class="fas fa-phone fa-3x text-success mb-3"></i>
                        <h5>Phone</h5>
                        <p class="mb-0">
                            Main Office: <strong>+91 11 12345678</strong><br>
                            Newsroom: <strong>+91 11 87654321</strong><br>
                            24x7 Helpline: <strong>+91 9876543210</strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <i class="fas fa-envelope fa-3x text-info mb-3"></i>
                        <h5>Email</h5>
                        <p class="mb-0">
                            General: <strong>info@bharatnews24hours.com</strong><br>
                            Editorial: <strong>editor@bharatnews24hours.com</strong><br>
                            Support: <strong>support@bharatnews24hours.com</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form Section -->
<section class="py-5 bg-light" id="contact-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="section-header text-center">Get in Touch</h2>
                <div class="contact-form">
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Please fix the errors below.</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <form action="{{ route('contact.store') }}" method="POST" id="contactForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Your Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Mobile Number</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                <select class="form-select form-select-lg @error('subject') is-invalid @enderror" id="subject" name="subject" required>
                                    <option value="">Select subject</option>
                                    @foreach(\App\Models\Contact::subjectOptions() as $value => $label)
                                    <option value="{{ $value }}" {{ old('subject') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Your Message <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="6" placeholder="Write your message here..." required>{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="newsletter" name="newsletter" value="1" {{ old('newsletter') ? 'checked' : '' }}>
                            <label class="form-check-label" for="newsletter">Send me the newsletter</label>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input @error('privacy') is-invalid @enderror" id="privacy" name="privacy" value="1" required>
                            <label class="form-check-label" for="privacy">I agree to the Privacy Policy <span class="text-danger">*</span></label>
                            @error('privacy')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-paper-plane me-2"></i>Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Contact Options -->
<section class="py-5">
    <div class="container">
        <h2 class="section-header text-center">Quick Contact</h2>
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-primary h-100">
                    <div class="card-body text-center">
                        <i class="fab fa-whatsapp fa-3x text-success mb-3"></i>
                        <h5>WhatsApp</h5>
                        <p class="small mb-2">Available 24x7</p>
                        <a href="https://wa.me/919876543210" class="btn btn-success btn-sm" target="_blank" rel="noopener"><i class="fab fa-whatsapp me-1"></i>Chat</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-info h-100">
                    <div class="card-body text-center">
                        <i class="fab fa-telegram fa-3x text-theme mb-3"></i>
                        <h5>Telegram</h5>
                        <p class="small mb-2">For news updates</p>
                        <a href="#" class="btn btn-primary btn-sm"><i class="fab fa-telegram me-1"></i>Join</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-danger h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-phone-alt fa-3x text-danger mb-3"></i>
                        <h5>Hotline</h5>
                        <p class="small mb-2">For news tips</p>
                        <a href="tel:+919876543210" class="btn btn-danger btn-sm"><i class="fas fa-phone me-1"></i>Call</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card border-warning h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-envelope-open fa-3x text-warning mb-3"></i>
                        <h5>Email Alert</h5>
                        <p class="small mb-2">For instant news</p>
                        <a href="{{ route('home') }}#latest-news" class="btn btn-warning btn-sm"><i class="fas fa-bell me-1"></i>Subscribe</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="section-header text-center">Frequently Asked Questions</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" data-bs-parent="#faqAccordion">How can I send a news tip?</button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">You can send your news tip through our contact form, WhatsApp, or by calling our newsroom. We are available 24x7.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" data-bs-parent="#faqAccordion">Will you keep my identity confidential?</button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">Yes, we respect your privacy. Your information will be kept secure and used only when necessary.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" data-bs-parent="#faqAccordion">When will I get a response?</button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">We try to respond within 24–48 hours. For urgent matters, please call our hotline.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" data-bs-parent="#faqAccordion">Can I contact you for advertising?</button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">Yes. For advertising enquiries, email us or contact our advertising department.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Office Hours -->
<section class="py-5">
    <div class="container">
        <h2 class="section-header text-center">Office Hours</h2>
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="fas fa-clock fa-3x text-theme"></i>
                        </div>
                        <div class="d-flex justify-content-between mb-2"><span><strong>Monday – Friday:</strong></span><span>11:00 AM – 8:00 PM</span></div>
                        <div class="d-flex justify-content-between mb-2"><span><strong>Saturday:</strong></span><span>11:00 AM – 5:00 PM</span></div>
                        <div class="d-flex justify-content-between mb-2"><span><strong>Sunday:</strong></span><span>11:00 AM – 5:00 PM</span></div>
                        <hr>
                        <div class="d-flex justify-content-between"><span><strong>Newsroom (24x7):</strong></span><span class="text-success">Always open</span></div>
                        <div class="alert alert-info mt-3 mb-0">
                            <i class="fas fa-info-circle me-2"></i><strong>Note:</strong> Our 24x7 hotline is available for breaking news and emergencies.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
