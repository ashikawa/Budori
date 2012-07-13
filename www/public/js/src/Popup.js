/**
 * Popup共通処理
 */
(function () {
	
	var global = (function () { return this; }());
	
	
	/**
	 * window.open 用のパラメータ
	 * オブジェクトを string に変更
	 */
	function objectToString(obj, prefix) {
		
		var key,
			isfirst	= true,
			output	= "";
		
		if (!prefix) {
			prefix = ",";
		}
		
		for (key in obj) {
			
			if (!obj.hasOwnProperty(key)) {
				continue;
			}
			
			if (!isfirst) {
				output += prefix;
			}
			
			isfirst = false;
			
			output += key + "=" + obj[key];
		}
		
		return output;
	}

	// DomWindow がブロックされているか判定する
	function isBlocked(domWindow) {
		
		// 左から FireFox&IE, Safari, Chrome(空のDomWindowオブジェクト)
		return (domWindow === null || domWindow === undefined || (domWindow.closed === undefined));
	}
	
	
	
	/**
	 * Popup
	 * @constructor
	 */
	function Popup() {
		
		var params = {
			width		: 1000,
			height		: 1000,
			menubar		: "no",
			toolbar		: "no",
			scrollbars	: "no"
		};
		
		params.left	= (window.screen.width - params.width) / 2;
		params.top	= (window.screen.height - params.height) / 2;
		
		
		this.params		= params;
		this.target		= "_blank";
		this.interval	= 500; // msec
	}
	
	/**
	 * window.open 関数に渡すパラメータ　連想配列
	 * 
	 * @param {string} key 連想配列のキー
	 * @param {string} value 値
	 */
	Popup.prototype.setParam = function (key, value) {
		this.params[key] = value;
	};
	
	/**
	 * ポップアップウインドウを開く
	 */
	Popup.prototype.open = function (url, option) {
		
		var self = this,
			dialog,
			si,
			interval	= this.interval,
			target		= this.target,
			params		= this.params;
		
		// build get paramater
		if (option) {
			url = url + "?" + objectToString(option, "&");
		}
		
		// window open
		dialog	= window.open(url, target, objectToString(params));
		
		if (isBlocked(dialog)) {
			this.onBlock();
			return;
		}
		
		// ウインドウが開いているか監視
		si	= window.setInterval(function () {
			
			if ((dialog !== null) && (dialog.closed)) {
				
				window.clearInterval(si);
				self.onClose();
			}
			
		}, interval);
	};
	
	/**
	 * 実装時に上書き
	 */
	Popup.prototype.onBlock = function () {};
	
	/**
	 * 実装時に上書き
	 */
	Popup.prototype.onClose = function () {};
	
	global.Popup = Popup;
}());