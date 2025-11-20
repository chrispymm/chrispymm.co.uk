class ThemeSwitcher extends HTMLElement {
  static tag = "theme-switcher";
  static dataAttributeName = "theme";

  static define(tag = this.tag) {
    customElements.define(tag, this);
  }

  constructor() {
    super();
  }

  connectedCallback() {
    this.buttons = this.querySelectorAll("button");

    this.load();
    this.initialize();

    if (this.currentButton) {
      this.emit(`init`, {
        theme: this.currentButton.value,
        accents: this.currentButton.dataset.hasAccents === "true",
      });
    }
  }

  disconnectedCallback() {
    this.removeEventListener("click", this);
  }

  initialize() {
    for (const button of this.buttons) {
      const label = button.textContent.trim();
      const icon = button.querySelector("svg");
      button.setAttribute("aria-label", label);
      button.innerHTML = "";
      if (icon) {
        button.appendChild(icon);
      }
    }

    this.addEventListener("click", this);
  }

  handleEvent(event) {
    this[`on${event.type}`](event);
  }

  onclick(event) {
    const button = event.target.closest("button");
    if (!(button instanceof HTMLButtonElement)) return;

    this.handleButtonPress(button);

    this.save(button.value);

    this.emit(`change`, {
      theme: button.value,
      accents: button.dataset.hasAccents === "true",
    });
  }

  emit(type, detail = {}) {
    console.log(detail);
    console.log(type);
    let event = new CustomEvent(`${this.constructor.tag}:${type}`, {
      bubbles: true,
      cancelable: true,
      detail: detail,
    });
    console.log(event);

    return this.dispatchEvent(event);
  }

  handleButtonPress(button) {
    const value = button.getAttribute("value");

    this.buttons.forEach((btn) => {
      btn.setAttribute("aria-current", "false");
    });

    document.body.setAttribute(
      `data-${this.constructor.dataAttributeName}`,
      value,
    );
    button.setAttribute("aria-current", true);
  }

  save(value) {
    localStorage.setItem("theme", value);
    console.log(`saved theme ${value}`);
  }

  load() {
    const storedTheme = localStorage.getItem("theme");
    if (storedTheme) {
      const button = this.querySelector(`button[value="${storedTheme}"]`);
      if (button) {
        console.log(`loaded theme ${storedTheme}`);
        this.handleButtonPress(button);
      }
    }
  }

  get currentButton() {
    return this.querySelector('button[aria-current="true"]');
  }
}

if (!new URL(import.meta.url).searchParams.has("nodefine")) {
  ThemeSwitcher.define();
}

window.ThemeSwitcher = ThemeSwitcher;
export { ThemeSwitcher };
