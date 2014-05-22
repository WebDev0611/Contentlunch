launch.module.controller('ContentController', [
	'$scope', '$routeParams', '$filter', '$location', '$modal', 'AuthService', 'UserService', 'ContentSettingsService', 'ContentService', 'ConnectionService', 'CampaignService', 'TaskService', 'NotificationService', function ($scope, $routeParams, $filter, $location, $modal, authService, userService, contentSettingsService, contentService, connectionService, campaignService, taskService, notificationService) {
		var self = this;

		self.loggedInUser = null;
		self.replaceFile = false;
		self.contentId = null;

		self.ajaxHandler = {
			success: function (r) {

			},
			error: function (r) {
				launch.utils.handleAjaxErrorResponse(r, notificationService);
			}
		};

		self.init = function () {
			self.loggedInUser = authService.userInfo();
			self.refreshContent();

			$scope.contentConnections = connectionService.queryContentConnections(self.loggedInUser.account.id, self.ajaxHandler);
			$scope.contentTypes = contentService.getContentTypes(self.ajaxHandler);
			$scope.users = userService.getForAccount(self.loggedInUser.account.id, self.ajaxHandler);
			$scope.campaigns = campaignService.query(self.loggedInUser.account.id, self.ajaxHandler);
			$scope.contentSettings = contentSettingsService.get(self.loggedInUser.account.id, {
				success: function (r) {
					if ($.isArray($scope.contentSettings.personaProperties)) {
						$scope.buyingStages = $scope.contentSettings.buyingStages();
					}
				},
				error: self.ajaxHandler.error
			});
		}

		self.refreshContent = function () {
			self.contentId = parseInt($routeParams.contentId);

			if (isNaN(self.contentId)) {
				$scope.content = contentService.getNewContent(self.loggedInUser);
				$scope.isNewContent = true;
			} else {
				// NEED TO PAUSE HERE DUE TO A RACE SITUATION BETWEEN TRYING TO FETCH CONTENT CONNECTIONS
				// AND THE CONTENT ITEM. THE SELECT2 DROP-DOWN NEEDS TO GET ITS OPTIONS IN PLACE BEFORE WE
				// SET THE MODEL FOR THE CONTROL. COMPLETE HACK DUE TO LIMITATIONS OF THE CONTROL.
				if (!$scope.contentConnections || !$scope.contentConnections.$resolved) {
					window.setTimeout(self.refreshContent, 200);
					return;
				}

				$scope.content = contentService.get(self.loggedInUser.account.id, self.contentId, {
					success: function (r) {
						$scope.showRichTextEditor = $scope.content.contentType.allowText();
						$scope.showAddFileButton = $scope.content.contentType.allowFile();

						$scope.contentConnectionIds = $.map($scope.content.accountConnections, function (cc) { return parseInt(cc.id).toString(); });
						$scope.contentTags = ($.isArray($scope.content.tags)) ? $scope.content.tags.join(',') : null;

						// TODO: GET ATTACHMENTS FROM API!!
						$scope.contentAttachments = [1, 2, 3, 4, 5];
					},
					error: function (r) {
						launch.utils.handleAjaxErrorResponse(r, notificationService);
					}
				});
				$scope.isNewContent = false;
			}
		};

		self.updateContentConnection = function() {
			var contentConnectionIds = $.map($scope.contentConnectionIds, function (id) { return parseInt(id); });
			var contentConnections = $.grep($scope.contentConnections, function (cc) { return $.inArray(cc.id, contentConnectionIds) >= 0; });

			$scope.content.accountConnections = contentConnections;
		};

		$scope.content = null;
		$scope.contentAttachments = null;
		$scope.contentTypes = null;
		$scope.contentSettings = null;
		$scope.contentConnections = null;
		$scope.campaigns = null;
		$scope.users = null;
		$scope.buyingStages = null;
		$scope.isNewContent = true;
		$scope.forceDirty = false;
		$scope.contentConnectionIds = null;
		$scope.contentTags = null;
		$scope.showRichTextEditor = false;
		$scope.showAddFileButton = false;
		$scope.isUploading = false;
		$scope.percentComplete = 0;
		$scope.defaultTaskGroup = null;

		$scope.formatContentTypeItem = launch.utils.formatContentTypeItem;
		$scope.formatCampaignItem = launch.utils.formatCampaignItem;
		$scope.formatContentConnectionItem = launch.utils.formatContentConnectionItem;
		$scope.formatBuyingStageItem = launch.utils.formatBuyingStageItem;

		$scope.formatUserItem = function (item, element, context) {
			var user = $.grep($scope.users, function (u, i) { return u.id === parseInt(item.id); });
			var style = (user.length === 1 && !launch.utils.isBlank(user[0].image)) ? ' style="background-image: ' + user[0].imageUrl() + '"' : '';

			return '<span class="user-image user-image-small"' + style + '></span> <span>' + item.text + '</span>';
		};

		$scope.showPublishingGuidelines = function() {
			$modal.open({
				templateUrl: 'publishing-guidelines.html',
				controller: [
					'$scope', '$modalInstance', function (scope, instance) {
						scope.publishingGuidelines = $scope.contentSettings.publishingGuidelines;
						scope.ok = function() {
							instance.dismiss('cancel');
						};
					}
				]
			});
		};

		$scope.analyzeContent = function() {
			notificationService.info('WARNING!', 'THIS IS NOT YET IMPLEMENTED!!');
		};

		$scope.saveContent = function (callback) {
			if (!$scope.content || $scope.content.$resolved === false) {
				return;
			}

			$scope.forceDirty = true;

			var msg = launch.utils.validateAll($scope.content);

			if (!launch.utils.isBlank(msg)) {
				notificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));

				if (!!callback && $.isFunction(callback.error)) {
					callback.error();
				}

				return;
			}

			var method = $scope.isNewContent ? contentService.add : contentService.update;
			var success = function(r) {
				$scope.isSaving = false;
				$scope.isUploading = false;

				var successMsg = $scope.isNewContent ? 'Successfully created new "' + $scope.content.title + '"!' : 'Successfully updated "' + $scope.content.title + '"';

				notificationService.success('Success!', successMsg);

				if (!!callback && $.isFunction(callback.success)) {
					callback.success(r);
				}

				if ($scope.isNewContent) {
					$location.path('/create/content/edit/' + r.id);
				} else {
					self.refreshContent();
				}
			};

			$scope.isSaving = true;

			self.updateContentConnection();

			method(self.loggedInUser.account.id, $scope.content, {
				success: function (r) {
					if (self.replaceFile) {
						$scope.isUploading = true;

						//TODO: UPLOAD FILE HERE!
						window.setTimeout(function() {
							success(r);
						}, 500);
					} else {
						success(r);
					}
				},
				error: function (r) {
					$scope.isSaving = false;
					launch.utils.handleAjaxErrorResponse(r, notificationService);

					if (!!callback && $.isFunction(callback.error)) {
						callback.error(r);
					}
				}
			});
		};

		$scope.submitForEditing = function () {
			// TODO: VALIDATE THAT ALL TASKS ARE COMPLETE BEFORE CONTINUING!!
			var msg = '';

			if ($.isArray($scope.content.taskGroups) && $scope.content.taskGroups.length > 0) {
				for (var i = 0; i < $scope.content.taskGroups.length; i++) {
					if ($scope.content.taskGroups[i].status > $scope.content.status) {
						continue;
					}

					var tasks = $.grep($scope.content.taskGroups[i].tasks, function (t) { return !t.isComplete; });
					var isOldStage = ($scope.content.taskGroups[i].status < $scope.content.status);

					if (tasks.length > 0) {
						$.each(tasks, function (j, t) {
							if (isOldStage) {
								t.isComplete = true;
							} else {
								msg += t.name + '\n';
							}
						});

						if (isOldStage) {
							taskService.saveContentTasks(self.loggedInUser.account.id, $scope.content.taskGroups[i], self.ajaxHandler);
						}
					}
				}
			}

			if (!launch.utils.isBlank(msg)) {
				notificationService.error('Error!', 'Please make sure all tasks are complete. The following tasks are outstanding:\n\n' + msg);
				return;
			}

			var oldStatus = $scope.content.status;

			$scope.content.status = 3;

			$scope.saveContent({
				error: function() {
					$scope.content.status = oldStatus;
				}
			});
		};

		$scope.updateContentType = function() {
			var contentTypeName = $scope.content.contentType.name;
			var contentType = $.grep($scope.contentTypes, function(ct) { return ct.name === contentTypeName; });

			$.extend($scope.content.contentType, contentType[0]);

			$scope.showRichTextEditor = $scope.content.contentType.allowText();
			$scope.showAddFileButton = $scope.content.contentType.allowFile();
		};

		$scope.updateAuthor = function () {
			var userId = parseInt($scope.content.author.id);
			var user = $.grep($scope.users, function (u) { return u.id === userId; });

			$scope.content.author = user[0];
		};

		$scope.updateCampaign = function () {
			var campaignId = parseInt($scope.content.campaign.id);
			var campaign = $.grep($scope.campaigns, function (u) { return u.id === campaignId; });

			$scope.content.campaign = campaign[0];
		};

		$scope.uploadContentFile = function(files, form, control) {
			if ($.isArray(files) && files.length !== 1) {
				notificationService.error('Invalid File!', 'Please make sure to select only one file for upload at a time.');
				$(control).replaceWith($(control).clone(true, true));
				return;
			}

			var file = $.isArray(files) ? files[0] : files;
			var msg = $scope.content.validateContentFile(file);

			if (!launch.utils.isBlank(msg)) {
				notificationService.error('Invalid File!', msg);
				$(control).replaceWith($(control).clone(true, true));
				return;
			}

			self.replaceFile = true;
		};

		$scope.$watch('contentTags', function () {
			if (!$scope.content || !$scope.content.$resolved) {
				return;
			}

			if (launch.utils.isBlank($scope.contentTags)) {
				$scope.content.tags = null;
			} else {
				$scope.content.tags = $scope.contentTags.split(',');
			}
		});

		self.init();
	}
]);