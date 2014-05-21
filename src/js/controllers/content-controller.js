﻿launch.module.controller('ContentController', [
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

		self.refreshTasks = function () {
			$scope.content.taskGroups = taskService.queryContentTasks(self.loggedInUser.account.id, self.contentId, self.ajaxHandler);
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

		$scope.openCalendar = function(opened, e) {
			e.stopImmediatePropagation();

			return !opened;
		};

		$scope.taskGroupIsActive = function(taskGroup) {
			return $scope.content.status <= taskGroup.status;
		};

		$scope.canEditTask = function (taskGroup) {
			if (!$scope.taskGroupIsActive(taskGroup)) {
				return false;
			}

			if (!self.loggedInUser.hasPrivilege('create_execute_content_own') && self.loggedInUser.id !== $scope.content.author.id &&
				!self.loggedInUser.hasPrivilege('create_edit_content_other')) {
				return false;
			}

			return true;
		};

		$scope.getUserName = function(id) {
			var user = launch.utils.getUserById($scope.users, id);

			return (!!user) ? user.formatName() : null;
		};

		$scope.saveTaskGroup = function (taskGroup, task) {
			if (!!task && launch.utils.isBlank(task.id)) {
				taskGroup.tasks.push(task);
			}

			var msg = launch.utils.validateAll(taskGroup);

			if (!launch.utils.isBlank(msg)) {
				notificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
				return;
			}

			taskGroup = taskService.saveContentTasks(self.loggedInUser.account.id, taskGroup, {
				success: function (r) {
					notificationService.success('Success!', ((!!task) ? 'Successfully modified task, "' + task.name + '"!' : 'Successfully modified "' + taskGroup.name() + '" task group!'));
				},
				error: function(r) {
					self.ajaxHandler.error(r);
				}
			});
		};

		$scope.editTask = function(taskGroup, task, e) {
			if ($scope.taskGroupIsActive(taskGroup)) {
				if (!task) {
					task = new launch.Task();
					task.taskGroupId = taskGroup.id;
					task.dueDate = new Date();
				}

				$modal.open({
					templateUrl: 'create-task.html',
					controller: [
						'$scope', '$modalInstance', function (scope, instance) {
							scope.task = task;

							scope.users = $scope.users;
							scope.openCalendar = $scope.openCalendar;
							scope.formatUserItem = $scope.formatUserItem;

							scope.cancel = function () {
								instance.dismiss('cancel');
							};

							scope.save = function() {
								var msg = launch.utils.validateAll(scope.task);

								if (!launch.utils.isBlank(msg)) {
									notificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
									return;
								}

								if (scope.task.dueDate > taskGroup.dueDate) {
									$modal.open({
										templateUrl: 'confirm.html',
										controller: [
											'$scope', '$modalInstance', function (scp, inst) {
												scp.message = 'A Task\'s Due Date cannot be after the Task Group\'s Due Date. Do you want to extend the Task Group\'s Due Date?';
												scp.okButtonText = 'Yes';
												scp.cancelButtonText = 'No';
												scp.onOk = function () {
													taskGroup.dueDate = task.dueDate;
													inst.close();
													instance.close();
													$scope.saveTaskGroup(taskGroup, task);
												};
												scp.onCancel = function () {
													inst.dismiss('cancel');
												};
											}
										]
									});

									return;
								}

								$scope.saveTaskGroup(taskGroup, task);
								instance.close();
							};
						}
					]
				});
			}

			e.stopImmediatePropagation();
		};

		self.init();
	}
]);