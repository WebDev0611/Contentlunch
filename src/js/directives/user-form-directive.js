﻿launch.module.directive('userForm', function ($modal, RoleService, UserService, NotificationService) {
	var link = function (scope, element, attrs) {
		var self = this;

		self.forceDirty = false;

		self.init = function() {
			scope.roles = RoleService.query();
		};

		self.discardChanges = function (form) {
			if ($.isFunction(scope.refreshMethod)) {
				scope.refreshMethod(form);
			}
		};

		self.validatePhotoFile = function(file) {
			if (!$.inArray(file.type, scope.photoFileTypes)) {
				NotificationService.error('Invalid File!', 'The file you selected is not supported. You may only upload JPG, PNG, GIF, or BMP images.');
				return false;
			} else if (file.size > 5000000) {
				NotificationService.error('Invalid File!', 'The file you selected is too big. You may only upload images that are 5MB or less.');
				return false;
			}

			return true;
		};

		scope.roles = [];
		scope.photoFile = null;
		scope.isLoading = false;
		scope.isSaving = false;
		scope.creatingNew = false;
		scope.photoFileTypes = ['image/gif', 'image/png', 'image/jpeg', 'image/bmp'];

		scope.cancelEdit = function(form) {
			if (form.$dirty) {
				$modal.open({
					templateUrl: 'confirm-cancel.html',
					controller: [
						'$scope', '$modalInstance', function(scp, instance) {
							scp.save = function () {
								scope.saveUser(form);
								instance.close();
							};
							scp.cancel = function () {
								self.discardChanges(form);
								instance.dismiss('cancel');
							};
						}
					]
				});

				return;
			}

			self.discardChanges(form);
		};

		scope.saveUser = function (form) {
			if (!scope.selectedUser || (!scope.selectedUser.$resolved && scope.selfEditing)) {
				return;
			}

			self.forceDirty = true;
			form.$setDirty();

			var msg = scope.selectedUser.validateAll();
			var isNew = launch.utils.isBlank(scope.selectedUser.id);

			if (!launch.utils.isBlank(msg)) {
				NotificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
				return;
			}

			var method = isNew ? UserService.add : UserService.update;

			scope.isSaving = true;

			method(scope.selectedUser, {
				success: function (r) {
					scope.isSaving = false;

					var successMsg = 'You have successfully saved ' + (scope.selfEditing ? 'your' : r.username + '\'s') + ' user settings!';

					NotificationService.success('Success!', successMsg);

					if ($.isFunction(scope.afterSaveSuccess)) {
						scope.afterSaveSuccess(r, form);
					}
				},
				error: function (r) {
					scope.isSaving = false;

					launch.utils.handleAjaxErrorResponse(r, NotificationService);
				}
			});
		};

		scope.deleteUser = function(form) {
			$modal.open({
				templateUrl: 'confirm-delete.html',
				controller: [
					'$scope', '$modalInstance', function (scp, instance) {
						scp.delete = function () {
							scope.isSaving = true;

							UserService.delete(scope.selectedUser, {
								success: function(r) {
									scope.isSaving = false;

									var successMsg = 'You have successfully deleted ' + r.username + '!';

									NotificationService.success('Success!', successMsg);

									if ($.isFunction(scope.afterSaveSuccess)) {
										scope.afterSaveSuccess(r, form);
									}
								},
								error: function(r) {
									scope.isSaving = false;

									launch.utils.handleAjaxErrorResponse(r, NotificationService);
								}
							});
							instance.close();
						};
						scp.cancel = function () {
							instance.dismiss('cancel');
						};
					}
				]
			});
		};

		scope.uploadPhoto = function (files) {
			if ($.isArray(files) && files.length === 1) {
				return false;
			}

			if (self.validatePhotoFile(files[0])) {
				UserService.savePhoto(scope.selectedUser, files[0], {
					success: function(r) {
						
					},
					error: function(r) {
						
					}
				});

				return true;
			}

			return false;
		};

		scope.errorMessage = function (property, control) {
			if (!control || !control.$dirty) {
				return false;
			}

			return (!scope.selectedUser || (!scope.selectedUser.$resolved && scope.selfEditing)) ? null : scope.selectedUser.validateProperty(property);
		};

		scope.errorState = function (property, control) {
			if (!control || !scope.selectedUser || (!scope.selectedUser.$resolved && scope.selfEditing)) {
				return false;
			}

			if (self.forceDirty) {
				control.$dirty = true;
			}

			control.$invalid = !launch.utils.isBlank(scope.selectedUser.validateProperty(property));

			return (control.$dirty && control.$invalid);
		};

		scope.getStates = function () {
			if (!!scope.selectedUser && !!scope.selectedUser.country) {
				switch (scope.selectedUser.country) {
					case 'Australia':
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
					case 'Canada':
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
		};

		scope.$watch(scope.selectedUser, function (user) {
			scope.creatingNew = (!!user && !launch.utils.isBlank(user.id));
		});

		self.init();
	};

	return {
		link: link,
		scope: {
			selectedUser: '=selectedUser',
			refreshMethod: '=refreshMethod',
			afterSaveSuccess: '=afterSaveSuccess',
			selfEditing: '=selfEditing'
		},
		templateUrl: '/assets/views/user-form.html'
	};
});