const burger = document.querySelector("[data-burger]");
const mobileMenu = document.querySelector("[data-mobile-menu]");
const navLinks = document.querySelectorAll("[data-mobile-menu] a");
const priceButton = document.querySelector("[data-show-prices]");
const priceList = document.querySelector(".price-list");
const worksButton = document.querySelector("[data-show-works]");
const worksGrid = document.querySelector("[data-works-grid]");
const form = document.querySelector("[data-form]");
const formStatus = document.querySelector("[data-form-status]");
const mobileCta = document.querySelector(".mobile-cta");
const footer = document.querySelector(".site-footer");

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

priceButton.addEventListener("click", () => {
  const expanded = priceList.classList.toggle("is-expanded");
  priceButton.textContent = expanded ? "Скрыть" : "Показать ещё";
});

worksButton.addEventListener("click", () => {
  const expanded = worksGrid.classList.toggle("is-expanded");
  worksButton.textContent = expanded ? "Скрыть" : "Показать ещё";
});

form.addEventListener("submit", (event) => {
  event.preventDefault();

  const formData = new FormData(form);
  const name = String(formData.get("name") || "").trim();
  const phone = String(formData.get("phone") || "").trim();
  const digits = phone.replace(/\D/g, "");

  if (!name || digits.length < 10) {
    formStatus.textContent = "Заполните имя и телефон, чтобы отправить заявку.";
    formStatus.style.color = "#ffb1b8";
    return;
  }

  formStatus.textContent = "Заявка принята. Скоро свяжемся для подтверждения записи.";
  formStatus.style.color = "#ffffff";
  form.reset();
});

document.addEventListener("click", (event) => {
  if (!mobileMenu.classList.contains("is-open")) return;
  if (mobileMenu.contains(event.target) || burger.contains(event.target)) return;
  closeMenu();
});

if (mobileCta && footer) {
  const updateMobileCta = () => {
    const footerRect = footer.getBoundingClientRect();
    if (footerRect.top < window.innerHeight) {
      mobileCta.classList.add("is-hidden");
    } else {
      mobileCta.classList.remove("is-hidden");
    }
  };

  updateMobileCta();
  window.addEventListener("scroll", updateMobileCta, { passive: true });
  window.addEventListener("resize", updateMobileCta);
}
