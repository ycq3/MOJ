webpackJsonp([14],{

/***/ 187:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(261)
}
var normalizeComponent = __webpack_require__(2)
/* script */
var __vue_script__ = __webpack_require__(263)
/* template */
var __vue_template__ = __webpack_require__(264)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = "data-v-73d5c58b"
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources\\assets\\js\\components\\exam\\examProblemDetail.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-73d5c58b", Component.options)
  } else {
    hotAPI.reload("data-v-73d5c58b", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 261:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(262);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(8)("2e134c9b", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-73d5c58b\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./examProblemDetail.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-73d5c58b\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./examProblemDetail.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 262:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(5)(false);
// imports


// module
exports.push([module.i, "\n.container strong[data-v-73d5c58b] {\n  text-indent: 2em;\n}\n.modal-dialog[data-v-73d5c58b] {\n  margin: 80px auto;\n}\n.overflow[data-v-73d5c58b] {\n  overflow-x: scroll;\n}\ntable th[data-v-73d5c58b], table td[data-v-73d5c58b] {\n  text-align: center;\n}\n.overflow pre[data-v-73d5c58b] {\n  text-align: left;\n}\n", ""]);

// exports


/***/ }),

/***/ 263:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
  name: "examProblemDetail",
  data: function data() {
    return {
      editorShow: false,
      showHistory: 'History',
      editorCode: '',
      editorBtn: 'Show Editor',
      subHistoryTitle: 'Submit History',
      showCodeTitle: 'Show Code',
      showCodeText: {
        code: '#include<iostream>\n' + 'using namespace std;\n' + 'int main(){\n' + '    int a,b,c;\n' + '    while(~scanf("%d%d%d",&a,&b,&c)){\n' + '        printf("%d\\n",a+b+c);\n' + '    }\n' + '}',
        copy: 'Copy'
      },
      subTableText: {
        status: 'Status',
        time: 'Time',
        mem: 'Mem.',
        submitTime: 'Submit Time',
        language: 'Language'
      },
      p_title: '',
      p_describe: '',
      p_input: '',
      p_output: '',
      p_sampleinput: '',
      p_sampleoutput: '',
      p_hint: '',
      p_time: '',
      p_memory: '',
      p_other_time: '',
      p_other_memory: ''
    };
  },

  methods: {
    show: function show() {
      this.editorShow = !this.editorShow;
      if (this.editorShow == true) {
        $("#pro").addClass("float-left").addClass("col-lg-4");
        this.editorBtn = 'Close Editor';
      } else {
        $("#pro").removeClass("float-left").removeClass("col-lg-4");
        this.editorBtn = 'Show Editor';
      }
    },
    clear: function clear() {
      this.editorCode = '';
    },
    copy: function copy() {
      $(".modal-footer button").removeClass("btn-default").addClass("btn-danger");
    },

    //加载数据
    load: function load() {
      var _this = this;

      //获取问题详情
      axios.post('/problem/id/' + this.$route.params.id).then(function (response) {
        var data = response.data;
        _this.p_title = data['title']; //标题
        _this.p_describe = data['describe']; //问题藐视
        _this.p_input = data['input']; //输入描述
        _this.p_output = data['output']; //输出描述
        _this.p_sampleinput = data['sampleinput']; //输入样例
        _this.p_sampleoutput = data['sampleoutput']; //输出样例
        _this.p_hint = data['hint']; //提示
        _this.p_time = data['time']; //C时间限制
        _this.p_memory = data['memory']; //C内存限制
        _this.p_other_time = data['other_time']; //其他语言时间限制
        _this.p_other_memory = data['other_memory']; //其他语言内存限制
      });
    }
  },
  created: function created() {
    this.load();
  }
});

/***/ }),

/***/ 264:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "container" }, [
    _c("div", { attrs: { id: "pro" } }, [
      _c("div", { attrs: { id: "head" } }, [
        _c("div", { staticClass: "row" }, [
          _c("h1", { staticClass: "col text-center" }, [
            _vm._v(_vm._s(_vm.p_title))
          ])
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "row" }, [
          _c("div", { staticClass: "col text-center" }, [
            _c("small", [
              _vm._v(
                "Time Limit: " +
                  _vm._s(_vm.p_time) +
                  "/" +
                  _vm._s(_vm.p_other_time) +
                  " MS (C/Others)  "
              )
            ]),
            _vm._v(" "),
            _c("small", [
              _vm._v(
                " Memory Limit: " +
                  _vm._s(_vm.p_memory) +
                  "/" +
                  _vm._s(_vm.p_other_memory) +
                  " K (C/Others)"
              )
            ])
          ])
        ])
      ]),
      _vm._v(" "),
      _c("div", { attrs: { id: "pDescription" } }, [
        _vm._m(0),
        _vm._v(" "),
        _c("div", {
          staticClass: "row",
          domProps: { innerHTML: _vm._s(_vm.p_describe) }
        })
      ]),
      _vm._v(" "),
      _c("div", { attrs: { id: "input" } }, [
        _vm._m(1),
        _vm._v(" "),
        _c("div", {
          staticClass: "row",
          domProps: { innerHTML: _vm._s(_vm.p_input) }
        })
      ]),
      _vm._v(" "),
      _c("div", { attrs: { id: "output" } }, [
        _vm._m(2),
        _vm._v(" "),
        _c("div", {
          staticClass: "row",
          domProps: { innerHTML: _vm._s(_vm.p_output) }
        })
      ]),
      _vm._v(" "),
      _c("div", { attrs: { id: "sInput" } }, [
        _vm._m(3),
        _vm._v(" "),
        _c("div", { staticClass: "row" }, [
          _c("pre", { domProps: { innerHTML: _vm._s(_vm.p_sampleinput) } })
        ])
      ]),
      _vm._v(" "),
      _c("div", { attrs: { id: "sOutput" } }, [
        _vm._m(4),
        _vm._v(" "),
        _c("div", { staticClass: "row" }, [
          _c("pre", { domProps: { innerHTML: _vm._s(_vm.p_sampleoutput) } })
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "row" }, [
        _c("div", { staticClass: "col text-center" }, [
          _c(
            "button",
            {
              staticClass: "btn btn-default",
              on: {
                click: function($event) {
                  _vm.show()
                }
              }
            },
            [_vm._v(_vm._s(_vm.editorBtn))]
          ),
          _vm._v(" "),
          _c(
            "button",
            {
              staticClass: "btn btn-default",
              attrs: { "data-toggle": "modal", "data-target": "#subHistory" }
            },
            [_vm._v(_vm._s(_vm.showHistory))]
          )
        ])
      ])
    ]),
    _vm._v(" "),
    _vm.editorShow
      ? _c(
          "div",
          {
            staticClass: "col-md-8 col-xs-12 float-right",
            attrs: { id: "edit" }
          },
          [
            _c("div", [
              _vm._m(5),
              _vm._v(" "),
              _c(
                "button",
                {
                  staticClass: "btn btn-default float-right",
                  on: {
                    click: function($event) {
                      _vm.show()
                    }
                  }
                },
                [_vm._v("Close")]
              ),
              _vm._v(" "),
              _c("div", { staticClass: "clearfix" })
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "row" }, [
              _c("textarea", {
                directives: [
                  {
                    name: "model",
                    rawName: "v-model",
                    value: _vm.editorCode,
                    expression: "editorCode"
                  }
                ],
                staticClass: "col-lg-12",
                attrs: { rows: "20" },
                domProps: { value: _vm.editorCode },
                on: {
                  input: function($event) {
                    if ($event.target.composing) {
                      return
                    }
                    _vm.editorCode = $event.target.value
                  }
                }
              })
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "row" }, [
              _c("form", { staticClass: "col text-center" }, [
                _c(
                  "button",
                  {
                    staticClass: "btn btn-default",
                    on: {
                      click: function($event) {
                        _vm.clear()
                      }
                    }
                  },
                  [_vm._v("Clear")]
                ),
                _vm._v(" "),
                _c("input", {
                  staticClass: "btn btn-default",
                  attrs: { type: "submit", value: "Submit" }
                })
              ])
            ])
          ]
        )
      : _vm._e(),
    _vm._v(" "),
    _c(
      "div",
      {
        staticClass: "modal fade",
        attrs: {
          id: "subHistory",
          tabindex: "-1",
          role: "dialog",
          "aria-labelledby": "myModalLabel",
          "aria-hidden": "true"
        }
      },
      [
        _c("div", { staticClass: "modal-dialog" }, [
          _c("div", { staticClass: "modal-content" }, [
            _c("div", { staticClass: "modal-header" }, [
              _c("h4", { staticClass: "modal-title" }, [
                _vm._v(
                  "\n            " +
                    _vm._s(_vm.subHistoryTitle) +
                    "\n          "
                )
              ]),
              _vm._v(" "),
              _c(
                "button",
                {
                  staticClass: "close",
                  attrs: {
                    type: "button",
                    "data-dismiss": "modal",
                    "aria-hidden": "true"
                  }
                },
                [_vm._v("×\n          ")]
              )
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "modal-body overflow" }, [
              _c("table", { staticClass: "table" }, [
                _c("thead", [
                  _c(
                    "tr",
                    _vm._l(_vm.subTableText, function(value) {
                      return _c("th", [_vm._v(_vm._s(value))])
                    })
                  )
                ]),
                _vm._v(" "),
                _vm._m(6)
              ])
            ])
          ])
        ])
      ]
    ),
    _vm._v(" "),
    _c(
      "div",
      {
        staticClass: "modal fade",
        attrs: {
          id: "showCode",
          tabindex: "-1",
          role: "dialog",
          "aria-labelledby": "myModalLabel",
          "aria-hidden": "true"
        }
      },
      [
        _c("div", { staticClass: "modal-dialog" }, [
          _c("div", { staticClass: "modal-content" }, [
            _c("div", { staticClass: "modal-header" }, [
              _c("h4", { staticClass: "modal-title" }, [
                _vm._v(
                  "\n            " + _vm._s(_vm.showCodeTitle) + "\n          "
                )
              ]),
              _vm._v(" "),
              _c(
                "button",
                {
                  staticClass: "close",
                  attrs: {
                    type: "button",
                    "data-dismiss": "modal",
                    "aria-hidden": "true"
                  }
                },
                [_vm._v("×\n          ")]
              )
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "modal-body overflow" }, [
              _c("code", [_c("pre", [_vm._v(_vm._s(_vm.showCodeText.code))])])
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "modal-footer" }, [
              _c(
                "button",
                {
                  staticClass: "btn btn-default",
                  on: {
                    click: function($event) {
                      _vm.copy()
                    }
                  }
                },
                [_vm._v(_vm._s(_vm.showCodeText.copy))]
              )
            ])
          ])
        ])
      ]
    )
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "row" }, [
      _c("h3", [_vm._v("Problem Description:")])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "row" }, [_c("h3", [_vm._v("Input:")])])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "row" }, [_c("h3", [_vm._v("Output:")])])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "row" }, [
      _c("h3", [_vm._v("Sample Input:")])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "row" }, [
      _c("h3", [_vm._v("Sample Output:")])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("select", { staticClass: "float-left" }, [
      _c("option", { attrs: { selected: "selected" } }, [_vm._v("C++")]),
      _vm._v(" "),
      _c("option", [_vm._v("Java")])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("tbody", [
      _c("tr", [
        _c("td", [_vm._v("AC")]),
        _vm._v(" "),
        _c("td", [_vm._v("111")]),
        _vm._v(" "),
        _c("td", [_vm._v("111")]),
        _vm._v(" "),
        _c("td", [_vm._v("111")]),
        _vm._v(" "),
        _c("td", [
          _c(
            "a",
            {
              attrs: {
                href: "#",
                "data-toggle": "modal",
                "data-dismiss": "modal",
                "data-target": "#showCode"
              }
            },
            [_vm._v("C++")]
          )
        ])
      ])
    ])
  }
]
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-73d5c58b", module.exports)
  }
}

/***/ })

});