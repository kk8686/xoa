(function (container) {
	container.FormatValidator = {
		//是否email格式
		isEmail: function (email) {
			return /^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/.test(email);
		},
		//是否手机号
		isTelNumber: function (mobile) {
			if (mobile.indexOf('+86') == 0) {
				return /^\+86[1][0-9]{10}$/.test(mobile);
			}
			return /^[1+][0-9]{10}$/.test(mobile);
		},
		//是否字母组合
		isAlphabet: function (str, minLength, maxLength) {
			if(!/^[a-zA-Z]+$/.test(str)){
				return false;
			}
			if(minLength){
				return str.length >= minLength && str.length <= maxLength;
			}
			return true;
		},
		//是否数字
		isNumber: function (number, min, max) {
			if(!/^[-+]{0,1}(\d+)[\.]{0,1}(\d+)$/.test(number)){
				return false;
			}
			if(min){
				return number >= min && number <= max;
			}
			return true;
		},
		//是否整数
		isInteger: function (number, min, max) {
			if(!/^[-]{0,1}\d+$/.test(number)){
				return false;
			}
			if(min){
				return number >= min && number <= max;
			}
			return true;
		},
		//是否是正整数
		isPositiveInteger: function (number, min, max) {
			if(!/^\d+$/.test(number)){
				return false;
			}
			if(min){
				return number >= min && number <= max;
			}
			return true;
		},
		//是否字母数字组合
		isAlphabeticCharacters: function (str, minLength, maxLength) {
			if(!/^[0-9a-zA-Z]+$/.test(str)){
				return false;
			}
			if(minLength){
				return str.length >= minLength && str.length <= maxLength;
			}
			return true;
		},
		//是否字母数字下划线组合
		isAlphabeticCharactersAndUnderline: function (str, minLength, maxLength) {
			if(!/^[0-9a-zA-Z_]+$/.test(str)){
				return false;
			}
			if(minLength){
				return str.length >= minLength && str.length <= maxLength;
			}
			return true;
		},
		//是否字母数字下划线组合
		isUrl: function (str) {
			return /^(https?:\/\/)([\da-z\.-]+)\.([a-z\.]{2,6})([/\w \.\-\&\?\=]*)*\/?$/.test(str);
		},
		//是否全中文
		isAllChinese: function (str, minLength, maxLength) {
			if(!/^[\u4e00-\u9fa5]+$/.test(str)){
				return false;
			}
			if(minLength){
				return str.length >= minLength && str.length <= maxLength;
			}
			return true;
		}
	};
})(window);

