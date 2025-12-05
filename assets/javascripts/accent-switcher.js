class AccentSwitcher extends HTMLElement {
  static tag = "accent-switcher";
  static dataAttributeName = "accent";

  static define(tag = this.tag) {
    customElements.define(tag, this);
  }

  constructor() {
    super();
    console.log(`${this.constructor.tag} constructed`);
  }

  connectedCallback() {
    console.log(`${this.constructor.tag} connected`);

    this.initialize();
    this.load();

    // if (this.pressedButton) {
    //   this.emit(`init`, {
    //     accent: this.pressedButton.value,
    //   });
    // }

    document.addEventListener("theme-switcher:init", this);
    document.addEventListener("theme-switcher:change", this);
  }

  disconnectedCallback() {
    this.removeEventListener("click", this);
  }

  initialize() {
    this.buttons = this.querySelectorAll("button");

    for (const button of this.buttons) {
      const label = button.textContent.trim();
      const icon = button.querySelector("svg");
      if (this.hideButtonLabels) {
        button.setAttribute("aria-label", label);
        button.innerHTML = "";
        if (icon) {
          button.appendChild(icon);
        }
      }
    }

    this.addEventListener("click", this);
  }

  handleEvent(event) {
    console.log(event);
    switch (event.type) {
      case "theme-switcher:init":
      case "theme-switcher:change":
        this.handleThemeChange(event);
        break;
      default:
        this[`on${event.type}`](event);
        break;
    }
  }

  onclick(event) {
    const button = event.target.closest("button");
    if (!(button instanceof HTMLButtonElement)) return;

    this.handleButtonPress(button);

    this.save(button.value);

    this.emit(`change`, {
      accent: button.value,
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

  handleThemeChange(event) {
    console.log(event);
    const { detail } = event;
    console.log(detail);
    if (detail.accents) {
      this.show();
    } else {
      this.reset();
      this.hide();
    }
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

  hide() {
    this.setAttribute("hidden", true);
  }

  show() {
    this.removeAttribute("hidden");
  }

  reset() {
    this.handleButtonPress(this.defaultButton);
  }

  save(value) {
    localStorage.setItem("accent", value);
  }

  load() {
    const storedAccent = localStorage.getItem("accent");
    if (storedAccent) {
      const button = this.querySelector(`button[value="${storedAccent}"]`);
      this.handleButtonPress(button);
    }
  }

  get currentButton() {
    return this.querySelector('button[aria-current="true"]');
  }

  get defaultButton() {
    return this.querySelector("button[default]");
  }

  get hideButtonLabels() {
    return this.getAttribute("hide-button-labels") || false;
  }
}

if (!new URL(import.meta.url).searchParams.has("nodefine")) {
  AccentSwitcher.define();
}

window.AccentSwitcher = AccentSwitcher;
export { AccentSwitcher };
