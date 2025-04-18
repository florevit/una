import Quill from 'quill';

function ownKeys(object, enumerableOnly) {
  var keys = Object.keys(object);

  if (Object.getOwnPropertySymbols) {
    var symbols = Object.getOwnPropertySymbols(object);

    if (enumerableOnly) {
      symbols = symbols.filter(function (sym) {
        return Object.getOwnPropertyDescriptor(object, sym).enumerable;
      });
    }

    keys.push.apply(keys, symbols);
  }

  return keys;
}

function _objectSpread2(target) {
  for (var i = 1; i < arguments.length; i++) {
    var source = arguments[i] != null ? arguments[i] : {};

    if (i % 2) {
      ownKeys(Object(source), true).forEach(function (key) {
        _defineProperty(target, key, source[key]);
      });
    } else if (Object.getOwnPropertyDescriptors) {
      Object.defineProperties(target, Object.getOwnPropertyDescriptors(source));
    } else {
      ownKeys(Object(source)).forEach(function (key) {
        Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key));
      });
    }
  }

  return target;
}

function _classCallCheck(instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
}

function _defineProperties(target, props) {
  for (var i = 0; i < props.length; i++) {
    var descriptor = props[i];
    descriptor.enumerable = descriptor.enumerable || false;
    descriptor.configurable = true;
    if ("value" in descriptor) descriptor.writable = true;
    Object.defineProperty(target, descriptor.key, descriptor);
  }
}

function _createClass(Constructor, protoProps, staticProps) {
  if (protoProps) _defineProperties(Constructor.prototype, protoProps);
  if (staticProps) _defineProperties(Constructor, staticProps);
  return Constructor;
}

function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
}

function _extends() {
  _extends = Object.assign || function (target) {
    for (var i = 1; i < arguments.length; i++) {
      var source = arguments[i];

      for (var key in source) {
        if (Object.prototype.hasOwnProperty.call(source, key)) {
          target[key] = source[key];
        }
      }
    }

    return target;
  };

  return _extends.apply(this, arguments);
}

function _inherits(subClass, superClass) {
  if (typeof superClass !== "function" && superClass !== null) {
    throw new TypeError("Super expression must either be null or a function");
  }

  subClass.prototype = Object.create(superClass && superClass.prototype, {
    constructor: {
      value: subClass,
      writable: true,
      configurable: true
    }
  });
  if (superClass) _setPrototypeOf(subClass, superClass);
}

function _getPrototypeOf(o) {
  _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) {
    return o.__proto__ || Object.getPrototypeOf(o);
  };
  return _getPrototypeOf(o);
}

function _setPrototypeOf(o, p) {
  _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) {
    o.__proto__ = p;
    return o;
  };

  return _setPrototypeOf(o, p);
}

function _isNativeReflectConstruct() {
  if (typeof Reflect === "undefined" || !Reflect.construct) return false;
  if (Reflect.construct.sham) return false;
  if (typeof Proxy === "function") return true;

  try {
    Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {}));
    return true;
  } catch (e) {
    return false;
  }
}

function _assertThisInitialized(self) {
  if (self === void 0) {
    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
  }

  return self;
}

function _possibleConstructorReturn(self, call) {
  if (call && (typeof call === "object" || typeof call === "function")) {
    return call;
  } else if (call !== void 0) {
    throw new TypeError("Derived constructors may only return object or undefined");
  }

  return _assertThisInitialized(self);
}

function _createSuper(Derived) {
  var hasNativeReflectConstruct = _isNativeReflectConstruct();

  return function _createSuperInternal() {
    var Super = _getPrototypeOf(Derived),
        result;

    if (hasNativeReflectConstruct) {
      var NewTarget = _getPrototypeOf(this).constructor;

      result = Reflect.construct(Super, arguments, NewTarget);
    } else {
      result = Super.apply(this, arguments);
    }

    return _possibleConstructorReturn(this, result);
  };
}

function _superPropBase(object, property) {
  while (!Object.prototype.hasOwnProperty.call(object, property)) {
    object = _getPrototypeOf(object);
    if (object === null) break;
  }

  return object;
}

function _get() {
  if (typeof Reflect !== "undefined" && Reflect.get) {
    _get = Reflect.get;
  } else {
    _get = function _get(target, property, receiver) {
      var base = _superPropBase(target, property);

      if (!base) return;
      var desc = Object.getOwnPropertyDescriptor(base, property);

      if (desc.get) {
        return desc.get.call(arguments.length < 3 ? target : receiver);
      }

      return desc.value;
    };
  }

  return _get.apply(this, arguments);
}

function _unsupportedIterableToArray(o, minLen) {
  if (!o) return;
  if (typeof o === "string") return _arrayLikeToArray(o, minLen);
  var n = Object.prototype.toString.call(o).slice(8, -1);
  if (n === "Object" && o.constructor) n = o.constructor.name;
  if (n === "Map" || n === "Set") return Array.from(o);
  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen);
}

function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;

  for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i];

  return arr2;
}

function _createForOfIteratorHelper(o, allowArrayLike) {
  var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"];

  if (!it) {
    if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") {
      if (it) o = it;
      var i = 0;

      var F = function () {};

      return {
        s: F,
        n: function () {
          if (i >= o.length) return {
            done: true
          };
          return {
            done: false,
            value: o[i++]
          };
        },
        e: function (e) {
          throw e;
        },
        f: F
      };
    }

    throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
  }

  var normalCompletion = true,
      didErr = false,
      err;
  return {
    s: function () {
      it = it.call(o);
    },
    n: function () {
      var step = it.next();
      normalCompletion = step.done;
      return step;
    },
    e: function (e) {
      didErr = true;
      err = e;
    },
    f: function () {
      try {
        if (!normalCompletion && it.return != null) it.return();
      } finally {
        if (didErr) throw err;
      }
    }
  };
}

var Keys = {
  TAB: 9,
  ENTER: 13,
  ESCAPE: 27,
  UP: 38,
  DOWN: 40
};

function attachDataValues(element, data, dataAttributes) {
  var mention = element;
  Object.keys(data).forEach(function (key) {
    if (dataAttributes.indexOf(key) > -1) {
      mention.dataset[key] = data[key];
    } else {
      delete mention.dataset[key];
    }
  });
  return mention;
}

function getMentionCharIndex(text, mentionDenotationChars, isolateChar, allowInlineMentionChar) {
  return mentionDenotationChars.reduce(function (prev, mentionChar) {
    var mentionCharIndex;

    if (isolateChar && allowInlineMentionChar) {
      var regex = new RegExp("^".concat(mentionChar, "|\\s").concat(mentionChar), 'g');
      var lastMatch = (text.match(regex) || []).pop();

      if (!lastMatch) {
        return {
          mentionChar: prev.mentionChar,
          mentionCharIndex: prev.mentionCharIndex
        };
      }

      mentionCharIndex = lastMatch !== mentionChar ? text.lastIndexOf(lastMatch) + lastMatch.length - mentionChar.length : 0;
    } else {
      mentionCharIndex = text.lastIndexOf(mentionChar);
    }

    if (mentionCharIndex > prev.mentionCharIndex) {
      return {
        mentionChar: mentionChar,
        mentionCharIndex: mentionCharIndex
      };
    }

    return {
      mentionChar: prev.mentionChar,
      mentionCharIndex: prev.mentionCharIndex
    };
  }, {
    mentionChar: null,
    mentionCharIndex: -1
  });
}

function hasValidChars(text, allowedChars) {
  return allowedChars.test(text);
}

function hasValidMentionCharIndex(mentionCharIndex, text, isolateChar, textPrefix) {
  if (mentionCharIndex === -1) {
    return false;
  }

  if (!isolateChar) {
    return true;
  }

  var mentionPrefix = mentionCharIndex ? text[mentionCharIndex - 1] : textPrefix;
  return !mentionPrefix || !!mentionPrefix.match(/\s/);
}

var Embed = Quill["import"]("blots/embed");

var MentionBlot = /*#__PURE__*/function (_Embed) {
  _inherits(MentionBlot, _Embed);

  var _super = _createSuper(MentionBlot);

  function MentionBlot(scroll, node) {
    var _this;

    _classCallCheck(this, MentionBlot);

    _this = _super.call(this, scroll, node);

    _defineProperty(_assertThisInitialized(_this), "hoverHandler", void 0);

    _defineProperty(_assertThisInitialized(_this), "hoverHandler", void 0);

    _this.clickHandler = null;
    _this.hoverHandler = null;
    _this.mounted = false;
    return _this;
  }

  _createClass(MentionBlot, [{
    key: "attach",
    value: function attach() {
      _get(_getPrototypeOf(MentionBlot.prototype), "attach", this).call(this);

      if (!this.mounted) {
        this.mounted = true;
        this.clickHandler = this.getClickHandler();
        this.hoverHandler = this.getHoverHandler();
        this.domNode.addEventListener("click", this.clickHandler, false);
        this.domNode.addEventListener("mouseenter", this.hoverHandler, false);
      }
    }
  }, {
    key: "detach",
    value: function detach() {
      _get(_getPrototypeOf(MentionBlot.prototype), "detach", this).call(this);

      this.mounted = false;

      if (this.clickHandler) {
        this.domNode.removeEventListener("click", this.clickHandler);
        this.clickHandler = null;
      }
    }
  }, {
    key: "getClickHandler",
    value: function getClickHandler() {
      var _this2 = this;

      return function (e) {
        var event = _this2.buildEvent("mention-clicked", e);

        window.dispatchEvent(event);
        e.preventDefault();
      };
    }
  }, {
    key: "getHoverHandler",
    value: function getHoverHandler() {
      var _this3 = this;

      return function (e) {
        var event = _this3.buildEvent('mention-hovered', e);

        window.dispatchEvent(event);
        e.preventDefault();
      };
    }
  }, {
    key: "buildEvent",
    value: function buildEvent(name, e) {
      var event = new Event(name, {
        bubbles: true,
        cancelable: true
      });
      event.value = _extends({}, this.domNode.dataset);
      event.event = e;
      return event;
    }
  }], [{
    key: "create",
    value: function create(data) {
      var node = _get(_getPrototypeOf(MentionBlot), "create", this).call(this);

      var denotationChar = document.createElement("span");
      denotationChar.className = "ql-mention-denotation-char";
      denotationChar.innerHTML = data.denotationChar;
      node.appendChild(denotationChar);
      node.innerHTML += data.value;
      return MentionBlot.setDataValues(node, data);
    }
  }, {
    key: "setDataValues",
    value: function setDataValues(element, data) {
      var domNode = element;
      Object.keys(data).forEach(function (key) {
        domNode.dataset[key] = data[key];
      });
      return domNode;
    }
  }, {
    key: "value",
    value: function value(domNode) {
      return domNode.dataset;
    }
  }]);

  return MentionBlot;
}(Embed);

MentionBlot.blotName = "mention";
MentionBlot.tagName = "span";
MentionBlot.className = "mention";
Quill.register(MentionBlot);

var Mention = /*#__PURE__*/function () {
  function Mention(quill, options) {
    var _this = this;

    _classCallCheck(this, Mention);

    this.isOpen = false;
    this.itemIndex = 0;
    this.mentionCharPos = null;
    this.cursorPos = null;
    this.values = [];
    this.suspendMouseEnter = false; //this token is an object that may contains one key "abandoned", set to
    //true when the previous source call should be ignored in favor or a
    //more recent execution.  This token will be null unless a source call
    //is in progress.

    this.existingSourceExecutionToken = null;
    this.quill = quill;
    this.options = {
      source: null,
      renderItem: function renderItem(item) {
        return "".concat(item.value);
      },
      renderLoading: function renderLoading() {
        return null;
      },
      onSelect: function onSelect(item, insertItem) {
        insertItem(item);
      },
      mentionDenotationChars: ["@"],
      showDenotationChar: true,
      allowedChars: /^[a-zA-Z0-9_]*$/,
      minChars: 0,
      maxChars: 31,
      offsetTop: 2,
      offsetLeft: 0,
      isolateCharacter: false,
      allowInlineMentionChar: false,
      fixMentionsToQuill: false,
      positioningStrategy: "normal",
      defaultMenuOrientation: "bottom",
      blotName: "mention",
      dataAttributes: ["id", "value", "denotationChar", "link", "target", "disabled"],
      linkTarget: "_blank",
      onOpen: function onOpen() {
        return true;
      },
      onBeforeClose: function onBeforeClose() {
        return true;
      },
      onClose: function onClose() {
        return true;
      },
      // Style options
      listItemClass: "ql-mention-list-item",
      mentionContainerClass: "ql-mention-list-container",
      mentionListClass: "ql-mention-list",
      spaceAfterInsert: true,
      selectKeys: [Keys.ENTER]
    };

    _extends(this.options, options, {
      dataAttributes: Array.isArray(options.dataAttributes) ? this.options.dataAttributes.concat(options.dataAttributes) : this.options.dataAttributes
    }); //Bind all option-functions so they have a reasonable context


    for (var o in this.options) {
      if (typeof this.options[o] === 'function') {
        this.options[o] = this.options[o].bind(this);
      }
    } //create mention container


    this.mentionContainer = document.createElement("div");
    this.mentionContainer.className = this.options.mentionContainerClass ? this.options.mentionContainerClass : "";
    this.mentionContainer.style.cssText = "display: none; position: absolute;";
    this.mentionContainer.onmousemove = this.onContainerMouseMove.bind(this);

    if (this.options.fixMentionsToQuill) {
      this.mentionContainer.style.width = "auto";
    }

    this.mentionList = document.createElement("ul");
    this.mentionList.id = 'quill-mention-list';
    quill.root.setAttribute('aria-owns', 'quill-mention-list');
    this.mentionList.className = this.options.mentionListClass ? this.options.mentionListClass : "";
    this.mentionContainer.appendChild(this.mentionList);
    quill.on("text-change", this.onTextChange.bind(this));
    quill.on("selection-change", this.onSelectionChange.bind(this)); //Pasting doesn't fire selection-change after the pasted text is
    //inserted, so here we manually trigger one

    quill.container.addEventListener("paste", function () {
      setTimeout(function () {
        var range = quill.getSelection();

        _this.onSelectionChange(range);
      });
    });
    quill.keyboard.addBinding({
      key: Keys.TAB
    }, this.selectHandler.bind(this));
    quill.keyboard.bindings[Keys.TAB].unshift(quill.keyboard.bindings[Keys.TAB].pop());

    var _iterator = _createForOfIteratorHelper(this.options.selectKeys),
        _step;

    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var selectKey = _step.value;
        quill.keyboard.addBinding({
          key: selectKey
        }, this.selectHandler.bind(this));
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }

    quill.keyboard.bindings[Keys.ENTER].unshift(quill.keyboard.bindings[Keys.ENTER].pop());
    quill.keyboard.addBinding({
      key: Keys.ESCAPE
    }, this.escapeHandler.bind(this));
    quill.keyboard.addBinding({
      key: Keys.UP
    }, this.upHandler.bind(this));
    quill.keyboard.addBinding({
      key: Keys.DOWN
    }, this.downHandler.bind(this));
  }

  _createClass(Mention, [{
    key: "selectHandler",
    value: function selectHandler() {
      if (this.isOpen && !this.existingSourceExecutionToken) {
        this.selectItem();
        return false;
      }

      return true;
    }
  }, {
    key: "escapeHandler",
    value: function escapeHandler() {
      if (this.isOpen) {
        if (this.existingSourceExecutionToken) {
          this.existingSourceExecutionToken.abandoned = true;
        }

        this.hideMentionList();
        return false;
      }

      return true;
    }
  }, {
    key: "upHandler",
    value: function upHandler() {
      if (this.isOpen && !this.existingSourceExecutionToken) {
        this.prevItem();
        return false;
      }

      return true;
    }
  }, {
    key: "downHandler",
    value: function downHandler() {
      if (this.isOpen && !this.existingSourceExecutionToken) {
        this.nextItem();
        return false;
      }

      return true;
    }
  }, {
    key: "showMentionList",
    value: function showMentionList() {
      if (this.options.positioningStrategy === "fixed") {
        document.body.appendChild(this.mentionContainer);
      } else {
        this.quill.container.appendChild(this.mentionContainer);
      }

      this.mentionContainer.style.visibility = "hidden";
      this.mentionContainer.style.display = "";
      this.mentionContainer.scrollTop = 0;
      this.setMentionContainerPosition();
      this.setIsOpen(true);
    }
  }, {
    key: "hideMentionList",
    value: function hideMentionList() {
      this.options.onBeforeClose();
      this.mentionContainer.style.display = "none";
      this.mentionContainer.remove();
      this.setIsOpen(false);
      this.quill.root.removeAttribute('aria-activedescendant');
    }
  }, {
    key: "highlightItem",
    value: function highlightItem() {
      var scrollItemInView = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;

      for (var i = 0; i < this.mentionList.childNodes.length; i += 1) {
        this.mentionList.childNodes[i].classList.remove("selected");
      }

      if (this.itemIndex === -1 || this.mentionList.childNodes[this.itemIndex].dataset.disabled === "true") {
        return;
      }

      this.mentionList.childNodes[this.itemIndex].classList.add("selected");
      this.quill.root.setAttribute('aria-activedescendant', this.mentionList.childNodes[this.itemIndex].id);

      if (scrollItemInView) {
        var itemHeight = this.mentionList.childNodes[this.itemIndex].offsetHeight;
        var itemPos = this.mentionList.childNodes[this.itemIndex].offsetTop;
        var containerTop = this.mentionContainer.scrollTop;
        var containerBottom = containerTop + this.mentionContainer.offsetHeight;

        if (itemPos < containerTop) {
          // Scroll up if the item is above the top of the container
          this.mentionContainer.scrollTop = itemPos;
        } else if (itemPos > containerBottom - itemHeight) {
          // scroll down if any part of the element is below the bottom of the container
          this.mentionContainer.scrollTop += itemPos - containerBottom + itemHeight;
        }
      }
    }
  }, {
    key: "getItemData",
    value: function getItemData() {
      var link = this.mentionList.childNodes[this.itemIndex].dataset.link;
      var hasLinkValue = typeof link !== "undefined";
      var itemTarget = this.mentionList.childNodes[this.itemIndex].dataset.target;

      if (hasLinkValue) {
        this.mentionList.childNodes[this.itemIndex].dataset.value = "<a href=\"".concat(link, "\" target=").concat(itemTarget || this.options.linkTarget, ">").concat(this.mentionList.childNodes[this.itemIndex].dataset.value);
      }

      return this.mentionList.childNodes[this.itemIndex].dataset;
    }
  }, {
    key: "onContainerMouseMove",
    value: function onContainerMouseMove() {
      this.suspendMouseEnter = false;
    }
  }, {
    key: "selectItem",
    value: function selectItem() {
      var _this2 = this;

      if (this.itemIndex === -1) {
        return;
      }

      var data = this.getItemData();

      if (data.disabled) {
        return;
      }

      this.options.onSelect(data, function (asyncData) {
        var programmaticInsert = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
        var overriddenOptions = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
        return _this2.insertItem(asyncData, programmaticInsert, overriddenOptions);
      });
      this.hideMentionList();
    }
  }, {
    key: "insertItem",
    value: function insertItem(data, programmaticInsert) {
      var overriddenOptions = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
      var render = data;

      if (render === null) {
        return;
      }

      var options = _objectSpread2(_objectSpread2({}, this.options), overriddenOptions);

      if (!options.showDenotationChar) {
        render.denotationChar = "";
      }

      var insertAtPos;

      if (!programmaticInsert) {
        insertAtPos = this.mentionCharPos;
        this.quill.deleteText(this.mentionCharPos, this.cursorPos - this.mentionCharPos, Quill.sources.USER);
      } else {
        insertAtPos = this.cursorPos;
      }

      var delta = this.quill.insertEmbed(insertAtPos, options.blotName, render, Quill.sources.USER);

      if (options.spaceAfterInsert) {
        this.quill.insertText(insertAtPos + 1, " ", Quill.sources.USER); // setSelection here sets cursor position

        this.quill.setSelection(insertAtPos + 2, Quill.sources.USER);
      } else {
        this.quill.setSelection(insertAtPos + 1, Quill.sources.USER);
      }

      this.hideMentionList();
      return delta;
    }
  }, {
    key: "onItemMouseEnter",
    value: function onItemMouseEnter(e) {
      if (this.suspendMouseEnter) {
        return;
      }

      var index = Number(e.target.dataset.index);

      if (!Number.isNaN(index) && index !== this.itemIndex) {
        this.itemIndex = index;
        this.highlightItem(false);
      }
    }
  }, {
    key: "onDisabledItemMouseEnter",
    value: function onDisabledItemMouseEnter(e) {
      if (this.suspendMouseEnter) {
        return;
      }

      this.itemIndex = -1;
      this.highlightItem(false);
    }
  }, {
    key: "onItemClick",
    value: function onItemClick(e) {
      if (e.button !== 0) {
        return;
      }

      e.preventDefault();
      e.stopImmediatePropagation();
      this.itemIndex = e.currentTarget.dataset.index;
      this.highlightItem();
      this.selectItem();
    }
  }, {
    key: "onItemMouseDown",
    value: function onItemMouseDown(e) {
      e.preventDefault();
      e.stopImmediatePropagation();
    }
  }, {
    key: "renderLoading",
    value: function renderLoading() {
      var renderedLoading = this.options.renderLoading();

      if (!renderedLoading) {
        return;
      }

      if (this.mentionContainer.getElementsByClassName("ql-mention-loading").length > 0) {
        this.showMentionList();
        return;
      }

      this.mentionList.innerHTML = "";
      var loadingDiv = document.createElement("div");
      loadingDiv.className = "ql-mention-loading";
      loadingDiv.innerHTML = this.options.renderLoading();
      this.mentionContainer.append(loadingDiv);
      this.showMentionList();
    }
  }, {
    key: "removeLoading",
    value: function removeLoading() {
      var loadingDiv = this.mentionContainer.getElementsByClassName("ql-mention-loading");

      if (loadingDiv.length > 0) {
        loadingDiv[0].remove();
      }
    }
  }, {
    key: "renderList",
    value: function renderList(mentionChar, data, searchTerm) {
      if (data && data.length > 0) {
        this.removeLoading();
        this.values = data;
        this.mentionList.innerHTML = "";
        var initialSelection = -1;

        for (var i = 0; i < data.length; i += 1) {
          var li = document.createElement("li");
          li.id = 'quill-mention-item-' + i;
          li.className = this.options.listItemClass ? this.options.listItemClass : "";

          if (data[i].disabled) {
            li.className += " disabled";
            li.setAttribute('aria-hidden', 'true');
          } else if (initialSelection === -1) {
            initialSelection = i;
          }

          li.dataset.index = i;
          li.innerHTML = this.options.renderItem(data[i], searchTerm);

          if (!data[i].disabled) {
            li.onmouseenter = this.onItemMouseEnter.bind(this);
            li.onmouseup = this.onItemClick.bind(this);
            li.onmousedown = this.onItemMouseDown.bind(this);
          } else {
            li.onmouseenter = this.onDisabledItemMouseEnter.bind(this);
          }

          li.dataset.denotationChar = mentionChar;
          this.mentionList.appendChild(attachDataValues(li, data[i], this.options.dataAttributes));
        }

        this.itemIndex = initialSelection;
        this.highlightItem();
        this.showMentionList();
      } else {
        this.hideMentionList();
      }
    }
  }, {
    key: "nextItem",
    value: function nextItem() {
      var increment = 0;
      var newIndex;

      do {
        increment++;
        newIndex = (this.itemIndex + increment) % this.values.length;
        var disabled = this.mentionList.childNodes[newIndex].dataset.disabled === "true";

        if (increment === this.values.length + 1) {
          //we've wrapped around w/o finding an enabled item
          newIndex = -1;
          break;
        }
      } while (disabled);

      this.itemIndex = newIndex;
      this.suspendMouseEnter = true;
      this.highlightItem();
    }
  }, {
    key: "prevItem",
    value: function prevItem() {
      var decrement = 0;
      var newIndex;

      do {
        decrement++;
        newIndex = (this.itemIndex + this.values.length - decrement) % this.values.length;
        var disabled = this.mentionList.childNodes[newIndex].dataset.disabled === "true";

        if (decrement === this.values.length + 1) {
          //we've wrapped around w/o finding an enabled item
          newIndex = -1;
          break;
        }
      } while (disabled);

      this.itemIndex = newIndex;
      this.suspendMouseEnter = true;
      this.highlightItem();
    }
  }, {
    key: "containerBottomIsNotVisible",
    value: function containerBottomIsNotVisible(topPos, containerPos) {
      var mentionContainerBottom = topPos + this.mentionContainer.offsetHeight + containerPos.top;
      return mentionContainerBottom > window.pageYOffset + window.innerHeight;
    }
  }, {
    key: "containerRightIsNotVisible",
    value: function containerRightIsNotVisible(leftPos, containerPos) {
      if (this.options.fixMentionsToQuill) {
        return false;
      }

      var rightPos = leftPos + this.mentionContainer.offsetWidth + containerPos.left;
      var browserWidth = window.pageXOffset + document.documentElement.clientWidth;
      return rightPos > browserWidth;
    }
  }, {
    key: "setIsOpen",
    value: function setIsOpen(isOpen) {
      if (this.isOpen !== isOpen) {
        if (isOpen) {
          this.options.onOpen();
        } else {
          this.options.onClose();
        }

        this.isOpen = isOpen;
      }
    }
  }, {
    key: "setMentionContainerPosition",
    value: function setMentionContainerPosition() {
      if (this.options.positioningStrategy === "fixed") {
        this.setMentionContainerPosition_Fixed();
      } else {
        this.setMentionContainerPosition_Normal();
      }
    }
  }, {
    key: "setMentionContainerPosition_Normal",
    value: function setMentionContainerPosition_Normal() {
      var _this3 = this;

      var containerPos = this.quill.container.getBoundingClientRect();
      var mentionCharPos = this.quill.getBounds(this.mentionCharPos);
      var containerHeight = this.mentionContainer.offsetHeight;
      var topPos = this.options.offsetTop;
      var leftPos = this.options.offsetLeft; // handle horizontal positioning

      if (this.options.fixMentionsToQuill) {
        var rightPos = 0;
        this.mentionContainer.style.right = "".concat(rightPos, "px");
      } else {
        leftPos += mentionCharPos.left;
      }

      if (this.containerRightIsNotVisible(leftPos, containerPos)) {
        var containerWidth = this.mentionContainer.offsetWidth + this.options.offsetLeft;
        var quillWidth = containerPos.width;
        leftPos = quillWidth - containerWidth;
      } // handle vertical positioning


      if (this.options.defaultMenuOrientation === "top") {
        // Attempt to align the mention container with the top of the quill editor
        if (this.options.fixMentionsToQuill) {
          topPos = -1 * (containerHeight + this.options.offsetTop);
        } else {
          topPos = mentionCharPos.top - (containerHeight + this.options.offsetTop);
        } // default to bottom if the top is not visible


        if (topPos + containerPos.top <= 0) {
          var overMentionCharPos = this.options.offsetTop;

          if (this.options.fixMentionsToQuill) {
            overMentionCharPos += containerPos.height;
          } else {
            overMentionCharPos += mentionCharPos.bottom;
          }

          topPos = overMentionCharPos;
        }
      } else {
        // Attempt to align the mention container with the bottom of the quill editor
        if (this.options.fixMentionsToQuill) {
          topPos += containerPos.height;
        } else {
          topPos += mentionCharPos.bottom;
        } // default to the top if the bottom is not visible


        if (this.containerBottomIsNotVisible(topPos, containerPos)) {
          var _overMentionCharPos = this.options.offsetTop * -1;

          if (!this.options.fixMentionsToQuill) {
            _overMentionCharPos += mentionCharPos.top;
          }

          topPos = _overMentionCharPos - containerHeight;
        }
      }

      if (topPos >= 0) {
        this.options.mentionContainerClass.split(' ').forEach(function (className) {
          _this3.mentionContainer.classList.add("".concat(className, "-bottom"));

          _this3.mentionContainer.classList.remove("".concat(className, "-top"));
        });
      } else {
        this.options.mentionContainerClass.split(' ').forEach(function (className) {
          _this3.mentionContainer.classList.add("".concat(className, "-top"));

          _this3.mentionContainer.classList.remove("".concat(className, "-bottom"));
        });
      }

      this.mentionContainer.style.top = "".concat(topPos, "px");
      this.mentionContainer.style.left = "".concat(leftPos, "px");
      this.mentionContainer.style.visibility = "visible";
    }
  }, {
    key: "setMentionContainerPosition_Fixed",
    value: function setMentionContainerPosition_Fixed() {
      var _this4 = this;

      this.mentionContainer.style.position = "fixed";
      this.mentionContainer.style.height = null;
      var containerPos = this.quill.container.getBoundingClientRect();
      var mentionCharPos = this.quill.getBounds(this.mentionCharPos);
      var mentionCharPosAbsolute = {
        left: containerPos.left + mentionCharPos.left,
        top: containerPos.top + mentionCharPos.top,
        width: 0,
        height: mentionCharPos.height
      }; //Which rectangle should it be relative to

      var relativeToPos = this.options.fixMentionsToQuill ? containerPos : mentionCharPosAbsolute;
      var topPos = this.options.offsetTop;
      var leftPos = this.options.offsetLeft; // handle horizontal positioning

      if (this.options.fixMentionsToQuill) {
        var rightPos = relativeToPos.right;
        this.mentionContainer.style.right = "".concat(rightPos, "px");
      } else {
        leftPos += relativeToPos.left; //if its off the righ edge, push it back

        if (leftPos + this.mentionContainer.offsetWidth > document.documentElement.clientWidth) {
          leftPos -= leftPos + this.mentionContainer.offsetWidth - document.documentElement.clientWidth;
        }
      }

      var availableSpaceTop = relativeToPos.top;
      var availableSpaceBottom = document.documentElement.clientHeight - (relativeToPos.top + relativeToPos.height);
      var fitsBottom = this.mentionContainer.offsetHeight <= availableSpaceBottom;
      var fitsTop = this.mentionContainer.offsetHeight <= availableSpaceTop;
      var placement;

      if (this.options.defaultMenuOrientation === "top" && fitsTop) {
        placement = "top";
      } else if (this.options.defaultMenuOrientation === "bottom" && fitsBottom) {
        placement = "bottom";
      } else {
        //it doesnt fit either so put it where there's the most space
        placement = availableSpaceBottom > availableSpaceTop ? "bottom" : "top";
      }

      if (placement === "bottom") {
        topPos = relativeToPos.top + relativeToPos.height;

        if (!fitsBottom) {
          //shrink it to fit
          //3 is a bit of a fudge factor so it doesnt touch the edge of the screen
          this.mentionContainer.style.height = availableSpaceBottom - 3 + "px";
        }

        this.options.mentionContainerClass.split(" ").forEach(function (className) {
          _this4.mentionContainer.classList.add("".concat(className, "-bottom"));

          _this4.mentionContainer.classList.remove("".concat(className, "-top"));
        });
      } else {
        topPos = relativeToPos.top - this.mentionContainer.offsetHeight;

        if (!fitsTop) {
          //shrink it to fit
          //3 is a bit of a fudge factor so it doesnt touch the edge of the screen
          this.mentionContainer.style.height = availableSpaceTop - 3 + "px";
          topPos = 3;
        }

        this.options.mentionContainerClass.split(" ").forEach(function (className) {
          _this4.mentionContainer.classList.add("".concat(className, "-top"));

          _this4.mentionContainer.classList.remove("".concat(className, "-bottom"));
        });
      }

      this.mentionContainer.style.top = "".concat(topPos, "px");
      this.mentionContainer.style.left = "".concat(leftPos, "px");
      this.mentionContainer.style.visibility = "visible";
    }
  }, {
    key: "getTextBeforeCursor",
    value: function getTextBeforeCursor() {
      var startPos = Math.max(0, this.cursorPos - this.options.maxChars);
      var textBeforeCursorPos = this.quill.getText(startPos, this.cursorPos - startPos);
      return textBeforeCursorPos;
    }
  }, {
    key: "onSomethingChange",
    value: function onSomethingChange() {
      var _this5 = this;

      var range = this.quill.getSelection();
      if (range == null) return;
      this.cursorPos = range.index;
      var textBeforeCursor = this.getTextBeforeCursor();
      var textOffset = Math.max(0, this.cursorPos - this.options.maxChars);
      var textPrefix = textOffset ? this.quill.getText(textOffset - 1, textOffset) : '';

      var _getMentionCharIndex = getMentionCharIndex(textBeforeCursor, this.options.mentionDenotationChars, this.options.isolateCharacter, this.options.allowInlineMentionChar),
          mentionChar = _getMentionCharIndex.mentionChar,
          mentionCharIndex = _getMentionCharIndex.mentionCharIndex;

      if (hasValidMentionCharIndex(mentionCharIndex, textBeforeCursor, this.options.isolateCharacter, textPrefix)) {
        var mentionCharPos = this.cursorPos - (textBeforeCursor.length - mentionCharIndex);
        this.mentionCharPos = mentionCharPos;
        var textAfter = textBeforeCursor.substring(mentionCharIndex + mentionChar.length);

        if (textAfter.length >= this.options.minChars && hasValidChars(textAfter, this.getAllowedCharsRegex(mentionChar))) {
          if (this.existingSourceExecutionToken) {
            this.existingSourceExecutionToken.abandoned = true;
          }

          this.renderLoading();
          var sourceRequestToken = {
            abandoned: false
          };
          this.existingSourceExecutionToken = sourceRequestToken;
          this.options.source(textAfter, function (data, searchTerm) {
            if (sourceRequestToken.abandoned) {
              return;
            }

            _this5.existingSourceExecutionToken = null;

            _this5.renderList(mentionChar, data, searchTerm);
          }, mentionChar);
        } else {
          if (this.existingSourceExecutionToken) {
            this.existingSourceExecutionToken.abandoned = true;
          }

          this.hideMentionList();
        }
      } else {
        if (this.existingSourceExecutionToken) {
          this.existingSourceExecutionToken.abandoned = true;
        }

        this.hideMentionList();
      }
    }
  }, {
    key: "getAllowedCharsRegex",
    value: function getAllowedCharsRegex(denotationChar) {
      if (this.options.allowedChars instanceof RegExp) {
        return this.options.allowedChars;
      } else {
        return this.options.allowedChars(denotationChar);
      }
    }
  }, {
    key: "onTextChange",
    value: function onTextChange(delta, oldDelta, source) {
      if (source === "user") {
        this.onSomethingChange();
      }
    }
  }, {
    key: "onSelectionChange",
    value: function onSelectionChange(range) {
      if (range && range.length === 0) {
        this.onSomethingChange();
      } else {
        this.hideMentionList();
      }
    }
  }, {
    key: "openMenu",
    value: function openMenu(denotationChar) {
      var selection = this.quill.getSelection(true);
      this.quill.insertText(selection.index, denotationChar);
      this.quill.blur();
      this.quill.focus();
    }
  }]);

  return Mention;
}();

Quill.register("modules/mention", Mention);

export { Mention as default };
