<?php
$page_title = "Placements - ACCHM";
require_once 'includes/header.php';
?>

<main>
    
    <!-- ══════════════════════════════════════════
     HERO BANNER
    ══════════════════════════════════════════ -->
    <div style="
        position: relative;
        height: 600px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.85)),
                    url('assets/images/downloads/unsplash_photo-1528605248644-14dd04022da1.jpg') center/cover no-repeat;
    ">
        <div style="
            position: relative;
            z-index: 2;
            color: #ffffff;
            padding: 0 2rem;
            margin-top: 72px;
        ">
            <div
                style="
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                    font-family: 'Poppins', sans-serif;
                    font-size: 0.78rem;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: 3px;
                    color: rgba(255, 255, 255, 0.75);
                    background: rgba(255, 255, 255, 0.1);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    padding: 0.45rem 1.2rem;
                    border-radius: 8px;
                    margin-bottom: 1.5rem;
                    backdrop-filter: blur(8px);
                "
            >
                <i class="fas fa-briefcase"></i>
                Your Future Starts Here
            </div>

            <h1 style="
                font-size: clamp(2.2rem, 5vw, 3.8rem);
                font-weight: 900;
                color: #ffffff;
                text-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
                margin-bottom: 1rem;
                letter-spacing: -0.5px;
            ">
                Placement & Training
            </h1>
            <p style="
                font-size: 1.05rem;
                color: rgba(255, 255, 255, 0.85);
                max-width: 580px;
                margin: 0 auto;
                font-weight: 300;
            ">
                Pioneering hospitality education with a 100% placement record for over a decade.
            </p>
        </div>
    </div>

    <!-- Premium Stats Banner (Glassmorphism overlap) -->
    <section class="premium-stats-banner" style="margin-top: -4rem; position: relative; z-index: 10;">
        <div class="container">
            <div class="glass-stats-container stats-grid" style="
                grid-template-columns: repeat(3, 1fr);
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.8);
                border-radius: var(--radius-xl);
                padding: 2.5rem 2rem;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            ">
                <div class="premium-stat-item">
                    <h3 style="background: var(--accent-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; font-size: 3rem;">2000+</h3>
                    <p style="color: var(--color-primary-dark); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Happy Alumni</p>
                </div>
                <div class="premium-stat-item">
                    <h3 style="background: var(--accent-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; font-size: 3rem;">100%</h3>
                    <p style="color: var(--color-primary-dark); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Placement Support</p>
                </div>
                <div class="premium-stat-item">
                    <h3 style="background: var(--accent-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; font-size: 3rem;">200+</h3>
                    <p style="color: var(--color-primary-dark); font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Entrepreneurs</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Placement Intro Section -->
    <section class="section" style="padding-top: 8rem; padding-bottom: 4rem;">
        <div class="container editorial-section" style="align-items: center; gap: 4rem;">
            <div class="editorial-content">
                <h4 style="color: var(--color-accent-red); text-transform: uppercase; letter-spacing: 2px;">Global Opportunities</h4>
                <h2 style="font-size: 3.5rem; margin-bottom: 1.5rem; color: var(--color-primary-dark);">Placement & Training</h2>
                <p style="font-size: 1.1rem; color: var(--color-text-light); margin-bottom: 1.5rem; line-height: 1.8;">We have maintained excellent placement records for over a decade. We arrange campus and off-campus interviews for all our students, holding long-standing associations with major leading hotels, H.R. Directors, and Training Managers globally.</p>
                <p style="font-size: 1.1rem; color: var(--color-text-light); line-height: 1.8;">Our students are placed in top-tier hotels and restaurant chains, earning premium starter packages. We also expertly guide students whose ambitions lie in becoming entrepreneurs in the hospitality sector.</p>
            </div>
            <div class="editorial-image-wrapper" style="box-shadow: 0 20px 50px rgba(0,0,0,0.15); border-radius: var(--radius-lg); position: relative;">
                <img src="assets/images/downloads/unsplash_photo-1528605248644-14dd04022da1.jpg" alt="Premium Placements" style="width: 100%; border-radius: var(--radius-lg);">
                
                <div class="floating-badge" style="
                    position: absolute;
                    bottom: 20px;
                    left: 20px;
                    background: white;
                    padding: 1.2rem;
                    border-radius: 8px;
                    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    z-index: 3;
                ">
                    <div class="badge-icon" style="
                        background: var(--accent-gradient);
                        color: white;
                        width: 45px;
                        height: 45px;
                        border-radius: 6px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 1.25rem;
                    "><i class="fas fa-award"></i></div>
                    <div>
                        <div style="font-weight: 800; font-size: 1.2rem; color: var(--color-primary-dark);">15+ Years</div>
                        <div style="font-size: 0.9rem; color: #666; font-weight: 500;">Placement Excellence</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Stories -->
    <section style="padding: 6rem 0; background: var(--color-light); overflow: hidden;">
        <div class="container">
            <div class="section-title text-center" style="margin-bottom: 4rem;">
                <h4 style="color: var(--color-accent-red); text-transform: uppercase; letter-spacing: 2px;">Alumni Excellence</h4>
                <h2 style="font-size: 2.8rem; margin-bottom: 0.5rem; color: var(--color-primary-dark);">Success Stories</h2>
                <p style="margin-top: 1rem; color: var(--color-text-light); max-width: 900px; margin-left: auto; margin-right: auto; font-size: 1.1rem;">See where our alumni are working across the globe.</p>
            </div>

            <!-- Slider Styles -->
            <style>
                .testimonials-slider-wrapper {
                    position: relative;
                    width: 100%;
                    margin: 0 auto;
                    padding: 0 3.5rem;
                    box-sizing: border-box;
                }

                .testimonials-slider-container {
                    overflow: hidden;
                    width: 100%;
                    padding: 1.5rem 0;
                    margin: -1.5rem 0;
                }

                .testimonials-track {
                    display: flex;
                    gap: 2.5rem;
                    align-items: stretch;
                    transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
                    will-change: transform;
                }

                .premium-testimonial-card {
                    flex: 0 0 calc((100% - (2 * 2.5rem)) / 3);
                    box-sizing: border-box;
                    height: auto;
                }

                /* Nav Buttons */
                .testimonials-slider-wrapper .slider-nav-btn {
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    width: 54px;
                    height: 54px;
                    border-radius: 50%;
                    background: #ffffff;
                    border: 1px solid rgba(0, 0, 0, 0.08);
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    z-index: 10;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    color: var(--color-primary-dark);
                    font-size: 1.1rem;
                }

                .testimonials-slider-wrapper .slider-nav-btn:hover {
                    background: var(--color-accent-red);
                    color: #ffffff;
                    box-shadow: 0 10px 25px rgba(255, 65, 108, 0.35);
                    border-color: var(--color-accent-red);
                    transform: translateY(-50%) scale(1.05);
                }

                .testimonials-slider-wrapper .slider-nav-btn:active {
                    transform: translateY(-50%) scale(0.95);
                }

                .testimonials-slider-wrapper .slider-nav-btn.disabled {
                    opacity: 0;
                    pointer-events: none;
                    cursor: not-allowed;
                }

                .testimonials-slider-wrapper .slider-nav-btn.prev-btn {
                    left: -10px;
                }

                .testimonials-slider-wrapper .slider-nav-btn.next-btn {
                    right: -10px;
                }

                @media (max-width: 1200px) {
                    .testimonials-slider-wrapper {
                        padding: 0 2rem;
                    }
                    .testimonials-slider-wrapper .slider-nav-btn.prev-btn {
                        left: -15px;
                    }
                    .testimonials-slider-wrapper .slider-nav-btn.next-btn {
                        right: -15px;
                    }
                }

                @media (max-width: 1024px) {
                    .testimonials-track {
                        gap: 2rem;
                    }
                    .premium-testimonial-card {
                        flex: 0 0 calc((100% - (1 * 2rem)) / 2);
                    }
                    .testimonials-slider-wrapper {
                        padding: 0 1.5rem;
                    }
                }

                @media (max-width: 768px) {
                    .testimonials-track {
                        gap: 1.5rem;
                    }
                    .premium-testimonial-card {
                        flex: 0 0 100%;
                    }
                    .testimonials-slider-wrapper {
                        padding: 0;
                    }
                    .testimonials-slider-wrapper .slider-nav-btn {
                        width: 44px;
                        height: 44px;
                        font-size: 0.95rem;
                        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
                    }
                    .testimonials-slider-wrapper .slider-nav-btn.prev-btn {
                        left: -5px;
                    }
                    .testimonials-slider-wrapper .slider-nav-btn.next-btn {
                        right: -5px;
                    }
                }
            </style>

            <div class="testimonials-slider-wrapper">
                <!-- Left Nav Button -->
                <button type="button" class="slider-nav-btn prev-btn" id="prevTestimonialBtn" aria-label="Previous testimonials">
                    <i class="fas fa-chevron-left"></i>
                </button>

                <!-- Slider Container -->
                <div class="testimonials-slider-container">
                    <div id="testimonialsTrack" class="testimonials-track">
                        <!-- Testimonial 1 -->
                        <div class="premium-testimonial-card" style="
                            background: #ffffff;
                            border-radius: var(--radius-xl);
                            padding: 2.5rem;
                            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
                            border: 1px solid #b6b5b5b0;
                            transition: var(--transition-smooth);
                        ">
                            <div class="testimonial-header">
                                <div class="testimonial-avatar" style="background: var(--primary-gradient);">D</div>
                                <div class="testimonial-info">
                                    <h3 style="color: var(--color-primary-dark); font-weight: 700;">Dhilip</h3>
                                    <p style="color: var(--color-accent-red);"><i class="fas fa-map-marker-alt"></i> Marriott Hotel, Sydney Australia</p>
                                </div>
                            </div>
                            <div class="testimonial-body">
                                "Completed my B.Sc. and now working in Marriott earning $4500 per month. The training here gave me the foundation I needed to succeed globally."
                            </div>
                        </div>
                        
                        <!-- Testimonial 2 -->
                        <div class="premium-testimonial-card" style="
                            background: #ffffff;
                            border-radius: var(--radius-xl);
                            padding: 2.5rem;
                            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
                            border: 1px solid #b6b5b5b0;
                            transition: var(--transition-smooth);
                        ">
                            <div class="testimonial-header">
                                <div class="testimonial-avatar" style="background: var(--primary-gradient);">S</div>
                                <div class="testimonial-info">
                                    <h3 style="color: var(--color-primary-dark); font-weight: 700;">Sathish</h3>
                                    <p style="color: var(--color-accent-red);"><i class="fas fa-ship"></i> Costa Cruise Line</p>
                                </div>
                            </div>
                            <div class="testimonial-body">
                                "Did part time at Accord, placed in ITC Grand Chola, and now at Costa Cruise line earning $1200 per month, traveling the world. An incredible journey."
                            </div>
                        </div>
                        
                        <!-- Testimonial 3 -->
                        <div class="premium-testimonial-card" style="
                            background: #ffffff;
                            border-radius: var(--radius-xl);
                            padding: 2.5rem;
                            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
                            border: 1px solid #b6b5b5b0;
                            transition: var(--transition-smooth);
                        ">
                            <div class="testimonial-header">
                                <div class="testimonial-avatar" style="background: var(--primary-gradient);">R</div>
                                <div class="testimonial-info">
                                    <h3 style="color: var(--color-primary-dark); font-weight: 700;">Ranjith</h3>
                                    <p style="color: var(--color-accent-red);"><i class="fas fa-map-marker-alt"></i> Leela Palace, Bangalore</p>
                                </div>
                            </div>
                            <div class="testimonial-body">
                                "From a rural background in Panrutti, now working as a Housekeeping Executive. Earning Rs. 35,000 per month has entirely changed my family's life."
                            </div>
                        </div>
                        
                        <!-- Testimonial 4 -->
                        <div class="premium-testimonial-card" style="
                            background: #ffffff;
                            border-radius: var(--radius-xl);
                            padding: 2.5rem;
                            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
                            border: 1px solid #b6b5b5b0;
                            transition: var(--transition-smooth);
                        ">
                            <div class="testimonial-header">
                                <div class="testimonial-avatar" style="background: var(--primary-gradient);">M</div>
                                <div class="testimonial-info">
                                    <h3 style="color: var(--color-primary-dark); font-weight: 700;">Manikandan</h3>
                                    <p style="color: var(--color-accent-red);"><i class="fas fa-map-marker-alt"></i> The Oberoi, Dubai</p>
                                </div>
                            </div>
                            <div class="testimonial-body">
                                "Currently working as a bakery chef earning 2,600 dirhams per month with accommodation. The practical exposure here was unmatched."
                            </div>
                        </div>
                        
                        <!-- Testimonial 5 -->
                        <div class="premium-testimonial-card" style="
                            background: #ffffff;
                            border-radius: var(--radius-xl);
                            padding: 2.5rem;
                            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
                            border: 1px solid #b6b5b5b0;
                            transition: var(--transition-smooth);
                        ">
                            <div class="testimonial-header">
                                <div class="testimonial-avatar" style="background: var(--primary-gradient);">G</div>
                                <div class="testimonial-info">
                                    <h3 style="color: var(--color-primary-dark); font-weight: 700;">Ganasivam</h3>
                                    <p style="color: var(--color-accent-red);"><i class="fas fa-map-marker-alt"></i> The Hermitage, New Zealand</p>
                                </div>
                            </div>
                            <div class="testimonial-body">
                                "Demi Chef de' Partie earning $19 (NZ) per hour. Thanks to my placement, I recently built a new house worth Rs. 54,00,000/- back home in Chengam."
                            </div>
                        </div>
                        
                        <!-- Testimonial 6 -->
                        <div class="premium-testimonial-card" style="
                            background: #ffffff;
                            border-radius: var(--radius-xl);
                            padding: 2.5rem;
                            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
                            border: 1px solid #b6b5b5b0;
                            transition: var(--transition-smooth);
                        ">
                            <div class="testimonial-header">
                                <div class="testimonial-avatar" style="background: var(--primary-gradient);">V</div>
                                <div class="testimonial-info">
                                    <h3 style="color: var(--color-primary-dark); font-weight: 700;">Vassin Sithanandan</h3>
                                    <p style="color: var(--color-accent-red);"><i class="fas fa-map-marker-alt"></i> Apollo Hospitals, Chennai</p>
                                </div>
                            </div>
                            <div class="testimonial-body">
                                "Worked in The Anantara Palm Resort, Dubai. Now a Restaurant Manager earning nearly Rs. 60,000/- per month. I owe my career progression to this college."
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Nav Button -->
                <button type="button" class="slider-nav-btn next-btn" id="nextTestimonialBtn" aria-label="Next testimonials">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Slider Logic Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const track = document.getElementById('testimonialsTrack');
            const cards = track ? track.querySelectorAll('.premium-testimonial-card') : [];
            const prevBtn = document.getElementById('prevTestimonialBtn');
            const nextBtn = document.getElementById('nextTestimonialBtn');
            
            if (!track || cards.length === 0) return;

            let currentIndex = 0;
            
            function getCardsPerPage() {
                if (window.innerWidth <= 768) return 1;
                if (window.innerWidth <= 1024) return 2;
                return 3;
            }
            
            function getGap() {
                const rootFontSize = parseFloat(getComputedStyle(document.documentElement).fontSize) || 16;
                if (window.innerWidth <= 768) return rootFontSize * 1.5; // 1.5rem
                if (window.innerWidth <= 1024) return rootFontSize * 2.0; // 2rem
                return rootFontSize * 2.5; // 2.5rem
            }

            function updateSlider() {
                const cardsPerPage = getCardsPerPage();
                const maxIndex = Math.max(0, cards.length - cardsPerPage);
                
                // Clamp currentIndex
                if (currentIndex > maxIndex) currentIndex = maxIndex;
                if (currentIndex < 0) currentIndex = 0;
                
                // Compute offset based on card width
                const cardWidth = cards[0].getBoundingClientRect().width;
                const gap = getGap();
                const offset = currentIndex * (cardWidth + gap);
                
                track.style.transform = `translateX(-${offset}px)`;
                
                // Update button states
                if (prevBtn) {
                    if (currentIndex === 0) {
                        prevBtn.classList.add('disabled');
                    } else {
                        prevBtn.classList.remove('disabled');
                    }
                }
                
                if (nextBtn) {
                    if (currentIndex >= maxIndex) {
                        nextBtn.classList.add('disabled');
                    } else {
                        nextBtn.classList.remove('disabled');
                    }
                }
            }
            
            if (prevBtn) {
                prevBtn.addEventListener('click', function() {
                    if (currentIndex > 0) {
                        currentIndex--;
                        updateSlider();
                    }
                });
            }
            
            if (nextBtn) {
                nextBtn.addEventListener('click', function() {
                    const cardsPerPage = getCardsPerPage();
                    const maxIndex = Math.max(0, cards.length - cardsPerPage);
                    if (currentIndex < maxIndex) {
                        currentIndex++;
                        updateSlider();
                    }
                });
            }
            
            // Support touch swipe gestures
            let startX = 0;
            let currentX = 0;
            let isSwiping = false;

            track.addEventListener('touchstart', function(e) {
                startX = e.touches[0].clientX;
                isSwiping = true;
            }, { passive: true });

            track.addEventListener('touchmove', function(e) {
                if (!isSwiping) return;
                currentX = e.touches[0].clientX;
            }, { passive: true });

            track.addEventListener('touchend', function() {
                if (!isSwiping) return;
                isSwiping = false;
                const diffX = startX - currentX;
                const cardsPerPage = getCardsPerPage();
                const maxIndex = Math.max(0, cards.length - cardsPerPage);

                if (Math.abs(diffX) > 50) { // threshold for swipe
                    if (diffX > 0 && currentIndex < maxIndex) {
                        currentIndex++;
                        updateSlider();
                    } else if (diffX < 0 && currentIndex > 0) {
                        currentIndex--;
                        updateSlider();
                    }
                }
            });
            
            // Handle window resize dynamically
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(updateSlider, 150);
            });
            
            // Initial call
            updateSlider();
        });
    </script>
        </div>
    </section>

    <!-- Part Time & ODC -->
    <section class="section" style="padding: 6rem 0; background: #ffffff;">
        <div class="container">
            <!-- Part Time -->
            <div class="editorial-section" style="margin-bottom: 8rem; align-items: center; gap: 4rem;">
                <div class="editorial-content">
                    <h4 style="color: var(--color-accent-red); text-transform: uppercase; letter-spacing: 2px; font-weight: 700;"><i class="fas fa-wallet"></i> Earn While Learn</h4>
                    <h2 style="font-size: 2.8rem; color: var(--color-primary-dark); margin-bottom: 1.5rem;">Part Time Jobs</h2>
                    <p style="font-size: 1.1rem; color: var(--color-text-light); margin-bottom: 1.5rem; line-height: 1.8;">We have strong tie-ups with leading hotels and restaurants in Pondicherry. Nearly 70% of our students are currently doing part-time jobs, earning approximately Rs. 8,000/- to Rs. 11,000/- per month alongside accommodation and on-duty meals.</p>
                    <p style="font-size: 1.1rem; color: var(--color-text-light); line-height: 1.8; margin-bottom: 2rem;">This incredible opportunity helps students easily manage their fees while gaining real-world hospitality standards.</p>
                    
                    <div class="premium-partner-grid" style="
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                        gap: 1rem;
                    ">
                        <div class="premium-partner-item" style="background:#f8fafc; border: 1px solid rgba(0,0,0,0.06); padding: 0.8rem 1.2rem; border-radius: 6px; font-weight:600; display:flex; align-items:center; gap:0.6rem;"><i class="fas fa-building" style="color: var(--color-primary);"></i> Shenbaga Hotel</div>
                        <div class="premium-partner-item" style="background:#f8fafc; border: 1px solid rgba(0,0,0,0.06); padding: 0.8rem 1.2rem; border-radius: 6px; font-weight:600; display:flex; align-items:center; gap:0.6rem;"><i class="fas fa-building" style="color: var(--color-primary);"></i> Accord Hotel</div>
                        <div class="premium-partner-item" style="background:#f8fafc; border: 1px solid rgba(0,0,0,0.06); padding: 0.8rem 1.2rem; border-radius: 6px; font-weight:600; display:flex; align-items:center; gap:0.6rem;"><i class="fas fa-building" style="color: var(--color-primary);"></i> Sunway Manor</div>
                        <div class="premium-partner-item" style="background:#f8fafc; border: 1px solid rgba(0,0,0,0.06); padding: 0.8rem 1.2rem; border-radius: 6px; font-weight:600; display:flex; align-items:center; gap:0.6rem;"><i class="fas fa-building" style="color: var(--color-primary);"></i> Villa Shanti</div>
                        <div class="premium-partner-item" style="background:#f8fafc; border: 1px solid rgba(0,0,0,0.06); padding: 0.8rem 1.2rem; border-radius: 6px; font-weight:600; display:flex; align-items:center; gap:0.6rem;"><i class="fas fa-coffee" style="color: var(--color-primary);"></i> Hope Café</div>
                        <div class="premium-partner-item" style="background:#f8fafc; border: 1px solid rgba(0,0,0,0.06); padding: 0.8rem 1.2rem; border-radius: 6px; font-weight:600; display:flex; align-items:center; gap:0.6rem;"><i class="fas fa-umbrella-beach" style="color: var(--color-primary);"></i> Lamel Cove</div>
                    </div>
                </div>
                <div class="editorial-image-wrapper" style="box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08); border-radius: var(--radius-xl); overflow: hidden;">
                    <img src="assets/images/downloads/unsplash_photo-1551504734-5ee1c4a1479b.jpg" alt="Part time jobs" style="width: 100%; transition: transform 0.6s ease;">
                </div>
            </div>

            <!-- ODC -->
            <div class="editorial-section" style="align-items: center; gap: 4rem;">
                <div class="editorial-image-wrapper" style="box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08); border-radius: var(--radius-xl); overflow: hidden;">
                    <img src="assets/images/downloads/unsplash_photo-1414235077428-338989a2e8c0.jpg" alt="Outdoor Catering" style="width: 100%; transition: transform 0.6s ease;">
                </div>
                <div class="editorial-content">
                    <h4 style="color: var(--color-accent-red); text-transform: uppercase; letter-spacing: 2px; font-weight: 700;"><i class="fas fa-globe"></i> Real World Experience</h4>
                    <h2 style="font-size: 2.8rem; color: var(--color-primary-dark); margin-bottom: 1.5rem;">Outdoor Catering (ODC)</h2>
                    <p style="font-size: 1.1rem; color: var(--color-text-light); margin-bottom: 1.5rem; line-height: 1.8;">Our students are consistently requested by various leading hotels across Pondicherry, Mahabalipuram, and Chennai for premier ODC events. They earn an average of Rs. 500/- per day, inclusive of duty meals. ACCHM students are highly regarded in the industry for their punctuality, obedience, initiative, and hard work.</p>
                    
                    <div style="
                        background: #f8fafc;
                        padding: 2.2rem;
                        border-radius: var(--radius-xl);
                        border-left: 4px solid var(--color-accent-red);
                        box-shadow: 0 10px 30px rgba(0,0,0,0.02);
                        margin-top: 2rem;
                    ">
                        <p style="font-size: 1.1rem; color: var(--color-text); font-style: italic; line-height: 1.7; margin: 0;">
                            "Our students frequently serve top diplomats at the French Consulate. Recently, a select team served at the prestigious Indian President Service held at Pondicherry University. Hoteliers universally prefer our students due to their immense dedication."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php require_once 'includes/footer.php'; ?>
