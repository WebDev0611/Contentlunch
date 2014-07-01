launch.module.controller('ContentController', [
	'$scope', '$routeParams', '$filter', '$location', '$modal', 'AuthService', 'AccountService', 'UserService', 'ContentSettingsService', 'ContentService', 'ConnectionService', 'CampaignService', 'TaskService', 'NotificationService', function ($scope, $routeParams, $filter, $location, $modal, authService, accountService, userService, contentSettingsService, contentService, connectionService, campaignService, taskService, notificationService) {
		var self = this;

		self.loggedInUser = null;
		self.replaceFile = false;
		self.contentId = null;
		self.uploadFile = null;

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

			$scope.contentTypes = contentService.getContentTypes(self.ajaxHandler);
			$scope.users = userService.getForAccount(self.loggedInUser.account.id, null, self.ajaxHandler);
			$scope.campaigns = campaignService.query(self.loggedInUser.account.id, null, {
				success: function(r) {
					if ($scope.isNewContent) {
						self.filterCampaigns();
					}
				},
				error: self.ajaxHandler.error
			});
			$scope.contentConnections = connectionService.queryContentConnections(self.loggedInUser.account.id, self.ajaxHandler);
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
			self.replaceFile = false;
			self.uploadFile = null;

			if (isNaN(self.contentId)) {
				$scope.content = contentService.getNewContent(self.loggedInUser);
				$scope.isNewContent = true;
				self.setPrivileges();
			} else {
				$scope.isNewContent = false;

				// NEED TO PAUSE HERE DUE TO A RACE SITUATION BETWEEN TRYING TO FETCH CONTENT CONNECTIONS
				// AND THE CONTENT ITEM. THE SELECT2 DROP-DOWN NEEDS TO GET ITS OPTIONS IN PLACE BEFORE WE
				// SET THE MODEL FOR THE CONTROL. COMPLETE HACK DUE TO LIMITATIONS OF THE CONTROL.
				if (!$scope.contentConnections || !$scope.contentConnections.$resolved) {
					window.setTimeout(self.refreshContent, 200);
					$scope.content = null;
					return;
				}

				$scope.content = contentService.get(self.loggedInUser.account.id, self.contentId, {
					success: function (r) {
						if ($scope.content.status === 0) {
							$location.path('/create/concept/edit/content/' + $scope.content.id);
							return;
						}

						$scope.isCollaborator = (self.loggedInUser.id === $scope.content.author.id ||
							$.grep($scope.content.collaborators, function (c) { return c.id === self.loggedInUser.id; }).length > 0);

						$scope.isCollaborator = ($scope.isCollaborator || self.loggedInUser.hasPrivilege('create_edit_content_other'));

						if (!$scope.isCollaborator) {
							return;
						}

						self.setPrivileges();

						$scope.activity = $scope.content.activity;

						self.filterCampaigns();
						self.refreshComments();
					},
					error: function (r) {
						launch.utils.handleAjaxErrorResponse(r, notificationService);
					}
				});
			}
		};

		self.refreshComments = function () {
			$scope.comments = contentService.queryComments(self.loggedInUser.account.id, $scope.content.id, null, self.ajaxHandler);
		};

		self.refreshActivity = function () {
			if ($scope.isNewContent) {
				return;
			}

			$scope.activity = contentService.getActivity(self.loggedInUser.account.id, self.contentId, self.ajaxHandler);
		};

		self.filterCampaigns = function() {
			$scope.campaigns = $.grep($scope.campaigns, function (c) {
				return ((c.isActive && !c.isEnded()) || (!!$scope.content && !!$scope.content.campaign && c.id === $scope.content.campaign.id));
			});
		};

		self.setPrivileges = function() {
			if ($scope.content.status < 3) {
				$scope.canViewContent = $scope.content.author.id === self.loggedInUser.id ? self.loggedInUser.hasPrivilege('create_execute_content_own') : self.loggedInUser.hasPrivilege(['create_view_content_other_unapproved', 'create_view_content_other', 'create_edit_content_as_collaborator']);
				$scope.canEditContent = $scope.content.author.id === self.loggedInUser.id ? self.loggedInUser.hasPrivilege('create_execute_content_own') : self.loggedInUser.hasPrivilege(['create_edit_content_other_unapproved', 'create_edit_content_other', 'create_edit_content_as_collaborator']);
				$scope.isReadOnly = $scope.collboratorsIsDisabled = $scope.attachmentsIsDisabled = !$scope.canEditContent;
			} else if ($scope.content.status === 3) {
				$scope.canViewContent = $scope.content.author.id === self.loggedInUser.id ? self.loggedInUser.hasPrivilege('launch_execute_content_own') : self.loggedInUser.hasPrivilege('launch_view_content_other');
				$scope.canEditContent = $scope.content.author.id === self.loggedInUser.id ? self.loggedInUser.hasPrivilege('launch_execute_content_own') : self.loggedInUser.hasPrivilege('launch_execute_content_other');
				$scope.isReadOnly = $scope.collboratorsIsDisabled = $scope.attachmentsIsDisabled = true;
			} else {
				// TODO: WHAT PRIVILEGES DO WE CHECK FOR PROMOTE?
				$scope.canPromoteContent = true;
				$scope.isReadOnly = $scope.collboratorsIsDisabled = $scope.attachmentsIsDisabled = true;
			}

			// TODO: VERIFY RULES FOR SUBMITTING CONTENT FOR APPROVAL!!
			$scope.canSubmitContent = ($scope.content.author.id === self.loggedInUser.id || self.loggedInUser.hasPrivilege('create_edit_content_other_unapproved'));
			$scope.canApproveContent = self.loggedInUser.hasPrivilege('collaborate_execute_approve');
			$scope.canLaunchContent = ($scope.content.author.id === self.loggedInUser.id) ? self.loggedInUser.hasPrivilege('launch_execute_content_own') : self.loggedInUser.hasPrivilege('launch_execute_content_other');
			$scope.canDiscussContent = self.loggedInUser.hasPrivilege('collaborate_execute_feedback');

			// TODO: WHAT PRIVILEGES DO WE CHECK FOR RESTORE AND ARCHIVE?
			$scope.canRestoreContent = $scope.content.archived ? true : false;
			$scope.canArchiveContent = $scope.content.archived ? false : true;

			$scope.showRichTextEditor = $scope.content.contentType.allowText();
			$scope.showAddFileButton = $scope.content.contentType.allowFile();
			$scope.showDownloadContentFile = (!!$scope.content.contentFile && ($scope.content.contentFile.isImage() || $scope.content.contentFile.isVideo() || $scope.content.contentFile.isAudio()));

			$scope.contentConnectionIds = $.map($scope.content.accountConnections, function (cc) { return parseInt(cc.id).toString(); });
			$scope.contentTags = ($.isArray($scope.content.tags)) ? $scope.content.tags.join(',') : null;
		};

		self.handleSaveContent = function (callback) {
			var method = $scope.isNewContent ? contentService.add : contentService.update;

			$scope.isSaving = true;

			method(self.loggedInUser.account.id, $scope.content, {
				success: function (r) {
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

		self.handleUploadFile = function (callback) {
			var responseHandler = {
				success: function (r) {
					$scope.showFullScreenModal = false;
					$scope.content.contentFile = r;
					self.handleSaveContent(callback);
				},
				error: function (err) {
					$scope.showFullScreenModal = false;
					self.ajaxHandler.error(err);
				}
			};

			$scope.showFullScreenModal = true;
			if ($scope.isNewContent || !self.contentFile || launch.utils.isBlank(self.contentFile.id)) {
				accountService.addFile(self.loggedInUser.account.id, self.uploadFile, null, responseHandler);
			} else {
				accountService.updateFile(self.loggedInUser.account.id, self.contentFile.id, self.uploadFile, null, responseHandler);
			}

			self.replaceFile = false;
			self.uploadFile = null;
		};

		self.approveContent = function () {
			if ($scope.canApproveContent) {
				self.handleSubmitContent();
				return;
			}

			self.showSelectApproverDialog('approve', 'approver', 'collaborate_execute_approve');
		};

		self.launchContent = function () {
			if ($scope.canLaunchContent) {
				self.handleSubmitContent();
				return;
			}

			self.showSelectApproverDialog('launch', 'launcher', 'launch_execute_content_other');
		};

		self.promoteContent = function () {
			if (!$scope.canPromoteContent) {
				notificationService.error('Error!', 'You do not have sufficient privileges to launch content. Please contact your administrator for more information.');
			}

			$location.path('/launch/content/' + $scope.content.id);
		};

		self.showSelectApproverDialog = function(taskName, actor, privilegeName) {
			$modal.open({
				templateUrl: 'select-user-to-complete.html',
				controller: [
					'$scope', '$modalInstance', function (scope, instance) {
						scope.taskName = taskName;
						scope.actor = actor;
						scope.userToComplete = null;
						scope.userToCompleteId = null;
						scope.userPool = userService.getForAccount(self.loggedInUser.account.id, { permission: privilegeName }, self.ajaxHandler, true);

						scope.formatUserItem = function (item, element, context) {
							var collaborator = $.grep($scope.content.collaborators, function (c, i) { return c.id === parseInt(item.id); });
							var html = $scope.formatUserItem(item, element, context);

							if (collaborator.length === 0) {
								return html;
							}

							return html + '<span class="fa fa-check-circle" style="display: inline-block; margin-left: 8px;"></span>';
						};

						scope.selectUserToComplete = function (id) {
							scope.userToComplete = $.grep(scope.userPool, function (a) { return a.id === parseInt(id); })[0];
						};

						scope.save = function () {
							if (!scope.userToComplete) {
								notificationService.error('Error!', 'Please select a content ' + actor + '.');
								return;
							}

							if ($.grep($scope.content.collaborators, function (c) { return (c.id === scope.userToComplete.id); }).length === 0) {
								contentService.insertCollaborator(self.loggedInUser.account.id, $scope.content.id, scope.userToComplete.id, self.ajaxHandler);
							}

							var taskGroup = $.grep($scope.content.taskGroups, function (tg) { return tg.status === $scope.content.status; });

							if (taskGroup.length != 1) {
								notificationService.error('Error!', 'Unable to find task group for ' + $scope.content.currentStep() + ' stage.');
								return;
							}

							var task = new launch.Task();

							task.name = launch.utils.titleCase(taskName) + ' Content';
							task.isComplete = false;
							task.dueDate = new Date();
							task.userId = scope.userToComplete.id;
							task.taskGroupId = taskGroup[0].id;
							task.dueDate.setDate((task.dueDate).getDate() + 2);

							taskGroup[0].tasks.push(task);

							taskService.saveContentTasks(self.loggedInUser.account.id, taskGroup[0], {
								success: function (r) {
									instance.close();
								},
								error: self.ajaxHandler.error
							});
						};

						scope.cancel = function () {
							instance.dismiss('cancel');
						};
					}
				]
			});
		};

		self.handleSubmitContent = function() {
			var oldStatus = $scope.content.status;

			$scope.content.status = oldStatus + 1;

			$scope.saveContent({
				error: function () {
					$scope.content.status = oldStatus;
				}
			});
		};

		$scope.content = null;
		$scope.comments = null;
		$scope.contentTypes = null;
		$scope.contentSettings = null;
		$scope.contentConnections = null;
		$scope.campaigns = null;
		$scope.users = null;
		$scope.activity = null;
		$scope.isCollaborator = true;
		$scope.buyingStages = null;
		$scope.isNewContent = true;
		$scope.forceDirty = false;
		$scope.isReadOnly = false;
		$scope.contentConnectionIds = null;
		$scope.contentTags = null;
		$scope.showRichTextEditor = true;
		$scope.showAddFileButton = false;
		$scope.showDownloadContentFile = false;
		$scope.isUploading = false;
		$scope.percentComplete = 0;
		$scope.defaultTaskGroup = null;
		$scope.taskUsers = null;
		$scope.collaborators = null;

		$scope.hasError = launch.utils.isPropertyValid;
		$scope.errorMessage = launch.utils.getPropertyErrorMessage;
		$scope.formatContentTypeItem = launch.utils.formatContentTypeItem;
		$scope.formatCampaignItem = launch.utils.formatCampaignItem;
		$scope.formatContentConnectionItem = launch.utils.formatContentConnectionItem;
		$scope.getConnectionProviderIconClass = launch.utils.getConnectionProviderIconClass;
		$scope.formatBuyingStageItem = launch.utils.formatBuyingStageItem;
		$scope.formatDate = launch.utils.formatDate;

		$scope.canViewContent = false;
		$scope.canEditContent = false;
		$scope.canSubmitContent = false;
		$scope.canApproveContent = false;
		$scope.canLaunchContent = false;
		$scope.canPromoteContent = false;
		$scope.canArchiveContent = false;
		$scope.canRestoreContent = false;
		$scope.canDiscussContent = false;

		$scope.collboratorsIsDisabled = false;
		$scope.attachmentsIsDisabled = false;

		$scope.formatUserItem = function (item, element, context) {
			return $scope.getUserImageHtml(item.id, item.text);
		};

		$scope.getUserImageHtml = function (userId, text) {
			var user = $.grep($scope.users, function (u, i) { return u.id === parseInt(userId); });
			var style = (user.length === 1 && !launch.utils.isBlank(user[0].image)) ? ' style="background-image: ' + user[0].imageUrl() + '"' : '';

			if (launch.utils.isBlank(text) && user.length === 1) {
				text = user[0].formatName();
			}

			var imageHtml = '<span class="user-image user-image-small"' + style + '></span>';
			var textHtml = '<span class="user-name">' + (launch.utils.isBlank(text) ? '' : text) + '</span>';

			return imageHtml + ' ' + textHtml;
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

		$scope.saveContent = function (callback) {
			if (!$scope.content || $scope.content.$resolved === false) {
				return;
			}

			$scope.forceDirty = true;

			$scope.updateContentConnection();

			var msg = launch.utils.validateAll($scope.content);

			if (!launch.utils.isBlank(msg)) {
				notificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));

				if (!!callback && $.isFunction(callback.error)) {
					callback.error();
				}

				return;
			}

			// If there is already a file associated with this content:
			//		1: Upload new file
			//		2: Save the content
			//		3: Delete the old file
			if (!!$scope.content.contentFile && self.replaceFile && !!self.uploadFile) {
				var oldFileId = $scope.content.contentFile.id;
				var newCallback = {
					success: function(r) {
						accountService.deleteFile(self.loggedInUser.account.id, oldFileId, {
							success: function(r1) {
								if (!!callback && $.isFunction(callback.success)) {
									callback.success(r1);
								}
							},
							error: function (r1) {
								if (!!callback && $.isFunction(callback.error)) {
									callback.error(r1);
								} else {
									self.ajaxHandler.error(r1);
								}
							}
						});
					},
					error: function(r) {
						if (!!callback && $.isFunction(callback.error)) {
							callback.error(r);
						} else {
							self.ajaxHandler.error(r);
						}
					}
				};

				self.handleUploadFile(newCallback);
			} else if (self.replaceFile && !!self.uploadFile) {
				self.handleUploadFile(callback);
			} else {
				self.handleSaveContent(callback);
			}
		};

		$scope.submitContent = function () {
			if (!$scope.canSubmitContent) {
				var action = null;

				switch ($scope.content.status) {
					case 0:
						action = 'convert a concept to content';
						break;
					case 1:
						action = 'submit content for approval';
						break;
					case 2:
						action = 'approve content';
						break;
					case 3:
						action = 'launch content';
						break;
					case 4:
						action = 'promote content';
						break;
				}

				notificationService.error('Error!', 'You do not have sufficient privileges to ' + action + ' content. Please contact your administrator for more information.');
				return;
			}

			var msg = (self.replaceFile) ? 'You have specified a new file to upload. Please save your changes before changing the status of the content.' : '';

			if (launch.utils.isBlank(msg) && $.isArray($scope.content.taskGroups) && $scope.content.taskGroups.length > 0) {
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

			if ($scope.content.status === 2) {
				self.approveContent();
			} else if ($scope.content.status === 3) {
				self.launchContent();
			} else if ($scope.content.status === 4) {
				self.promoteContent();
			} else {
				self.handleSubmitContent();
			}
		};

		$scope.updateContentConnection = function () {
			if ($.isArray($scope.contentConnectionIds)) {
				var contentConnectionIds = $.map($scope.contentConnectionIds, function (id) { return parseInt(id); });
				var contentConnections = $.grep($scope.contentConnections, function (cc) { return $.inArray(cc.id, contentConnectionIds) >= 0; });

				$scope.content.accountConnections = contentConnections;
			}
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

		$scope.uploadContentFile = function (files, form, control) {
			console.log(files);
			self.uploadFile = null;

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
			self.uploadFile = file;
			console.log(self.uploadFile);
		};

		$scope.deleteContentFile = function() {
			$modal.open({
				templateUrl: 'confirm.html',
				controller: [
					'$scope', '$modalInstance', function (scope, instance) {
						scope.message = 'Are you sure you want to delete this file?';
						scope.okButtonText = 'Delete';
						scope.cancelButtonText = 'Cancel';
						scope.onOk = function () {
							var oldFileId = $scope.content.contentFile.id;

							$scope.content.contentFile = null;

							$scope.saveContent({
								success: function() {
									accountService.deleteFile(self.loggedInUser.account.id, oldFileId);
								},
								error: self.ajaxHandler.error
							});

							instance.close();
						};
						scope.onCancel = function () {
							instance.dismiss('cancel');
						};
					}
				]
			});
		};

		$scope.addAttachment = function(uploadFile) {
			if (!!$scope.content && !launch.utils.isBlank($scope.content.id)) {
				$scope.saveContent();
			}
		};

		$scope.filterTaskAssignees = function (collaborators) {
			if (!$scope.content) {
				return;
			}

			$scope.taskUsers = $.grep($scope.users, function (u) {
				if (u.id === self.loggedInUser.id) {
					return true;
				}

				if ($.isArray(collaborators) && collaborators.length > 0) {
					if ($.grep(collaborators, function (c) { return c.id === u.id; }).length > 0) {
						return true;
					}
				}

				return false;
			});

			self.refreshActivity();
		};

		$scope.filterCollaborators = function() {
			if (!$scope.content || !$scope.content.author) {
				return;
			}

			$scope.collaborators = $.grep($scope.users, function(u) {
				return u.id !== $scope.content.author.id;
			});

			self.refreshActivity();
		};

		$scope.isCollaboratorFinished = function (collaborator) {
			var collaboratorTasks = [];

			if (!!$scope.content && $.isArray($scope.content.taskGroups) && $scope.content.taskGroups.length > 0) {
				$.each($scope.content.taskGroups, function (i, tg) {
					if ($.isArray(tg.tasks) && tg.tasks.length > 0) {
						var tasks = $.grep(tg.tasks, function (t) {
							return t.userId === collaborator.id && !t.isComplete;
						});

						if (tasks.length > 0) {
							collaboratorTasks.push(tasks);
						}
					}
				});
			}

			return (collaboratorTasks.length === 0);
		};

		$scope.getNextStepText = function() {
			if (!$scope.content || !$scope.content.$resolved) {
				return null;
			}

			switch ($scope.content.status) {
				case 0:
					return 'Convert to Content';
				case 1:
					return 'Submit for Review';
				case 2:
					return 'Approve';
				case 3:
					return 'Launch';
				case 4:
					return 'Promote';
				default:
					return null;
			}
		};

		$scope.restoreContent = function() {
			if (!$scope.content.archived) {
				return;
			}

			if (!$scope.canRestoreContent) {
				notificationService.error('Error!', 'You do not have sufficient privileges to restore archived content. Please contact your administrator for more information.');
				return;
			}

			$scope.content.archived = false;

			$scope.saveContent();
		};

		$scope.downloadFile = function(file) {
			window.open('/api/uploads/' + file.id + '/download');
		};

		$scope.viewFile = function(file) {
			if (!file || launch.utils.isBlank(file.path)) {
				return;
			}

			if (file.isImage() || file.isVideo() || file.isAudio()) {
				$modal.open({
					templateUrl: '/assets/views/dialogs/view-file-dialog.html',
					size: 'lg',
					controller: [
						'$scope', '$modalInstance', function (scope, instance) {
							scope.title = file.fileName;
							scope.path = file.path;
							scope.mimeType = file.mimeType;
							scope.isImage = file.isImage();
							scope.isVideo = file.isVideo();
							scope.isAudio = file.isAudio();

							scope.ok = function () {
								instance.dismiss('cancel');
							};
						}
					]
				});
			} else {
				window.open(file.path, 'view_file_' + file.id);
			}
		};

		$scope.archiveContent = function() {
			if ($scope.content.archived) {
				return;
			}

			if (!$scope.canArchiveContent) {
				notificationService.error('Error!', 'You do not have sufficient privileges to archive content. Please contact your administrator for more information.');
				return;
			}

			$scope.content.archived = true;
			$scope.saveContent();
		};

		$scope.addComment = function(message) {
			var comment = new launch.Comment();

			comment.id = null;
			comment.comment = message;
			comment.itemId = $scope.content.id;
			comment.commentDate = launch.utils.formatDateTime(new Date());
			comment.commentor = {
				id: self.loggedInUser.id,
				name: self.loggedInUser.displayName,
				image: self.loggedInUser.imageUrl()
			};

			var msg = launch.utils.validateAll(comment);

			if (!launch.utils.isBlank(msg)) {
				notificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
				return;
			}

			$scope.content.comments = contentService.insertComment(self.loggedInUser.account.id, comment, {
				success: function (r) {
					self.refreshComments();
				},
				error: self.ajaxHandler.error
			});
		};

		$scope.$watch('content.collaborators', $scope.filterTaskAssignees);

		$scope.$watch('content.author', $scope.filterCollaborators);

		$scope.$watch('content.taskGroups', $scope.filterCollaborators);

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