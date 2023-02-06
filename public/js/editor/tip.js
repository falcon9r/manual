class Tip {

  /**
   * Notify core that read-only mode is supported
   */
  static get isReadOnlySupported() {
    return true;
  }

  /**
   * Get Toolbox settings
   *
   * @public
   * @returns {string}
   */
  static get toolbox() {
    return {
      icon: `<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">
      <g><path stroke="black" stroke-width="40" d="M500,210.2c-74.5,0-144.6,28.7-197.3,80.9C250,343.3,221,412.7,221,486.5c0,73.8,29,143.2,81.7,195.3c52.7,52.2,122.8,80.9,197.3,80.9s144.6-28.7,197.3-80.9C750,629.7,779,560.3,779,486.5c0-73.8-29-143.2-81.7-195.4C644.7,238.9,574.5,210.2,500,210.2z M500,704.5c-121.4,0-220.3-97.7-220.3-218c0-120.2,98.8-218,220.3-218c121.4,0,220.3,97.8,220.3,218C720.3,606.7,621.5,704.5,500,704.5z M503.2,138.8c16.2,0,29.4-13,29.4-29.2V39.2c0-16.1-13.2-29.2-29.4-29.2c-16.2,0-29.5,13-29.5,29.2v70.5C473.8,125.8,487,138.8,503.2,138.8z M256,237.6c7.6,0,15.1-2.9,20.8-8.6c11.5-11.4,11.5-29.8,0-41.2l-50.3-49.9c-11.5-11.4-30.1-11.4-41.6,0c-11.5,11.4-11.5,29.8,0,41.2l50.3,49.8C240.9,234.7,248.4,237.6,256,237.6z M151.6,422.2H80.4c-16.2,0-29.5,13-29.5,29.2c0,16.1,13.2,29.2,29.5,29.2h71.2c16.2,0,29.5-13,29.5-29.2C181.1,435.3,167.9,422.2,151.6,422.2z M919.6,428.8h-71.2c-16.2,0-29.5,13-29.5,29.2c0,16.1,13.2,29.2,29.5,29.2h71.2c16.2,0,29.5-13,29.5-29.2C949.1,441.8,935.9,428.8,919.6,428.8z M748.8,242.2c7.6,0,15.1-2.9,20.8-8.5l50.3-49.9c11.5-11.4,11.5-29.8,0-41.2s-30.1-11.4-41.7,0l-50.4,49.9c-11.5,11.4-11.5,29.8,0,41.2C733.7,239.3,741.1,242.2,748.8,242.2z M608.7,820.9H391.4c-16.2,0-29.5,13.1-29.5,29.2s13.2,29.2,29.5,29.2h217.3c16.2,0,29.5-13,29.5-29.2C638.1,834,624.9,820.9,608.7,820.9z M569.4,931.6H430.7c-16.2,0-29.5,13-29.5,29.2c0,16.1,13.2,29.2,29.5,29.2h138.6c16.2,0,29.5-13.1,29.5-29.2C598.8,944.7,585.6,931.6,569.4,931.6z"/></g>
      </svg>`,
      title: 'Tip',
    };
  }

  /**
   * Allow to press Enter inside the Tip
   *
   * @public
   * @returns {boolean}
   */
  static get enableLineBreaks() {
    return true;
  }

  /**
   * Default placeholder for tip title
   *
   * @public
   * @returns {string}
   */
  static get DEFAULT_TITLE_PLACEHOLDER() {
    return 'Title';
  }

  /**
   * Default placeholder for tip message
   *
   * @public
   * @returns {string}
   */
  static get DEFAULT_MESSAGE_PLACEHOLDER() {
    return 'Message';
  }

  /**
   * tip Tool`s styles
   *
   * @returns {object}
   */
  get CSS() {
    return {
      baseClass: this.api.styles.block,
      wrapper: 'cdx-tip',
      title: 'cdx-tip__title',
      input: this.api.styles.input,
      message: 'cdx-tip__message',
    };
  }

  /**
   * Render plugin`s main Element and fill it with saved data
   *
   * @param {TipData} data — previously saved data
   * @param {TipConfig} config — user config for Tool
   * @param {object} api - Editor.js API
   * @param {boolean} readOnly - read-only mode flag
   */
  constructor({ data, config, api, readOnly }) {
    this.api = api;
    this.readOnly = readOnly;

    this.titlePlaceholder = config.titlePlaceholder || Tip.DEFAULT_TITLE_PLACEHOLDER;
    this.messagePlaceholder = config.messagePlaceholder || Tip.DEFAULT_MESSAGE_PLACEHOLDER;

    this.data = {
      title: data.title || '',
      message: data.message || '',
    };
  }

  /**
   * Create Tip Tool container with inputs
   *
   * @returns {Element}
   */
  render() {
    const container = this._make('div', [this.CSS.baseClass, this.CSS.wrapper]);
    const title = this._make('div', [this.CSS.input, this.CSS.title], {
      contentEditable: !this.readOnly,
      innerHTML: this.data.title,
    });
    const message = this._make('div', [this.CSS.input, this.CSS.message], {
      contentEditable: !this.readOnly,
      innerHTML: this.data.message,
    });

    title.dataset.placeholder = this.titlePlaceholder;
    message.dataset.placeholder = this.messagePlaceholder;

    container.appendChild(title);
    container.appendChild(message);

    return container;
  }

  /**
   * Extract Tip data from Tip Tool element
   *
   * @param {HTMLDivElement} tipElement - element to save
   * @returns {TipData}
   */
  save(tipElement) {
    const title = tipElement.querySelector(`.${this.CSS.title}`);
    const message = tipElement.querySelector(`.${this.CSS.message}`);

    return Object.assign(this.data, {
      title: title.innerHTML,
      message: message.innerHTML,
    });
  }

  /**
   * Helper for making Elements with attributes
   *
   * @param  {string} tagName           - new Element tag name
   * @param  {Array|string} classNames  - list or name of CSS classname(s)
   * @param  {object} attributes        - any attributes
   * @returns {Element}
   */
  _make(tagName, classNames = null, attributes = {}) {
    const el = document.createElement(tagName);

    if (Array.isArray(classNames)) {
      el.classList.add(...classNames);
    } else if (classNames) {
      el.classList.add(classNames);
    }

    for (const attrName in attributes) {
      el[attrName] = attributes[attrName];
    }

    return el;
  }

  /**
   * Sanitizer config for Tip Tool saved data
   *
   * @returns {object}
   */
  static get sanitize() {
    return {
      title: {},
      message: {},
    };
  }
}
