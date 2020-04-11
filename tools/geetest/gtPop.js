/* initGeetest 1.0.0
 * 用于加载id对应的验证码库，并支持宕机模式
 * 暴露 initGeetest 进行验证码的初始化
 * 一般不需要用户进行修改
 */
(function (global, factory) {
	"use strict";
	if (typeof module === "object" && typeof module.exports === "object") {
		// CommonJS
		module.exports = global.document ?
			factory(global, true) :
			function (w) {
				if (!w.document) {
					throw new Error("Geetest requires a window with a document");
				}
				return factory(w);
			};
	} else {
		factory(global);
	}
})(typeof window !== "undefined" ? window : this, function (window, noGlobal) {
	"use strict";
	if (typeof window === 'undefined') {
		throw new Error('Geetest requires browser environment');
	}
	var document = window.document;
	var Math = window.Math;
	var head = document.getElementsByTagName("head")[0];

	function _Object(obj) {
		this._obj = obj;
	}

	_Object.prototype = {
		_each: function (process) {
			var _obj = this._obj;
			for (var k in _obj) {
				if (_obj.hasOwnProperty(k)) {
					process(k, _obj[k]);
				}
			}
			return this;
		}
	};
	function Config(config) {
		var self = this;
		new _Object(config)._each(function (key, value) {
			self[key] = value;
		});
	}

	Config.prototype = {
		api_server: 'api.geetest.com',
		protocol: 'http://',
		type_path: '/gettype.php',
		fallback_config: {
			slide: {
				static_servers: ["static.geetest.com", "dn-staticdown.qbox.me"],
				type: 'slide',
				slide: '/static/js/geetest.0.0.0.js'
			},
			fullpage: {
				static_servers: ["static.geetest.com", "dn-staticdown.qbox.me"],
				type: 'fullpage',
				fullpage: '/static/js/fullpage.0.0.0.js'
			}
		},
		_get_fallback_config: function () {
			var self = this;
			if (isString(self.type)) {
				return self.fallback_config[self.type];
			} else if (self.new_captcha) {
				return self.fallback_config.fullpage;
			} else {
				return self.fallback_config.slide;
			}
		},
		_extend: function (obj) {
			var self = this;
			new _Object(obj)._each(function (key, value) {
				self[key] = value;
			})
		}
	};
	var isNumber = function (value) {
		return (typeof value === 'number');
	};
	var isString = function (value) {
		return (typeof value === 'string');
	};
	var isBoolean = function (value) {
		return (typeof value === 'boolean');
	};
	var isObject = function (value) {
		return (typeof value === 'object' && value !== null);
	};
	var isFunction = function (value) {
		return (typeof value === 'function');
	};
	var callbacks = {};
	var status = {};
	var random = function () {
		return parseInt(Math.random() * 10000) + (new Date()).valueOf();
	};
	var loadScript = function (url, cb) {
		var script = document.createElement("script");
		script.charset = "UTF-8";
		script.async = true;
		script.onerror = function () {
			cb(true);
		};
		var loaded = false;
		script.onload = script.onreadystatechange = function () {
			if (!loaded &&
				(!script.readyState ||
				"loaded" === script.readyState ||
				"complete" === script.readyState)) {

				loaded = true;
				setTimeout(function () {
					cb(false);
				}, 0);
			}
		};
		script.src = url;
		head.appendChild(script);
	};
	var normalizeDomain = function (domain) {
		return domain.replace(/^https?:\/\/|\/$/g, '');
	};
	var normalizePath = function (path) {
		path = path.replace(/\/+/g, '/');
		if (path.indexOf('/') !== 0) {
			path = '/' + path;
		}
		return path;
	};
	var normalizeQuery = function (query) {
		if (!query) {
			return '';
		}
		var q = '?';
		new _Object(query)._each(function (key, value) {
			if (isString(value) || isNumber(value) || isBoolean(value)) {
				q = q + encodeURIComponent(key) + '=' + encodeURIComponent(value) + '&';
			}
		});
		if (q === '?') {
			q = '';
		}
		return q.replace(/&$/, '');
	};
	var makeURL = function (protocol, domain, path, query) {
		domain = normalizeDomain(domain);

		var url = normalizePath(path) + normalizeQuery(query);
		if (domain) {
			url = protocol + domain + url;
		}

		return url;
	};
	var load = function (protocol, domains, path, query, cb) {
		var tryRequest = function (at) {

			var url = makeURL(protocol, domains[at], path, query);
			loadScript(url, function (err) {
				if (err) {
					if (at >= domains.length - 1) {
						cb(true);
					} else {
						tryRequest(at + 1);
					}
				} else {
					cb(false);
				}
			});
		};
		tryRequest(0);
	};
	var jsonp = function (domains, path, config, callback) {
		if (isObject(config.getLib)) {
			config._extend(config.getLib);
			callback(config);
			return;
		}
		if (config.offline) {
			callback(config._get_fallback_config());
			return;
		}
		var cb = "geetest_" + random();
		window[cb] = function (data) {
			if (data.status === 'success') {
				callback(data.data);
			} else if (!data.status) {
				callback(data);
			} else {
				callback(config._get_fallback_config());
			}
			window[cb] = undefined;
			try {
				delete window[cb];
			} catch (e) {
			}
		};
		load(config.protocol, domains, path, {
			gt: config.gt,
			callback: cb
		}, function (err) {
			if (err) {
				callback(config._get_fallback_config());
			}
		});
	};
	var throwError = function (errorType, config) {
		var errors = {
			networkError: '网络错误'
		};
		if (typeof config.onError === 'function') {
			config.onError(errors[errorType]);
		} else {
			throw new Error(errors[errorType]);
		}
	};
	var detect = function () {
		return !!window.Geetest;
	};
	if (detect()) {
		status.slide = "loaded";
	}
	var initGeetest = function (userConfig, callback) {
		var config = new Config(userConfig);
		if (userConfig.https) {
			config.protocol = 'https://';
		} else if (!userConfig.protocol) {
			config.protocol = window.location.protocol + '//';
		}
		jsonp([config.api_server || config.apiserver], config.type_path, config, function (newConfig) {
			var type = newConfig.type;
			var init = function () {
				config._extend(newConfig);
				callback(new window.Geetest(config));
			};
			callbacks[type] = callbacks[type] || [];
			var s = status[type] || 'init';
			if (s === 'init') {
				status[type] = 'loading';
				callbacks[type].push(init);
				load(config.protocol, newConfig.static_servers || newConfig.domains, newConfig[type] || newConfig.path, null, function (err) {
					if (err) {
						status[type] = 'fail';
						throwError('networkError', config);
					} else {
						status[type] = 'loaded';
						var cbs = callbacks[type];
						for (var i = 0, len = cbs.length; i < len; i = i + 1) {
							var cb = cbs[i];
							if (isFunction(cb)) {
								cb();
							}
						}
						callbacks[type] = [];
					}
				});
			} else if (s === "loaded") {
				init();
			} else if (s === "fail") {
				throwError('networkError', config);
			} else if (s === "loading") {
				callbacks[type].push(init);
			}
		});
	};
	window.initGeetest = initGeetest;
	return initGeetest;
});



	var handlerEmbed = function (captchaObj) {
	/*	$("#embed-submit").click(function (e) {
			var validate = captchaObj.getValidate();
			if (!validate) {
				$("#geetestNote")[0].className = "show";
				setTimeout(function () {
					$("#geetestNote")[0].className = "hide";
				}, 2000);
				e.preventDefault();
			}
		});
	*/
		// 将验证码加到id为captcha的元素里，同时会有三个input的值：geetest_challenge, geetest_validate, geetest_seccode
		captchaObj.appendTo("#geePopDiv");
		captchaObj.onReady(function () {
			$("#geePopWait")[0].className = "display";
		});
		// 更多接口参考：http://www.geetest.com/install/sections/idx-client-sdk.html
	};
	$.ajax({
		// 获取id，challenge，success（是否启用failback）
		url: webPathPart +"read.php?mudi=getGeetest&t=" + (new Date()).getTime(), // 加随机数防止缓存
		type: "get",
		dataType: "json",
		success: function (data) {
			console.log(data);
			// 使用initGeetest接口
			// 参数1：配置参数
			// 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
 			if (typeof(geetPopWidth)=="undefined"){
				var scriptArgs;
				try{
					scriptArgs = document.getElementById('geePopBox').getAttribute('data');
				}catch (e){}
				gwPara = ToGetPara(scriptArgs,'geetPopWidth');
				if (gwPara.length > 0){
					geetPopWidth = gwPara;
				}else{
					geetPopWidth = "300px";
				}
			}
			initGeetest({
				width: geetPopWidth,
				gt: data.gt,
				challenge: data.challenge,
				new_captcha: data.new_captcha,
				product: "embed", // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
				offline: !data.success // 表示用户后台检测极验服务器是否宕机，一般不需要关注
				// 更多配置参数请参见：http://www.geetest.com/install/sections/idx-client-sdk.html#config
			}, handlerEmbed);
		}
	});