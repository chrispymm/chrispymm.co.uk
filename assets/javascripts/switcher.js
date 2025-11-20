class Switcher extends HTMLElement {
  static tag = "switcher"
  constructor() {
    super();
  }

  connectedCallback() {
    this.initialize()
  }

  disconnectedCallback() {
    this.removeEventListener("click", this);
  }

  initialize() {
    this.buttons = this.querySelectorAll("button");

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

    this.handleButtonPress(button)

    this.emit(`change-${this.dataAttributeName}`, { [this.dataAttributeName]: value })
  }

  emit(type, detail = {}) {
        console.log(detail)
        console.log(type)
    let event = new CustomEvent(`${type}`, {
      bubbles: true,
      cancelable: true,
      detail: detail,
    });
    console.log(event)

    return this.dispatchEvent(event);
  }

  handleButtonPress(button) {
    this.buttons.forEach((btn) => {
      btn.setAttribute("aria-pressed", "false");
    });

    const value = button.getAttribute("value");
    document.body.setAttribute(`data-${this.dataAttributeName}`, value);
    button.setAttribute("aria-pressed", true);
  }
  

  get dataAttributeName() {
    return `${this.getAttribute("attribute-name") || "theme"}`;
  }

  get pressedButton() {
        return this.querySelector('button[aria-pressed="true"]')
    }
}

// if (!new URL(import.meta.url).searchParams.has("nodefine")) {
//   Switcher.define();
// }

export { Switcher };
