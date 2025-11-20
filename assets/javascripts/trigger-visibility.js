class TriggerVisibility extends HTMLElement {
  static tag = "trigger-visibility";

  static define(tag = this.tag) {
    this.tag = tag;
    customElements.define(tag, this);
  }

  constructor() {
    super();
  }

  connectedCallback() {
    console.log(this.eventNames);
    console.log(this.action);
    if (!this.eventNames) return;
    if (!this.action) {
      console.error(
        "<trigger-visibility> element requires an 'action' attribute set to either 'show' or 'hide'.",
      );
      return;
    }

    for (const eventName of this.eventNames) {
      document.addEventListener(eventName, this);
    }
  }

  disconnectedCallback() {
    for (const eventName of this.eventNames) {
      document.removeEventListener(eventName, this);
    }
  }

  handleEvent(event) {
    const { detail } = event;
    console.log(event);
    console.log(detail);
    let conditionMatched = false;
    for (const condition of this.conditions) {
      conditionMatched = this.checkCondition(condition, detail);
      if (conditionMatched) break;
    }

    if (conditionMatched) {
      this.action === "show" ? this.show() : this.hide();
    } else {
      this.action === "show" ? this.hide() : this.show();
    }
  }

  checkCondition(condition, detail) {
    const [key, value] = condition.split("=");

    return detail[key] === value;
  }

  show() {
    this.removeAttribute("hidden");
  }

  hide() {
    this.setAttribute("hidden", "true");
  }

  get eventNames() {
    return this.getAttribute("on")?.split(" ") || [];
  }

  get conditions() {
    return this.getAttribute("if")?.split("||");
  }

  get action() {
    return this.getAttribute("action");
  }
}

if (!new URL(import.meta.url).searchParams.has("nodefine")) {
  TriggerVisibility.define();
}

window.TriggerVisibility = TriggerVisibility;
export { TriggerVisibility };
