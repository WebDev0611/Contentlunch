launch.module.controller('ContentController', [
	'$scope', '$sce', '$routeParams', '$filter', '$location', '$modal', 'ecommercePlatforms', 'AuthService', 'AccountService', 'UserService', 'ContentSettingsService', 'ContentService', 'ConnectionService', 'CampaignService', 'TaskService', 'NotificationService', 'Restangular', function ($scope, $sce, $routeParams, $filter, $location, $modal, ecommercePlatforms, authService, accountService, userService, contentSettingsService, contentService, connectionService, campaignService, taskService, notificationService, Restangular) {
		var self = this;

		self.loggedInUser = null;
		self.replaceFile = false;
		self.contentId = null;
		self.uploadFile = null;

		self.ajaxHandler = {
			success: function(r) {

			},
			error: function (r) {
				$scope.analyzingContent = false;
				$scope.isSaving = false;
				$scope.isUploading = false;
				launch.utils.handleAjaxErrorResponse(r, notificationService);
			}
		};

		self.init = function() {
			self.loggedInUser = authService.userInfo();

			$scope.contentTypes = contentService.getContentTypes(self.ajaxHandler);
			$scope.users = userService.getForAccount(self.loggedInUser.account.id, null, self.ajaxHandler);
			$scope.contentConnections = connectionService.queryContentConnections(self.loggedInUser.account.id, self.ajaxHandler);
			$scope.promoteConnections = connectionService.queryPromoteConnections(self.loggedInUser.account.id, {
				success: function(r) {
					self.getPromoteAutomationConnections();
				},
				error: self.ajaxHandler.error
			});
			$scope.campaigns = campaignService.query(self.loggedInUser.account.id, null, {
				success: function(r) {
					if ($scope.campaigns.length === 0 && r.length > 0) {
						console.log('Why is $scope.campaigns not populated here??');
						$scope.campaigns = r;
					}

                    self.filterCampaigns();
				},
				error: self.ajaxHandler.error
			});
			$scope.contentSettings = contentSettingsService.get(self.loggedInUser.account.id, {
				success: function(r) {
					if ($.isArray($scope.contentSettings.personaProperties)) {
						$scope.buyingStages = $scope.contentSettings.buyingStages();
					}
				},
				error: self.ajaxHandler.error
			});

			self.refreshContent();
		}

		self.refreshContent = function() {
			self.contentId = parseInt($routeParams.contentId);
			self.replaceFile = false;
			self.uploadFile = null;

			if (isNaN(self.contentId)) {
				$scope.content = contentService.getNewContent(self.loggedInUser);
				$scope.isNewContent = true;

				self.setPrivileges();

				if ($location.path() === '/promote/content/new') {
					$scope.content.status = 4;
					$scope.isReadOnly = false;
					$scope.showAddFileButton = true;
					$scope.isPromote = true;

					$scope.content.contentType = $.grep($scope.contentTypes, function(ct) { return ct.name === 'direct-upload'; });
					$scope.content.contentType = ($scope.content.contentType.length === 1) ? $scope.content.contentType[0] : null;
				}
			} else {
				$scope.isNewContent = false;

				if (launch.utils.isValidPattern($location.path(), /^\/promote\/content\/\d+$/)) {
					$scope.isPromote = true;
				}

				$scope.content = contentService.get(self.loggedInUser.account.id, self.contentId, {
					success: function(r) {
						if ($scope.content.status === 0) {
							$location.path('/create/concept/edit/content/' + $scope.content.id);
							return;
						}

						$scope.isCollaborator = (self.loggedInUser.id === $scope.content.author.id ||
							$.grep($scope.content.collaborators, function(c) { return c.id === self.loggedInUser.id; }).length > 0);

						$scope.isCollaborator = ($scope.isCollaborator || self.loggedInUser.hasPrivilege('create_edit_content_other'));

						if (!$scope.isCollaborator) {
							return;
						}

						self.setPrivileges();

						$scope.activity = $scope.content.activity;
						$scope.showConcept = !launch.utils.isBlank($scope.content.concept);

						self.filterCampaigns();
						self.refreshComments();

						if ($scope.content.status === 4) {
							self.refreshLaunches();
						}
					},
					error: self.ajaxHandler.error
				});
			}
		};

		self.refreshComments = function() {
			$scope.comments = contentService.queryComments(self.loggedInUser.account.id, $scope.content.id, null, self.ajaxHandler);
		};

		self.refreshActivity = function() {
			if ($scope.isNewContent) {
				return;
			}

			$scope.activity = contentService.getActivity(self.loggedInUser.account.id, self.contentId, self.ajaxHandler);
		};

		self.refreshLaunches = function() {
			if ($scope.isNewContent) {
				return;
			}

			$scope.launches = contentService.getLaunches(self.loggedInUser.account.id, self.contentId, self.ajaxHandler);
		};

		self.filterCampaigns = function() {
			$scope.campaigns = $.grep($scope.campaigns, function(c) {
				return ((c.isActive && !c.isEnded()) || (!!$scope.content && !!$scope.content.campaign && c.id && c.id === $scope.content.campaign.id));
			});
            console.log($scope.campaigns);
		};

		self.setPrivileges = function () {
			if (!$scope.content || !$scope.content.author || !$scope.content.contentType) {
				return;
			}

			if ($scope.content.status < 3) {
				$scope.canViewContent = $scope.content.author.id === self.loggedInUser.id ? self.loggedInUser.hasPrivilege('create_execute_content_own') : self.loggedInUser.hasPrivilege(['create_view_content_other_unapproved', 'create_view_content_other', 'create_edit_content_as_collaborator']);
				$scope.canEditContent = $scope.content.author.id === self.loggedInUser.id ? self.loggedInUser.hasPrivilege('create_execute_content_own') : self.loggedInUser.hasPrivilege(['create_edit_content_other_unapproved', 'create_edit_content_other', 'create_edit_content_as_collaborator']);
				$scope.canApproveContent = self.loggedInUser.hasPrivilege('create_execute_approve');
				$scope.isReadOnly = $scope.collboratorsIsDisabled = $scope.attachmentsIsDisabled = !$scope.canEditContent;
			} else if ($scope.content.status === 3) {
				$scope.canViewContent = $scope.content.author.id === self.loggedInUser.id ? self.loggedInUser.hasPrivilege('create_execute_launch_content_own') : self.loggedInUser.hasPrivilege('create_view_launch_content_other');
				$scope.canEditContent = $scope.content.author.id === self.loggedInUser.id ? self.loggedInUser.hasPrivilege('create_execute_launch_content_own') : self.loggedInUser.hasPrivilege('create_execute_launch_content_other');
				$scope.canLaunchContent = ($scope.content.author.id === self.loggedInUser.id) ? self.loggedInUser.hasPrivilege('create_execute_launch_content_own') : self.loggedInUser.hasPrivilege('create_execute_launch_content_other');
				$scope.isReadOnly = $scope.collboratorsIsDisabled = $scope.attachmentsIsDisabled = true;
			} else {
				$scope.canLaunchContent = ($scope.content.author.id === self.loggedInUser.id) ? self.loggedInUser.hasPrivilege('create_execute_launch_content_own') : self.loggedInUser.hasPrivilege('create_execute_launch_content_other');
				$scope.canPromoteContent = ($scope.content.author.id === self.loggedInUser.id) ? self.loggedInUser.hasPrivilege('promote_content_own') : self.loggedInUser.hasPrivilege('promote_content_other');
				$scope.isReadOnly = $scope.collboratorsIsDisabled = $scope.attachmentsIsDisabled = true;
			}

			$scope.canDeleteContent = self.loggedInUser.hasPrivilege('create_execute_content_delete');
			$scope.canSubmitContent = ($scope.content.author.id === self.loggedInUser.id || self.loggedInUser.hasPrivilege('create_edit_content_other_unapproved'));
			$scope.canDiscussContent = self.loggedInUser.hasPrivilege('collaborate_execute_feedback');

			if (!$scope.isPromote && !$scope.isNewContent) {
				$scope.canRestoreContent = ($scope.content.archived === true) ? ($scope.content.author.id === self.loggedInUser.id) ? self.loggedInUser.hasPrivilege('create_execute_archive_restore_content_own') : self.loggedInUser.hasPrivilege('create_execute_archive_restore_content_other'): false;
				$scope.canArchiveContent = ($scope.content.archived === true) ? false : ($scope.content.author.id === self.loggedInUser.id) ? self.loggedInUser.hasPrivilege('create_execute_archive_restore_content_own') : self.loggedInUser.hasPrivilege('create_execute_archive_restore_content_other');
			}

			$scope.showRichTextEditor = $scope.content.contentType.allowText();
			$scope.showAddFileButton = $scope.content.contentType.allowFile();
			$scope.showMetaInfo = $scope.content.contentType.allowMetaTags();
			$scope.showDownloadContentFile = (!!$scope.content.contentFile && ($scope.content.contentFile.isImage() || $scope.content.contentFile.isVideo() || $scope.content.contentFile.isAudio()));

			$scope.contentConnectionIds = $.map($scope.content.accountConnections, function(cc) { return parseInt(cc.id).toString(); });
			$scope.contentTags = ($.isArray($scope.content.tags)) ? $scope.content.tags.join(',') : null;
			$scope.relatedContent = ($.isArray($scope.content.relatedContent)) ? $scope.content.relatedContent.join(',') : null;

			if ($scope.canLaunchContent && $scope.content.contentType.canExportToAutomationProvider()) {
				$scope.showPromoteButtons = true;
			}
		};

		self.handleSaveContent = function(callback) {
			var method = $scope.isNewContent ? contentService.add : contentService.update;

			$scope.isSaving = true;

			method(self.loggedInUser.account.id, $scope.content, {
				success: function(r) {
					$scope.isSaving = false;
					$scope.isUploading = false;

					var successMsg = $scope.isNewContent ? 'Successfully created new "' + $scope.content.title + '"!' : 'Successfully updated "' + $scope.content.title + '"';

					notificationService.success('Success!', successMsg);

					if (!!callback && $.isFunction(callback.success)) {
						callback.success(r);
					}

					if ($scope.isNewContent) {
						if ($scope.isPromote) {
							$location.path('/promote/content/' + r.id);
						} else {
							$location.path('/create/content/edit/' + r.id);
						}
					} else {
						self.refreshContent();
					}
				},
				error: function(r) {
					$scope.isSaving = false;
					$scope.isUploading = false;
					launch.utils.handleAjaxErrorResponse(r, notificationService);

					if (!!callback && $.isFunction(callback.error)) {
						callback.error(r);
					}

					if (!$scope.isNewContent) {
						self.refreshContent();
					}
				}
			});
		};

		self.handleUploadFile = function(callback) {
			var responseHandler = {
				success: function(r) {
					$scope.showFullScreenModal = false;
					$scope.content.contentFile = r;
					self.handleSaveContent(callback);
				},
				error: function(err) {
					$scope.showFullScreenModal = false;
					$scope.isSaving = false;

					if (!!callback && $.isFunction(callback.error)) {
						callback.error(err);
					} else {
						self.ajaxHandler.error(err);
					}
				},
				progress: function (e) {
					$scope.percentComplete = parseInt(100.0 * e.loaded / e.total);
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

			$scope.isNewFile = false;
			$scope.newFile = null;
			$scope.newFileType = null;
		};

		self.validateTasks = function() {
			var msg = '';

			if ($.isArray($scope.content.taskGroups) && $scope.content.taskGroups.length > 0) {
				for (var i = 0; i < $scope.content.taskGroups.length; i++) {
					if ($scope.content.taskGroups[i].status > $scope.content.status) {
						continue;
					}

					var tasks = $.grep($scope.content.taskGroups[i].tasks, function(t) { return !t.isComplete; });
					var isOldStage = ($scope.content.taskGroups[i].status < $scope.content.status);

					if (tasks.length > 0) {
						$.each(tasks, function(j, t) {
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
				return false;
			}

			return true;
		};

		self.approveContent = function() {
			if ($scope.canApproveContent) {
				self.handleSubmitContent();
				return;
			}

			self.showSelectApproverDialog('approve', 'approver', 'create_execute_approve');
		};

		self.launchContent = function() {
			if ($scope.canLaunchContent) {
				self.handleSubmitContent();
				return;
			}

			self.showSelectApproverDialog('launch', 'launcher', 'launch_execute_content_other');
		};

		self.promoteContent = function() {
			if (!$scope.canPromoteContent) {
				notificationService.error('Error!', 'You do not have sufficient privileges to launch content. Please contact your administrator for more information.');
			}

			$location.path('/promote/content/' + $scope.content.id);
		};

		self.showSelectApproverDialog = function(taskName, actor, privilegeName) {
			$modal.open({
				templateUrl: 'select-user-to-complete.html',
				controller: [
					'$scope', '$modalInstance', function(scope, instance) {
						scope.taskName = taskName;
						scope.actor = actor;
						scope.userToComplete = null;
						scope.userToCompleteId = null;
						scope.userPool = userService.getForAccount(self.loggedInUser.account.id, { permission: privilegeName }, self.ajaxHandler, true);

						scope.formatUserItem = function(item, element, context) {
							var collaborator = $.grep($scope.content.collaborators, function(c, i) { return c.id === parseInt(item.id); });
							var html = $scope.formatUserItem(item, element, context);

							if (collaborator.length === 0) {
								return html;
							}

							return html + '<span class="fa fa-check-circle" style="display: inline-block; margin-left: 8px;"></span>';
						};

						scope.selectUserToComplete = function(id) {
							scope.userToComplete = $.grep(scope.userPool, function(a) { return a.id === parseInt(id); })[0];
						};

						scope.save = function() {
							if (!scope.userToComplete) {
								notificationService.error('Error!', 'Please select a content ' + actor + '.');
								return;
							}

							if ($.grep($scope.content.collaborators, function(c) { return (c.id === scope.userToComplete.id); }).length === 0) {
								contentService.insertCollaborator(self.loggedInUser.account.id, $scope.content.id, scope.userToComplete.id, self.ajaxHandler);
							}

							var taskGroup = $.grep($scope.content.taskGroups, function(tg) { return tg.status === $scope.content.status; });

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
								success: function(r) {
									instance.close();
								},
								error: self.ajaxHandler.error
							});
						};

						scope.cancel = function() {
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
					$scope.isSaving = false;
					$scope.content.status = oldStatus;
				}
			});
		};

		self.getPromoteAutomationConnections = function() {
			if (!!$scope.promoteConnections && $.isArray($scope.promoteConnections) && $scope.promoteConnections.length > 0) {
				$scope.hubspotConnection = $.grep($scope.promoteConnections, function(c) { return c.provider === 'hubspot'; });
				$scope.actOnConnection = $.grep($scope.promoteConnections, function(c) { return c.provider === 'acton'; });

				$scope.hubspotConnection = $scope.hubspotConnection.length === 1 ? $scope.hubspotConnection[0] : null;
				$scope.actOnConnection = $scope.actOnConnection.length === 1 ? $scope.actOnConnection[0] : null;
			}
		};

		self.formatUserImage = function (userId, text) {
			var user = $.grep($scope.users, function (u, i) { return u.id === parseInt(userId); });
			var style = (user.length === 1 && !launch.utils.isBlank(user[0].image)) ? ' style="background-image: ' + user[0].imageUrl() + '"' : '';

			if (launch.utils.isBlank(text) && user.length === 1) {
				text = user[0].formatName();
			}

			var imageHtml = '<span class="user-image user-image-small"' + style + '></span>';
			var textHtml = '<span class="user-name">' + (launch.utils.isBlank(text) ? '' : text) + '</span>';

			return (imageHtml + ' ' + textHtml);
		};

		$scope.content = null;
		$scope.comments = null;
		$scope.contentTypes = null;
		$scope.contentSettings = null;
		$scope.contentConnections = null;
		$scope.promoteConnections = null;
		$scope.allowedConnections = null;
		$scope.hubspotConnection = null;
		$scope.actOnConnection = null;
		$scope.campaigns = null;
		$scope.users = null;
		$scope.activity = null;
		$scope.launches = null;
		$scope.isCollaborator = true;
		$scope.buyingStages = null;
		$scope.isNewContent = true;
		$scope.forceDirty = false;
		$scope.isReadOnly = false;
		$scope.contentConnectionIds = null;
		$scope.contentTags = null;
		$scope.relatedContent = null;
		$scope.showRichTextEditor = true;
		$scope.showAddFileButton = false;
		$scope.showDownloadContentFile = false;
		$scope.showMetaInfo = false;
		$scope.showPromoteButtons = false;
		$scope.showConcept = true;
		$scope.isSaving = false;
		$scope.isUploading = false;
		$scope.percentComplete = 0;
		$scope.defaultTaskGroup = null;
		$scope.taskUsers = null;
		$scope.collaborators = null;
		$scope.selectedConnections = [];
		$scope.analyzingContent = false;
		$scope.isNewFile = false;
		$scope.newFile = null;
		$scope.newFileType = null;

		$scope.isPromote = false;
		$scope.hasError = launch.utils.isPropertyValid;
		$scope.errorMessage = launch.utils.getPropertyErrorMessage;
		$scope.formatContentTypeItem = launch.utils.formatContentTypeItem;
		$scope.formatCampaignItem = launch.utils.formatCampaignItem;
		$scope.formatContentConnectionItem = launch.utils.formatContentConnectionItem;
		$scope.getConnectionProviderIconClass = launch.utils.getConnectionProviderIconClass;
		$scope.formatEcommercePlatformItem = launch.utils.formatEcommercePlatformItem;
		$scope.formatBuyingStageItem = launch.utils.formatBuyingStageItem;
		$scope.formatDate = launch.utils.formatDate;
		$scope.ecommercePlatforms = ecommercePlatforms;
		$scope.percentComplete = 0;

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

		$scope.formatUserItem = function(item, element, context) {
			return self.formatUserImage(item.id, item.text);
		};

		$scope.getUserImageHtml = function(userId, text) {
			return $sce.trustAsHtml(self.formatUserImage(userId, text));
		};

		$scope.showPublishingGuidelines = function() {
			$modal.open({
				templateUrl: '/assets/views/dialogs/publishing-guidelines.html',
				controller: [
					'$scope', '$modalInstance', function(scope, instance) {
						scope.publishingGuidelines = $scope.contentSettings.publishingGuidelines;
						scope.ok = function() {
							instance.dismiss('cancel');
						};
					}
				]
			});
		};

		$scope.saveContent = function(callback) {
			if (!$scope.content || $scope.content.$resolved === false) {
				return;
			}

			$scope.forceDirty = true;

			$scope.updateContentConnection($scope.contentConnectionIds);

			var msg = launch.utils.validateAll($scope.content);

			if (!launch.utils.isBlank(msg)) {
				notificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));

				if (!!callback && $.isFunction(callback.error)) {
					callback.error();
				}

				return;
			}

			$scope.isSaving = true;

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
							error: function(r1) {
								if (!!callback && $.isFunction(callback.error)) {
									callback.error(r1);
								} else {
									self.ajaxHandler.error(r1);
								}
							}
						});
					},
					error: function (r) {
						$scope.isSaving = false;

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

		$scope.submitContent = function() {
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

			if (!launch.utils.isBlank(msg)) {
				notificationService.error('Error!', msg);
				return;
			}

			if (!self.validateTasks()) {
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

		$scope.updateContentConnection = function(ids) {
			$scope.contentConnectionIds = ids;

			if ($.isArray($scope.contentConnectionIds)) {
				var contentConnectionIds = $.map($scope.contentConnectionIds, function(id) { return parseInt(id); });
				var contentConnections = $.grep($scope.contentConnections, function(cc) { return $.inArray(cc.id, contentConnectionIds) >= 0; });

				$scope.content.accountConnections = contentConnections;
			}
		};

		$scope.updateTags = function (tags) {
			$scope.contentTags = launch.utils.isBlank(tags) ? null : tags;
			$scope.content.tags = launch.utils.isBlank($scope.contentTags) ? null : $scope.contentTags.replace(', ', ',').split(',');
		};

        $scope.updateRelatedContent = function(relatedContent) {
            $scope.relatedContent = launch.utils.isBlank(relatedContent) ? null : relatedContent;
            $scope.content.relatedContent = launch.utils.isBlank($scope.relatedContent) ? null : $scope.relatedContent.replace(', ', ',').split(',');
        }

		$scope.updateContentType = function() {
			var contentTypeName = $scope.content.contentType.name;
			var contentType = $.grep($scope.contentTypes, function(ct) { return ct.name === contentTypeName; });

			$.extend($scope.content.contentType, contentType[0]);

			$scope.showRichTextEditor = $scope.content.contentType.allowText();
			$scope.showAddFileButton = $scope.content.contentType.allowFile();
			$scope.showMetaInfo = $scope.content.contentType.allowMetaTags();
		};

		$scope.updateAuthor = function() {
			var userId = parseInt($scope.content.author.id);
			var user = $.grep($scope.users, function(u) { return u.id === userId; });

			$scope.content.author = user[0];
		};

		$scope.updateCampaign = function() {
			var campaignId = parseInt($scope.content.campaign.id);
			var campaign = $.grep($scope.campaigns, function(u) { return u.id === campaignId; });

			$scope.content.campaign = campaign[0];
		};

		$scope.uploadContentFile = function(files, form, control) {
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
			$scope.isNewFile = true;
			$scope.newFile = file.name;
			$scope.newFileType = launch.utils.getFileTypeCssClass(file.name.substring(file.name.lastIndexOf('.') + 1));
			console.log(self.uploadFile);
		};

		$scope.deleteContentFile = function() {
			$modal.open({
				templateUrl: 'confirm.html',
				controller: [
					'$scope', '$modalInstance', function(scope, instance) {
						scope.message = 'Are you sure you want to delete this file?';
						scope.okButtonText = 'Delete';
						scope.cancelButtonText = 'Cancel';
						scope.onOk = function() {
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
						scope.onCancel = function() {
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

		$scope.filterTaskAssignees = function(collaborators) {
			if (!$scope.content) {
				return;
			}

			$scope.taskUsers = $.grep($scope.users, function(u) {
				if (u.id === self.loggedInUser.id) {
					return true;
				}

				if ($.isArray(collaborators) && collaborators.length > 0) {
					if ($.grep(collaborators, function(c) { return c.id === u.id; }).length > 0) {
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

		$scope.isCollaboratorFinished = function(collaborator) {
			var collaboratorTasks = [];

			if (!!$scope.content && $.isArray($scope.content.taskGroups) && $scope.content.taskGroups.length > 0) {
				$.each($scope.content.taskGroups, function(i, tg) {
					if ($.isArray(tg.tasks) && tg.tasks.length > 0) {
						var tasks = $.grep(tg.tasks, function(t) {
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
						'$scope', '$modalInstance', function(scope, instance) {
							scope.title = file.fileName;
							scope.path = file.path;
							scope.mimeType = file.mimeType;
							scope.isImage = file.isImage();
							scope.isVideo = file.isVideo();
							scope.isAudio = file.isAudio();
							scope.isSupported =  !file.isVideo() || file.fileIsSupported();

							scope.ok = function() {
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
			comment.created = launch.utils.formatDateTime(new Date());
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
				success: function(r) {
					self.refreshComments();
				},
				error: self.ajaxHandler.error
			});
		};

		$scope.connectionIsSupported = function(connection) {
			if (!connection) {
				return false;
			}

			if (connection.connectionType === 'content') {
				return launch.utils.connectionIsSupportsContentType(connection, $scope.content);
			} else if (connection.connectionType === 'promote') {
				return ($.grep($scope.promoteConnections, function(c) { return c.provider === connection.provider; }).length > 0);
			}

			return false;
		};

		$scope.launchContentHubspot = function (connection, refresh) {

			if (!$scope.canLaunchContent) {
				notificationService.error('Error!', 'You do not have sufficient privileges to launch content. Please contact your administrator for more information.');
				return;
			}

			if (!self.validateTasks()) {
				return;
			}

			var parentScope = $scope;

			// If content type is blog_post, let user select blog author
			// If content type is landing_page, site_page, let user select template
			if ($scope.content.contentType.name == 'blog-post') {
				var templateUrl = '/assets/views/dialogs/hubspot-options-blog-post.html';
				var initModal = function($scope) {
					$scope.authors = [];
					connectionService.getAuthors(self.loggedInUser.account.id, connection.id, {
						success: function(response) {
							if ($.isArray(response)) {
								$scope.options.author_id = response[0].id;
								$scope.authors = response;
							}
						}
					});
				};
			} else if (_.indexOf(['landing-page', 'site-page'], $scope.content.contentType.name) != -1) {
				var templateUrl = '/assets/views/dialogs/hubspot-options-page.html';
				var initModal = function($scope) {
					$scope.templates = [];
					connectionService.getTemplates(self.loggedInUser.account.id, connection.id, {
						success: function(response) {
							if ($.isArray(response)) {
								$scope.options.template_path = response[0].path;
								$scope.templates = response;
							}
						}
					})
				};
			} else {
				return $scope.launchContent(connection, refresh);
			}

			$modal.open({
				templateUrl: templateUrl,
				controller: function($scope, $modalInstance) {
					$scope.options = { };
					initModal($scope);
					$scope.cancel = function() {
						$modalInstance.dismiss('cancel');
					};
					$scope.ok = function() {
						parentScope.launchContent(connection, refresh, $scope.options);
						$modalInstance.close();
					};
				}
			});
		};

		$scope.launchContent = function (connection, refresh, extraParams) {
			if (!connection) {
				notificationService.notify('Warning!!', 'Invalid connection information!');
				return;
			}

			if (!$scope.canLaunchContent) {
				notificationService.error('Error!', 'You do not have sufficient privileges to launch content. Please contact your administrator for more information.');
				return;
			}

			if (!self.validateTasks()) {
				return;
			}

			if (connection.provider == 'linkedin') {
				var today = moment();

				var connectionLastUpdated = moment([
					connection.updated.getFullYear(), 
					connection.updated.getMonth() + 1, 
					connection.updated.getDay()]);

				if (today.diff(connectionLastUpdated, 'days') > 50 &&
					!window.launch.utils.isBlank(self.loggedInUser.account) &&
					!window.launch.utils.isBlank(self.loggedInUser.account.id)) {

					$modal.open({
						templateUrl: '/assets/views/dialogs/linkedin-refresh-connection.html',
						controller: function($scope, $modalInstance) {
							$scope.connection = connection;

							$scope.cancel = function() {
								$modalInstance.dismiss('cancel');
							};

							$scope.ok = function(opts) {
								// set extraParams with options
								var authUrl = '/api/account/' + self.loggedInUser.account.id + '/connections/create?connection_id=' + connection.connectionId;
								window.location = authUrl;
							};
						}
					});
				} else {
					$modal.open({
						templateUrl: '/assets/views/dialogs/linkedin-launch-options.html',
						controller: function($scope, $modalInstance) {
							$scope.connection = connection;
							$scope.options = { };

							$scope.cancel = function() {
								$modalInstance.dismiss('cancel');
							};

							$scope.ok = function(opts) {
								// set extraParams with options
								opts = (opts.timeOrGroup == 'group' || opts.timeOrGroup == 'company') ? opts : false;
								if (opts) {

									if (opts.timeOrGroup == 'group') {
										opts.groupId = opts.selectedGroup;
										opts.groupName = ((_.findWhere($scope.groups, { id: opts.groupId }) || { }).group || { }).name;
										opts.groupId = opts.selectedGroup;
									} else if (opts.timeOrGroup == 'company') {
										opts.companyId = opts.selectedCompany;
										opts.companyName = ((_.findWhere($scope.groups, { id: opts.companyId }) || { }).company || { }).name;
										opts.companyId = opts.selectedCompany;
									}
									
								}
								launch(opts);
								$modalInstance.close();
							};

							$scope.showGroups = function(timeOrGroup) {
								$scope.showGroupList = false;
								$scope.showCompanyList = false;

								if (timeOrGroup == 'group') {
									$scope.showGroupListLoader = true;
									// get groups and show list
									Restangular.one('account', self.loggedInUser.account.id)
										.one('connections', connection.id).getList('groups')
										.then(function(groups) {
											$scope.groups = groups;
											$scope.showGroupList = true;
										}).catch($scope.globalErrorHandler).then(function() {
											$scope.showGroupListLoader = false;
										});
								} else if (timeOrGroup == 'company') {
									$scope.showCompanyListLoader = true;
									// get companies and show list
									Restangular.one('account', self.loggedInUser.account.id)
										.one('connections', connection.id).getList('companies')
										.then(function(companies) {
											$scope.companies = companies;
											$scope.showCompanyList = true;
										}).catch($scope.globalErrorHandler).then(function() {
											$scope.showCompanyListLoader = false;
										});
								}
							};
						}
					});
				}
			} else if (connection.provider == 'acton') {
				// popup
				$modal.open({
					templateUrl: '/assets/views/dialogs/acton-launch-options.html',
					controller: function($scope, $modalInstance) {
						$scope.options = {
							type: 'draft'
						};
						$scope.cancel = function() {
							$modalInstance.dismiss('cancel');
						};

						$scope.ok = function(opts) {
							launch(opts);
							$modalInstance.close();
						};
					}
				});
			} else if (connection.provider === 'twitter') {
                var strippedBody = window.launch.utils.stripTags($scope.content.body);
                if(strippedBody.length >= 140) {
                    notificationService.notify('Warning!!', 'Content is too long to launch to twitter');
                }
                else {
                    launch();
                }
            } else {
				launch();
			}

			function launch(extraOpts) {
				if (extraOpts) {
					extraParams = _.merge(extraParams || { }, extraOpts);
					console.log('extraParams', extraParams);
					console.log('extraOpts', extraOpts);
					if (extraOpts.groupId) {
						extraParams = _.merge(extraParams, {
							group_id: extraOpts.groupId
						});
					}
				}

                //If launched connection is not a destination, add it
                if($.inArray(connection.id, $scope.contentConnectionIds) == -1) {
                    $scope.contentConnectionIds.push(connection.id)
                }

				contentService.launch(self.loggedInUser.account.id, $scope.content.id, connection.id, extraParams, {
					success: function(r) {
						if ($scope.content.status <= 3) {
							$scope.content.status = 4;
							$scope.saveContent();
						} else if (refresh) {
							self.refreshLaunches();
						}

						notificationService.success('Success!', 'Successfully launched to ' + ((extraOpts || { }).groupName ? extraOpts.groupName : connection.name) + '!');
					},
					error: function(r) {
						self.ajaxHandler.error(r);

						if (refresh) {
							self.refreshLaunches();
						}
					}
				});
			}
		};

		$scope.toggleSelectedConnections = function(connection, e) {
			var checkbox = $(e.currentTarget);

			if (checkbox.is(':checked')) {
				if ($.grep($scope.selectedConnections, function(c) { return c.id === connection.id; }).length === 0) {
					$scope.selectedConnections.push(connection);
				}
			} else {
				$scope.selectedConnections = $.grep($scope.selectedConnections, function(c) {
					return c.id !== connection.id;
				});
			}

			e.stopImmediatePropagation();
		};

		$scope.launchSelected = function() {
			if (!$scope.canLaunchContent) {
				notificationService.error('Error!', 'You do not have sufficient privileges to launch content. Please contact your administrator for more information.');
				return;
			}

			if (!self.validateTasks()) {
				return;
			}

			if (!$.isArray($scope.selectedConnections) || $scope.selectedConnections.length === 0) {
				notificationService.error('Error!', 'Please select one or more connections to which to launch the content.');
				return;
			}

			$.each($scope.selectedConnections, function(i, c) {
				$scope.launchContent(c, (i === $scope.selectedConnections.length - 1));
			});

			$scope.selectedConnections = [];
		};

        $scope.sendToScribe = function() {
            if ($scope.analyzingContent === true) return;
            $scope.analyzingContent = true;
            contentService.analyze(self.loggedInUser.account.id, $scope.content.id, {
            	success: function (r) {
            		if (!launch.utils.isBlank(r.fleschScore)) {
			            r.fleschScore = launch.utils.titleCase(r.fleschScore);
		            }

		            $scope.analyzingContent = false;
                    $modal.open({
                        windowClass: 'modal-large',
                        templateUrl: '/assets/views/content/scribe-analysis.html',
                        controller: [
                            '$scope', '$modalInstance', function (scope, instance) {
                                scope.scribe = r;
                            }
                        ]
                    });
                },
                error: self.ajaxHandler.error
            });

        }

        $scope.cancelEdit = function (form) {
	        var finish = function() {
		        $location.path('/create');
	        };

	        if ($scope.isReadOnly) {
		        finish();
	        }

	        if (form.$dirty || $scope.isNewContent) {
        		$modal.open({
        			templateUrl: 'confirm.html',
        			controller: [
						'$scope', '$modalInstance', function (scope, instance) {
							scope.message = 'You have not saved your changes. Are you sure you want to cancel?';
							scope.okButtonText = 'Save Changes';
							scope.cancelButtonText = 'Discard Changes';
							scope.onOk = function () {
								$scope.saveContent({
									success: function(r) {
										instance.close();
										finish();
									},
									error: function(r) {
										instance.close();
										self.ajaxHandler.error(r);
									}
								});
							};
							scope.onCancel = function () {
								instance.dismiss('cancel');

								if ($scope.isNewContent) {
									finish();
									return;
								}

								self.refreshContent();
							};
						}
        			]
        		});
	        } else {
        		finish();
	        }
        };

		$scope.deleteContent = function() {
			if (!$scope.canDeleteContent) {
				notificationService.error('Error!!', 'You do not have sufficient privileges to delete content.');
				return;
			}

			$modal.open({
				templateUrl: 'confirm.html',
				controller: [
					'$scope', '$modalInstance', function (scope, instance) {
						scope.message = 'Are you sure you want to delete this content item?';
						scope.okButtonText = 'Delete';
						scope.cancelButtonText = 'Cancel';
						scope.onOk = function () {
							contentService.delete(self.loggedInUser.account.id, $scope.content, {
								success: function(r) {
									instance.close();
									notificationService.success('Success!!', 'You have successfully deleted "' + $scope.content.title + '".');
									$location.path('/create');
								},
								error: function(r) {
									instance.close();
									self.ajaxHandler.error(r);
								}
							});
						};
						scope.onCancel = function () {
							instance.dismiss('cancel');
						};
					}
				]
			});
		};

		$scope.outbrainPopup = function (url) {
			$modal.open({
				templateUrl: '/api/outbrain?url=' + url,
				controller: function ($scope, $modalInstance) {
					$scope.cancel = function () {
						$modalInstance.dismiss('cancel');
					}
				}
			});
		};

		$scope.$watch('content.collaborators', $scope.filterTaskAssignees);

		$scope.$watch('content.author', $scope.filterCollaborators);

		$scope.$watch('content.taskGroups', $scope.filterCollaborators);

		self.init();
	}
]);
