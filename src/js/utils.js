launch.utils = {
	isBlank: function(str) {
		var i;

		if (!!str) {
			if (typeof str === 'number' || typeof str === 'boolean' || typeof str === 'object' || typeof str === 'function') {
				return false;
			}

			for (i = 0; i < str.length; i++) {
				if (str.charCodeAt(i) >= 33) {
					return false;
				}
			}
		}

		return true;
	},

	startsWith: function(s1, s2) {
		if (!this.isBlank(s1) && !this.isBlank(s2)) {
			return (s1.toLowerCase().match('^' + s2.toLowerCase()) !== null);
		}

		return false;
	},

	endsWith: function(s1, s2) {
		if (!this.isBlank(s1) && !this.isBlank(s2)) {
			return (s1.toLowerCase().match(s2.toLowerCase() + '$') !== null);
		}

		return false;
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

	handleAjaxErrorResponse: function(response, notificationService) {
		var err = (!launch.utils.isBlank(response.message)) ? response.message : null;
		var type = (!launch.utils.isBlank(response.type)) ? response.type : null;
		var file = (!launch.utils.isBlank(response.file)) ? response.file : null;
		var line = (!launch.utils.isBlank(response.line)) ? response.line : null;
		var msg = 'Looks like we\'ve encountered an error.';
		var title = 'Whoops!';

		if (launch.utils.isBlank(err)) {
			if (!!response.data) {
				err = (!launch.utils.isBlank(response.data.message)) ? response.data.message : null;
				type = (!launch.utils.isBlank(response.data.type)) ? response.data.type : null;
				file = (!launch.utils.isBlank(response.data.file)) ? response.data.file : null;
				line = (!launch.utils.isBlank(response.data.line)) ? response.data.line : null;
			} else if (!!response.data.error) {
				err = (!launch.utils.isBlank(response.data.error.message)) ? response.data.error.message : null;
				type = (!launch.utils.isBlank(response.data.error.type)) ? response.data.error.type : null;
				file = (!launch.utils.isBlank(response.data.error.file)) ? response.data.error.file : null;
				line = (!launch.utils.isBlank(response.data.error.line)) ? response.data.error.line : null;
			}
		}

		if (!launch.utils.isBlank(err)) {
			err = '\n\nMessage: ' + err;
		}

		if (launch.config.DEBUG_MODE) {
			if (!launch.utils.isBlank(type)) { err += '\n\nType: ' + type; }
			if (!launch.utils.isBlank(file)) { err += '\n\nFile: ' + file; }
			if (!launch.utils.isBlank(line)) { err += '\n\nLine: ' + line; }
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

	validateAll: function(obj) {
		if (!obj || !obj.validateProperty) {
			return null;
		}

		var properties = Object.keys(obj);
		var msgs = [];

		for (var i = 0; i < properties.length; i++) {
			var msg = obj.validateProperty(properties[i]);

			if (!launch.utils.isBlank(msg)) {
				msgs.push(msg);
			}
		}

		return msgs.length > 0 ? msgs : null;
	},

	validate: function(obj) {
		if (!obj || !obj.validateProperty) {
			return true;
		}

		var properties = Object.keys(obj);

		for (var i = 0; i < properties.length; i++) {
			if (!launch.utils.isBlank(this.validateProperty(properties[i]))) {
				return false;
			}
		}

		return true;
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

	formatDate: function(date) {
		date = new Date(date);

		var mo = date.getMonth() + 1;
		var dt = date.getDate();
		var yr = date.getFullYear();

		return launch.utils.pad(mo, 2, '0') + '/' + launch.utils.pad(dt, 2, '0') + '/' + yr;
	},

	formatDateTime: function(date) {
		date = new Date(date);

		var dateString = formatDate(date);
		var hr = date.getHours();
		var mi = date.getMinutes();
		var ap = hr > 11 ? 'PM' : 'AM';

		if (hr > 12) {
			hr = hr - 12;
		} else if (hr === 0) {
			hr = 12;
		}

		return dateString + ' ' + launch.utils.pad(hr, 2, '0') + ':' + launch.utils.pad(mi, 2, '0') + ' ' + ap;
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
	}
};