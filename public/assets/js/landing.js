const navbar = document.querySelector('.navbar');
const heroSection = document.querySelector('.hero-section');
if (navbar && heroSection) {
    const updateNavbarBackground = () => {
        const heroBottom = heroSection.getBoundingClientRect().bottom;
        if (heroBottom <= navbar.offsetHeight) {
            navbar.classList.add('navbar-transparent');
        } else {
            navbar.classList.remove('navbar-transparent');
        }
    };

    window.addEventListener('scroll', updateNavbarBackground, { passive: true });
    window.addEventListener('resize', updateNavbarBackground);
    updateNavbarBackground();
}
const heroVideo = document.getElementById('homepage-video-bg');
if (heroVideo) {
    heroVideo.muted = true;
    heroVideo.setAttribute('muted', '');
    heroVideo.setAttribute('playsinline', '');

    const videoWrapper = heroVideo.parentElement;
    let interactionBound = false;

    const markVideoPlaying = () => {
        heroVideo.removeAttribute('poster');
        if (videoWrapper) {
            videoWrapper.classList.add('is-playing');
        }
    };

    const bindUserInteraction = () => {
        if (interactionBound) {
            return;
        }
        interactionBound = true;

        const resumeOnInteraction = () => {
            interactionBound = false;
            playHeroVideo();
            document.removeEventListener('click', resumeOnInteraction);
            document.removeEventListener('touchstart', resumeOnInteraction);
        };

        document.addEventListener('click', resumeOnInteraction, { once: true });
        document.addEventListener('touchstart', resumeOnInteraction, { once: true });
    };

    const playHeroVideo = () => {
        const playPromise = heroVideo.play();
        if (playPromise !== undefined) {
            playPromise.catch(() => {
                bindUserInteraction();
            });
        }
    };

    heroVideo.addEventListener('playing', markVideoPlaying, { once: true });

    if (heroVideo.readyState >= 2) {
        playHeroVideo();
    } else {
        heroVideo.addEventListener('loadeddata', playHeroVideo, { once: true });
    }
}

const backToTopButton = document.getElementById('backToTop');
if (backToTopButton) {
    const updateBackToTop = () => {
        const scrollTop = window.scrollY || document.documentElement.scrollTop;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const progressRatio = docHeight > 0 ? Math.min(Math.max(scrollTop / docHeight, 0), 1) : 0;
        backToTopButton.style.setProperty('--progress', `${progressRatio * 360}deg`);

        if (scrollTop > 200) {
            backToTopButton.classList.add('is-visible');
        } else {
            backToTopButton.classList.remove('is-visible');
        }
    };

    window.addEventListener('scroll', updateBackToTop, { passive: true });
    window.addEventListener('resize', updateBackToTop);
    backToTopButton.addEventListener('click', (event) => {
        event.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    updateBackToTop();
}
const revenueCanvas = document.getElementById('revenueChart');
if (typeof Chart !== 'undefined' && revenueCanvas && revenueCanvas.dataset.autodemo === 'true') {
    const ctx = revenueCanvas.getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Revenue',
                data: [987765 * 0.1, 987765 * 0.15, 987765 * 0.2, 987765 * 0.18, 987765 * 0.12, 987765 * 0.15, 987765 * 0.1],
                backgroundColor: 'rgba(121, 200, 104, 0.8)',
                borderColor: 'rgba(121, 200, 104, 1)',
                borderWidth: 1,
                borderRadius: 4,
                maxBarThickness: 30
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#6c757d'
                    },
                    grid: {
                        color: 'rgba(108,117,125,0.1)'
                    }
                },
                x: {
                    ticks: {
                        color: '#6c757d'
                    },
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return 'Revenue: ' + context.formattedValue;
                        }
                    }
                }
            }
        }
    });
}

const trustedMapEl = document.getElementById('trustedMap');
if (typeof L !== 'undefined' && trustedMapEl) {
    const map = L.map(trustedMapEl, {
        zoomControl: false,
        scrollWheelZoom: false
    }).setView([31.5, 73.0], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const locations = [
        { name: 'Islamabad', coords: [33.6844, 73.0479] },
        { name: 'Lahore', coords: [31.5204, 74.3587] },
        { name: 'Gujrat', coords: [32.5740, 74.0789] },
        { name: 'Jhelum', coords: [32.9407, 73.7276] },
        { name: 'Bahawalpur', coords: [29.3956, 71.6836] }
    ];

    const markerIcon = L.icon({
        iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
        iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [0, -32],
        shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
        shadowSize: [41, 41],
        shadowAnchor: [14, 41]
    });

    locations.forEach(loc => {
        L.marker(loc.coords, { icon: markerIcon }).addTo(map).bindPopup(`<strong>${loc.name}</strong>`);
    });

    L.control.zoom({ position: 'bottomright' }).addTo(map);
}
