launch.module.controller('ContentConceptController', [
	'$scope', '$routeParams', '$filter', '$location', '$modal', 'AuthService', 'UserService', 'ContentSettingsService', 'ContentService', 'CampaignService', 'NotificationService', 'AccountService',
    function ($scope, $routeParams, $filter, $location, $modal, authService, userService, contentSettingsService, contentService, campaignService, notificationService, accountService) {
		var self = this;

		self.loggedInUser = null;

		self.ajaxHandler = {
			success: function (r) {

			},
			error: function (r) {
				launch.utils.handleAjaxErrorResponse(r, notificationService);
			}
		};

		self.init = function () {
			self.loggedInUser = authService.userInfo();
			self.refreshConcept();
            self.refreshBrainstorms();

			$scope.showCollaborate = (!$scope.isNewConcept && self.loggedInUser.hasModuleAccess('collaborate'));
            $scope.showBrainstorms = !$scope.isNewConcept;

			$scope.contentTypes = contentService.getContentTypes(self.ajaxHandler);
			$scope.users = userService.getForAccount(self.loggedInUser.account.id, null, self.ajaxHandler);
			$scope.campaigns = campaignService.query(self.loggedInUser.account.id, null, {
				success: function (r) {
					if ($scope.isNewConcept) {
						self.filterCampaigns();
					}
				},
				error: self.ajaxHandler.error
			});

		}

        self.refreshBrainstorms = function() {
            $scope.brainstorms = accountService.getBrainstorms(self.loggedInUser.account.id, 'content', $routeParams.contentId, self.ajaxHandler);
        }

		self.refreshConcept = function() {
			var contentId = parseInt($routeParams.contentId);

			if (isNaN(contentId)) {
				$scope.content = contentService.getNewContentConcept(self.loggedInUser);
				$scope.isNewConcept = true;
			} else {
				$scope.content = contentService.get(self.loggedInUser.account.id, contentId, {
					success: function(r) {
						if ($scope.content.status > 0) {
							$location.path('/create/content/edit/' + $scope.content.id);
							return;
						}

						$scope.isCollaborator = (self.loggedInUser.id === $scope.content.author.id ||
							$.grep($scope.content.collaborators, function (c) { return c.id === self.loggedInUser.id; }).length > 0);

						$scope.isCollaborator = ($scope.isCollaborator || self.loggedInUser.hasPrivilege('create_edit_ideas_other'));

						if (!$scope.isCollaborator) {
							return;
						}

						if (self.loggedInUser.id === $scope.content.author.id) {
							$scope.canConvertConept = self.loggedInUser.hasPrivilege('create_execute_convert_concept_own');
							$scope.canEditContent = true;
						} else {
							$scope.canConvertConept = self.loggedInUser.hasPrivilege('create_execute_convert_concept_other');
							$scope.canEditContent = self.loggedInUser.hasPrivilege('create_edit_ideas_other');
						}

						self.refreshComments();
						self.filterCampaigns();

						$scope.guestCollaborators = contentService.queryGuestCollaborators(self.loggedInUser.account.id, contentId, null, self.ajaxHandler);
					},
					error: self.ajaxHandler.error
				});
				$scope.isNewConcept = false;
			}
		};

		self.refreshComments = function() {
			$scope.comments = contentService.queryComments(self.loggedInUser.account.id, $scope.content.id, null, self.ajaxHandler);
		};

		self.filterCampaigns = function () {
			$scope.campaigns = $.grep($scope.campaigns, function (c) {
				return ((c.isActive && !c.isEnded()) || (!!$scope.content && !!$scope.content.campaign && c.id === $scope.content.campaign.id));
			});
		};

		self.filterCollaborators = function () {
			if (!$scope.content || !$scope.content.author) {
				return;
			}

			$scope.collaborators = $.grep($scope.users, function (u) {
				return u.id !== $scope.content.author.id;
			});
		};



		$scope.hasError = launch.utils.isPropertyValid;
		$scope.errorMessage = launch.utils.getPropertyErrorMessage;
		$scope.forceDirty = false;
		$scope.isSaving = false;

		$scope.content = null;
		$scope.comments = null;
		$scope.contentTypes = null;
		$scope.campaigns = null;
		$scope.users = null;
		$scope.collaborators = null;
		$scope.guestCollaborators = null;
		$scope.isCollaborator = true;
		$scope.isNewConcept = true;
		$scope.isContentConcept = true;

		$scope.canEditContent = true;
		$scope.showCollaborate = false;
        $scope.showBrainstorms = false;
		$scope.canConvertConept = false;

		$scope.formatContentTypeItem = launch.utils.formatContentTypeItem;
		$scope.formatCampaignItem = launch.utils.formatCampaignItem;

		$scope.formatUserItem = function (item, element, context) {
			var user = $.grep($scope.users, function (u, i) { return u.id === parseInt(item.id); });
			var style = (user.length === 1 && !launch.utils.isBlank(user[0].image)) ? ' style="background-image: ' + user[0].imageUrl() + '"' : '';

			return '<span class="user-image user-image-small"' + style + '></span> <span>' + item.text + '</span>';
		};

		$scope.saveConcept = function() {
			if (!$scope.content || $scope.content.$resolved === false) {
				return;
			}

			$scope.forceDirty = true;

			var msg = launch.utils.validateAll($scope.content);

			if (!launch.utils.isBlank(msg)) {
				notificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
				return;
			}

			var method = $scope.isNewConcept ? contentService.add : contentService.update;

			$scope.isSaving = true;

			method(self.loggedInUser.account.id, $scope.content, {
				success: function(r) {
					$scope.isSaving = false;

					var successMsg = $scope.isNewConcept ? 'Successfully created new concept!' : 'Successfully updated concept!';

					notificationService.success('Success!', successMsg);

					if ($scope.isNewConcept) {
						$location.path('/create/concept/edit/content/' + r.id);
					} else {
						self.refreshConcept();
					}
				},
				error: function (r) {
					$scope.isSaving = false;
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		$scope.viewInCollaborate = function () {
			$location.path('/collaborate/content/' + $scope.content.id);
		};

		$scope.convertConcept = function() {
			$scope.content.status = 1;
			$scope.content.concept = $scope.content.body;

			contentService.update(self.loggedInUser.account.id, $scope.content, {
				success: function(r) {
					$location.path('/create/content/edit/' + $scope.content.id);
				},
				error: function(r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		$scope.updateContentType = function() {
			var contentTypeName = $scope.content.contentType.name;
			var contentType = $.grep($scope.contentTypes, function(ct) { return ct.name === contentTypeName; });

			$scope.content.contentType = contentType[0];
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

		$scope.addAttachment = function (uploadFile) {
			if (!!$scope.content && !launch.utils.isBlank($scope.content.id)) {
				$scope.saveConcept();
			}
		};

		$scope.addComment = function (message) {
			launch.utils.insertComment(message, $scope.content.id, self.loggedInUser, contentService, notificationService, {
				success: function (r) {
					self.refreshComments();
				},
				error: self.ajaxHandler.error
			});
		};

		$scope.$watch('content.author', self.filterCollaborators);



        $scope.showScheduleBrainstorm = function(brainstorm) {
            if (!brainstorm) brainstorm = accountService.getNewBrainstorm(self.loggedInUser.id, self.loggedInUser.account.id, 'content', $scope.content.id);
            $modal.open({
                templateUrl: '/assets/views/content/concept-brainstorm-modal.html',
                controller: [
                    '$scope', '$modalInstance', function(scope, instance) {
                        scope.brainstorm = brainstorm;
                        scope.message = 'Brainstorm Session';
                        scope.schedule = function() {
                            accountService.addBrainstorm(scope.brainstorm, {
                                success : function(r) {
                                    self.refreshBrainstorms();
                                    notificationService.success('Success!', 'Your brainstorm has successfully been scheduled.');
                                },
                                error : self.ajaxHandler.error
                            });
                            instance.close();
                        }
                    }
                ]
            });
        };

        $scope.removeBrainstorm = function(brainstorm) {
            accountService.removeBrainstorm(brainstorm, {
                success : function(r) {
                    self.refreshBrainstorms();
                    notificationService.success('Success!', 'Your brainstorm has successfully been removed.');
                },
                error : self.ajaxHandler.error
            });
        }

		self.init();
	}
]);