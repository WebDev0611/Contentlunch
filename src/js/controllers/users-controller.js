launch.module.controller('UsersController', [
	'$scope', '$location', '$filter', '$modal', 'UserService', 'RoleService', 'NotificationService', function ($scope, $location, $filter, $modal, userService, roleService, notificationService) {
		var self = this;

		self.forceDirty = false;

		self.loadUsers = function (callback) {
			$scope.users = userService.query(null, {
				success: function (users) {
					$scope.search.applyFilter(true);

					if (!!callback && $.isFunction(callback.success)) {
						callback.success(users);
					}
				},
				error: function(r) {
					if (!!callback && $.isFunction(callback.error)) {
						callback.error(r);
					}
				}
			});
		};

		self.discardChanges = function (form) {
			self.reset(form);
			self.loadUsers();
		};

		self.reset = function (form) {
			$scope.selectedIndex = null;
			$scope.selectedUser = null;
			form.$setPristine();
			self.forceDirty = false;
		};

		$scope.users = [];
		$scope.roles = [];
		$scope.filteredUsers = [];
		$scope.pagedUsers = [];
		$scope.selectedIndex = null;
		$scope.selectedUser = null;

		$scope.search = {
			searchTerm: null,
			searchTermMinLength: 3,
			userStatus: 'active',
			toggleStatus: function(status) {
				this.userStatus = status;
				this.applyFilter(true);
			},
			applyFilter: function(reset) {
				$scope.filteredUsers = $filter('filter')($scope.users, function(user) {
					if (!launch.utils.isBlank($scope.search.searchTerm) && $scope.search.searchTerm.length >= $scope.search.searchTermMinLength) {
						return (launch.utils.isBlank($scope.search.searchTerm) ? true : user.matchSearchTerm($scope.search.searchTerm));
					}

					return ($scope.search.userStatus === 'all' || $scope.search.userStatus === user.active);
				});

				if (reset === true) {
					$scope.pagination.currentPage = 1;
				}

				$scope.pagination.totalItems = $scope.filteredUsers.length;
				$scope.pagination.groupToPages();
			}
		};

		$scope.pagination = {
			totalItems: 0,
			pageSize: 3,
			currentPage: 1,
			currentSort: 'firstName',
			currentSortDirection: 'ASC',
			onPageChange: function(page, form) {
				$scope.selectUser(null, null, form);

				// IF WE WANT TO PAGE FROM THE SERVER, ENTER THAT CODE AND
				// REMOVE THE getPagedUsers FUNCTION BELOW. ALSO, WE'LL NEED
				// TO TWEAK THE WHAT THAT pagination.totalItems IS CALCULATED
				// SUCH THAT THIS VALUE COMES BACK IN THE JSON RESPONSE.
			},
			showPager: function() {
				return (this.totalItems > this.pageSize);
			},
			groupToPages: function() {
				$scope.pagedUsers = [];

				for (var i = 0; i < $scope.filteredUsers.length; i++) {
					if (i % $scope.pagination.pageSize === 0) {
						$scope.pagedUsers[Math.floor(i / $scope.pagination.pageSize)] = [$scope.filteredUsers[i]];
					} else {
						$scope.pagedUsers[Math.floor(i / $scope.pagination.pageSize)].push($scope.filteredUsers[i]);
					}
				}
			}
		};

		self.loadUsers();

		$scope.roles = roleService.query();

		$scope.isSelectedUser = function(user) {
			if (!$scope.selectedUser || !user) {
				return false;
			}

			return (user.id === $scope.selectedUser.id);
		};

		$scope.enterNewUser = function(form) {
			form.$setPristine();
			self.forceDirty = false;
			$scope.selectedIndex = -1;
			$scope.selectedUser = userService.getNewUser();
		};

		$scope.selectUser = function(user, i, form) {
			if (!user || $scope.selectedUser === user) {
				self.reset(form);
			} else {
				$scope.selectedIndex = ((($scope.pagination.currentPage - 1) * $scope.pagination.pageSize) + i);
				$scope.selectedUser = user;
			}
		};

		$scope.cancelEdit = function(form) {
			if (form.$dirty) {
				$modal.open({
					templateUrl: 'form-dirty.html',
					controller: [
						'$scope', '$modalInstance', function(scope, instance) {
							scope.save = function() {
								$scope.saveUser(form);
								instance.close();
							};
							scope.cancel = function() {
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

		$scope.saveUser = function (form) {
			if (!$scope.selectedUser) {
				self.reset(form);
				return;
			}

			self.forceDirty = true;
			form.$setDirty();

			var msg = $scope.selectedUser.validateAll();
			var isNew = launch.utils.isBlank($scope.selectedUser.id);

			if (!launch.utils.isBlank(msg)) {
				notificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
				return;
			}

			var method = isNew ? userService.add : userService.update;

			method($scope.selectedUser, {
				success: function (r) {
					notificationService.success('Success!', 'You have successfully saved user ' + r.id + '!');

					if (isNew) {
						self.loadUsers({
							success: function (users) {
								var index = null;
								var user = $.grep(users, function (u, i) {
									if (r.id === u.id) {
										index = i;
										return true;
									}

									return false;
								});

								if (user.length === 1) {
									$scope.pagination.currentPage = parseInt(index / $scope.pagination.pageSize) + 1;
									$scope.selectUser(user[0], index, form);
								}
							}
						});
					}

					if ($scope.selectedIndex >= 0) {
						$scope.users[$scope.selectedIndex] = r;
						$scope.search.applyFilter(true);
					}
				},
				error: function (r) {
					var err = (!launch.utils.isBlank(r.message)) ? r.message : null;
					var msg = 'Looks like we\'ve encountered an error trying to save this user.';

					if (!launch.utils.isBlank(err)) {
						msg += ' Here is more information:\n\n' + err;
					}

					notificationService.error('Whoops!', msg);
				}
			});
		};

		$scope.errorMessage = function (property, control) {
			if (!control || !control.$dirty) {
				return false;
			}

			return (!$scope.selectedUser) ? null : $scope.selectedUser.validateProperty(property);
		};

		$scope.errorState = function (property, control) {
			if (!control || !$scope.selectedUser) {
				return false;
			}

			if (self.forceDirty) {
				control.$dirty = true;
			}

			control.$invalid = !launch.utils.isBlank($scope.selectedUser.validateProperty(property));

			return (control.$dirty && control.$invalid);
		};

		$scope.getStates = function() {
			if (!!$scope.selectedUser) {
				switch ($scope.selectedUser.country) {
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
	}
]);