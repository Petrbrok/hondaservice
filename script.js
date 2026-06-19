const burger = document.querySelector("[data-burger]");
const mobileMenu = document.querySelector("[data-mobile-menu]");
const navLinks = document.querySelectorAll("[data-mobile-menu] a");
const priceButton = document.querySelector("[data-show-prices]");
const priceList = document.querySelector(".price-list");
const worksButton = document.querySelector("[data-show-works]");
const worksGrid = document.querySelector("[data-works-grid]");
const mobileCta = document.querySelector(".mobile-cta");
const heroPhoneButton = document.querySelector(".hero .hero-actions .btn[href^='tel:']");
const motionCards = document.querySelectorAll(
  ".trust-badge, .feature-grid article, .reviews-grid article, .price-item, .map-frame, .work-card"
);
const openStatusBadges = document.querySelectorAll("[data-open-status-badge]");
const schedule = window.HS_SCHEDULE || {
  openHour: 10,
  openMinute: 0,
  closeHour: 20,
  closeMinute: 0,
};

function pad(value) {
  return String(value).padStart(2, "0");
}

function updateOpenStatusBadge() {
  if (!openStatusBadges.length) return;

  const now = new Date();
  const openTime = new Date(now);
  openTime.setHours(schedule.openHour, schedule.openMinute, 0, 0);

  const closeTime = new Date(now);
  closeTime.setHours(schedule.closeHour, schedule.closeMinute, 0, 0);

  const isOpen = now >= openTime && now < closeTime;
  const openText = `${pad(schedule.openHour)}:${pad(schedule.openMinute)}`;
  const closeText = `${pad(schedule.closeHour)}:${pad(schedule.closeMinute)}`;
  const text = isOpen ? `Открыто до ${closeText}` : `Откроемся в ${openText}`;

  openStatusBadges.forEach((badge) => {
    badge.classList.toggle("is-open", isOpen);
    badge.classList.toggle("is-closed", !isOpen);
    badge.textContent = text;
  });
}

function closeMenu() {
  burger.setAttribute("aria-expanded", "false");
  mobileMenu.classList.remove("is-open");
  document.body.classList.remove("menu-open");
}

burger.addEventListener("click", () => {
  const isOpen = burger.getAttribute("aria-expanded") === "true";
  burger.setAttribute("aria-expanded", String(!isOpen));
  mobileMenu.classList.toggle("is-open", !isOpen);
  document.body.classList.toggle("menu-open", !isOpen);
});

navLinks.forEach((link) => {
  link.addEventListener("click", closeMenu);
});

if (priceButton && priceList) {
  priceButton.addEventListener("click", () => {
    const expanded = priceList.classList.toggle("is-expanded");
    priceButton.textContent = expanded ? "Скрыть" : "Показать ещё";
  });
}

if (worksButton && worksGrid) {
  worksButton.addEventListener("click", () => {
    const expanded = worksGrid.classList.toggle("is-expanded");
    worksButton.textContent = expanded ? "Скрыть" : "Показать ещё";
  });
}

document.addEventListener("click", (event) => {
  if (!mobileMenu.classList.contains("is-open")) return;
  if (mobileMenu.contains(event.target) || burger.contains(event.target)) return;
  closeMenu();
});

if (mobileCta && heroPhoneButton) {
  const updateMobileCta = () => {
    const heroRect = heroPhoneButton.getBoundingClientRect();
    const isVisible = heroRect.bottom > 0 && heroRect.top < window.innerHeight;
    mobileCta.classList.toggle("is-hidden", isVisible);
  };

  updateMobileCta();
  window.addEventListener("scroll", updateMobileCta, { passive: true });
  window.addEventListener("resize", updateMobileCta);
}

if (motionCards.length) {
  const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
  const mobileMotion = window.matchMedia("(hover: none), (pointer: coarse)");

  const revealCards = () => {
    motionCards.forEach((card) => card.classList.add("is-inview"));
  };

  if (reducedMotion.matches || !("IntersectionObserver" in window)) {
    revealCards();
  } else if (mobileMotion.matches) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          entry.target.classList.toggle("is-inview", entry.intersectionRatio >= 0.38);
        });
      },
      {
        threshold: [0, 0.38, 0.55],
        rootMargin: "-16% 0px -18% 0px",
      }
    );

    motionCards.forEach((card) => observer.observe(card));
  } else {
    revealCards();
  }
}

updateOpenStatusBadge();
setInterval(updateOpenStatusBadge, 60 * 1000);
window.addEventListener("resize", updateOpenStatusBadge);
