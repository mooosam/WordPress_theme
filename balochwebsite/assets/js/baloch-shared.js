/* ═══════════════════════════════════════════════════════
   BALOCH HERITAGE — Shared JavaScript
   ═══════════════════════════════════════════════════════ */

/* ─── NAV INIT ─── */
function initNav() {
  const navbar = document.getElementById('navbar');
  const hamburger = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobileMenu');

  if (navbar) {
    window.addEventListener('scroll', () => {
      navbar.classList.toggle('scrolled', window.scrollY > 60);
    });
  }

  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', () => {
      mobileMenu.classList.toggle('open');
    });
    document.querySelectorAll('.mobile-menu a').forEach(a => {
      a.addEventListener('click', () => mobileMenu.classList.remove('open'));
    });
  }

  // Set active nav link
  const path = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav-links a, .mobile-menu a').forEach(a => {
    if (a.getAttribute('href') === path) a.classList.add('active');
  });
}

/* ─── SCROLL REVEAL ─── */
function initReveal() {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('visible');
        observer.unobserve(e.target);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

  document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .stagger').forEach(el => observer.observe(el));
}

/* ─── COUNT UP ─── */
function initCountUp() {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const el = entry.target;
      const target = parseInt(el.getAttribute('data-target'));
      const start = target > 500 ? target - Math.round(target * 0.15) : 0;
      const duration = 1800;
      const step = (target - start) / (duration / 16);
      let current = start;
      const timer = setInterval(() => {
        current = Math.min(current + step, target);
        el.textContent = Math.floor(current).toLocaleString();
        if (current >= target) clearInterval(timer);
      }, 16);
      observer.unobserve(el);
    });
  }, { threshold: 0.5 });
  document.querySelectorAll('.count-up').forEach(c => observer.observe(c));
}

/* ─── 3D CARD TILT ─── */
function initCardTilt() {
  if (window.matchMedia('(max-width: 768px)').matches) return;
  document.querySelectorAll('[data-tilt]').forEach(card => {
    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = (e.clientX - rect.left) / rect.width - 0.5;
      const y = (e.clientY - rect.top) / rect.height - 0.5;
      card.style.transform = `perspective(800px) rotateY(${x * 5}deg) rotateX(${-y * 5}deg) translateY(-4px)`;
    });
    card.addEventListener('mouseleave', () => { card.style.transform = ''; });
  });
}

/* ─── TABS ─── */
function initTabs(containerSelector) {
  const containers = document.querySelectorAll(containerSelector || '[data-tabs]');
  containers.forEach(container => {
    const buttons = container.querySelectorAll('.tab-btn');
    const panels = container.querySelectorAll('.tab-panel');
    buttons.forEach((btn, i) => {
      btn.addEventListener('click', () => {
        buttons.forEach(b => b.classList.remove('active'));
        panels.forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        panels[i] && panels[i].classList.add('active');
      });
    });
  });
}

/* ─── LIGHTBOX ─── */
function initLightbox() {
  const overlay = document.getElementById('lightbox');
  if (!overlay) return;

  const img = overlay.querySelector('.lightbox-img');
  const caption = overlay.querySelector('.lightbox-caption');
  const items = [...document.querySelectorAll('[data-lightbox]')];
  let current = 0;

  function open(index) {
    current = index;
    const item = items[current];
    img.src = item.getAttribute('data-src') || item.querySelector('img')?.src || item.style.backgroundImage?.slice(5, -2) || '';
    img.alt = item.getAttribute('data-caption') || '';
    if (caption) caption.textContent = item.getAttribute('data-caption') || '';
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function close() {
    overlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  items.forEach((item, i) => {
    item.style.cursor = 'pointer';
    item.addEventListener('click', () => open(i));
  });

  overlay.querySelector('.lightbox-close')?.addEventListener('click', close);
  overlay.querySelector('.lightbox-prev')?.addEventListener('click', () => open((current - 1 + items.length) % items.length));
  overlay.querySelector('.lightbox-next')?.addEventListener('click', () => open((current + 1) % items.length));
  overlay.addEventListener('click', e => { if (e.target === overlay) close(); });
  document.addEventListener('keydown', e => {
    if (!overlay.classList.contains('active')) return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowLeft') open((current - 1 + items.length) % items.length);
    if (e.key === 'ArrowRight') open((current + 1) % items.length);
  });
}

/* ─── MODAL ─── */
function initModals() {
  document.querySelectorAll('[data-modal-open]').forEach(btn => {
    btn.addEventListener('click', () => {
      const modal = document.getElementById(btn.getAttribute('data-modal-open'));
      if (modal) { modal.classList.add('active'); document.body.style.overflow = 'hidden'; }
    });
  });
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.querySelector('.modal-close')?.addEventListener('click', () => closeModal(overlay));
    overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(overlay); });
  });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.active').forEach(closeModal);
  });
}
function closeModal(overlay) {
  overlay.classList.remove('active');
  document.body.style.overflow = '';
}
function openModal(id) {
  const m = document.getElementById(id);
  if (m) { m.classList.add('active'); document.body.style.overflow = 'hidden'; }
}

/* ─── NOTIFICATION TOAST ─── */
function showNotification(message, type = 'info', duration = 3500) {
  let notif = document.querySelector('.notification');
  if (!notif) {
    notif = document.createElement('div');
    notif.className = 'notification';
    document.body.appendChild(notif);
  }
  notif.textContent = message;
  notif.className = `notification ${type}`;
  setTimeout(() => notif.classList.add('show'), 10);
  setTimeout(() => notif.classList.remove('show'), duration);
}

/* ─── CARPET BORDER SVG (reusable) ─── */
function carpetBorderHTML(fillColor = '#561010') {
  return `<div class="carpet-border" aria-hidden="true">
  <svg viewBox="0 0 1440 28" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <pattern id="cp${Math.random().toString(36).slice(2)}" x="0" y="0" width="36" height="28" patternUnits="userSpaceOnUse">
        <polygon points="18,4 26,14 18,24 10,14" fill="rgba(224,123,57,0.15)" stroke="#E07B39" stroke-width="0.8"/>
        <polygon points="18,9 22,14 18,19 14,14" fill="rgba(196,98,45,0.2)" stroke="#D4A574" stroke-width="0.6"/>
        <circle cx="0" cy="14" r="1.5" fill="#E07B39"/>
        <circle cx="36" cy="14" r="1.5" fill="#E07B39"/>
        <line x1="0" y1="14" x2="10" y2="14" stroke="#C4622D" stroke-width="0.5"/>
        <line x1="26" y1="14" x2="36" y2="14" stroke="#C4622D" stroke-width="0.5"/>
      </pattern>
    </defs>
    <rect width="1440" height="28" fill="${fillColor}"/>
    <line x1="0" y1="1.5" x2="1440" y2="1.5" stroke="#D4A574" stroke-width="1" opacity="0.5"/>
    <line x1="0" y1="26.5" x2="1440" y2="26.5" stroke="#D4A574" stroke-width="1" opacity="0.5"/>
  </svg></div>`;
}

/* ─── PARALLAX ─── */
function initParallax() {
  const elements = document.querySelectorAll('[data-parallax]');
  if (!elements.length || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  window.addEventListener('scroll', () => {
    const scrolled = window.scrollY;
    elements.forEach(el => {
      const speed = parseFloat(el.getAttribute('data-parallax')) || 0.2;
      el.style.transform = `translateY(${scrolled * speed}px)`;
    });
  }, { passive: true });
}

/* ─── HISTORY SCROLL STORY ─── */
function initHistoryStory() {
  const section = document.getElementById('history');
  const timeline = section?.querySelector('.timeline');
  const steps = [...(timeline?.querySelectorAll('.timeline-item') || [])];
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  if (!section || !timeline || !steps.length || reduceMotion.matches) return;
  section.classList.add('history-scroll-ready');

  let ticking = false;
  const updateStory = () => {
    const rect = timeline.getBoundingClientRect();
    const viewport = window.innerHeight || document.documentElement.clientHeight;
    const travel = Math.max(1, rect.height + viewport * 0.35);
    const progress = Math.max(0, Math.min(1, (viewport * 0.72 - rect.top) / travel));

    section.style.setProperty('--history-progress', progress.toFixed(4));
    steps.forEach((step, index) => {
      const threshold = index / Math.max(1, steps.length - 1);
      step.classList.toggle('is-active', progress >= threshold - 0.05);
    });
    ticking = false;
  };
  const requestUpdate = () => {
    if (!ticking) {
      ticking = true;
      window.requestAnimationFrame(updateStory);
    }
  };

  updateStory();
  window.addEventListener('scroll', requestUpdate, { passive: true });
  window.addEventListener('resize', requestUpdate);
}

/* ─── FORM VALIDATION ─── */
function validateForm(formEl) {
  let valid = true;
  formEl.querySelectorAll('[required]').forEach(field => {
    const err = field.parentElement.querySelector('.form-error');
    if (!field.value.trim()) {
      field.style.borderColor = '#e53935';
      if (err) { err.textContent = 'This field is required.'; err.style.display = 'block'; }
      valid = false;
    } else if (field.type === 'email' && !/^\S+@\S+\.\S+$/.test(field.value)) {
      field.style.borderColor = '#e53935';
      if (err) { err.textContent = 'Please enter a valid email.'; err.style.display = 'block'; }
      valid = false;
    } else {
      field.style.borderColor = '';
      if (err) err.style.display = 'none';
    }
  });
  return valid;
}

/* ─── SESSION / MOCK AUTH ─── */
const Auth = {
  getUser() { try { return JSON.parse(sessionStorage.getItem('bh_user') || 'null'); } catch { return null; } },
  setUser(u) { sessionStorage.setItem('bh_user', JSON.stringify(u)); },
  logout() { sessionStorage.removeItem('bh_user'); window.location.href = 'index.html'; },
  isLoggedIn() { return !!this.getUser(); },
  hasRole(role) {
    const roles = { pending: 0, member: 1, moderator: 2, board_member: 3, admin: 4 };
    const u = this.getUser();
    if (!u) return false;
    return (roles[u.role] || 0) >= (roles[role] || 0);
  }
};

/* ─── MOCK MEMBER DATABASE ─── */
const MemberDB = {
  key: 'bh_members',
  getAll() { try { return JSON.parse(localStorage.getItem(this.key) || '[]'); } catch { return []; } },
  save(members) { localStorage.setItem(this.key, JSON.stringify(members)); },
  add(member) {
    const members = this.getAll();
    member.id = Date.now();
    member.joinDate = new Date().toISOString();
    member.role = 'pending';
    member.status = 'pending';
    members.push(member);
    this.save(members);
    return member;
  },
  approve(id, approverRole) {
    const members = this.getAll();
    const m = members.find(m => m.id === id);
    if (m) { m.role = 'member'; m.status = 'approved'; m.approvedBy = approverRole; m.approvedAt = new Date().toISOString(); }
    this.save(members);
    return m;
  },
  reject(id) {
    const members = this.getAll();
    const m = members.find(m => m.id === id);
    if (m) { m.status = 'rejected'; }
    this.save(members);
  },
  findByEmail(email) { return this.getAll().find(m => m.email === email); }
};

/* ─── SEED DEMO MEMBERS ─── */
function seedDemoMembers() {
  if (MemberDB.getAll().length > 0) return;
  const demos = [
    { id: 1, firstName: 'Ahmad', lastName: 'Baloch', email: 'ahmad@example.com', password: 'demo123', role: 'admin', status: 'approved', city: 'Toronto', joinDate: '2020-01-15T00:00:00.000Z' },
    { id: 2, firstName: 'Fareeda', lastName: 'Mengal', email: 'fareeda@example.com', password: 'demo123', role: 'board_member', status: 'approved', city: 'Vancouver', joinDate: '2020-03-20T00:00:00.000Z' },
    { id: 3, firstName: 'Zara', lastName: 'Rind', email: 'zara@example.com', password: 'demo123', role: 'moderator', status: 'approved', city: 'Calgary', joinDate: '2021-06-10T00:00:00.000Z' },
    { id: 4, firstName: 'Hassan', lastName: 'Marri', email: 'hassan@example.com', password: 'demo123', role: 'member', status: 'approved', city: 'Ottawa', joinDate: '2022-02-28T00:00:00.000Z' },
    { id: 5, firstName: 'Laila', lastName: 'Bugti', email: 'laila@example.com', password: 'demo123', role: 'pending', status: 'pending', city: 'Montreal', joinDate: '2026-04-20T00:00:00.000Z' },
    { id: 6, firstName: 'Nawab', lastName: 'Zehri', email: 'nawab@example.com', password: 'demo123', role: 'pending', status: 'pending', city: 'Edmonton', joinDate: '2026-04-22T00:00:00.000Z' },
  ];
  localStorage.setItem(MemberDB.key, JSON.stringify(demos));
}

/* ─── INIT ALL ─── */
document.addEventListener('DOMContentLoaded', () => {
  seedDemoMembers();
  initNav();
  initReveal();
  initCountUp();
  initCardTilt();
  initTabs();
  initLightbox();
  initModals();
  initParallax();
  initHistoryStory();
});
