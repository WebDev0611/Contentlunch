launch.utils = {
	isBlank: function(str) {
		var i;

		if (str === null || str === undefined || (typeof str === 'number' && isNaN(str))) {
			return true;
		} else if (typeof str === 'number' || typeof str === 'boolean' || typeof str === 'object' || typeof str === 'function' || $.isArray(str)) {
			return false;
		}

		for (i = 0; i < str.length; i++) {
			if (str.charCodeAt(i) >= 33) {
				return false;
			}
		}

		return true;
	},

	startsWith: function(s1, s2) {
		if (!this.isBlank(s1) && !this.isBlank(s2)) {
			return s1.toLowerCase().indexOf(s2.toLowerCase()) === 0;
		}

		return false;
	},

	endsWith: function(s1, s2) {
		if (!this.isBlank(s1) && !this.isBlank(s2)) {
			return (s1.toLowerCase().match(s2.toLowerCase() + '$') !== null);
		}

		return false;
	},

	truncateAfter: function(str, len) {
		if (isBlank(str)) {
			return '';
		} else if (str.length > len) {
			return str.substring(0, len - 1) + '...';
		}

		return str;
	},

	isValidPattern: function(s, pattern) {
		if (this.isBlank(s)) {
			return false;
		}

		if (!(new RegExp(pattern).test(s))) {
			return false;
		}

		return true;
	},

	isValidEmail: function(s) {
		return this.isValidPattern(s, launch.config.EMAIL_ADDRESS_REGEX);
	},

	validatePassword: function(p) {
		if (typeof p !== 'string' || launch.utils.isBlank(p)) {
			return 'Password must be a string. It is invalid to use a type of ' + typeof p + ' as a password.';
		}

		if (p.length < launch.config.MIN_PASSWORD_LENGTH) {
			return 'Password must be at least ' + launch.config.MIN_PASSWORD_LENGTH + ' characters in length.';
		}

		if (launch.utils.isValidPattern(p, /\s/)) {
			return 'Password cannot contain whitespace including spaces, tabs, or newline characters.';
		}

		var criteriaCount = 0;

		if (launch.utils.isValidPattern(p, /[A-Z]/)) {
			criteriaCount++;
		}
		if (launch.utils.isValidPattern(p, /[a-z]/)) {
			criteriaCount++;
		}
		if (launch.utils.isValidPattern(p, /[0-9]/)) {
			criteriaCount++;
		}
		if (launch.utils.isValidPattern(p, /\W/)) {
			criteriaCount++;
		}

		return (criteriaCount >= 2) ? '' : 'Password must contain at least two of the following: lower-case letter, upper-case letter, number, symbol.';
	},

	titleCase: function(str) {
		var newString = '';

		if (!(this.isBlank(str))) {
			var stringArray = str.toLowerCase().split(/\s+/);
			var lastWordIndex = stringArray.length - 1;

			for (var i = 0; i < stringArray.length; i++) {
				if (!this.isBlank(stringArray[i])) {
					newString += stringArray[i].substring(0, 1).toUpperCase() + stringArray[i].substring(1);
				}

				if (i < lastWordIndex) {
					newString += ' ';
				}
			}
		}

		return newString;
	},

	handleAjaxErrorResponse: function(response, notificationService) {
		var err = (!launch.utils.isBlank(response.message)) ? response.message : null;
		var type = (!launch.utils.isBlank(response.type)) ? response.type : null;
		var file = (!launch.utils.isBlank(response.file)) ? response.file : null;
		var line = (!launch.utils.isBlank(response.line)) ? response.line : null;
		var msg = 'Looks like we\'ve encountered an error.';
		var title = 'Whoops!';

		if (launch.utils.isBlank(err)) {
			if (!!response.data) {
				if (!!response.data.error) {
					err = (!launch.utils.isBlank(response.data.error.message)) ? response.data.error.message : null;
					type = (!launch.utils.isBlank(response.data.error.type)) ? response.data.error.type : null;
					file = (!launch.utils.isBlank(response.data.error.file)) ? response.data.error.file : null;
					line = (!launch.utils.isBlank(response.data.error.line)) ? response.data.error.line : null;
				} else if ($.isArray(response.data.errors)) {
					err = '';

					$.each(response.data.errors, function(i, e) {
						err += e + '\n\n';
					});
				} else {
					err = (!launch.utils.isBlank(response.data.message)) ? response.data.message : null;
					type = (!launch.utils.isBlank(response.data.type)) ? response.data.type : null;
					file = (!launch.utils.isBlank(response.data.file)) ? response.data.file : null;
					line = (!launch.utils.isBlank(response.data.line)) ? response.data.line : null;
				}
			}
		}

		if (!launch.utils.isBlank(err)) {
			err = '\n\nMessage: ' + err;
		}

		if (launch.config.DEBUG_MODE) {
			if (!launch.utils.isBlank(type)) {
				err += '\n\nType: ' + type;
			}
			if (!launch.utils.isBlank(file)) {
				err += '\n\nFile: ' + file;
			}
			if (!launch.utils.isBlank(line)) {
				err += '\n\nLine: ' + line;
			}
		}

		if (!launch.utils.isBlank(err)) {
			msg += ' Here is more information:' + err;
		}

		notificationService.error(title, msg);
	},

	convertFileToByteArray: function(file, onload) {
		var reader = new FileReader();

		// Closure to capture the file information.
		if ($.isFunction(onload)) {
			reader.onload = (function() {
				return function(e) {
					onload(e.target.result.substring(e.target.result.indexOf(',') + 1));
				};
			})(file);
		}

		// Read in the file as a data URL.
		reader.readAsDataURL(file);
	},

	validateAll: function(obj, itemPrefix) {
		if (!obj || !obj.validateProperty) {
			return null;
		}

		var properties = Object.keys(obj);
		var msgs = [];

		for (var i = 0; i < properties.length; i++) {
			var msg = obj.validateProperty(properties[i]);

			if ($.isArray(msg)) {
				$.each(msg, function(i, m) {
					if (!launch.utils.isBlank(itemPrefix)) {
						msgs.push(itemPrefix + ' ' + m);
					} else {
						msgs.push(m);
					}
				});
			} else if (!launch.utils.isBlank(msg)) {
				if (!launch.utils.isBlank(itemPrefix)) {
					msgs.push(itemPrefix + ' ' + msg);
				} else {
					msgs.push(msg);
				}
			}
		}

		return msgs.length > 0 ? msgs : null;
	},

	getStates: function(country) {
		if (!launch.utils.isBlank(country)) {
			switch (country.toUpperCase()) {
				case 'AUSTRALIA':
					return [
						{ value: 'ACT', name: 'Capital Territory' },
						{ value: 'NSW', name: 'New South Wales' },
						{ value: 'NT', name: 'Northern Territory' },
						{ value: 'QLD', name: 'Queensland' },
						{ value: 'SA', name: 'South Australia' },
						{ value: 'TAS', name: 'Tasmania' },
						{ value: 'VIC', name: 'Victoria' },
						{ value: 'WA', name: 'Western Australia' }
					];
				case 'CANADA':
					return [
						{ value: 'AB', name: 'Alberta' },
						{ value: 'BC', name: 'British Columbia' },
						{ value: 'MB', name: 'Manitoba' },
						{ value: 'NB', name: 'New Brunswick' },
						{ value: 'NL', name: 'Newfoundland and Labrador' },
						{ value: 'NS', name: 'Nova Scotia' },
						{ value: 'ON', name: 'Ontario' },
						{ value: 'PE', name: 'Prince Edward Island' },
						{ value: 'QC', name: 'Quebec' },
						{ value: 'SK', name: 'Saskatchewan' },
						{ value: 'NT', name: 'Northwest Territories' },
						{ value: 'NU', name: 'Nunavut' },
						{ value: 'YT', name: 'Yukon' }
					];
				case 'UK':
					return [
						{ value: 'I0', name: 'Aberconwy and Colwyn' },
						{ value: 'I1', name: 'Aberdeen City' },
						{ value: 'I2', name: 'Aberdeenshire' },
						{ value: 'I3', name: 'Anglesey' },
						{ value: 'I4', name: 'Angus' },
						{ value: 'I5', name: 'Antrim' },
						{ value: 'I6', name: 'Argyll and Bute' },
						{ value: 'I7', name: 'Armagh' },
						{ value: 'I8', name: 'Avon' },
						{ value: 'I9', name: 'Ayrshire' },
						{ value: 'IB', name: 'Bath and NE Somerset' },
						{ value: 'IC', name: 'Bedfordshire' },
						{ value: 'IE', name: 'Belfast' },
						{ value: 'IF', name: 'Berkshire' },
						{ value: 'IG', name: 'Berwickshire' },
						{ value: 'IH', name: 'BFPO' },
						{ value: 'II', name: 'Blaenau Gwent' },
						{ value: 'IJ', name: 'Buckinghamshire' },
						{ value: 'IK', name: 'Caernarfonshire' },
						{ value: 'IM', name: 'Caerphilly' },
						{ value: 'IO', name: 'Caithness' },
						{ value: 'IP', name: 'Cambridgeshire' },
						{ value: 'IQ', name: 'Cardiff' },
						{ value: 'IR', name: 'Cardiganshire' },
						{ value: 'IS', name: 'Carmarthenshire' },
						{ value: 'IT', name: 'Ceredigion' },
						{ value: 'IU', name: 'Channel Islands' },
						{ value: 'IV', name: 'Cheshire' },
						{ value: 'IW', name: 'City of Bristol' },
						{ value: 'IX', name: 'Clackmannanshire' },
						{ value: 'IY', name: 'Clwyd' },
						{ value: 'IZ', name: 'Conwy' },
						{ value: 'J0', name: 'Cornwall/Scilly' },
						{ value: 'J1', name: 'Cumbria' },
						{ value: 'J2', name: 'Denbighshire' },
						{ value: 'J3', name: 'Derbyshire' },
						{ value: 'J4', name: 'Derry/Londonderry' },
						{ value: 'J5', name: 'Devon' },
						{ value: 'J6', name: 'Dorset' },
						{ value: 'J7', name: 'Down' },
						{ value: 'J8', name: 'Dumfries and Galloway' },
						{ value: 'J9', name: 'Dunbartonshire' },
						{ value: 'JA', name: 'Dundee' },
						{ value: 'JB', name: 'Durham' },
						{ value: 'JC', name: 'Dyfed' },
						{ value: 'JD', name: 'East Ayrshire' },
						{ value: 'JE', name: 'East Dunbartonshire' },
						{ value: 'JF', name: 'East Lothian' },
						{ value: 'JG', name: 'East Renfrewshire' },
						{ value: 'JH', name: 'East Riding Yorkshire' },
						{ value: 'JI', name: 'East Sussex' },
						{ value: 'JJ', name: 'Edinburgh' },
						{ value: 'JK', name: 'England' },
						{ value: 'JL', name: 'Essex' },
						{ value: 'JM', name: 'Falkirk' },
						{ value: 'JN', name: 'Fermanagh' },
						{ value: 'JO', name: 'Fife' },
						{ value: 'JP', name: 'Flintshire' },
						{ value: 'JQ', name: 'Glasgow' },
						{ value: 'JR', name: 'Gloucestershire' },
						{ value: 'JS', name: 'Greater London' },
						{ value: 'JT', name: 'Greater Manchester' },
						{ value: 'JU', name: 'Gwent' },
						{ value: 'JV', name: 'Gwynedd' },
						{ value: 'JW', name: 'Hampshire' },
						{ value: 'JX', name: 'Hartlepool' },
						{ value: 'HAW', name: 'Hereford and Worcester' },
						{ value: 'JY', name: 'Hertfordshire' },
						{ value: 'JZ', name: 'Highlands' },
						{ value: 'K0', name: 'Inverclyde' },
						{ value: 'K1', name: 'Inverness-Shire' },
						{ value: 'K2', name: 'Isle of Man' },
						{ value: 'K3', name: 'Isle of Wight' },
						{ value: 'K4', name: 'Kent' },
						{ value: 'K5', name: 'Kincardinshire' },
						{ value: 'K6', name: 'Kingston Upon Hull' },
						{ value: 'K7', name: 'Kinross-Shire' },
						{ value: 'K8', name: 'Kirklees' },
						{ value: 'K9', name: 'Lanarkshire' },
						{ value: 'KA', name: 'Lancashire' },
						{ value: 'KB', name: 'Leicestershire' },
						{ value: 'KC', name: 'Lincolnshire' },
						{ value: 'KD', name: 'Londonderry' },
						{ value: 'KE', name: 'Merseyside' },
						{ value: 'KF', name: 'Merthyr Tydfil' },
						{ value: 'KG', name: 'Mid Glamorgan' },
						{ value: 'KI', name: 'Mid Lothian' },
						{ value: 'KH', name: 'Middlesex' },
						{ value: 'KJ', name: 'Monmouthshire' },
						{ value: 'KK', name: 'Moray' },
						{ value: 'KL', name: 'Neath &amp; Port Talbot' },
						{ value: 'KM', name: 'Newport' },
						{ value: 'KN', name: 'Norfolk' },
						{ value: 'KP', name: 'North Ayrshire' },
						{ value: 'KQ', name: 'North East Lincolnshire' },
						{ value: 'KR', name: 'North Lanarkshire' },
						{ value: 'KT', name: 'North Lincolnshire' },
						{ value: 'KU', name: 'North Somerset' },
						{ value: 'KV', name: 'North Yorkshire' },
						{ value: 'KO', name: 'Northamptonshire' },
						{ value: 'KW', name: 'Northern Ireland' },
						{ value: 'KX', name: 'Northumberland' },
						{ value: 'KZ', name: 'Nottinghamshire' },
						{ value: 'L0', name: 'Orkney and Shetland Isles' },
						{ value: 'L1', name: 'Oxfordshire' },
						{ value: 'L2', name: 'Pembrokeshire' },
						{ value: 'L3', name: 'Perth and Kinross' },
						{ value: 'L4', name: 'Powys' },
						{ value: 'L5', name: 'Redcar and Cleveland' },
						{ value: 'L6', name: 'Renfrewshire' },
						{ value: 'L7', name: 'Rhonda Cynon Taff' },
						{ value: 'L8', name: 'Rutland' },
						{ value: 'L9', name: 'Scottish Borders' },
						{ value: 'LB', name: 'Shetland' },
						{ value: 'LC', name: 'Shropshire' },
						{ value: 'LD', name: 'Somerset' },
						{ value: 'LE', name: 'South Ayrshire' },
						{ value: 'LF', name: 'South Glamorgan' },
						{ value: 'LG', name: 'South Gloucesteshire' },
						{ value: 'LH', name: 'South Lanarkshire' },
						{ value: 'LI', name: 'South Yorkshire' },
						{ value: 'LJ', name: 'Staffordshire' },
						{ value: 'LK', name: 'Stirling' },
						{ value: 'LL', name: 'Stockton On Tees' },
						{ value: 'LM', name: 'Suffolk' },
						{ value: 'LN', name: 'Surrey' },
						{ value: 'LO', name: 'Swansea' },
						{ value: 'LP', name: 'Torfaen' },
						{ value: 'LQ', name: 'Tyne and Wear' },
						{ value: 'LR', name: 'Tyrone' },
						{ value: 'LS', name: 'Vale Of Glamorgan' },
						{ value: 'LT', name: 'Wales' },
						{ value: 'LU', name: 'Warwickshire' },
						{ value: 'LV', name: 'West Berkshire' },
						{ value: 'LW', name: 'West Dunbartonshire' },
						{ value: 'LX', name: 'West Glamorgan' },
						{ value: 'LY', name: 'West Lothian' },
						{ value: 'LZ', name: 'West Midlands' },
						{ value: 'M0', name: 'West Sussex' },
						{ value: 'M1', name: 'West Yorkshire' },
						{ value: 'M2', name: 'Western Isles' },
						{ value: 'M3', name: 'Wiltshire' },
						{ value: 'M4', name: 'Wirral' },
						{ value: 'M5', name: 'Worcestershire' },
						{ value: 'M6', name: 'Wrexham' },
						{ value: 'M7', name: 'York' }
					];
				case 'US':
				case 'USA':
					return [
						{ value: 'AL', name: 'Alabama' },
						{ value: 'AK', name: 'Alaska' },
						{ value: 'AZ', name: 'Arizona' },
						{ value: 'AR', name: 'Arkansas' },
						{ value: 'CA', name: 'California' },
						{ value: 'CO', name: 'Colorado' },
						{ value: 'CT', name: 'Connecticut' },
						{ value: 'DE', name: 'Delaware' },
						{ value: 'DC', name: 'District Of Columbia' },
						{ value: 'FL', name: 'Florida' },
						{ value: 'GA', name: 'Georgia' },
						{ value: 'HI', name: 'Hawaii' },
						{ value: 'ID', name: 'Idaho' },
						{ value: 'IL', name: 'Illinois' },
						{ value: 'IN', name: 'Indiana' },
						{ value: 'IA', name: 'Iowa' },
						{ value: 'KS', name: 'Kansas' },
						{ value: 'KY', name: 'Kentucky' },
						{ value: 'LA', name: 'Louisiana' },
						{ value: 'ME', name: 'Maine' },
						{ value: 'MD', name: 'Maryland' },
						{ value: 'MA', name: 'Massachusetts' },
						{ value: 'MI', name: 'Michigan' },
						{ value: 'MN', name: 'Minnesota' },
						{ value: 'MS', name: 'Mississippi' },
						{ value: 'MO', name: 'Missouri' },
						{ value: 'MT', name: 'Montana' },
						{ value: 'NE', name: 'Nebraska' },
						{ value: 'NV', name: 'Nevada' },
						{ value: 'NH', name: 'New Hampshire' },
						{ value: 'NJ', name: 'New Jersey' },
						{ value: 'NM', name: 'New Mexico' },
						{ value: 'NY', name: 'New York' },
						{ value: 'NC', name: 'North Carolina' },
						{ value: 'ND', name: 'North Dakota' },
						{ value: 'OH', name: 'Ohio' },
						{ value: 'OK', name: 'Oklahoma' },
						{ value: 'OR', name: 'Oregon' },
						{ value: 'PA', name: 'Pennsylvania' },
						{ value: 'RI', name: 'Rhode Island' },
						{ value: 'SC', name: 'South Carolina' },
						{ value: 'SD', name: 'South Dakota' },
						{ value: 'TN', name: 'Tennessee' },
						{ value: 'TX', name: 'Texas' },
						{ value: 'UT', name: 'Utah' },
						{ value: 'VT', name: 'Vermont' },
						{ value: 'VA', name: 'Virginia' },
						{ value: 'WA', name: 'Washington' },
						{ value: 'WV', name: 'West Virginia' },
						{ value: 'WI', name: 'Wisconsin' },
						{ value: 'WY', name: 'Wyoming' }
					];
				default:
					return [];
			}
		}

		return [];
	},

	getState: function(country, stateCode) {
		if (launch.utils.isBlank(stateCode)) {
			return null;
		}

		if ($.isPlainObject(stateCode) && !launch.utils.isBlank(stateCode.name) && !launch.utils.isBlank(stateCode.value)) {
			return stateCode;
		}

		var states = launch.utils.getStates(country);
		var state = $.grep(states, function(s, i) { return (s.value.toLowerCase() === stateCode.toLowerCase()); });

		return (state.length === 1) ? state[0] : null;
	},

	formatDate: function(date) {
		if (launch.utils.isBlank(date) || (Object.prototype.toString.call(date) === '[object Date]') && isNaN(date.getTime())) {
			return '';
		}

		return moment(new Date(date)).local().format('MM/DD/YYYY');
	},

	formatDateTime: function(date) {
		if (launch.utils.isBlank(date) || (Object.prototype.toString.call(date) === '[object Date]') && isNaN(date.getTime())) {
			return '';
		}

		return moment(new Date(date)).local().format('MM/DD/YYYY hh:mm:ss A');
	},

	isValidDate: function(date) {
		return moment(date).isValid();
	},

	sortByDate: function(a, b) {
		var aDate = new Date(a);
		var bDate = new Date(b);

		return ((aDate < bDate) ? 1 : ((aDate > bDate) ? -1 : 0));
	},

	pad: function(val, size, padChar) {
		var str = new String(val);
		var pc = new String(padChar);

		return str.length < size ? launch.utils.pad(pc + str, size) : str;
	},

	formatCurrency: function(amount) {
		if (launch.utils.isBlank(amount) || isNaN(amount)) {
			return '$0.00';
		}

		return '$' + parseFloat(amount).toFixed(2);
	},

	getPropertyErrorMessage: function(model, property, control) {
		if (!control || !control.$dirty) {
			return null;
		}

		return (!model || !$.isFunction(model.validateProperty)) ? null : model.validateProperty(property);
	},

	isPropertyValid: function(model, property, control, forceDirty) {
		if (!control || !model) {
			return false;
		}

		if (forceDirty) {
			control.$dirty = true;
		}

		if ($.isFunction(model.validateProperty)) {
			control.$invalid = !launch.utils.isBlank(model.validateProperty(property));
		}

		return (control.$dirty && control.$invalid);
	},

	newGuid: function() {
		var fourHex = function() {
			return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
		};

		return fourHex() + fourHex() + '-' + fourHex() + '-' + fourHex() + '-' + fourHex() + '-' + fourHex() + fourHex() + fourHex();
	},

	selectAllText: function(element) {
		var range = document.createRange();
		range.selectNodeContents(element);
		var sel = window.getSelection();
		sel.removeAllRanges();
		sel.addRange(range);
	},

	getConnectionProviderIconClass: function(provider) {
		if (launch.utils.isBlank(provider)) {
			return null;
		}

		switch (provider.toLowerCase()) {
			case 'blogspot':
				return 'cl-icon-blogspot';
			case 'dropbox':
				return 'fa fa-dropbox';
			case 'facebook':
				return 'fa fa-facebook';
			case 'google':
				return 'cl-icon-google-plus';
			case 'google-plus':
				return 'fa fa-google-plus';
			case 'hubspot':
				return 'cl-icon-hubspot';
			case 'linkedin':
				return 'fa fa-linkedin';
			case 'papershare':
				return 'cl-icon-papershare';
			case 'salesforce':
				return 'cl-icon-salesforce';
			case 'slideshare':
				return 'cl-icon-slideshare';
			case 'soundcloud':
				return 'cl-icon-soundcloud';
			case 'tumblr':
				return 'fa fa-tumblr';
			case 'twitter':
				return 'fa fa-twitter';
			case 'trapit':
				return 'cl-icon-trapit';
			case 'wordpress':
				return 'cl-icon-wordpress';
			case 'youtube':
				return 'fa fa-youtube';
			default:
				return 'fa fa-question';
		}
	},

	getContentTypeIconClass: function(contentType) {
		if (launch.utils.isBlank(contentType)) {
			return null;
		}

		var ct = (!launch.utils.isBlank(contentType.contentType)) ? contentType.contentType.toLowerCase() : contentType.toLowerCase();

		switch (ct) {
			case 'audio-recording':
				return 'fa fa-volume-up';
			case 'blog-post':
				return 'cl-icon cl-icon-content-type-blog-post';
			case 'casestudy':
			case 'case_study':
			case 'case-study':
				return 'cl-icon cl-icon-content-type-casestudy';
			case 'ebook':
				return 'fa fa-book';
			case 'email':
				return 'fa fa-envelope';
			case 'facebook-post':
				return 'fa fa-facebook';
			//case 'feature-article':
			//	return 'fa fa-';
			case 'google-drive-doc':
				return 'cl-icon cl-icon-google-drive';
			case 'google-plus-update':
				return 'fa fa-google-plus';
			case 'newsletter':
			//case 'landing-page':
			//	return 'fa fa-';
			case 'linkedin-update':
				return 'fa fa-linkedin';
			case 'photo':
				return 'fa fa-picture-o';
			//case 'salesforce-asset':
			//	return 'fa fa-';
			//case 'sales-letter':
			//	return 'fa fa-';
			//case 'sellsheet-content':
			//	return 'fa fa-';
			case 'tweet':
				return 'fa fa-twitter';
			case 'video':
				return 'fa fa-video-camera';
			//case 'website-page':
			//	return 'fa fa-';
			case 'whitepaper':
				return 'cl-icon cl-icon-content-type-whitepaper';
			//case 'workflow-email':
			//	return 'fa fa-';
			default:
				return 'fa fa-question';
		}
	},

	getWorkflowIconCssClass: function(stage) {
		if (launch.utils.isBlank(stage)) {
			return null;
		}

		var cs = (!!stage.currentStep && !launch.utils.isBlank(stage.currentStep())) ? stage.currentStep().toLowerCase() : stage.toLowerCase();

		switch (cs) {
			case 'concept':
				return 'cl-icon cl-icon-workflow-concept';
			case 'create':
				return 'cl-icon cl-icon-workflow-create';
			case 'edit':
				return 'cl-icon cl-icon-workflow-review';
			case 'approve':
				return 'cl-icon cl-icon-workflow-approve';
			case 'launch':
				return 'cl-icon cl-icon-workflow-launch';
			case 'promote':
				return 'cl-icon cl-icon-workflow-promote';
			case 'archive':
				return 'cl-icon cl-icon-workflow-archive';
			default:
				return 'fa fa-question';
		}
	},

	formatContentTypeItem: function(item, element, context) {
		return '<span class="' + launch.utils.getContentTypeIconClass(item.id) + '"></span> <span>' + item.text + '</span>';
	},

	formatCampaignItem: function(item, element, context) {
		return '<span class="campaign-dot" style="background-color: ' + $(item.element).data('color') + '"></span> <span>' + item.text + '</span>';
	},

	formatBuyingStageItem: function(item, element, context) {
		return '<span class="cl-icon cl-icon-personas-' + item.id + '"></span> <span>' + item.text + '</span>';
	},

	formatStepItem: function(item, element, context) {
		return '<span class="' + launch.utils.getWorkflowIconCssClass(item.id) + '"></span> <span>' + item.text + '</span>';
	},

	formatContentConnectionItem: function(item, element, context) {
		return '<span class="cl-icon ' + launch.utils.getConnectionProviderIconClass(item.text.toLowerCase()) + '"></span> <span>' + item.text + '</span>';
	},

	formatDocumentTypeItem: function(item, element, context) {
		return '<span class="cl-icon ' + launch.utils.getFileTypeCssClass(item.id) + '"></span> <span>' + item.text + '</span>';
	},

	formatDocumentUploaderItem: function(item, element, context) {
		return '<span class="cl-icon"></span> <span>' + item.text + '</span>';
	},

	getUserById: function(users, id) {
		if (!$.isArray(users) || users.length === 0 || launch.utils.isBlank(id)) {
			return null;
		}

		var user = $.grep(users, function(u) { return u.id === id; });

		return (user.length === 1) ? user[0] : null;
	},

	getFileTypeCssClass: function(fileExtension) {
		console.log(fileExtension);
		if (launch.utils.isBlank(fileExtension)) {
			return null;
		}

		switch (fileExtension.toLowerCase()) {
			case 'jpg':
			case 'gif':
			case 'tif':
			case 'tiff':
			case 'png':
			case 'jpeg':
			case 'image':
				return 'cl-icon-file-image';
			case 'avi':
			case 'mp4':
			case 'mpg':
			case 'wmv':
			case 'mov':
			case 'video':
				return 'cl-icon-file-video';
			case 'wav':
			case 'mp3':
			case 'wma':
			case 'audio':
				return 'cl-icon-file-audio';
			case 'pdf':
				return 'cl-icon-file-pdf';
			case 'doc':
			case 'docx':
			case 'document':
				return 'cl-icon-file-ms-word';
			case 'xls':
			case 'xlsx':
			case 'spreadsheet':
				return 'cl-icon-file-ms-excel';
			case 'ppt':
			case 'pptx':
				return 'cl-icon-file-ms-powerpoint';
			default:
				return null;
		}
	},

	// Classify file type to match document types options
	// audio, document, image, pdf, ppt, spreadsheet, video, other
	mediaTypeMap: function(media_type, extension) {
		switch (media_type) {
			case 'text':
				fileType = 'document';
				break;
			case 'application':
				// Check extension
				switch (extension.toLowerCase()) {
					case 'pdf':
						fileType = 'pdf';
						break;
					case 'pot':
					case 'potm':
					case 'potx':
					case 'pps':
					case 'ppsm':
					case 'ppsx':
					case 'ppt':
					case 'pptm':
					case 'pptx':
						fileType = 'ppt';
						break;
					case '123':
					case 'accdb':
					case 'accde':
					case 'accdr':
					case 'accdt':
					case 'nb':
					case 'numbers':
					case 'ods':
					case 'ots':
					case 'sdc':
					case 'xl':
					case 'xlr':
					case 'xls':
					case 'xlsb':
					case 'xlsm':
					case 'xlsx':
					case 'xlt':
					case 'xltm':
					case 'xltx':
					case 'xlw':
						fileType = 'spreadsheet';
						break;
					default:
						fileType = 'document';
				}
				break;
			case 'audio':
				fileType = 'audio';
				break;
			case 'image':
				fileType = 'image';
				break;
			case 'video':
				fileType = 'video';
				break;
			default:
				fileType = 'other';
		}
		return fileType;
	},

	insertComment: function(message, id, user, service, notificationService, callback) {
		var comment = new launch.Comment();

		comment.id = null;
		comment.comment = message;
		comment.itemId = id;
		comment.commentDate = launch.utils.formatDateTime(new Date());
		comment.isGuestComment = !launch.utils.isBlank(user.accessCode);
		comment.commentor = {
			id: user.id
		};

		var msg = launch.utils.validateAll(comment);

		if (!launch.utils.isBlank(msg)) {
			notificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
			return;
		}

		service.insertComment((launch.utils.isBlank(user.accessCode) ? user.account.id : user.accountId), comment, callback);
	}
};
